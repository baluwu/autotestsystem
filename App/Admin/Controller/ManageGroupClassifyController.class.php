<?php
namespace Admin\Controller;

//用户组分类控制器
class ManageGroupClassifyController extends AuthController {
    //用户组分类
    public function index() {
        $this->display();
    }

    public function addNode()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $treeNode = I('get.treeNode', '');
        $data = array(
            'pid'=>$treeNode['pId'],
            'name'=>$treeNode['name'],
            'modify_time'=>date('Y-m-d H:i:s'),
        );
        D('ManageGroupClassify')->addData($data);
        $this->ajaxReturn($treeNode);
    }

    public function delNode()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $treeNode = I('get.treeNode', '');
        $id = isset($treeNode['id'])?$treeNode['id']:null;
        if( $id == null ) {
            $this->error('ID错误！');
        }
        D('ManageGroupClassify')->delData($id);
        $this->ajaxReturn($treeNode);
    }

    public function editNode()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $treeNode = I('get.treeNode', '');
        $id = $treeNode['id'];
        $data = array(
            'id'=>$id,
            'pid'=>$treeNode['pId'],
            'name'=>$treeNode['name'],
            'modify_time'=>date('Y-m-d H:i:s')
        );
        D('ManageGroupClassify')->saveData($id, $data);
        $this->ajaxReturn($treeNode);
    }

    //初始化节点
    public function getData($group = 0)
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $group_id = session('admin.group_id');
        $ret = D('AuthGroup')->getClassifyData($group_id, true);
        $this->ajaxReturn($ret);
    }

    public function getAllNodes()
    {
        $ret = D('ManageGroupClassify')->getList();
        $this->ajaxReturn($ret);
    }


}
