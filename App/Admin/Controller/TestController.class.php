<?php

namespace Admin\Controller;
use Think\Controller;

class TestController extends Controller {
    public function single($mid) {
        /*single*/
        $taskData = [
            'isgroup'     => 0,
            'mid'         => $mid,
            'uid'         => 1,
            'ip'          => '192.168.1.6',//ip必须真实存在
            'port'        => '8080',
            'create_time' => REQUEST_TIME,
            'id' => 1,
            'type' => 'IMME'
        ];

        /*同步添加任务,需接受返回数据*/
        $resp = SyncTask($taskData);
        DB($resp);
    }

    public function group() {
        //group
        $taskData = ["isgroup"=>1,"type"=>"IMME","mid"=>"22","uid"=>"1","ip"=>"192.168.1.6","port"=>"8080","create_time"=>"2016-07-12 16:35:08","id"=>118];
        $resp = SyncTask($taskData);
        /*同步添加任务,需接受返回数据*/
        DB($resp);
    }

    public function multi_imme_single() {
        for ($x = 0; $x < 5; $x++) {
            $taskData = [
                'isgroup'     => 0,
                'mid'         => 1,
                'uid'         => 1,
                'ip'          => '192.168.1.6',//ip必须真实存在
                'port'        => '8080',
                'create_time' => REQUEST_TIME,
                'id' => 1,
                'type' => 'IMME'
            ];
            AddTask($taskData);
        }
    }

    public function multi_timer_single() {
        for ($x = 0; $x < 5; $x++) {
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
                'id' => 1,
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
            "mid"=>"47,48,49,61,61,62",
            "description"=>"只是一个注释",
            "ver"=>"1.0.1",
            "run_at"=>date('Y-m-d H:i:s', time() + 15),
            "uid"=>"1",
            "ip"=>"192.168.1.6",
            "port"=>"8080",
            "create_time"=>"2016-07-12 16:35:08"
        ];
        $resp = SyncTask($taskData);
        DB($resp);
    }

}
