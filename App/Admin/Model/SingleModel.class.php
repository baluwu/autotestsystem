<?php
namespace Admin\Model;

use Think\Model;

//用户用例模型
class SingleModel extends Model {

    //自动验证
    protected $_validate = [
        //-1,'名称长度不合法！'
        ['name', '/^[^@]{2,100}$/i', '名称长度不合法！', self::EXISTS_VALIDATE],
        //-5,'帐号被占用！'
//    ['name', '', -5, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
    ];

    //用户表自动完成
    protected $_auto = [
        ['create_time', 'time', self::MODEL_INSERT, 'function'],
    ];



    public function getList($order, $sort, $page, $rows, $where = []) {
        if (!isset($where['tid'])) {
            $user_group_id = session('admin')['group_id'];
            $project_ids = M('AuthGroup')->where(['id' => $user_group_id])->getField('project_ids');
            
            if (!empty($project_ids)) {
                $model_ids = M('ManageGroupClassify')->where(['pid' => [ 'IN', $project_ids]])->getField('id', true);
                if (!empty($model_ids)) {
                    $group_ids = M('ManageGroupClassify')->where(['pid' => ['IN', $model_ids]])->getField('id', true);
                }
            }
        }

        foreach ($where as $key => $value) {
            $map['s.' . $key] = $value;
        }
        
        $obj = M('GroupSingle')
            ->field('s.id,s.uid,s.name,s.create_time,s.nlp,s.arc,u.manager,u.nickname')
            ->join('s LEFT JOIN  __MANAGE__ u  ON s.uid = u.id')
            ->where($map)
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();

        //转换属性及规则
        if ($obj) foreach ($obj as $k => $v) {
            $obj[$k]['short_name'] = mb_substr($v['name'],0,20,"utf-8") . (strlen($v['name']) > 20 ? '...' : '');
            //$obj[$k]['short_nlp'] = mb_substr($v['nlp'],0,20,"utf-8")."...";
            //$obj[$k]['short_arc'] = mb_substr($v['arc'],0,20,"utf-8")."...";
        }
        $total = M('GroupSingle')->where($map)->join('s LEFT JOIN  __MANAGE__ u  ON s.uid = u.id')->count();

        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }

    //获取数据总数
    public function countData($where = []) {
        return $this->where($where)->count();

    }

    //获取公共数据总数
    public function countPubData($where = []) {
        return $this->where($where)->count();

    }

    public function getListById($id) {
        $map = ['uid' => $id];
        $obj = $this->table('__SINGLE__')
            ->field('`id`,`uid`,`name`,`ispublic`,`create_time`,`nlp`,`arc`,`isrecovery`,`validates`')
            ->where($map)
            ->order(['create_time' => 'desc'])
            ->select();

        return $obj;
    }

    //新增用例
    public function addSingle($mc, $nlp, $arc, $v1, $dept, $v2) {
        $data = [
            'uid'      => session('admin')['id'],
            'name'     => $mc,
            'ispublic' => $property,
        ];
        if ($type_switch) {
            $data['arc'] = $arc;
        } else {
            $data['nlp'] = $nlp;
        }
        $data['validates'] = serialize($this->getVali($v1, $dept, $v2));

        if ($this->create($data)) {
            $data['create_time'] = REQUEST_TIME;
            $sid = $this->add($data);

            return $sid ? $sid : '';
        }
        return $this->getError();
    }

    //获取规则公用方法
    private function getVali($data1, $data2, $data3) {
        $temp = [];
        foreach ($data1 as $key => $value) {
            if ($value && $data2[$key] && $data3[$key]) {
                $temp[] = [
                    'v1'   => $value,
                    'dept' => $data2[$key],
                    'v2'   => $data3[$key],
                ];
            }
        }
        return $temp;
    }

    //获取一条数据
    public function getSingle($id) {
        $vali = $this
            ->field('s.id,s.name,s.ispublic,s.create_time,s.uid,s.validates,s.nlp,s.arc,u.manager,u.nickname,s.status')
            ->join('s LEFT JOIN  __MANAGE__ u  ON s.uid = u.id')
            ->where(['s.id' => $id])
            ->find();
        if (!$vali) return false;
        $vali['validates'] = unserialize($vali['validates']);
        return $vali;
    }

    //修改用例
    public function updateSingle($id, $mc_edit, $property_edit, $type_switch, $nlp_edit, $arc_edit, $v1_edit, $dept_edit, $v2_edit) {
        $data = [
            'id'       => $id,
            'name'     => $mc_edit,
            'ispublic' => $property_edit
        ];
        if ($type_switch) {
            $data['arc'] = $arc_edit;
            $data['nlp'] = '';
        } else {
            $data['arc'] = '';
            $data['nlp'] = $nlp_edit;
        }
        $data['validates'] = serialize($this->getVali($v1_edit, $dept_edit, $v2_edit));

        if (!$this->create($data)) {
            return $this->getError();
        }
        $sid = $this->save($data);

        return $sid ? $sid : '';

    }

    //删除到回收站
    public function Remove($ids) {
      return $this->where(['id' => ['IN', $ids]])->setField('isrecovery', 1);
    }

    //还原用例
    public function Restore($ids) {
        return $this->where(['id' => ['IN', $ids]])->setField('isrecovery', 0);

    }

    //状态修改
    public function setStatus($id, $status) {
        $rows = $this->where(['id' => $id])->setField('status', $status);

        return $rows;
    }

    public function setfields($id, $data = []) {
        return $this->where(['id' => $id])->setField($data);
    }
}
