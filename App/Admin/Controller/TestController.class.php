<?php

namespace Admin\Controller;
use Think\Controller;

class TestController extends Controller {
    public function single() {
        /*single*/
        $taskData = [
            'isgroup'     => 0,
            'mid'         => 1,
            'uid'         => 1,
            'ip'          => '192.168.1.6',//ip必须真实存在
            'port'        => '8080',
            'create_time' => time(),
            'type' => 'IMME'
        ];

        /*同步添加任务,需接受返回数据*/
        $resp = SyncTask($taskData);
        DB($resp);
    }

    public function group() {
        //group
        $taskData = ["isgroup"=>1,"type"=>"IMME","mid"=>"22","uid"=>"1","ip"=>"192.168.1.6","port"=>"8080","create_time"=>time()];
        $resp = SyncTask($taskData);
        /*同步添加任务,需接受返回数据*/
        DB($resp);
    }

    public function multi_imme_single() {
        for ($x = 0; $x < 1; $x++) {
            $taskData = [
                'isgroup'     => 0,
                'mid'         => 1,
                'uid'         => 1,
                'ip'          => '192.168.1.6',//ip必须真实存在
                'port'        => '8080',
                'create_time' => time(),
                'type' => 'IMME'
            ];
            AddTask($taskData);
        }
    }

    public function multi_timer_single() {
        for ($x = 0; $x < 1; $x++) {
            $taskData = [
                'isgroup'     => 0,
                'mid'         => 1,
                'uid'         => 1,
                'ip'          => '192.168.1.6',//ip必须真实存在
                'port'        => '8080',
                'create_time' => time(),
                "run_at"=>time() + 15 + $x,
                'ver' => '1.0.0',
                'name' => 'task'. $x,
                'notify_email' => 'baluwu.carp@gmail.com',
                'description' => '这是一个注释',
                'type' => 'TIMER'
            ];
            AddTask($taskData);
        }
    }

    public function task() {
        //task
        $taskData = [
            "id"=>1,
            "isgroup"=>2,
            "type"=>"TIMER",
            "mid"=>"1,5",
            "description"=>"只是一个注释",
            'notify_email' => 'baluwu.carp@gmail.com',
            "ver"=>"1.0.1",
            "run_at"=>date('Y-m-d H:i:s', time() + 15),
            "uid"=>"1",
            "ip"=>"192.168.1.6",
            "port"=>"8080",
            "create_time"=>time()
        ];
        $resp = SyncTask($taskData);
        DB($resp);
    }

    public function json() {
        $r = M('ExecHistory')->select();

        $r[0]['exec_content'] = json_decode($r[0]['exec_content'], true);
        $r[1]['exec_content'] = json_decode($r[1]['exec_content'], true);
        echo json_encode($r[1]);
        //echo json_encode($r[1]);

    }
}
