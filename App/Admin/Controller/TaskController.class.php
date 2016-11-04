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

}
