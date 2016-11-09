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
        $pid = intval(I('get.pid'));
        $nextId =  $this->nextId($pid);
        $data = array(
            'id' => $nextId,
            'pid' => $pid,
            'name'=>I('get.name'),
            'modify_time'=>date('Y-m-d H:i:s')
        );
        M('ManageGroupClassify')->add($data);
        $this->ajaxReturn(['error' => false, 'msg' => '', 'data' => $nextId]);
    }

    public function delNode()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $id = I('get.id');
        if( $id == null ) {
            $this->error('ID错误！');
        }
        D('ManageGroupClassify')->where(['id' => $id])->delete();
        $this->ajaxReturn(['error' => false, 'msg' => '']);
    }

    public function editNode()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $id = intval(I('get.id'));
        $name = I('get.name');
        $data = array(
            'name'=>$name,
            'modify_time'=>date('Y-m-d H:i:s')
        );
        D('ManageGroupClassify')->where(['id' => $id])->save($data);
        $this->ajaxReturn(['error' => false, 'msg' => '']);
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

    public function nextId() {
        $nextId = M('ManageGroupClassify')->max('id');
        return $nextId ? $nextId + 1 : 1;
    }
}
