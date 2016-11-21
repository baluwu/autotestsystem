<?php
namespace Admin\Model;

use Think\Model;

//用例组模型
class GroupModel extends Model {
    //自定义数据表
//  protected $tableName = 'type';
    //自动验证
    protected $_validate = [
        //-1,'名称长度不合法！'
        ['name', '/^[^@]{2,100}$/i', -1, self::EXISTS_VALIDATE],
        //-2 '名称被占用'
        ['name', '', -2, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
    ];
    //用户表自动完成
    protected $_auto = [
        ['create_time', 'time', self::MODEL_INSERT, 'function'],
    ];

    /*
    public function getList($order, $sort, $page, $rows, $all = false, $where = [], $isrecovery = 0) {
        $allow_group_ids = $this->getAllowGroupIds();
        if ($all) {
            return $this->field('g.id,g.name,g.ispublic,u.manager,u.nickname')
                ->join('g LEFT JOIN  __MANAGE__ u  ON g.uid = u.id')
                ->where(['g.id' => ['IN', $allow_group_ids]])
                ->order('g.ispublic asc')
                ->select();
        }
        $map = [];
        $map['g.id'] = ['IN', $allow_group_ids];
        foreach ($where as $key => $value) {
            $map['g.' . $key] = $value;
        }
        if (!isset($map['g.ispublic'])) $map['g.uid'] = ['eq', session('admin')['id']];

        $map['g.isrecovery'] = ['eq', $isrecovery];

        $obj = $this
            ->field('g.id,g.name,g.ispublic,g.create_time,g.uid, 0 as status,u.manager,u.nickname')
            ->join('g LEFT JOIN  __MANAGE__ u  ON g.uid = u.id')
            ->where($map)
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();

        if ($obj) foreach ($obj as $key => $value) {
            if ($value['ispublic'] == 0) {
                $obj[$key]['ispublic'] = '私有';
            } else if ($value['ispublic'] == 1) {
                $obj[$key]['ispublic'] = '公共';
            }
        }

        $total = $this->where($map)->join('g LEFT JOIN  __MANAGE__ u  ON g.uid = u.id')->count();
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }
    */
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
            $obj[$k]['short_name'] = mb_substr($v['name'],0,30,"utf-8") . (strlen($v['name']) > 30 ? '...' : '');
        }

        $total = M('GroupSingle')->where($map)->join('s LEFT JOIN  __MANAGE__ u  ON s.uid = u.id')->count();

        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }



    //新增用例组
    public function addGroup($name, $ispublic, $classify) {
        $data = [
            'uid'        => session('admin')['id'],
            'name'       => $name,
            'ispublic'   => $ispublic,
            'isrecovery' => 0,
            'classify' => $classify
        ];

        if ($this->create($data)) {
            $data['create_time'] = REQUEST_TIME;
            $obj = $this->add($data);

            return $obj ? $obj : '';
        } else {
            return $this->getError();
        }
    }

    //修改用例组
    public function updateGroup($id, $name, $ispublic, $classify) {
        $data = [
            'id'       => $id,
            'name'     => $name,
            'ispublic' => $ispublic,
            'classify' => $classify
        ];

        $obj = $this->save($data);

        return $obj ? $obj : 0;

    }

    //获取数据总数
    public function countData($where = []) {
        return $this->where($where)->count();
    }

    //获取一条数据
    public function getGroup($id) {
        return $this
            ->field('g.id,g.name,g.ispublic,g.create_time,g.classify,g.uid,u.manager,u.nickname')
            ->join('g LEFT JOIN  __MANAGE__ u  ON g.uid = u.id')
            ->where(['g.id' => $id])
            ->find();
    }

    //删除 用例组
    public function Remove($ids) {
        return $this->where(['id' => ['IN', $ids]])->setField('isrecovery', 1);
    }

    //还原 用例组
    public function Restore($ids) {
        return $this->where(['id' => ['IN', $ids]])->setField('isrecovery', 0);
    }

    //状态修改
    public function setStatus($id, $status) {
        return $this->where(['id' => $id])->setField('status', $status);
    }

    public function setfields($id, $data = []) {
        return $this->where(['id' => $id])->setField($data);
    }

    public function getAllowGroupIds() {
        $group_id = session('admin')['group_id'];
        $classify = M('AuthGroup')->where(['id' => $group_id])->select();
        $classify_ids = $classify[0]['classify'];

        $group_ids = M('Group')->field('id')->where(['classify' => ['IN', $classify_ids]])->select();

        $temp = [];
        foreach ($group_ids as $v) {
            $temp[] = $v['id'];
        }
        return implode(',', $temp);
    }

}
