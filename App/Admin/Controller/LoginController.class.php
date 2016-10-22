<?php
namespace Admin\Controller;

//后台登陆控制器
class LoginController extends BaseController {

  public function getRules() {
    return [
      'checkManager' => [
        'manager' => ['name' => 'manager', 'type' => 'string', 'require' => true, 'method'=>'post','desc' => '用户名'],
        'password' => ['name' => 'password', 'type' => 'string','min'=>6,'max'=>30,'require' => true, 'method'=>'post','desc' => '密码'],
        'isRemember' => ['name' => 'remember', 'type' => 'boolean', 'default'=>false, 'method'=>'post','desc' => '记住用户']
      ],
    ];
  }

    //显示登录页
    public function index() {
        $redirect_url = I('redirect_url');//返回链接
        $this->assign('redirect_url', $redirect_url);
        if (session('admin')) {
            $this->redirect('Index/index');
        } else {
            $this->assign('manager', session('admin.manager'));
            $this->display();
        }
    }

    //验证管理员
    public function checkManager() {

        if (!IS_AJAX) $this->error('非法操作！');
        $Manage = D('Manage');

        //ldap认证登陆
        if (C('LDAP_ENABLED') == 1) {
            $data = $Manage->ldap_register($this->manager, $this->password,$this->isRemember);
        } else {
            //普通登陆
            $data = $Manage->checkManager($this->manager, $this->password,$this->isRemember);
        }
        logs('login', $data > 0);
        echo $data;
    }

    //退出登录
    public function out() {
        session('admin', null);
        session('[regenerate]');
        $this->redirect('Login/index');
    }

}
