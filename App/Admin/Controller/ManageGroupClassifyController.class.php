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
        $lv = intval(I('get.lv'));
        $nextId =  $this->nextId($pid);
        $data = array(
            'id' => $nextId,
            'pid' => $pid,
            'uid' => session('admin')['id'],
            'name'=> I('get.name'),
            'level' => $lv,
            'modify_time'=>date('Y-m-d H:i:s')
        );
        M('ManageGroupClassify')->add($data);
        $this->ajaxReturn(['error' => false, 'msg' => '', 'data' => $nextId]);
    }

    public function delNode()
    {
        $id = I('get.id');

        if (!IS_AJAX) $this->error('非法操作！');
        if (!canModify($id)) $this->ajaxReturn(['error' => true, 'msg' => '无权操作', 'code' => -1]);

        if( $id == null ) {
            $this->error('ID错误！');
        }

        $mdl = D('ManageGroupClassify');
        $r = $mdl->removeNode($id);

        $this->ajaxReturn(['error' => !$r, 'msg' => '']);
    }

    public function editNode()
    {
        $id = intval(I('get.id'));
        if (!IS_AJAX) $this->error('非法操作！');
        if (!canModify($id)) $this->ajaxReturn(['error' => true, 'msg' => '无权操作', 'code' => -1]);
        $name = I('get.name');
        $data = array(
            'name'=>$name,
            'modify_time'=>date('Y-m-d H:i:s')
        );
        D('ManageGroupClassify')->where(['id' => $id])->save($data);
        $this->ajaxReturn(['error' => false, 'msg' => '']);
    }

    public function getProjectData()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $ret = D('ManageGroupClassify')->getProjectData(I('get.project_id'));
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
