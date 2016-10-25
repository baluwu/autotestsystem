<?php
namespace Admin\Controller;

use Admin\Model\ExecHistoryModel;
use Think\Model;

//用户用例控制器
class SingleController extends AuthController {
    public function index() {
        $this->display();
    }

//添加用例
    public function add() {
        $priveGroup =D('Group')->getList('','','','',true);
        $this->assign('group', $priveGroup);    
        $this->display();
    }

    static $addSingleRules = [
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例名称'],
        'groupid'    => ['name' => 'groupid', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'ispublic'    => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'type_switch' => ['name' => 'type_switch', 'type' => 'string', 'method' => 'post', 'desc' => '类型'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array', 'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array', 'max' => 20, 'method' => 'post', 'desc' => '验证规则value']
    ];

    public function addSingle() {
        if (!IS_AJAX) $this->error("非法操作！");

        if ($this->type_switch) {
            if (!$this->arc) $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => 'arc为空'
            ]);
        } else if (!$this->nlp) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => 'nlp为空'
            ]);
        }

        if(empty($this->groupid)){
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '请选择用例分组'
            ]);   
        }

        //R7：创建用例，指定用例组
        $single = D('GroupSingle');
        $data = $single->addSingle($this->singleName, $this->groupid, $this->type_switch, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2);

        //$single = D('Single');
        //$data = $single->addSingle($this->singleName, $this->ispublic, $this->type_switch, $this->nlp, $this->arc, //$this->v1, $this->dept, $this->v2);

        logs('single.add', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

//编辑用例
    public function edit($id) {
        $single = D('Single');
        $singleData = $single->getSingle($id);
        if ($singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $priveGroup =D('Group')->getList('','','','',true);
        $this->assign('group', $priveGroup);
        if (!$singleData) {
            $this->error('该用例已被删除');
        }
        $this->assign('data', $singleData);

        $this->display();
    }

//修改用例
    static $updateSingleRules = [
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例名称'],
        'groupid'    => ['name' => 'groupid', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'ispublic'    => ['name' => 'property', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'type_switch' => ['name' => 'type_switch', 'type' => 'string', 'method' => 'post', 'desc' => '类型'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array', 'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array', 'max' => 20, 'method' => 'post', 'desc' => '验证规则value']
    ];

    public function updateSingle($id) {
        if (!IS_AJAX) $this->error('非法操作');
        $single = D('Single');

        $singleData = $single->getSingle($id);
        if ($singleData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        if(empty($this->groupid)){
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '请选择用例分组'
            ]);   
        }
        $single = D('GroupSingle');
        //编辑原来没有绑定分组的用例，做分组用例插入
        $data = $single->addSingle($this->singleName, $this->groupid, $this->type_switch, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2);
        D('Single')->Remove($id);//删除原用例
       
        logs('single.update', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -11,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

//删除用例
    static $RemoveRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Remove() {
        if (!IS_AJAX) $this->error('非法操作！');
        $single = D('Single');
        $singleData = $single->getSingle("$this->ids");
        if ($singleData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }
        $data = $single->Remove("$this->ids");

        logs('single.remove', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -11,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

//还原用例
    static $RestoreRules = [
        'ids' => ['name' => 'ids', 'type' => 'int', 'method' => 'post', 'desc' => 'ids'],
    ];

    public function Restore() {
        if (!IS_AJAX) $this->error('非法操作！');
        $single = D('Single');
        $singleData = $single->getSingle("$this->ids");
        if (!$singleData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }
        if ($singleData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }
        $data = $single->Restore("$this->ids");

        logs('single.restore', $data > 0);
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

//执行记录
    public function execute_history($id) {
        $single = D('Single');
        $singleData = $single->getSingle($id);
        if (!$singleData) {
            $this->error('非法参数');
        }
        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $this->assign('single', $singleData);
        $this->display();
    }


    public function execute_history_show($id) {
        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetById($id);

        if (!$data) {
            $this->error('非法参数');
        }

        if ($data['isgroup'] == 1) {
            $this->error('非法参数');
        }

        $single = D('Single');
        $singleData = $single->getSingle($data['mid']);
        if (!$singleData) {
            $this->error('非法参数');
        }

        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }


        $this->assign('single', $singleData);
        $this->assign('data', $data);

        $this->display();
    }

    static $execute_history_diffRules = [
        'history_ids' => ['name' => 'history_ids', 'type' => 'array','require'=>true,'format'=>'explode','separator' => ',', 'method' => 'get', 'desc' => 'history_ids'],
    ];
    public function execute_history_diff($id) {
        $ids = $this->history_ids;
        if(count($ids) < 2){
            $this->error('参数至少为两个');
        }

        $single = D('Single');
        $singleData = $single->getSingle($id);
        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }

        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetByIds($ids, 0, ['mid' => $id]);

        $this->assign('single', $singleData);
        $this->assign('execute_data', $data);
        $this->display();
    }



    public function pub() {
        $this->display();
    }

//执行记录
    public function execute_history_pub($id) {
        $single = D('Single');
        $singleData = $single->getSingle($id);
        if (!$singleData) {
            $this->error('非法参数');
        }
        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }
        $this->assign('single', $singleData);
        $this->display();
    }

    public function execute_history_pub_show($tid, $id) {
        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetById($id);

        if (!$data) {
            $this->error('非法参数');
        }

        if ($data['isgroup'] == 1) {
            $this->error('非法参数');
        }

        $single = D('Single');
        $singleData = $single->getSingle($tid);
        if (!$singleData) {
            $this->error('非法参数');
        }

        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }


        $this->assign('single', $singleData);
        $this->assign('data', $data);

        $this->display();
    }

    //公共用例对比记录
    static $execute_history_pub_diffRules = [
        'history_ids' => ['name' => 'history_ids', 'type' => 'array','require'=>true,'format'=>'explode','separator' => ',', 'method' => 'get', 'desc' => 'history_ids'],
    ];

    public function execute_history_pub_diff($id) {
        if (count($this->history_ids) < 2 ) {
            $this->error("参数至少为两个！");
        }

        $single = D('Single');
        $singleData = $single->getSingle($id);
        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->error('非法参数');
        }


        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetByIds($this->history_ids, 0, ['mid' => $id]);

        $this->assign('single', $singleData);
        $this->assign('execute_data', $data);
        $this->display();
    }


//获取用例列表
    static $getListRules = [
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

    public function getList() {
        if (!IS_AJAX) $this->error('非法操作！');
        $single = D('Single');
        $order = [
            'list'   => ['id', 'name', 'nlp', 'ispublic', 'validates', 'create_time'],
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

        $this->ajaxReturn($single->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where));

    }

    //获取删除的用例列表
    static $getRecyleListRules = [
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

    public function getRecycleList() {
        if (!IS_AJAX) $this->error('非法操作！');
        $single = D('Single');

        $order = [
            'list'   => ['id', 'uid', 'nlp', 'ispublic', 'validates', 'create_time'],
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

        $this->ajaxReturn($single->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where, 1));
    }


//获取公共用例列表
    public function getListPub() {
        if (!IS_AJAX) $this->error('非法操作！');
        $single = D('Single');

        $order = [
            'list'   => ['id', 'uid', 'nlp', 'ispublic', 'validates', 'create_time'],
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

        $this->ajaxReturn($single->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $this->isAll, $where, 0, 1));
    }


//获取用例列表执行纪录
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


        $this->ajaxReturn($single->getList($this->id, 0, $this->page_start, $this->page_rows, $order['column'], $order['dir'], $where));

    }


//执行
    static $ExecuteSingleRules = [
        'id'   => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'ip'   => ['name' => 'ip', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'port' => ['name' => 'port', 'type' => 'int', 'method' => 'post', 'desc' => 'port'],
    ];

    public function ExecuteSingle() {
        if (!IS_AJAX) $this->ajaxReturn("非法操作！");
        $single = D('Single');
        $singleData = $single->getSingle($this->id);
        if (!$singleData['ispublic'] && $singleData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '非法参数'
            ]);
        }

        $excuteAr = D('ExecHistory');
        $data = $excuteAr->ExecuteSingle($this->id, $this->ip, $this->port);
        logs('single.execute', $data > 0);
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -11,
            'data'  => $data,
            'msg'   => ''
        ]);
    }


    //文件上传
    public function uploadFile() {
        //登陆状态判断  return array('status'=>0,'msg'=>$Upload->getError());
        $File = D('File');
        $this->ajaxReturn($File->uploadFile());
    }

    public function uploadAsr() {
        $File = D('File');
        $this->ajaxReturn($File->uploadAsr());
    }
}
