<?php
namespace Admin\Model;

use Think\Model;
use Think\Auth;

//管理员模型
class ManageGroupClassifyModel extends Model {


    //获取管理员列表
    public function getList()
    {
        $ret = $this->field('id,pid as pId,name')->select();
        return $ret;
    }
}
