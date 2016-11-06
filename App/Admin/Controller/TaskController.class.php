<?php
namespace Admin\Controller;


//任务控制器
class TaskController extends AuthController {
   
    //任务列表
    //
    public function index() {
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
        $this->ajaxReturn($execHistory->getTaskList($this->page_start, $this->page_rows, $order['column'], $order['dir'], $where));
    }


    //执行记录查看
    public function execute_history_show($id) {
        $ExecHistory = D('ExecHistory');
        $data = $ExecHistory->GetById($id, 2);

        if ($data['uid'] != session('admin')['id']) {
                $this->error('非法参数');
        }
        $this->assign('data', $data);
        $ExecHistoryRs = D('GroupExecHistory');
        $ExecHistory = $ExecHistoryRs->getbyid($id, 1);
        $this->assign('ExecHistory', $ExecHistory);
        $this->display();
    }

    public function add() {
        $task_name = I('post.name');
        $run_at = I('post.run_at');
        $single_ids = I('post.single_ids');
        $ver = I('post.ver');
        $ip = I('post.ip');

        if (!$single_ids || !$task_name || !$ver || !$ip || !$run_at) {
            return $this->ajaxReturn(['error' => true, 'msg' => '参数非法']);
        }

        $now = time();
        $at = strtotime($run_at);
        if ($at < $now ||  $at - $now < 10) {
            return $this->ajaxReturn([ 'error' => true, 'msg' => '运行时间至少需大于当前时间10秒' ]);
        }

        $port = I('post.port');
        $taskData = [
            "isgroup" => 2,
            'name' => $task_name,
            "type"=>"TIMER",
            "mid"=>$single_ids,
            'description' => I('post.description'),
            'notify_email' => I('post.notify_email'),
            "ver"=>$ver,
            "run_at"=>$at,
            'uid' => session('admin')['id'],
            'ip' => $ip,
            'port' => $port ? $port : '8080',
            "create_time"=>time()
        ];

        $resp = SyncTask($taskData);

        $resp = json_decode($resp, true);

        $is_error = $resp && $resp['isSuccess'] ? false : true;
        return $this->ajaxReturn([
            'error' => $is_error,
            'msg' => $resp ? $resp['msg'] : ''
        ]);
    }
}
