<?php

namespace Admin\Controller;
use Think\Controller;

class TestController extends Controller {
    public function single() {
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
