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
        $ret = D('ManageGroupClassify')->getList();
        $classify_str = $this->where(array('id'=>$groupid))->getField('classify');
        $classify_arr = explode(',', $classify_str);

        $groups = M('Group')->where(['classify' => ['IN', $classify_str] ])->select();
        $groups = arrayGroup($groups, 'classify');

        if( !empty($classify_arr) && !empty($ret)) {
            foreach( $ret as $k=>$v ) {
                if($filter && !in_array($v['id'], $classify_arr)) {
                    unset($ret[$k]);
                    continue;
                }

                $v['checked'] = false;

                $classify_groups = $groups[$v['id']];

                $result[] = $v;
                if (!empty($classify_groups)) {
                    foreach ($classify_groups as $k => &$iv) {
                        $iv['id'] = $v['id'] . $k;
                        $iv['pid'] = $v['id'];

                        $result[] = $iv;                        
                    }
                }
            }
        }

        return fmt_tree_data($result);
    }

    public function saveClassifyData()
    {
        return $this->where(array('id'=>$groupid))->save(array('classify'=>$classify_str));
    }
}
