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
        $data['uid'] = session('admin')['id'];
        return $this->add($data);
    }

    public function getProjectData($project_id) {
        $result = array();

        $project = M('ManageGroupClassify')->field('id,name,pid')->where(['id' => $project_id])->select();
        if (!empty($project)) {
            $project[0]['open'] = true;
            $model = M('ManageGroupClassify')->field('id,name,pid')->where(['pid' => $project_id])->select();
            $model_ids = [];
            foreach ($model as $md) {
                $project[] = $md;
                $model_ids[] = $md['id'];
            }
            
            if (!empty($model)) {
                $group = M('ManageGroupClassify')->where(['pid' => ['IN', implode(',', $model_ids)]])->select();
                foreach ($group as &$gp) {
                    $gp['group_id'] = $gp['id'];
                    $project[] = $gp;
                }
            }
        }

        return empty($project) ? [] : $project;
    }
}
