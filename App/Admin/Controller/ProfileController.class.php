<?php
namespace Admin\Controller;

//修改密码控制器
class ProfileController extends AuthController {

    //修改密码视图
    public function index() {

        $info = D('manage')->getManager(session('admin')['id']);
        $this->assign('info', $info);
        $this->display();
    }

    //修改密码
    static $updatePassRules = [
        'id'       => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'password' => ['name' => 'password', 'type' => 'string', 'method' => 'post', 'desc' => '密码'],
        'newpass'  => ['name' => 'newpass', 'type' => 'string', 'method' => 'post', 'desc' => '新密码'],
        'respass'  => ['name' => 'respass', 'type' => 'string', 'method' => 'post', 'desc' => '确认密码'],
    ];

    public function updatePass() {
        if (!IS_AJAX) $this->error('非法操作！');
        $pwd = D('UpdatePassword');
        echo $pwd->updatePass($this->id, $this->password, $this->newpass, $this->respass);
    }

}
