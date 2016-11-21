<?php
namespace Admin\Controller;


//用例组控制器
class GroupController extends AuthController {
    //显示用户用例
    public function index() {
        $where = ['level' => 0];

        $sess = session('admin');
        $user_group_id = $sess['group_id'];

        if ($user_group_id != 1 && $user_group_id != 3) {
            /* 管理员配置允许访问的项目 */
            $allowProjectIds = M('AuthGroup')->where(['id' => $user_group_id])->getField('project_ids');
            $allowProjectIdsArr = explode(',', $allowProjectIds);
            /* 当前用户自己创建的项目 */
            $ownProjectIdsArr = M('ManageGroupClassify')->where(['uid' => $sess['id'], 'level' => 0])->getField('id', true);

            $enableProjectIdsArr = array_merge($allowProjectIdsArr, $ownProjectIdsArr);

            if (!empty($enableProjectIdsArr)) {
                $where['id'] = ['IN', $enableProjectIdsArr];
            }
        }

        $projects = M('ManageGroupClassify')->field('id, name')->where($where)->select();

        foreach ($projects as &$p) {
            if (strlen($p['name']) > 22) {
                $p['name'] = mb_substr($p['name'], 0, 22, 'utf8') . '..';
            }
        } 
        $this->assign('projects', $projects);
        $this->assign('firstname', !empty($projects) ? $projects[0]['name'] : '暂无项目');
        $this->assign('project_id', !empty($projects) ? $projects[0]['id'] : 0);
        $this->display();
    }

    //获取用例列表
    static $getListRules = [
        'order'       => ['name' => 'order', 'type' => 'array', 'format' => 'json', 'method' => 'post', 'desc' => '排序'],
        'search_type' => ['name' => 'search_single_type', 'type' => 'string', 'method' => 'post', 'desc' => '属性'],
        'search_name' => ['name' => 'search_single_name', 'type' => 'string', 'method' => 'post', 'desc' => '用例名称'],
        'case_type'  => ['name' => 'case_type', 'type' => 'string', 'method' => 'post', 'desc' => '类型'],
        'date_from'   => ['name' => 'date_from', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '开始时间'],
        'date_to'     => ['name' => 'date_to', 'type' => 'date', 'format' => 'Y-m-d H:i:s', 'method' => 'post', 'desc' => '结束时间'],
        'page_start'  => ['name' => 'start', 'type' => 'int', 'default' => 0, 'method' => 'post', 'desc' => '第几条记录开始'],
        'page_rows'   => ['name' => 'length', 'type' => 'int', 'default' => 20, 'method' => 'post', 'desc' => '输出多少条记录'],
        'isAll'       => ['name' => 'all', 'type' => 'boolean', 'default' => false, 'method' => 'post', 'desc' => '是否输出所有记录'],
    ];

    public function getList() {
        if (!IS_AJAX) $this->error('非法操作！');
        
        $order = [
            'list'   => ['id', 'name', 'nlp', 'nickname', 'create_time'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        $where = [];

        if ($this->search_name) $where['name'] = ['like', '%' . $this->search_name . '%'];
        if ($this->case_type && $this->case_type != 'all') {
            if ($this->case_type == 'nlp') $where['nlp'] = ['exp', ' IS NOT NULL AND nlp <> \'\'  '];
            else $where['arc'] = ['exp', ' IS NOT NULL AND arc <> \'\' '];
        }

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }

        $group_ids = I('post.group_ids', 0);
        $project_id = I('post.project_id', 0);

        if (!$project_id) {
            $this->ajaxReturn(["recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
        }

        $gid = session('admin')['group_id'];
        $is_admin = $gid == 1 || $gid == 3;
        $group_ids_arr = explode(',', $group_ids);

        if ($is_admin) {
            if($group_ids) {
                $where['tid'] = ['IN', $group_ids];
            }
            else if ($project_id) {
                $allow_group_ids = D('AuthGroup')->getGroupIds($project_id);
                $where['tid'] = ['IN', implode(',', $allow_group_ids)];
            }
        }
        else {
            $allow_group_ids = D('AuthGroup')->getGroupIds($project_id);

            if (!$allow_group_ids) {
                $this->ajaxReturn(["recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            }

            if ($group_ids) {
                $allow_group_ids = array_intersect($allow_group_ids, $group_ids_arr);
            }

            $where['tid'] = ['IN', implode(',', $allow_group_ids)];
        }
        
        $this->ajaxReturn(D('Group')->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows, $where));
    }

    //添加用例
    public function add() {
        $group_id = intval(I('get.group_id'));

        $group = M('ManageGroupClassify')->field('pid,name')->where(['id' => $group_id])->find();
        if (!$group) { $this->error('用例组不存在', '#'); }
        $this->assign('group_name', truncate($group['name']));

        $model = M('ManageGroupClassify')->field('pid,name')->where(['id' => $group['pid']])->find();
        if (!$model) { $this->error('模块不存在', '#'); }
        $this->assign('model_name', truncate($model['name']));

        $project = M('ManageGroupClassify')->where(['id' => $model['pid']])->getField('name');
        if (!$project) { $this->error('项目不存在', '#'); }
        $this->assign('project_name', truncate($project));

        $this->assign('user', session('admin')['nickname']);

        $this->assign('group_id', $group_id);     
        $this->display();

    }

    //编辑用例
    public function edit($id) {
        $data = M('GroupSingle')->where(['id'=>intval($id)])->find();
        if (!$data) {
            $this->GroupSingle('该用例已被删除');
        }
        $data['validates'] = unserialize($data['validates']);
        $path = D('ManageGroupClassify')->getSinglePathInfo([$id], false);

        $nickname = M('Manage')->where(['id' => $data['uid']])->getField('nickname');
        $this->assign('data', $data);    
        $this->assign('owner', $nickname);    
        $this->assign('path', $path[$id]);    
        $this->display();
    }

    //执行
    static $ExecuteRules = [
        'id'   => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
        'ip'   => ['name' => 'ip', 'type' => 'string', 'method' => 'post', 'desc' => 'ip'],
        'port' => ['name' => 'port', 'type' => 'int', 'method' => 'post', 'desc' => 'port'],
    ];

    public function Execute() {
        if (!IS_AJAX) $this->error('非法操作！');
        $groupData = M('ManageGroupClassify')->where([ 'id' => $this->id ])->find();
        if (!$groupData) {
            $this->ajaxReturn([
                'error' => -10,
                'data'  => '',
                'msg'   => '参数错误'
            ]);
        }

        if ($groupData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '无权限'
            ]);
        }

        $excuteAr = D('ExecHistory');
        $data = $excuteAr->ExecuteGroup($this->id, $this->ip, $this->port, I('post.interval', 2));

        $ret = ['error' => -1, 'msg' => '后端服务错误'];
        if ($data) {
            $data = json_decode($data, true);

            $ret['error'] = $data['isSuccess'] ? 0 : -11;
            $ret['msg'] = $data['msg'];
        }

        $this->ajaxReturn($ret);
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

    public function addTask(){
        $this->display();
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

    public function getSingleByGroupId() {
        $group_ids = I('post.group_ids');
        $r = M('GroupSingle')->where([ 'tid' => [ 'IN', $group_ids ] ])->select();
        
        $this->ajaxReturn([
            'error' => false,
            'data'  => $r,
            'msg'   => ''
        ]);
    }

    public function ExecuteSingle() {
        if (!IS_AJAX) $this->ajaxReturn("非法操作！");
        $single = D('GroupSingle');
        $singleData = $single->getSingle($this->id);

        $excuteAr = D('ExecHistory');

        $data = $excuteAr->ExecuteSingle(I('post.id'), I('post.ip'), I('post.port', '8080'));

        $ret = ['error' => -1, 'data' => '', 'msg' => '后端服务错误'];
        if ($data) {
            $data = json_decode($data, true);
            $ret['error'] = $data['isSuccess'] ? 0 : -11;
            $ret['msg'] = $data['msg'];
        }

        $this->ajaxReturn($ret);
    }

    static $RemoveRules = [
        'id' => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
    ];

    public function Remove() {
        if (!IS_AJAX) $this->error('非法操作！');
        if (!canModifySingle($this->id)) {
            $this->ajaxReturn([ 'error' => -12, 'data' => '', 'msg' => '无权限' ]);
        }

        $single = M('GroupSingle');
        $singleData = $single->where(['id' => $this->id])->find();
        if ($singleData['uid'] != session('admin')['id']) {
            $this->ajaxReturn([ 'error' => -10, 'data' => '', 'msg' => '非法参数' ]);
        }

        $data = $single->where(['id' => $this->id])->delete();

        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -11,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    static $addSingleRules = [
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例名称'],
        'groupid'    => ['name' => 'groupid', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array', 'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array', 'max' => 20, 'method' => 'post', 'desc' => '验证规则value']
    ];

    public function addSingle() {
        if (!IS_AJAX) $this->error("非法操作！");

        if (!D('GroupSingle')->canAddSingle($this->groupid)) {
            $this->ajaxReturn([ 'error' => -10, 'data'  => '', 'msg' => '无权限' ]);
        }

        if (!$this->arc && !$this->nlp) {
            $this->ajaxReturn([ 'error' => -10, 'data'  => '', 'msg'   => 'NLP或ASR参数为空' ]);
        }

        if(empty($this->groupid)){
            $this->ajaxReturn([
                'error' => -11,
                'data'  => '',
                'msg'   => '请选择用例分组'
            ]);   
        }

        $single = D('GroupSingle');
        $data = $single->addSingle($this->singleName, $this->groupid, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2);

        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -12,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    static $updateSingleRules = [
        'singleName'  => ['name' => 'mc', 'type' => 'string', 'min' => 2, 'max' => 100, 'method' => 'post', 'desc' => '用例名称'],
        'groupid'    => ['name' => 'groupid', 'type' => 'int', 'method' => 'post', 'desc' => '属性'],
        'nlp'         => ['name' => 'nlp', 'type' => 'string', 'method' => 'post', 'desc' => 'NLP'],
        'arc'         => ['name' => 'arc', 'type' => 'string', 'method' => 'post', 'desc' => 'ARC'],
        'v1'          => ['name' => 'v1', 'type' => 'array', 'max' => 100, 'method' => 'post', 'desc' => '验证规则key'],
        'dept'        => ['name' => 'dept', 'type' => 'array', 'method' => 'post', 'desc' => '验证规则条件'],
        'v2'          => ['name' => 'v2', 'type' => 'array', 'max' => 20, 'method' => 'post', 'desc' => '验证规则value']
    ];

    public function updateSingle() {
        if (!IS_AJAX) $this->error('非法操作');

        $groupSingle = D('GroupSingle');

        $id = intval(I('post.id', 0));
        $data = $groupSingle->getSingle($id);

        if (!$data) { $this->ajaxReturn([ 'error' => -11, 'data' => '', 'msg' => '该用例已被删除' ]); }
        
        if (!isSuper() && session('admin')['id'] != $data['uid']) {
            $this->ajaxReturn([ 'error' => -11, 'data' => '', 'msg' => '无权操作' ]);
        }

        $type = I('post.type_switch') ? 'ASR' : 'NLP';

        $data = $groupSingle->updateSingle($id, $type, $this->singleName, $this->nlp, $this->arc, $this->v1, $this->dept, $this->v2,$this->groupid);
       
        $this->ajaxReturn([
            'error' => $data > 0 ? 0 : -11,
            'data'  => $data,
            'msg'   => ''
        ]);
    }

    public function uploadLocalAudio() {
        $mdl = D('AudioUploads');
        $this->ajaxReturn($mdl->uploadLocalAudio());
    }

    public function uploadRecordAudio() {
        $mdl = D('AudioUploads');
        $this->ajaxReturn($mdl->uploadRecordAudio(intval(I('post.len'))));
    }
    
    public function getAudioList() {
        $mdl = D('AudioUploads');

        $this->ajaxReturn(
            $mdl->getAudioList([ 'name' => ['LIKE', '%' . I('post.search_name') . '%']], I('post.start'), I('post.length'))
        );
    }

    public function RemoveAudio() {
        $this->ajaxReturn(
            D('AudioUploads')->RemoveAudio(intval(I('post.id')))
        );
    }


    public function editPreOrNext($id,$type="pre"){
        if($type == "pre"){
            $comp = "lt";
            $order = "id desc";
        }else{
            $comp = "gt";
            $order = "id asc";
        }

        $where = [
            'id' => array($comp, $id)
        ];

        $groupid = session('admin')['group_id'];

        if ($groupid != 1 && $groupid != 3) {
            $allowed_group_ids = D('AuthGroup')->getGroupIds();
            if (!$allowed_group_ids) $this->redirect("Group/index");
            $where['tid'] = ['IN', $allowed_group_ids];
        }

        $single = D('GroupSingle')->where($where)->find();

        if(empty($single)){
            $this->redirect("Group/index");
        }else{
            $this->redirect("Group/edit/id/".$single['id']);
        }
    }
}
