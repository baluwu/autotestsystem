<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Auth;

//权限控制器
class AuthController extends BaseController {

  protected $not_verify_action = []; //不需要权限的 ACTION_NAME

  protected function _initialize() {

    parent::_initialize();

    if (!$this->checkAdminSession()) {
      session('admin', null);
      session('[regenerate]');
      if (IS_AJAX) {
        $this->gotoURL('当前用户未登录或登录超时，请重新登录！', 0, 'Login/index?redirect_url='.get_now_url(), false);
        die();
      }
      echo '<script>window.top.location.href = "/Login/index?redirect_url='.get_now_url().'";</script>';
      die();
    };


    if (!empty($this->not_verify_action)  && in_array(ACTION_NAME, $this->not_verify_action)) {
      return;
    }
    $Auth = new Auth();
    if (!$Auth->check(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ',' . MODULE_NAME . '/' . CONTROLLER_NAME, session('admin')['id'])) {
      echo '<p style="margin:10px;color:red;">对不起，您没有权限操作此模块！</p>';
      exit();
    }


  }


  /**
   *设置登陆超时 //todo 判断错误
   */
  public function checkAdminSession() {
    if (!session('admin')) {
      return false;
    }
    //设置超时为2小时
    $nowtime = time();
    $session_time =strtotime(session('admin')['logtime']);;
    if (($nowtime - $session_time) > 7200) {
      return false;
    } else {
      session('admin.logtime',date("Y-m-d H:i:s"));
      return true;
    }
  }


}
