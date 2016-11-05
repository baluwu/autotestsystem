<?php
namespace Admin\Model;
use Think\Model;

class TaskModel {
    public function getWorkingMachineSize() { return REDIS()->sCard('working.machine'); }
    public function addWorkingMachine($key) { return REDIS()->sAdd('working.machine', $key); }
    public function freeWokingMachine($key) { REDIS()->sRemove('working.machine', $key); }

    /* 任务分发 */
    public function onTask($serv, $task_id, $from_id, $strTaskData) {
        tasklog('开始执行任务:' . $task_id . ',' . $strTaskData);

        $taskData = json_decode($strTaskData, true);
        $taskData['src_string'] = $strTaskData; 

        $runFunc = ['runSingle', 'runGroup', 'runTask'][$taskData['isgroup'] ?? 0];
        $type = $taskData['type'];

        if ($type == 'TIMER') {
            $r = M('Task')->add([
                'name' => $taskData['name'],
                'uid' => $taskData['uid'],
                'ver' => $taskData['ver'],
                'description' => $taskData['description'],
                'mid' => $taskData['mid'],
                'run_at' => $taskData['run_at'],
                'notify_email' => $taskData['notify_email'],
                'create_time' => $taskData['create_time'],
                'ip' => $taskData['ip'],
                'port' => $taskData['port'] ? $taskData['port'] : '8080'
            ]);

            tasklog('添加任务:' . json_encode($taskData));

            $serv->finish(['isSuccess' => $r ? true : false, 'data' => $taskData, 'msg' => '']);
            return ;
        }

        return $this->doRunTask($strTaskData, $taskData, $serv);
    }

    //task type in [IMME, TASK]
    private function doRunTask($strTaskData, $taskData, $wrapObj) {

        $ret = ['isSuccess' => false, 'data' => $taskData, 'msg' => ''];
        $type = $taskData['type'];

        //超出最大并发数
        if ($this->getWorkingMachineSize() > C('PARALLEL_TASKS')) {
            if ($type == 'IMME') {
                $ret['msg'] = '服务器正忙,请稍后操作';
                $wrapObj->finish($ret);   
                return ;
            }

            $ret['msg'] = '已加入执行队列,稍后将执行';
            $wrapObj->finish($ret);   
            return ;
        }
        else {
            //当前任务的目标机器正在工作
            if (0 == $this->addWorkingMachine($taskData['ip'])) {
                if ($type == 'IMME') {
                    $ret['msg'] = '目标机器正忙,请稍后操作';
                    $wrapObj->finish($ret);   
                    return ;
                }
                else {
                    $ret['msg'] = '已加入执行队列,稍后将执行';
                    $wrapObj->finish($ret);
                    return ;
                }
            }
            //执行任务
            else {
                $ret['free_machine'] = true;
                $runFunc = $this->getRunFunc($taskData['isgroup']);
                $r = $this->$runFunc($taskData);

                tasklog('执行结果:' . json_encode($r));

                $ret['isSuccess'] = $r['isSuccess'];
                $ret['msg'] = $r['msg'];

                $wrapObj->finish($ret);
                return ;
            }
        }
    }

    /**
     * 用例执行 用例状态 0 等待执行  1 正在执行
     * 执行状态 0 等待任务执行  1 正在执行 2 执行成功 3 执行失败
     */
    public function runSingle($taskData) {
        $taskData['history_id'] = $this->addExecHistory($taskData);
        $taskData['exec_start_time'] = date('Y-h-d H:i:s');
        tasklog('INSERT history: ' . $taskData['history_id']);
        $thisData = $this->initExecSingle($taskData, null);
        $r = $this->startExecSingle($taskData, $thisData);
        $this->setExecHistoryResult($taskData, $ret['isSuccess'], $ret['isSuccess'] ? '成功' : '失败', $r->response);       
        return $r;
    }

    private function initExecSingle($taskData, $thisData) {
        $is_group = $taskData['isgroup'];

        if ($thisData) {
            return $thisData;
        }
        else {
            return M('GroupSingle')->where(['isrecovery' => 0])->find($taskData['mid']);
        }
    }

    private function endExecSingle($taskData) {
        return ;
    }

    private function startExecSingle($taskData, $thisData) {
        tasklog('开始执行用例[' . json_encode($thisData) . ']');

        $ret = ['isSuccess' => false, 'data' => $taskData, 'msg' => ''];

        $type = $thisData['nlp'] ? 'NLP' : 'ASR';
        $taskData['single_id'] = $thisData['id'];
        $taskData['single_exec_start_time'] = date('Y-m-d H:i:s');
        $taskData['asr'] = $thisData['arc'];
        $taskData['nlp'] = $thisData['nlp'];

        $postParms = [];
        $resData = null;

        do {
            /* NLP */
            if ($type == 'NLP') { $postParms['asrToNlp'] = $thisData['nlp']; } 
            /* ASR */
            else if ($type == 'ASR') 
            {
                $arc = $thisData['arc'];
                tasklog('arc:' . ABS_ROOT . $arc, 'INFO');

                $postParms['voiceUrl'] = 'http://192.168.1.12:8090' . $arc;
                //$postParms['voiceUrl'] = (C('USE_HTTPS') ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $arc;
                $postParms['asrVoiceInject'] = $thisData['name'];

                if (!file_exists(ABS_ROOT . $arc)) {
                    $ret['msg'] = 'asr文件不存在';
                    break;
                }
            }

            $url = 'http://' . $taskData['ip'] . ':' . $taskData['port'] .
                ($type == 'NLP' ? '/asrToNlp' : '/asrVoiceInject');

            $response = $this->getHttpClient()->post($url, $postParms);
            if (!$response->isOk()) {
                $ret['msg'] = 'HTTP请求失败';
                break;
            }

            tasklog('机器响应:' . $response);
            $resData = contentAsArray($response);
            $ret['response'] = $resData;

            if (empty($resData)) {
                $ret['msg'] = '请求数据错误';
                break;
            }

            if (!judged_all($resData, $thisData['validates'])) {
                $ret['msg'] = '判定条件不通过';
                break;
            }

            $ret['isSuccess'] = true;
            break;
        } while (1);

        $this->addGroupSingleExecHistory($taskData, $ret['isSuccess'], $ret['msg'], $resData);

        return $ret;
    }

    /* 用例组执行 */
    public function runGroup($taskData) {

        $groupSingleData = $this->getGroupSingle($taskData['mid']);

        tasklog('开始执行用例组: ' . count($groupSingleData));
        
        if (!is_array($groupSingleData) || count($groupSingleData) == 0) {
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        $isSuccess = true;
        $taskData['exec_start_time'] = date('Y-m-d H:i:s');
        $taskData['history_id'] = $this->addExecHistory($taskData);

        $msg = [];
        foreach ($groupSingleData as $key => $thisData) {
            $thisData = $this->initExecSingle($taskData, $thisData);
            $ret = $this->startExecSingle($taskData, $thisData);
            
            if ($isSuccess && !$ret['isSuccess']) {
                $isSuccess = false;
            }

            $msg[] = $ret['msg'];

            $this->endExecSingle($taskData);
        }

        $this->setExecHistoryResult($taskData, $isSuccess, $isSuccess ? '成功' : '失败');       
        tasklog('用例组执行完成！');

        return [ 'isSuccess' => $isSuccess, 'data' => $taskData, 'msg' => implode('#', array_unique($msg)) ];
    }

    public function runTask($taskData) {
        tasklog('开始执行任务组');
        $ret = ['isSuccess' => false, 'data' => $taskData, 'msg' => ''];

        $single_ids = $taskData['mid'];
        if (!$single_ids) {
            $ret['msg'] = '缺少参数single_ids';
            return $ret;
        }

        $singlesData = $this->getSingles($single_ids);
        if (empty($singlesData)) {
            $ret['msg'] = '没有找到用例';
            return $ret;
        }

        $ret['isSuccess'] = true;
        $taskData['exec_start_time'] = date('Y-m-d H:i:s');

        $taskData['history_id'] = $this->addExecHistory($taskData);

        foreach ($singlesData as $thisData) {
            $thisData = $this->initExecSingle($taskData, $thisData);
            $execRs = $this->startExecSingle($taskData, $thisData);

            if ($ret['isSuccess'] && !$execRs['isSuccess']) {
                $ret['isSuccess'] = false;
                $ret['msg'] = $execRs['msg'];
            }
            $this->endExecSingle($taskData);
        }

        M('Task')->where(['id' => $taskData['id']])->delete();
        $this->setExecHistoryResult($taskData, $ret['isSuccess'], $ret['isSuccess'] ? '成功' : '失败');       

        return $ret;
    }

    public function getGroupSingle($mid) {
        return 
            M('GroupSingle')
            ->field('a.id,a.tid,a.name,a.nlp,a.arc,a.validates,a.create_time,a.isrecovery,b.uid,b.ispublic,b.name')
            ->join(' a RIGHT JOIN __GROUP__ b ON a.tid = b.id')
            ->where(['tid' => $mid])
            ->where(['a.isrecovery' => 0])
            ->where(['b.isrecovery' => 0])
            ->order(['create_time' => 'desc'])
            ->select();
    }

    private function getSingles($single_ids) {
        return 
            M('GroupSingle')
            ->where(['id' => ['IN', $single_ids]])
            ->where(['isrecovery' => 0])
            ->order(['create_time' => 'desc'])
            ->select();
    }

    private function getHttpClient() {
        $cli = new \Leaps\HttpClient\Adapter\Curl();
        $cli->setOption(CURLOPT_TIMEOUT, 30)
            ->setHeader('Content-Type', 'application/x-www-form-urlencoded');

        return $cli;
    }

    private function getRunFunc($isgroup) {
        return ['runSingle', 'runGroup', 'runTask'][$isgroup ?? 0];
    }

    /* 添加执行记录 */
    public function addExecHistory($td) {
        return M('ExecHistory')->add([
            'mid' => $td['mid'],
            'uid' => $td['uid'],
            'isgroup' => $td['isgroup'],
            'ip' => $td['ip'],
            'port' => $td['port'],
            'create_time' => date('Y-m-d H:i:s', $td['create_time']),
            'exec_start_time' => $td['exec_start_time'],
            'status' => 1,
            'exec_plan_time' => $td['run_at'] ? date('Y-m-d H:i:s', $td['run_at']) : '',
            'ver' => $td['ver'] ? $td['ver'] : '',
            'description' => $td['description'] ? $td['description'] : '',
            'task_name' => $td['name'] ? $td['name'] : ''
        ]);
    }

    public function setExecHistoryResult($td, $is_succ, $msg, $response = null) {
        tasklog('执行' . ($is_succ ? '成功' : ('失败:' . $msg)), $is_succ ? 'INFO' : 'ERROR');

        $history_id = $td['history_id'];
        if (!$history_id) return;

        M('ExecHistory')->where(['id' => $history_id])->setField([
            'status'        => $is_succ ? 2 : 3,
            'exec_start_time' => $td['exec_start_time'],
            'exec_end_time' => date("Y-m-d H:i:s")
        ]);
    }

    public function addGroupSingleExecHistory($td, $is_succ, $msg, $resArr) {
        tasklog('组单例执行' . ($is_succ ? '成功' : ('失败:' . $msg)), $is_succ ? 'INFO' : 'ERROR');

        $history_id = $td['history_id'];
        if (!$history_id) return;

        return M('GroupExecHistory')->add([
            'exec_history_id' => $td['history_id'],
            'group_id'        => $td['isgroup'] == 1 ? $td['mid'] : 0,
            'single_id'       => $td['single_id'],
            'issuccess'       => $is_succ,
            'exec_content'    => json_encode([
                'msg'     => $msg,
                'content' => [
                    'IP'   => $td['ip'] ?? '',
                    'port' => $td['port'] ?? '',
                    'asr'  => $td['asr'] ?? '',
                    'nlp'  => $td['nlp'] ?? '',
                    'Content' => $resArr
                ]
            ], JSON_UNESCAPED_UNICODE),
            'exec_start_time' => $td['single_exec_start_time'],
            'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
    }
}
