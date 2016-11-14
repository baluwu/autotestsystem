<?php
namespace Admin\Model;

use Think\Model;

//权限角色模型
class AuthGroupModel extends Model {

    //权限角色自动验证
    protected $_validate = [
        //角色名称不能为空-1
        ['title', '/^[^@]{2,20}$/i', -1, self::EXISTS_VALIDATE],
        //权限规则不能为空-2
        ['rules', '/^[^@]{2,20}$/i', -2, self::EXISTS_VALIDATE],
        //角色名称被占用 -3
        ['title', '', -3, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
    ];

    //获取角色列表
    public function getList($page, $rows, $order, $sort) {
        $obj = $this->field('id,title,rules')
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();
        foreach ($obj as $key => $value) {
            $map['id'] = ['in', $value['rules']];
            $titAR = M('AuthRule')->field('title')->where($map)->select();
            foreach ($titAR as $k => $v) {
                $obj[$key]['auth'] .= $v['title'] . ',';
            }
            $obj[$key]['auth'] = substr($obj[$key]['auth'], 0, -1);
        }

        $total = $this->count();
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }

    //新增角色
    public function addRole($title, $rules) {
        $map['title'] = ['in', $rules];
        $idAr = M('AuthRule')->field('id')->where($map)->select();
        $ids = '';
        foreach ($idAr as $key => $value) {
            $ids .= $value['id'] . ',';
        }
        $ids = substr($ids, 0, -1);
        $data = [
            'title' => $title,
            'rules' => $ids
        ];

        if ($this->create($data)) {
            $aid = $this->add($data);
            return $aid ? $aid : 0;
        } else {
            return $this->getError();
        }

    }

    //修改角色
    public function updateAuth($id, $rules_edit) {
        $map['title'] = ['in', $rules_edit];
        $idAr = M('AuthRule')->field('id')->where($map)->select();
        $ids = '';
        foreach ($idAr as $value) {
            $ids .= $value['id'] . ',';
        }
        $ids = substr($ids, 0, -1);
        $data = [
            'id'    => $id,
            'rules' => $ids
        ];

        return $this->save($data);
    }

    //删除角色
    public function remove($ids) {
        return $this->delete($ids);
    }

    //获取所有角色
    public function getListAll() {
        return $this->field('id,title')->select();
    }

    //获取一条数据
    public function getAuth($id) {
        $obj = $this->field('id,title,rules')->where(['id' => $id])->find();
        return $obj;
    }

    public function getClassifyData($groupid, $filter = false)
    {
        $result = array();
        $classify_str = $this->where(array('id'=>$groupid))->getField('classify');
        $classify_arr = explode(',', $classify_str);

        $project = M('ManageGroupClassify')->where(['id' => ['IN', $classify_str]])->select();
        $model = M('ManageGroupClassify')->where(['pid' => ['IN', $classify_str]])->select();

        $model_ids = [];
        foreach ($model as $md) {
            $project[] = $md;
            $model_ids[] = $md['id'];
        }

        if (!empty($model_ids)) {
            $group = M('ManageGroupClassify')->where(['pid' => ['IN', $model_ids]])->select();
            foreach ($group as &$gp) {
                $gp['group_id'] = $gp['id'];
                $project[] = $gp;
            }
        }

        return empty($project) ? [] : $project;
    }

    public function saveClassifyData()
    {
        return $this->where(array('id'=>$groupid))->save(array('classify'=>$classify_str));
    }

    public function getAllowedProjectIds() {
        return $this->where(['id' => session('admin')['group_id']])->getField('project_ids');
    }

    public function getAllowedModelIds($projectIds) {
        return M('ManageGroupClassify')->where(['pid' => ['IN', $projectIds], 'level' => 1])->getField('id', true);
    }

    public function getAllowedGroupIds($modelIds) {
        return M('ManageGroupClassify')->where(['pid' => ['IN', $modelIds], 'level' => 2])->getField('id', true);
    }

    public function getGroupIds($project_id = 0) {
        $pro_ids = $this->getAllowedProjectIds();

        if (!$pro_ids) return 0;

        if ($project_id && !in_array($project_id, explode(',', $pro_ids))) {
            return 0;
        }

        $allow_pro_ids = $project_id ? $project_id : $pro_ids;

        $model_ids = $this->getAllowedModelIds($allow_pro_ids);

        if (!$model_ids) return 0;
        return $this->getAllowedGroupIds(implode(',', $model_ids));
    }
}
