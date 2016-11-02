<?php
namespace Admin\Controller;
//权限角色控制器
class AuthGroupController extends AuthController {

	//显示角色列表
     public function index(){
     	$this->display();
     }

     //获取角色列表
     public  function getList(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	$this->ajaxReturn($AuthGroup->getList(I('post.page'),I('post.rows')));
     }

     //新增角色
     public function addRole(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	echo $AuthGroup->addRole(I('post.title'),I('post.rules'));
     }

     //修改角色
     public function updateAuth(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	echo $AuthGroup->updateAuth(I('post.id'),I('post.rules_edit'));
     }

     //删除角色
     public function remove(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	echo $AuthGroup->remove(I('post.ids'));
     }

     //获取所有角色
     public function getListAll(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	$this->ajaxReturn($AuthGroup->getListAll());
     }

     //获取一条数据
     public function getAuth(){
     	if(!IS_AJAX) $this->error('非法操作！');
     	$AuthGroup = D('AuthGroup');
     	return $this->ajaxReturn($AuthGroup->getAuth(I('post.id')));
     }

	//保存用户分组分类
	public function save_classify()
	{
		if(!IS_AJAX) $this->error('非法操作！');
		$classify_str = I('get.classify_str', '');
		$group_id = I('get.group_id', '');
		return $this->ajaxReturn(D('AuthGroup')->saveClassifyData($group_id, $classify_str));
	}


}
?>
