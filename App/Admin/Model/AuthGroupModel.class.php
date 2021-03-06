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
        $classify_str = $this->where(array('id'=>$groupid))->getField('project_ids');
        $classify_arr = explode(',', $classify_str);

        $projects = M('ManageGroupClassify')->where(['level' => 0])->select();

        if (!empty($projects)) {
            foreach ($projects as &$project) {
                if ($groupid == 1 || $groupid ==3 || in_array($project['id'], $classify_arr)) {
                    $project['checked'] = true;
                }
                else $project['checked'] = false;
            }
        }

        return empty($projects) ? [] : $projects;
    }

    public function saveClassifyData()
    {
        return $this->where(array('id'=>$groupid))->save(array('classify'=>$classify_str));
    }

    public function getAllowedProjectIds($project_id) {
        $sess = session('admin');
        $ug_id = $sess['group_id'];

        if ($ug_id == 1 || $ug_id == 3) {
            return $project_id;
        }

        $ownPids = M('ManageGroupClassify')->where(['uid' => $sess['id'], 'level' => 0])->getField('id', true);
        $allowPidsStr = $this->where(['id' => $ug_id])->getField('project_ids');
        $allowPids = explode(',', $allowPidsStr);

        $enablePids = array_unique(array_merge($ownPids, $allowPids));
        return $project_id && in_array($project_id, $enablePids) ? $project_id : $enablePids;
    }

    public function getAllowedModelIds($projectIds) {
        return M('ManageGroupClassify')->where(['pid' => ['IN', $projectIds], 'level' => 1])->getField('id', true);
    }

    public function getAllowedGroupIds($modelIds) {
        return M('ManageGroupClassify')->where(['pid' => ['IN', $modelIds], 'level' => 2])->getField('id', true);
    }

    public function getGroupIds($project_id = 0) {
        $pro_ids = $this->getAllowedProjectIds($project_id);

        if (empty($pro_ids)) return 0;

        $model_ids = $this->getAllowedModelIds($pro_ids);

        if (!$model_ids) return 0;
        return $this->getAllowedGroupIds(implode(',', $model_ids));
    }
}
