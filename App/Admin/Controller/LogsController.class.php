<?php
namespace Admin\Controller;

//操作日志控制器
class LogsController extends AuthController {
    //显示日志列表
    public function index() {
        $this->display();
    }

    //获取日志列表
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
        $log = D('Logs');

        $order = [
            'list'   => ['act', 'state', 'operate_time'],
            'column' => 'create_time',
            'dir'    => "desc"
        ];

        $getOrder = $this->order;
        if (is_array($getOrder)) {
            $order['column'] = $order['list'][$getOrder[0]['column']];
            $order['dir'] = $getOrder[0]['dir'];
        }

        if ($this->date_from && $this->date_to) {
            $where['create_time'] = [['egt', $this->date_from], ['elt', $this->date_to]];
        } else if ($this->date_from) {
            $where['create_time'] = ['egt', $this->date_from];
        } else if ($this->date_to) {
            $where['create_time'] = ['elt', $this->date_to];
        }
        $this->ajaxReturn($log->getList($order['column'], $order['dir'], $this->page_start, $this->page_rows));


    }

    //删除日志
//	public function Remove(){
//		if(!IS_AJAX) $this->error('非法操作！');
//		$log = D('Logs');
//		echo $log->remove(I('post.ids'));
//	}

}
