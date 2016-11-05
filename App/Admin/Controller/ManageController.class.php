<?php
namespace Admin\Controller;

//管理员控制器
class ManageController extends AuthController {
    //显示管理员列表
    public function index() {
        $this->display();
    }

    //获取管理员列表
    static $getListRules = [
        'order'           => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_username' => ['name' => 'search_username', 'type' => 'string', 'method' => 'post', 'desc' => '用例名称'],
        'search_name'     => ['name' => 'search_name', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP名称'],
        'date_from'       => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'         => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
        'page_start'      => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'       => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
    ];

    public function getList() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Manage = D('Manage');
        $order = [
            'list'   => ['ldap_uid', 'manager', 'nickname', 'email', 'email', 'create_time', 'last_login', 'last_ip', 'isrecovery'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];

        if ($this->search_username) $where['manager'] = ['like', '%' . $this->search_username . '%'];

        if ($this->search_name) $where['nickname'] = ['like', '%' . $this->search_name . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $this->ajaxReturn($Manage->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $where));
    }


    //添加管理员
    public function add() {
        $auth_group = D('AuthGroup')->getListAll();
        $this->assign('auth_group', $auth_group);
        $this->display();
    }

//新增管理员
    static $saveGroupRules = [
        'manager'    => ['name' => 'username', 'type' => 'string', 'method' => 'post', 'desc' => '用户名'],
        'password'   => ['name' => 'password', 'type' => 'string', 'method' => 'post', 'desc' => '管理员密码'],
        'repassword' => ['name' => 'repassword', 'type' => 'string', 'method' => 'post', 'desc' => '确认密码'],
        'name'       => ['name' => 'name', 'type' => 'string', 'method' => 'post', 'desc' => '用户组'],
        'email'      => ['name' => 'email', 'type' => 'string', 'method' => 'post', 'desc' => '邮箱'],
        'groupid'    => ['name' => 'groupid', 'type' => 'string', 'method' => 'post', 'desc' => '用户组'],
    ];

    public function saveGroup() {
        if (!IS_AJAX) $this->error('非法操作！');
        if (!$this->password || $this->password != $this->password) {
            logs('manage.add');
            $this->ajaxReturn(-1);
        }
        $Manage = D('Manage');

        $data = $Manage->saveManage($this->manager, $this->password, $this->repassword, $this->name, $this->email, $this->groupid);

        logs('manage.add', $data > 0);
        $this->ajaxReturn($data);
    }


    //编辑管理员
    public function edit($id) {
        $Manage = D('Manage');
        $user = $Manage->getManager($id);
        $this->assign('user', $user);
        $auth_group = D('AuthGroup')->getListAll();

        $this->assign('auth_group', $auth_group);
        $this->display();
    }


    //修改管理员
    static $updateRules = [
        'id'         => ['name' => 'id', 'type' => 'string', 'method' => 'post', 'desc' => '用户名'],
        'password'   => ['name' => 'password', 'type' => 'string', 'method' => 'post', 'desc' => '管理员密码'],
        'repassword' => ['name' => 'repassword', 'type' => 'string', 'method' => 'post', 'desc' => '确认密码'],
        'name'       => ['name' => 'name', 'type' => 'string', 'method' => 'post', 'desc' => '用户组'],
        'email'      => ['name' => 'email', 'type' => 'string', 'method' => 'post', 'desc' => '邮箱'],
        'groupid'    => ['name' => 'groupid', 'type' => 'string', 'method' => 'post', 'desc' => '用户组'],
    ];

    public function update() {
        if (!IS_AJAX) $this->error('非法操作！');
        if (!$this->password || $this->password != $this->password) {
            logs('manage.update');
            $this->ajaxReturn(-1);
        }
        $Manage = D('Manage');
        $res = $Manage->updateManage($this->id, $this->password, $this->repassword, $this->name, $this->email, $this->groupid);
        logs('manage.update', $res > 0);
        $this->ajaxReturn($res);
    }


    //删除管理员
    static $RemoveRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Remove() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Manage = D('Manage');
        $data = $Manage->remove("$this->ids");

        logs('manage.remove', $data > 0);
        $this->ajaxReturn($data);
    }

//恢复管理员
    static $RestoreRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Restore() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Manage = D('Manage');
        $data = $Manage->Restore("$this->ids");

        logs('manage.remove', $data > 0);
        $this->ajaxReturn($data);
    }


    public function group() {
        $this->display();
    }

    //用户组管理列表
    static $getGroupListRules = [
        'order'      => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'page_start' => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'  => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
    ];

    public function getGroupList() {
        if (!IS_AJAX) $this->error('非法操作！');
        $AuthGroup = D('AuthGroup');
        $order = [
            'list'   => ['id', 'title', 'rules'],
            'column' => 'id',
            'dir'    => "asc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $this->ajaxReturn($AuthGroup->getList($this->page_start, $this->page_rows, $order['column'], $order['dir']));
    }

    public function editgroup($id)
    {
        $groupid = $id;
        $auth_group = M('AuthGroup')->where(['id' => $groupid])->select();
        $classify = M('ManageGroupClassify')->where(['pid' => 0])->select();

        $this->assign('group_id', $groupid);
        $this->assign('group_name', $auth_group[0]['title']);
        $this->assign('classify', $classify);
        $this->display();
    }

    public function getClassifyData($group_id)
    {
        if( empty($group_id) ) {
            $this->error('参数传入不正确！');
        }
        $ret = D('AuthGroup')->getClassifyData($group_id);
        $this->ajaxReturn($ret);
    }

    public function saveGroupClassify() {
        $gid = I('post.id');
        $classify_str = I('post.classify_str');
        
        if (!$gid) {
            E('参数非法');
        }

        $r = M('AuthGroup')->where(['id' => $gid])->save([
            'classify' => $classify_str
        ]);

        $this->ajaxReturn($r >= 0 ? true : false);
    }
}
