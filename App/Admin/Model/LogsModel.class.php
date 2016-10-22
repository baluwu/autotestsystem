<?php
namespace Admin\Model;

use Think\Model;

//操作日志模型
class LogsModel extends Model {
    private $action = [
        'login'                => '登陆系统',
        'logout'               => '退出登录',


        'manage.add'           => '添加了一个管理员',
        'manage.update'        => '修改了一个管理员',
        'manage.remove'        => '删除了一个管理员',
        'manage.execute'       => '执行了一个管理员',
        'manage.restore'       => '还原了一个管理员',


        'single.add'           => '添加了一个用例',
        'single.update'        => '修改了一个用例',
        'single.remove'        => '删除了一个用例',
        'single.execute'       => '执行了一个用例',
        'single.restore'       => '还原了一个用例',


        'group.add'            => '添加了一个用例组',
        'group.update'         => '修改了一个用例组',
        'group.remove'         => '删除了一个用例组',
        'group.execute'        => '执行了一个用例组',
        'group.restore'        => '还原了一个用例组',


        'group.single.add'     => '添加了一个组用例',
        'group.single.update'  => '修改了一个组用例',
        'group.single.remove'  => '删除了一个组用例',
        'group.single.restore' => '还原了一个组用例',
    ];

    //获取日志列表
    public function getList($order, $sort, $page, $rows) {
        $map = [];

        $map['uid'] = ['eq', session('admin')['id']];
        $obj = $this->field('id,uid,act,status,operate_time')
            ->where($map)
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();

        foreach ($obj as $key => $value) {
            $obj[$key]['act'] = $value['act'];
            if ($this->action[$value['act']]) {
                $obj[$key]['act'] = $this->action[$value['act']];
            }

            if ($value['status'] == 1) {
                $obj[$key]['status'] = "成功";
            } else {
                $obj[$key]['status'] = '失败';
            }

        }
        $total = $this->where($map)->count();
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];

    }

    //删除日志
    public function remove($ids) {
        return $this->delete($ids);
    }

    //添加日志
    public function addLog($data) {
        return $this->add($data);
    }


}
