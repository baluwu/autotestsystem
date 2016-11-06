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

    //用例组列表
    public function getList($order, $sort, $page, $rows, $all = false, $where = [], $isrecovery = 0) {
        if ($all) {
            return $this->field('g.id,g.name,g.ispublic,u.manager,u.nickname')
                        ->join('g LEFT JOIN  __MANAGE__ u  ON g.uid = u.id')
                        ->order('g.ispublic asc')
                        ->select();
        }
        $map = [];
        foreach ($where as $key => $value) {
            $map['g.' . $key] = $value;
        }
        if (!isset($map['g.ispublic'])) $map['g.uid'] = ['eq', session('admin')['id']];

        $map['g.isrecovery'] = ['eq', $isrecovery];

        $obj = $this
            ->field('g.id,g.name,g.ispublic,g.create_time,g.uid,g.status,u.manager,u.nickname')
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

}
