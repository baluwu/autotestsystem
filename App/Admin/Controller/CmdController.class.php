<?php

namespace Admin\Controller;
use Think\Controller;

class CmdController extends Controller {
    private $serv;
    
    public function index() {
        if (!IS_CLI) $this->error('此文件不能在非cli模式执行', '/Index');
        $this->serv = new \swoole_server('127.0.0.1', C('SWOOLE_PORT'));
        $this->serv->set(
            [
                'task_worker_num' => C('PARALLEL_TASKS'),
                'worker_num'      => 1,   //工作进程数量
                'backlog'         => 128,
                'daemonize'       => true, //是否作为守护进程
                'log_file'        => LOG_PATH . 'swoole_' . date('Y-m-d') . '.log',
                'log_level'       => 0
            ]
        );

        $worker = D('Worker');
        $tasker = D('Task');

        $this->serv->on('onStart', [$this, 'onStart']);
        $this->serv->on('connect', [$worker, 'onConnect']);
        $this->serv->on('WorkerStart', [$worker, 'onWorkerStart']);
        $this->serv->on('Receive', [$worker, 'onReceive']);
        $this->serv->on('Task', [$tasker, 'onTask']);
        $this->serv->on('Finish', [$worker, 'onFinish']);
        $this->serv->start();

        tasklog( '服务已启动, 开始监听端口: '.C('SWOOLE_PORT') );
    }

    public function onStart($serv, $fd) {
        tasklog('manager_pid' . $this->serv->manager_pid);
        tasklog('master_pid' . $this->serv->master_pid);     

    }

    public function demo() {
        /*single*/
        $taskData = [
            'isgroup'     => 0,
            'mid'         => 47,
            'uid'         => 1,
            'ip'          => '192.168.1.6',//ip必须真实存在
            'port'        => '8080',
            'create_time' => REQUEST_TIME,
            'id' => 1,
            'type' => 'IMME'
        ];

        /*同步添加任务,需接受返回数据*/
        SyncTask($taskData);
        return ;
        
        //group
        $taskData = ["isgroup"=>1,"type"=>"IMME","mid"=>"22","uid"=>"1","ip"=>"192.168.121.132","port"=>"","create_time"=>"2016-07-12 16:35:08","id"=>118];
        /*异步添加任务，无需返回*/
        AddTask($taskData);

        //task
        $taskData = [
            "id"=>1,
            "isgroup"=>2,
            "type"=>"TIMER",
            "mid"=>"47,48,49,50,51,52,53,54,55",
            "description"=>"只是一个注释",
            "ver"=>"1.0.1",
            "run_at"=>date('Y-m-d H:i:s', time() + 10),
            "uid"=>"1",
            "ip"=>"192.168.121.132",
            "port"=>"3333",
            "create_time"=>"2016-07-12 16:35:08"
        ];
        AddTask($taskData);
    }

}
