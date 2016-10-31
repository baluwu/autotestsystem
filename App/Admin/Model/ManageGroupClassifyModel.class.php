<?php
namespace Admin\Model;

use Think\Model;
use Think\Auth;

//管理员模型
class ManageGroupClassifyModel extends Model {


    //获取管理员列表
    public function getList()
    {
        $ret = $this->select();
        return fmt_tree_data($ret);
    }

    public function saveData($id, $data)
    {
        return $this->where(array('id'=>$id))->save($data);
    }

    public function delData($id)
    {
        return $this->where(array('id'=>$id))->delete();
    }

    public function addData($data)
    {
        return $this->add($data);
    }


}