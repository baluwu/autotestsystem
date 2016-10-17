<?php
//空控制器
namespace Admin\Controller;
use Think\Controller;
class EmptyController extends AuthController{
	public function index(){
		$this->redirect('Index/index');
	}
}
