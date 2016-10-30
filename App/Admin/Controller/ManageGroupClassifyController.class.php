<?php
namespace Admin\Controller;

//用户组分类控制器
class ManageGroupClassifyController extends AuthController {
    //用户组分类
    public function index() {
        $this->display();
    }

    //初始化节点
    public function initData()
    {
        if (!IS_AJAX) $this->error('非法操作！');
        $ret = D('ManageGroupClassify')->getList();
        $this->ajaxReturn($ret);
    }


}
