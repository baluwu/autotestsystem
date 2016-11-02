<?php
namespace Admin\Controller;


//用例组控制器
class GroupController extends AuthController {
    //显示用户用例
    public function index() {
        $this->display();
    }

    //获取用例组列表
    static $getListRules = [
        'order'       => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_type' => ['name' => 'search_type', 'type' => 'string', 'method' => 'post', 'desc' => '属性'],
        'search_name' => ['name' => 'search_name', 'type' => 'string', 'method' => 'post', 'desc' => '用例组名称'],
        'date_from'   => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'     => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
        'page_start'  => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'   => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'isAll'       => ['name' => 'all', 'type' => 'boolean', 'default' => false, 'method' => 'post', 'desc' => '是否输出所有记录'],
    ];

    public function getList() {
        //var_dump(I('get.classify'));exit;
        if (!IS_AJAX) $this->error('非法操作！');
        $Group = D('Group');
        $order = [
            'list'   => ['id', 'name', 'ispublic', 'create_time', 'uid'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];
        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }
        $where = [];
        if (in_array($this->search_type, ['public', 'self'])) {
            $where['ispublic'] = ($this->search_type == 'public' ? 1 : 0);
        }

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }
        $this->ajaxReturn($Group->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where));
    }


    //添加用例
    public function add() {
        $this->display();
    }

    //新增用例组
    static $addGroupRules = [
        'groupName' => ['name' => 'name', 'type' => 'string', 'min' => 2, 'max' => 20, 'method' => 'post', 'desc' => '用例组名称'],
        'ispublic'  => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
    ];

    public function addGroup() {
        if (!IS_AJAX) $this->error('非法操作');

        $Group = D('Group');
        $data = $Group->addGroup($this->groupName, $this->ispublic);
        logs('group.add', $data > 0);


        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -10,
            'data'  => $data,
            'msg'   => ''
        ]);
    }


    //编辑用例
    public function edit($id) {
        $Group = D('Group');

        $groupData = $Group->getGroup($id);
        if (!$groupData) {
            $this->error('该用例已被删除');
        }

        if (session('admin')['group_id'] !=1 && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法操作');
        }


        $this->assign('data', $groupData);
        $this->display();
    }


    //修改用例组
    static $updateGroupRules = [
        'id'        => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'groupName' => ['name' => 'name', 'type' => 'string', 'min' => 2, 'max' => 20, 'method' => 'post', 'desc' => '用例组名称'],
        'ispublic'  => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
    ];

    public function updateGroup() {
        if (!IS_AJAX) $this->error('非法操作');
        $Group = D('Group');
        $groupData = $Group->getGroup($this->id);
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }

        if (session('admin')['group_id'] !=1 && $groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '非法操作'
            ]);
        }


        $data = $Group->updateGroup($this->id, $this->groupName, $this->ispublic);
        logs('group.update', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);

    }

    //删除用例组
    static $RemoveRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Remove() {
        if (!IS_AJAX) $this->error('非法操作！');

        $Group = D('Group');
        $groupData = $Group->getGroup("$this->ids");
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }

        if ($groupData['uid'] != session('admin')['id']) {

            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }


        $data = $Group->Remove("$this->ids");

        logs('group.remove', $data > 0);

        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

//还原用例组
    static $RestoreRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Restore() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Group = D('Group');

        $groupData = $Group->getGroup("$this->ids");
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }

        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '非法操作'
            ]);
        }

        $data = $Group->Restore("$this->ids");

        logs('group.restore', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);

    }

    //回收站
    public function recycle() {
        $this->display();
    }

    //获取删除的用例组列表
    public function getRecycleList() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Group = D('Group');
        $order = [
            'list'   => ['id', 'name', 'ispublic', 'create_time', 'uid'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];
        if (in_array($this->search_type, ['public', 'self'])) {
            $where['ispublic'] = ($this->search_type == 'public' ? 1 : 0);
        }

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];

        if ($this->search_nlp) $where['nlp'] = ['like', '%' . $this->search_nlp . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }


        $this->ajaxReturn($Group->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where, 1));
    }

//用例组执行管理

    //执行
    static $ExecuteRules = [
        'id'   => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'ip'   => ['name' => 'ip', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'port' => ['name' => 'port', 'type' => 'int', 'method' => 'post', 'desc' => 'port'],
    ];

    public function Execute() {
        if (!IS_AJAX) $this->error('非法操作！');
        $group = D('Group');
        $groupData = $group->getGroup($this->id);
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '参数错误'
            ]);
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $excuteAr = D('ExecHistory');
        $data = $excuteAr->ExecuteGroup($this->id, $this->ip, $this->port);
        logs('group.execute', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    //执行记录
    public function execute_history($tid) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $this->assign('group', $groupData);

        $this->display();
    }

    //任务列表
    //
    public function tasks() {
        $this->display();
    }

    public function addTask(){
        $this->display();
    }

    //获取任务列表执行纪录
    static $getTasksRules = [
        'page_start'     => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'      => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'order'          => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_ip_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'date_from'      => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'        => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
    ];
    //获取任务列表
    public function getTasks(){
        //if (!IS_AJAX) $this->error('非法操作！');
        $order = [
            'list'   => ['id', 'ip', 'port', 'status', 'exec_start_time', 'uid'],
            'column' => 'exec_start_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }
        $where = [];
        if ($this->search_ip_name) $where['e.exec_content'] = ['like', '%' . $this->search_ip_name . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }


        $execHistory = D('ExecHistory');
        $this->ajaxReturn($execHistory->getList(0, 2, $this->page_start, $this->page_rows, $order['column'], $order['dir'], $where));
    }

    //获取组用例列表执行纪录
    static $getHistoryRules = [
        'id'             => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'page_start'     => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'      => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'order'          => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_ip_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'date_from'      => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'        => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
    ];

    public function getHistory($id) {
        if (!IS_AJAX) $this->error('非法操作！');
        $order = [
            'list'   => ['id', 'ip', 'port', 'status', 'exec_start_time', 'uid'],
            'column' => 'exec_start_time',
            'dir'    => "desc"
        ];
        $Group = D('Group');

        $groupData = $Group->getGroup($id);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn('非法参数');
        }

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }
        $where = [];
        if ($this->search_ip_name) $where['e.exec_content'] = ['like', '%' . $this->search_ip_name . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $single = D('ExecHistory');

        $this->ajaxReturn($single->getList($this->id, 1, $this->page_start, $this->page_rows, $order['column'], $order['dir'], $where));
    }

    //执行记录查看
    public function execute_history_show($tid, $id) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $this->assign('group', $groupData);


        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetById($id, 1);
        $this->assign('data', $data);

        $ExecHistoryRs = D('GroupExecHistory');
        $ExecHistory = $ExecHistoryRs->getbyid($id, 1);
        $this->assign('ExecHistory', $ExecHistory);

        $this->display();
    }

//  执行记录对比  
    static $execute_history_diffRules = [
        'ids' => ['name' => 'ids', 'type' => 'array','require'=>true,'format'=>'explode','separator' => ',', 'method' => 'get', 'desc' => 'history_ids'],
    ];
    public function execute_history_diff($tid) {

         if(count($this->ids) < 2){
            $this->error('参数至少为两个！');
        }

        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $this->assign('group', $groupData);



        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetByIds($this->ids, 1);

        $ExecHistoryRs = D('GroupExecHistory');
        foreach ($data as $k => $v) {
            $data[$k]['exec'] = $ExecHistoryRs->getbyid($data[$k]['id']);
        }

        $this->assign('execute_data', $data);
        $this->display();
    }


//////////////////////////////////////////用例组单例管理


    //以下是用例组的用例管理
    public function single($tid) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('非法参数');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $this->assign('group', $groupData);
        $this->display();
    }

    //获取组用例列表
    static $getSingleListRules = [
        'order'       => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_type' => ['name' => 'search_single_type', 'type' => 'string', 'method' => 'post', 'desc' => '属性'],
        'search_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => '用例名称'],
        'search_nlp'  => ['name' => 'search_single_nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP名称'],
        'date_from'   => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'     => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
        'page_start'  => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'   => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'isAll'       => ['name' => 'all', 'type' => 'boolean', 'default' => false, 'method' => 'post', 'desc' => '是否输出所有记录'],
    ];

    public function getSingleList($tid) {

        if (!IS_AJAX) $this->error('非法操作！');
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->ajaxReturn('非法参数');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn('非法参数');
        }

        $single = D('GroupSingle');
        $order = [
            'list'   => ['id', 'name', 'nlp', 'isrecovery', 'create_time'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];
        if (in_array($this->search_type, ['public', 'self'])) {
            $where['ispublic'] = ($this->search_type == 'public' ? 1 : 0);
        }

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];

        if ($this->search_nlp) $where['nlp'] = ['like', '%' . $this->search_nlp . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $this->ajaxReturn($single->getList($tid, $order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where));
    }


    //用例组添加
    public function single_add($tid) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $this->assign('group', $groupData);
        $this->display();
    }

    //用例组保存
    static $single_saveRules = [
        'tid'         => ['name' => 'tid', 'type' => 'int', 'method' => 'post', 'desc' => 'tid'],
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例组名称'],
        'ispublic'    => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'type_switch' => ['name' => 'type_switch', 'type' => 'string', 'method' => 'post', 'desc' => '类型'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array', 'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array',  'max' => 20,'method' => 'post', 'desc' => '验证规则value']
    ];

    public function single_save() {
        if (!IS_AJAX) $this->error("非法操作！");
        $group = D('Group');
        $groupData = $group->getGroup($this->tid);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        if ($this->type_switch) {
            if (!$this->arc) $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => 'arc为空'
            ]);
        } elseif (!$this->nlp) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => 'nlp为空'
            ]);
        }


        $single = D('GroupSingle');
        $data = $single->addSingle($this->singleName, $this->tid, $this->type_switch, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2);

        logs('group.single.add', $data > 0);

        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);
    }


    //用例组编辑
    public function single_edit($id, $tid) {
        //判断uid 和 session id 是否相同
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $this->assign('group', $groupData);

        $single = D('GroupSingle');
        $data = $single->getSingle($id);

        if (!$data) {
            $this->GroupSingle('该用例已被删除');
        }
        if ($data['tid'] != $tid) {
            $this->GroupSingle('非法参数');
        }


        $this->assign('data', $data);
        $this->assign('tid', $tid);
        $this->assign('id', $id);
        $this->display();

    }

    //用例组更新
    static $single_updateRules = [
        'id'          => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'tid'         => ['name' => 'tid', 'type' => 'int', 'method' => 'post', 'desc' => 'tid'],
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例名称'],
        'ispublic'    => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'type_switch' => ['name' => 'type_switch', 'type' => 'string', 'method' => 'post', 'desc' => '类型'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array',  'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array',  'max' => 20, 'method' => 'post', 'desc' => '验证规则value']
    ];

    public function single_update() {
        if (!IS_AJAX) $this->error("非法操作！");
        $group = D('Group');
        $groupData = $group->getGroup($this->tid);

        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }
        $single = D('GroupSingle');
        $data = $single->getSingle($this->id);

        if (!$data) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }
        if ($data['tid'] != $this->tid) {
            $this->ajaxReturn([
                'error' => -12,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $data = $single->updateSingle($this->id, $this->singleName, $this->type_switch, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2);

        logs('group.single.update', $data > 0);


        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -13,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    //删除用例管理中的用例
    static $single_removeRules = [
        'tid' => ['name' => 'tid', 'type' => 'int', 'method' => 'post', 'desc' => 'tid'],
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function single_remove() {
        if (!IS_AJAX) $this->error('非法操作！');
        $group = D('Group');
        $groupData = $group->getGroup($this->tid);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $single = D('GroupSingle');
        $singleData = $single->getSingle($this->ids);
        if (!$singleData) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }
        if ($singleData['tid'] != $this->tid) {
            $this->ajaxReturn([
                'error' => -12,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $data = $single->Remove("$this->ids");

        logs('group.single.remove', $data > 0);

        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -13,
            'data'  => $data,
            'msg'   => ''
        ]);

    }

    //组还原
    static $single_restoreRules = [
        'tid' => ['name' => 'tid', 'type' => 'int', 'method' => 'post', 'desc' => 'tid'],
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function single_restore() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Group = D('Group');
        $groupData = $Group->getGroup($this->tid);
        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法操作'
            ]);
        }
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }

        $single = D('GroupSingle');
        $singleData = $single->getSingle($this->ids);
        if (!$singleData) {
            $this->ajaxReturn([
                'error' => -12,
                'data'  => '',
                'msg'   => '该用例已被删除'
            ]);
        }
        if ($singleData['tid'] != $this->tid) {
            $this->ajaxReturn([
                'error' => -13,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $data = $single->Restore("$this->ids");
        logs('group.single.restore', $data > 0);


        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -14,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    //回收站
    public function single_recycle($tid) {
        $Group = D('Group');
        $groupData = $Group->getGroup($tid);
        if (!$groupData) {
            $this->erroe('该用例已被删除');
        }
        if ($groupData['uid'] != session('admin')['id']) {
            $this->erroe('非法操作');
        }


        $this->assign('group', $groupData);
        $this->display();
    }

    //获取被删除的列表
    public function getSingleRecycleList($tid) {

        if (!IS_AJAX) $this->error('非法操作！');
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->ajaxReturn('非法参数');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn('非法参数');
        }

        $single = D('GroupSingle');
        $order = [
            'list'   => ['id', 'name', 'nlp', 'validates', 'create_time'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];
        if (in_array($this->search_type, ['public', 'self'])) {
            $where['ispublic'] = ($this->search_type == 'public' ? 1 : 0);
        }

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];

        if ($this->search_nlp) $where['nlp'] = ['like', '%' . $this->search_nlp . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $this->ajaxReturn($single->getList($tid, $order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where, 1));
    }




//////////////////////////////////////////公共用例组管理

    //获取 公共用例组 首页
    public function pub() {
        $this->display();
    }

    //获取 公共用例组 列表
    public function getListPub() {
        if (!IS_AJAX) $this->error('非法操作！');
        $Group = D('Group');
        $order = [
            'list'   => ['id', 'name', 'create_time', 'uid'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = I('post.order');
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }
        $where = [];

        $where['ispublic'] = 1;


        if (I('post.search_name')) {
            $where['name'] = ['like', '%' . I('post.search_name') . '%'];
        }


        if (I('post.date_from')) {
            $where['create_time'] = ['egt', strtotime(I('post.date_from'))];
        }
        if (I('post.date_to')) {
            $where['create_time'] = ['elt', strtotime(I('post.date_to'))];
        }
        $this->ajaxReturn($Group->getList($order['column'], $order['dir'], I('post.start'), I('post.length'), I('post.all'), $where));
    }

    //公共用例组的用例列表
    public function single_publist($tid) {
        $Group = D('Group');

        $groupData = $Group->getGroup($tid);
        if (!$groupData) {
            $this->error('该用例已被删除');
        }

        if (!$groupData['ispublic']) {
            $this->error('非法参数');
        }

        $this->assign('group', $groupData);
        $this->display();
    }

    //公共用例组的用例列表数据获取
    static $getSinglePubListRules = [
        'order'       => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_type' => ['name' => 'search_single_type', 'type' => 'string', 'method' => 'post', 'desc' => '属性'],
        'search_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => '用例名称'],
        'search_nlp'  => ['name' => 'search_single_nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP名称'],
        'date_from'   => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'     => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
        'page_start'  => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'   => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'isAll'       => ['name' => 'all', 'type' => 'boolean', 'default' => false, 'method' => 'post', 'desc' => '是否输出所有记录'],
    ];

    public function getSinglePubList($tid) {

        if (!IS_AJAX) $this->error('非法操作！');
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->ajaxReturn('非法参数');
        }

        if (!$groupData['ispublic']) {
            $this->error('非法参数');
        }

        $single = D('GroupSingle');
        $order = [
            'list'   => ['id', 'name', 'nlp', 'isrecovery', 'create_time'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];
        if (in_array($this->search_type, ['public', 'self'])) {
            $where['ispublic'] = ($this->search_type == 'public' ? 1 : 0);
        }

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];

        if ($this->search_nlp) $where['nlp'] = ['like', '%' . $this->search_nlp . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $this->ajaxReturn($single->getList($tid, $order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where));

    }


    //执行记录
    public function execute_history_pub($tid) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $this->assign('group', $groupData);

        $this->display();
    }

    //获取组用例列表执行纪录
    static $getHistory_pubRules = [
        'id'             => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'page_start'     => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'      => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'order'          => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_ip_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'date_from'      => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'        => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
    ];

    public function getHistory_pub($id) {
        if (!IS_AJAX) $this->error('非法操作！');
        $order = [
            'list'   => ['id', 'ip', 'port', 'status', 'exec_start_time', 'uid'],
            'column' => 'exec_start_time',
            'dir'    => "desc"
        ];
        $Group = D('Group');

        $groupData = $Group->getGroup($id);
        if (!$groupData['ispublic']) {
            $this->ajaxReturn('非法参数');
        }

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }
        $where = [];
        if ($this->search_ip_name) $where['e.exec_content'] = ['like', '%' . $this->search_ip_name . '%'];

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }


        $single = D('ExecHistory');
        $this->ajaxReturn($single->getList($this->id, 1, $this->page_start, $this->page_rows, $order['column'], $order['dir'], $where));
    }

    //执行记录查看
    public function execute_history_pub_show($tid, $id) {
        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic']) {
            $this->error('非法参数');
        }
        $this->assign('group', $groupData);


        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetById($id, 1);
        $this->assign('data', $data);

        $ExecHistoryRs = D('GroupExecHistory');
        $ExecHistory = $ExecHistoryRs->getbyid($id, 1);
        $this->assign('ExecHistory', $ExecHistory);

        $this->display();
    }

//  执行记录对比
    static $execute_history_pub_diffRules = [
        'ids' => ['name' => 'ids', 'type' => 'array','require'=>true,'format'=>'explode','separator' => ',', 'method' => 'get', 'desc' => 'history_ids'],
    ];
    public function execute_history_pub_diff($tid) {
        if(count($this->ids) < 2){
            $this->error('参数至少为两个！');
        }

        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData) {
            $this->error('参数错误');
        }
        if (!$groupData['ispublic']) {
            $this->error('非法参数');
        }
        $this->assign('group', $groupData);


        $group = D('Group');
        $groupData = $group->getGroup($tid);
        if (!$groupData['ispublic'] && $groupData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetByIds($this->ids, 1);

        $ExecHistoryRs = D('GroupExecHistory');
        foreach ($data as $k => $v) {
            $data[$k]['exec'] = $ExecHistoryRs->getbyid($data[$k]['id']);
        }

        $this->assign('execute_data', $data);
        $this->display();
    }

}
