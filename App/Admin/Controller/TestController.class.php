<?php

namespace Admin\Controller;
use Think\Controller;
use Common\Libs\Tool;

class TestController extends Controller {

    /**
      *邮件发送demo。邮件服务默认配置在Common/Conf/config.php文件
      *接收方邮箱，qq类型邮箱回被过滤，163可能被当成垃圾邮件
      */
    public function mail(){
        $rz = Tool::mail('baluwu.crap@gmail.com', 'Test PHPMailer Lite 1', 'something here ...');
        echo "<pre>";
        print_r($rz);
    }

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
            "run_at"=>time() + 10,
            "uid"=>"1",
            "ip"=>"192.168.1.6",
            "port"=>"8080",
            "create_time"=>time()
        ];
        $resp = SyncTask($taskData);
        DB($resp);
    }
}
