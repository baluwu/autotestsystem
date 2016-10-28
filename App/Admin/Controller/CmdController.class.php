<?php

namespace Admin\Controller;

use Think\Controller;

class CmdController extends Controller {

    private $serv;

    /* 主任务入口 */
    public function index() {
        if (!IS_CLI) $this->error('此文件不能在非cli模式执行', '/Index');
        $this->serv = new \swoole_server('127.0.0.1', C('SWOOLE_PORT'));
        $this->serv->set(
            [
                'task_worker_num' => 5,
                'worker_num'      => 8,   //工作进程数量
                'backlog'         => 128,
                'daemonize'       => true, //是否作为守护进程
                'log_file'        => LOG_PATH . 'swoole_' . date('Y-m-d') . '.log',
                'log_level'       => 0,
            ]
        );
        $this->serv->on('onStart', [$this, 'onStart']);
        $this->serv->on('connect', [$this, 'connect']);
        $this->serv->on('WorkerStart', [$this, 'WorkerStart']);
        $this->serv->on('Receive', [$this, 'Receive']);
        $this->serv->on('Task', [$this, 'taskDispatch']);
        $this->serv->on('Finish', [$this, 'Finish']);
        $this->serv->start();

        self::log( '服务已启动, 开始监听端口: '.C('SWOOLE_PORT') );
    }

    public function onStart($serv, $fd) {
        //管理进程的PID，通过向管理进程发送SIGUSR1信号可实现柔性重启
        self::log('manager_pid' . $this->serv->manager_pid);
        //主进程的PID，通过向主进程发送SIGTERM信号可安全关闭服务器
        self::log('master_pid' . $this->serv->master_pid);     
    }

    public function connect($serv, $fd) {
        self::log('异步任务 连接');
        //self::log(json_encode($this->serv->connections)); //当前服务器的客户端连接，可使用foreach遍历所有连接
    }

    public function WorkerStart($serv, $fd) {
        //定时任务 循环执行
        /*
        $serv->tick(1000*60*5, function ($id) {
            self::log('定时任务'.$id.'执行,时间:' .date("Y-m-d H:i:s"), 'info');
        });
        */
    }

    public function Receive($serv, $fd, $from_id, $taskData) {
        //定时任务 执行一次
        //      $serv->after(1000*60*5, function ($id) {
        //        self::log('定时任务'.$id.'执行,时间:' .date("Y-m-d H:i:s"), 'info');
        //      });
        $serv->send($fd, '任务数据接收成功');
        $serv->close($fd);
        $task_id = $serv->task($taskData);
        self::log('接到任务,数据:' . $taskData . '  task_id' . $task_id);
    }

    /* 任务分发 */
    public function taskDispatch($serv, $task_id, $from_id, $taskData) {
        self::log('开始执行任务:' . $task_id . '  task_id' . $task_id);
        $taskData = @json_decode($taskData, true);
        $runFunc = ($taskData['isgroup'] == 0) ? 'runSingle' : 'runGroup';
        $this->pushTask($taskData);
        $serv->finish($this->$runFunc($taskData));
    }

    public function Finish($serv, $task_id, $taskData) {
        $this->popTask($taskData);
        self::log('任务完成:' . $task_id . ' 数据:' . @json_encode($taskData));
        $serv->finish(@json_encode($taskData));
    }

    /* 入队列 */
    private function pushTask($td) {
        $queue_name = 'task.' . ($td['data']['isgroup'] ? 'group' : 'single');
        REDIS()->sAdd($queue_name, $td['data']['id']);
    }

    /* 出队列 */
    private function popTask($td) {
        $queue_name = 'task.' . ($td['data']['isgroup'] ? 'group' : 'single');
        REDIS()->sPop($queue_name, $td['data']['id']);
    }

    /* 记录日志 */
    static public function log($message, $lv = 'info') {
        \Think\Log::write($message, '[' . date('Y-m-d H:i:s') . '] ' . $lv);
    }

    private function getHttpClient() {
        $cli = new \Leaps\HttpClient\Adapter\Curl();
        $cli->setOption(CURLOPT_TIMEOUT, 30)
            ->setHeader('Content-Type', 'application/x-www-form-urlencoded');

        return $cli;
    }

    public function demo() {
        $taskData = ["leixin"=>0,"mid"=>"59","ip"=>"192.168.121.131","port"=>"8080","create_time"=>1467108919,"id"=>52];
        $this->runSingle($taskData);
    }

    /**
     * 用例执行
     * 用例状态 0 等待执行  1 正在执行
     * 执行状态 0 等待任务执行  1 正在执行 2 执行成功 3 执行失败
     */
    private function runSingle($taskData) {
        //  {"leixin":0,"mid":"82","ip":"121.42.0.84","port":"8080","create_time":1467108919,"id":52}
        $taskData['is_group'] = false;
        $thisData = $this->initExecSingle($taskData, null);
        return $this->startExecSingle($taskData, $thisData);
    }

    private function initExecSingle($taskData, $thisData) {
        $is_group = $taskData['is_group'];

        self::log(($is_group ? '组单例' : '单例') . '数据' . json_encode($taskData), 'info');

        if ($is_group) {
            return $thisData;
        }
        else {
            //M('Single')->where(['id' => $taskData['mid']])->setField(['status' => 1]);
            M('ExecHistory')->where(['id' => $taskData['id']])->setField([
                'status'          => 1,
                'exec_start_time' => date("Y-m-d H:i:s")
            ]);

            return M('GroupSingle')->where(['isrecovery' => 0])->find($taskData['mid']);
        }
    }

    private function endExecSingle($taskData) {
        if ($taskData['is_group']) return;
        //M('Single')->where(['id' => $thisData['id']])->setField(['status' => 0]);
    }

    private function startExecSingle($taskData, $thisData) {
        $type = $thisData['nlp'] ? 'NLP' : 'ASR';

        $postParms = [];
        /* NLP */
        if ($type == 'NLP') {
            $postParms['asrToNlp'] = $thisData['nlp'];
        } 
        /* ASR */
        else if ($type == 'ASR') 
        {
            self::log('arc:' . $thisData['arc'], 'info');

            $arc = $thisData['arc'];

            $postParms['voiceUrl'] = C('SERVER_NAME') . $arc;
            $postParms['asrVoiceInject'] = pathinfo($arc)['basename'];

            if (!file_exists(ABS_ROOT . $arc)) {
                $this->addHistoryWhenExecSingle($taskData, false, 'asr文件读取错误');
                return [ 'isSuccess' => false, 'data' => $taskData ];
            }
        }

        $httpClient = $this->getHttpClient();
        $url = 'http://' . $taskData['ip'] . ':' . $taskData['port'] .
            ($type == 'NLP' ? '/asrToNlp' : '/asrVoiceInject');

        $response = $httpClient->post($url, $postParms);

        if (!$response->isOk()) {
            $this->addHistoryWhenExecSingle($taskData, false, 'HTTP请求失败', $response);
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        $resData = contentAsArray($response->getContent());

        if (empty($resData)) {
            $this->addHistoryWhenExecSingle($taskData, false, 'HTTP请求数据错误', $response);
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        if (!judged_all($resData, $thisData['validates'])) {
            $this->addHistoryWhenExecSingle($taskData, false, '判定条件不通过', $response);
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        $this->addHistoryWhenExecSingle($taskData, true, '');
        return [ 'isSuccess' => true, 'data' => $taskData ];
    }

    /* 用例组执行 */
    private function runGroup($taskData) {
        $taskData['is_group'] = true;
        //  {"leixin":0,"mid":"82","ip":"121.42.0.84","port":"8080","create_time":1467108919,"id":52}
        //  {"isgroup":1,"mid":"19","uid":"1","ip":"121.42.0.84","port":"","create_time":"2016-07-12 16:35:08","id":118}
        self::log('组任务数据：' . json_encode($taskData), 'info');

        //设置组状态
        M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 1]);
        //设置任务状态 和执行时间
        M('ExecHistory')->where(['id' => $taskData['id']])->setField([
            'status'          => 1,
            'exec_start_time' => date("Y-m-d H:i:s")
        ]);

        $groupSingleData = $this->getGroupSingle($taskData['mid']);
        
        if (!is_array($GsingleData) || count($GsingleData) == 0) {
            M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 0]);
            $this->addExecHistory($taskData, 3, false, '用例组无用例');
            return [ 'isSuccess' => false, 'data' => $taskData ];
        }

        $isSuccess = true;
        foreach ($GsingleData as $key => $thisData) {
            $exec_start_time = date("Y-m-d H:i:s");
            $taskData['stime'] = $exec_start_time;
            $taskData['single_id'] = $GsingleData[$key]['id'];

            $thisData = $this->initExecSingle($taskData, $thisData);
            $isSuccess = $this->startExecSingle($taskData, $thisData)['isSuccess'];
            $this->endExecSingle($taskData);
        }

        M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 0]);
        self::log('组任务执行完成！', 'info');

        $this->addExecHistory($taskData, $isSuccess, $isSuccess ? '用例组执行成功' : '用例组执行失败');
        return [ 'isSuccess' => $isSuccess, 'data' => $taskData ];
    }

    /* 添加执行记录 */
    public function addExecHistory($td, $is_succ, $msg, $resp = null) {
        self::log('单例执行' . ($is_succ ? '成功' : ('失败:' . $msg)), $is_succ ? 'info' : 'error');

        M('ExecHistory')->where(['id' => $td['id']])->setField([
            'status'        => $is_succ ? 2 : 3,
            'exec_end_time' => date("Y-m-d H:i:s"),
            'exec_content'  => json_encode([
                'is_success' => $is_succ,
                'msg'        => $msg,
                'content'    => [
                    'IP'   => $td['ip'] ?? '',
                    'port' => $td['port'] ?? '',
                    'arc'  => $td['arc'] ?? '',
                    'StatusCode' => $resp ? $resp->getStatusCode() : '',
                    'Header'     => $resp ? $resp->getRawHeader() : '',
                    'Content'    => $resp ? $resp->getContent() : ''
                ]
            ], JSON_UNESCAPED_UNICODE)
        ]);
    }

    /* 增加用例组用例的执行记录 */
    public function addGroupSingleExecHistory($td, $is_succ, $msg, $resp = null) {
        self::log('组单例执行' . ($is_succ ? '成功' : ('失败:' . $msg)), $is_succ ? 'info' : 'error');
        M('GroupExecHistory')->add([
            'exec_history_id' => $td['id'],
            'group_id'        => $td['mid'],
            'single_id'       => $td['single_id'],
            'issuccess'       => $is_succ,
            'exec_content'    => json_encode([
                'msg'     => $msg,
                'content' => [
                    'IP'   => $td['ip'] ?? '',
                    'port' => $td['port'] ?? '',
                    'arc'  => $td['arc'] ?? '',
                    'StatusCode' => $resp ? $resp->getStatusCode() : '',
                    'Header'     => $resp ? $resp->getRawHeader() : '',
                    'Content'    => $resp ? $resp->getContent() : ''
                ]
            ], JSON_UNESCAPED_UNICODE),
            'exec_start_time' => $td['stime'],
            'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * 在执行用例时, 写记录
     * 执行单个用例时，写入sys_exec_history
     * 执行用例组单例时, 写入sys_group_exec_history
     */
    public function addHistoryWhenExecSingle($td, $is_succ, $msg, $resp = null) {
        if (!$td['is_group']) {
            return $this->addExecHistory($td, $is_succ, $msg, $resp);
        }

        $this->addGroupSingleExecHistory($td, $is_succ, $msg, $resp);
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

}
