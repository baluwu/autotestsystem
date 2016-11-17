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

    private function truncate($str) {
        $len = strlen($str);
        if ($len <= 16) return $str;
        return mb_substr($str, 0, 16, 'utf-8') . '..';
    }

    public function getProjectData($project_id) {
        $result = array();

        $project = M('ManageGroupClassify')->field('id,name,pid')->where(['id' => $project_id])->select();
        $project[0]['name'] = $this->truncate($project[0]['name']);

        if (!empty($project)) {
            $project[0]['open'] = true;
            $model = M('ManageGroupClassify')->field('id,name,pid')->where(['pid' => $project_id])->select();
            $model_ids = [];
            foreach ($model as $md) {
                $md['name'] = $this->truncate($md['name']);
                $project[] = $md;
                $model_ids[] = $md['id'];
            }
            
            if (!empty($model)) {
                $group = M('ManageGroupClassify')->where(['pid' => ['IN', implode(',', $model_ids)]])->select();
                foreach ($group as &$gp) {
                    $gp['group_id'] = $gp['id'];
                    $gp['name'] = $this->truncate($gp['name']);
                    $project[] = $gp;
                }
            }
        }

        return empty($project) ? [] : $project;
    }

    public function getSinglePathInfo($single_ids, $full_path = true) {
        $ret = [];

        $single_ids_str = implode(',', $single_ids);

        $single_group = M('GroupSingle')->where(['id' => ['IN', $single_ids_str]])->getField('id,tid');
    
        if (!$single_group) return $ret;

        $group_ids = implode(',', $single_group);
        $group_names = $this->where(['id' => ['IN', $group_ids]])->getField('id,name');

        $group_model = $this->where(['id' => ['IN', $group_ids]])->getField('id,pid');
        if (!$group_model) return $ret;

        $model_ids = implode(',', $group_model);
        $model_names = $this->where(['id' => $model_ids])->getField('id,name');

        $model_project = $this->where(['id' => ['IN', $model_ids]])->getField('id,pid');
        if (!$model_project) return $ret;

        $project_ids = implode(',', $model_project);
        $project_names = $this->where(['id' => ['IN', $project_ids]])->getField('id,name');

        foreach ($single_ids as $sid) {
            $path = '';

            $group_id = $single_group[$sid];
            $group_name = $group_names[$group_id];

            $model_id = $group_model[$group_id];
            $model_name = $model_names[$model_id];

            $project_id = $model_project[$model_id];
            $project_name = $project_names[$project_id];
            if ($full_path) {
                $ret[$sid] = truncate($project_name) . '/' . truncate($model_name) . '/' . truncate($group_name);
            }
            else $ret[$sid] = ['project' => truncate($project_name), 'model' => truncate($model_name), 'group' => truncate($group_name)];
        }       
        
        return $ret;
    }

    public function removeNode($id) {
        $classify = $this->where(['id' => $id])->find();

        if (!$classify) return false;

        $lv = $classify['level'];
        $remove_ids = [$id];

        if ($lv == 0) {
            $model_ids = $this->where(['pid' => $id])->getField('id', true);
            if (!$model_ids) $model_ids = [];
            $remove_ids = array_merge($remove_ids, $model_ids);
            $lv = 11;
        }

        if ($lv == 1 || $lv == 11) {
            $group_ids = $this->where(
                [ 'pid' => $lv == 1 ? $id : ['IN', $model_ids] ]
            )->getField('id', true);
            if (!$group_ids) $group_ids = [];
            $remove_ids = array_merge($remove_ids, $group_ids);

            $lv = 22;
        }
        
        if ($lv == 2 || $lv == 22) {
            M('GroupSingle')->where(
                [ 'tid' => $lv == 2 ? $id : ['IN', $group_ids] ]
            )->delete();
        }

        $this->where([ 'id' => [ 'IN', $remove_ids ] ])->delete();

        return true;
    }
}
