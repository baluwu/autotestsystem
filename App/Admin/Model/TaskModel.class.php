<?php
namespace Admin\Model;
use Think\Model;

class TaskModel {
    public function getWorkingMachineSize() { return REDIS()->sCard('working.machine'); }
    public function addWorkingMachine($key) { return REDIS()->sAdd('working.machine', $key); }
    public function addPendingCase($val) { return REDIS()->sAdd('pending.case', $val); }
    public function freeWokingMachine($key) { REDIS()->sRemove('working.machine', $key); }

    /* 任务分发 */
    public function onTask($serv, $task_id, $from_id, $strTaskData) {
        tasklog('开始执行任务:' . $task_id);

        $taskData = json_decode($strTaskData, true);
        $taskData['src_string'] = $strTaskData; 

        $runFunc = ['runSingle', 'runGroup', 'runTask'][$taskData['isgroup'] ?? 0];
        $type = $taskData['type'] ?? 'IMME';

        if ($type == 'TIMER') {
            tasklog('定时任务将于' . $taskData['secs'] . '秒后执行');
            $serv->finish(['isSuccess' => true, 'data' => $taskData, 'msg' => '已添加定时任务']);

            $serv->after($taskData['secs'] * 1000, function ($id) use ($runFunc, $taskData) {
                $taskData['type'] = 'FORCE';
                $self = D('Task');

                tasklog('执行定时任务:' . date("Y-m-d H:i:s"));

                $self->doRunTask($strTaskData, $taskData, $self);
            });

            return ;
        }

        return $this->doRunTask($strTaskData, $taskData, $serv);
    }

    private function doRunTask($strTaskData, $taskData, $wrapObj) {

        $ret = ['isSuccess' => false, 'data' => $taskData, 'msg' => ''];
        $type = $taskData['type'] ?? 'IMME';

        //超出最大并发数
        if ($this->getWorkingMachineSize() > C('PARALLEL_TASKS')) {
            if ($type == 'IMME') {
                $ret['msg'] = '服务器正忙,请稍后操作';
                return $wrapObj->finish($ret);   
            }

            $this->addPendingCase($strTaskData);
            $ret['msg'] = '已加入执行队列,稍后将执行';
            return $wrapObj->finish($ret);   
        }
        else {
            //当前任务的目标机器正在工作
            if (0 == $this->addWorkingMachine($taskData['ip'])) {
                if ($type == 'IMME') {
                    $ret['msg'] = '目标机器正忙,请稍后操作';
                    return $wrapObj->finish($ret);   
                }
                else {
                    $this->addPendingCase($strTaskData);
                    $ret['msg'] = '已加入执行队列,稍后将执行';
                    return $wrapObj->finish($ret);   
                }
            }
            //执行任务
            else {
                $runFunc = $this->getRunFunc($taskData['isgroup']);
                $r = $this->$runFunc($taskData);
                tasklog('执行结果:' . json_encode($r));
                $wrapObj->finish($r);
                return ;
                //$wrapObj->finish($this->$runFunc($taskData));
            }
        }
    }

    /**
     * 用例执行 用例状态 0 等待执行  1 正在执行
     * 执行状态 0 等待任务执行  1 正在执行 2 执行成功 3 执行失败
     */
    public function runSingle($taskData) {
        $thisData = $this->initExecSingle($taskData, null);
        return $this->startExecSingle($taskData, $thisData);
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
        tasklog('开始执行用例[' . $thisData['name'] . ']');

        $ret = ['isSuccess' => false, 'data' => $taskData, 'msg' => ''];
        $type = $thisData['nlp'] ? 'NLP' : 'ASR';

        $postParms = [];
        /* NLP */
        if ($type == 'NLP') { $postParms['asrToNlp'] = $thisData['nlp']; } 
        /* ASR */
        else if ($type == 'ASR') 
        {
            $arc = $thisData['arc'];
            tasklog('arc:' . ABS_ROOT . $arc, 'INFO');

            $postParms['voiceUrl'] = C('SERVER_NAME') . $arc;
            $postParms['asrVoiceInject'] = $thisData['name'];//pathinfo($arc)['basename'];

            if (!file_exists(ABS_ROOT . $arc)) {
                //$mdl->addHistoryWhenExecSingle($taskData, false, 'asr文件不存在');
                $ret['msg'] = 'asr文件不存在';
                return $ret;
            }
        }

        $url = 'http://' . $taskData['ip'] . ':' . $taskData['port'] .
            ($type == 'NLP' ? '/asrToNlp' : '/asrVoiceInject');

        $response = sendRequest($url, $postParms, 10);

        if (!$response) {
            //$mdl->addHistoryWhenExecSingle($taskData, false, 'HTTP请求失败', $response);
            $ret['msg'] = 'HTTP请求失败';
            return $ret;
        }

        $resData = contentAsArray($response->getContent());

        if (empty($resData)) {
            $ret['msg'] = '请求数据错误';
            return $ret;
            //$mdl->addHistoryWhenExecSingle($taskData, false, 'HTTP请求数据错误', $response);
        }

        if (!judged_all($resData, $thisData['validates'])) {
            $ret['msg'] = '判定条件不通过';
            return $ret;
            //$mdl->addHistoryWhenExecSingle($taskData, false, '判定条件不通过', $response);
        }

        //$mdl->addHistoryWhenExecSingle($taskData, true, '');
        return [ 'isSuccess' => true, 'data' => $taskData ];
    }

    /* 用例组执行 */
    public function runGroup($taskData) {

        //$mdl->setGroupStatus($taskData['mid'], 1);
        //$mdl->setExecHistoryStatus($taskData['id'], 1);

        $groupSingleData = $this->getGroupSingle($taskData['mid']);

        tasklog('开始执行用例组: ' . count($groupSingleData));
        
        if (!is_array($groupSingleData) || count($groupSingleData) == 0) {
            //$mdl->setGroupStatus($taskData['mid'], 0);
            //$mdl->addExecHistory($taskData, false, '用例组无用例');
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        $isSuccess = true;
        foreach ($groupSingleData as $key => $thisData) {
            $exec_start_time = date("Y-m-d H:i:s");
            $taskData['stime'] = $exec_start_time;
            $taskData['single_id'] = $groupSingleData[$key]['id'];

            $thisData = $this->initExecSingle($taskData, $thisData);
            $isSucc = $this->startExecSingle($taskData, $thisData)['isSuccess'];
            if ($isSuccess && !$isSucc) {
                $isSuccess = false;
            }

            $this->endExecSingle($taskData);
        }

        //$mdl->addExecHistory($taskData, $isSuccess, $isSuccess ? '用例组执行成功' : '用例组执行失败');
        //$mdl->setGroupStatus($taskData['mid'], 0);
        tasklog('用例组执行完成！');

        return [ 'isSuccess' => $isSuccess, 'data' => $taskData ];
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

        foreach ($singlesData as $thisData) {
            $thisData = $this->initExecSingle($taskData, $thisData);
            $execRs = $this->startExecSingle($taskData, $thisData);

            if ($ret['isSuccess'] && !$execRs['isSuccess']) {
                $ret['isSuccess'] = false;
                $ret['msg'] = $execRs['msg'];
            }
            $this->endExecSingle($taskData);
        }

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

    private function finish($data) {
        tasklog('定时任务执行完成');
        $taskData = $data['data'];
        $type = $taskData['type'] ?? 'IMME';
        
        $this->freeWokingMachine($taskData['ip']);

        if ($type == 'QUEUE') {
            $this->removePendingCase($taskData['src_string']);
        }
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
}
