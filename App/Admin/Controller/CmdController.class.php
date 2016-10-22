<?php

namespace Admin\Controller;

use Think\Controller;

class CmdController extends Controller {
  private $serv;

  /**
   * 主任务入口
   */
  public function index() {
    if (!IS_CLI) $this->error('此文件不能在非cli模式执行', '/Index');
    $this->serv = new \swoole_server('127.0.0.1', C('SWOOLE_PORT'));
    $this->serv->set(
      [
        'task_worker_num' => 50,
        'worker_num'      => 8,   //工作进程数量
        'backlog'         => 128,
        'daemonize'       => true, //是否作为守护进程
        'log_file'        => LOG_PATH . 'swoole_' . date('Y-m-d') . '.log',
        'log_level'       => 0,
      ]);
    $this->serv->on('onStart', [$this, 'onStart']);
    $this->serv->on('connect', [$this, 'connect']);
    $this->serv->on('WorkerStart', [$this, 'WorkerStart']);
    $this->serv->on('Receive', [$this, 'Receive']);
    $this->serv->on('Task', [$this, 'Task']);
    $this->serv->on('Finish', [$this, 'Finish']);
    $this->serv->start();
    echo '服务器监听成功 \n端口号:'.C('SWOOLE_PORT');
  }

  public function onStart($serv, $fd) {
    self::log_write('manager_pid' . $this->serv->manager_pid);//管理进程的PID，通过向管理进程发送SIGUSR1信号可实现柔性重启
    self::log_write('master_pid' . $this->serv->master_pid); //主进程的PID，通过向主进程发送SIGTERM信号可安全关闭服务器
  }

  public function connect($serv, $fd) {
    self::log_write('异步任务 连接');
//    self::log_write(json_encode($this->serv->connections)); //当前服务器的客户端连接，可使用foreach遍历所有连接
  }

  public function WorkerStart($serv, $fd) {

    //定时任务 循环执行
//      $serv->tick(1000*60*5, function ($id) {
//        \Think\Log::write('定时任务'.$id.'执行,时间:' .date("Y-m-d H:i:s"), 'info');
//      });

  }

  public function Receive($serv, $fd, $from_id, $taskData) {

    //定时任务 执行一次
//      $serv->after(1000*60*5, function ($id) {
//        \Think\Log::write('定时任务'.$id.'执行,时间:' .date("Y-m-d H:i:s"), 'info');
//      });
    $serv->send($fd, '任务数据接收成功');
    $serv->close($fd);
    $task_id = $serv->task($taskData);
    self::log_write('接到任务,数据:' . $taskData . '  task_id' . $task_id);

  }

  public function Task($serv, $task_id, $from_id, $taskData) {
//    $redis = REDIS();


    self::log_write('开始执行任务:' . $task_id . '  task_id' . $task_id);
    $taskData = @json_decode($taskData, true);
//    $redis->sAdd('task:' . ($taskData['isgroup'] ==0? 'single' : 'group'), $taskData['id']);
    $runFunc = ($taskData['isgroup'] == 0) ? 'StaskRun' : 'GtaskRun';
    $serv->finish($this->$runFunc($taskData));

  }

  public function Finish($serv, $task_id, $taskData) {
//    $redis = REDIS();
    //集合里面删除 对应id
//    $redis->sPop('task:' . ($taskData['data']['isgroup'] ==0? 'single' : 'group'), $taskData['data']['id']);
    self::log_write('任务完成:' . $task_id . ' 数据:' . @json_encode($taskData));
    $serv->finish(@json_encode($taskData));
  }

  /**
   * 记录日志
   * @param $message
   */
  static public function log_write($message, $lv = 'info') {
    $now = date("H:i:s");
    \Think\Log::write($message, $lv);
    print_r("{$now} : {$message}\r\n");
  }

  /**
   * 单任务
   * @desc 组任务运行
   * 用例状态 0 等待执行  1 正在执行
   * 执行状态 0 等待任务执行  1 正在执行 2 执行成功 3 执行失败
   */
  private function StaskRun($taskData) {
//  {"leixin":0,"mid":"82","ip":"121.42.0.84","port":"8080","create_time":1467108919,"id":52}
    \Think\Log::write('任务数据：' . json_encode($taskData), 'info');
    //设置用例状态
    $single = M('Single');
    $single->where(['id' => $taskData['mid']])->setField(['status' => 1]);
    $ExecHistory = M('ExecHistory');
    $ExecHistory->where(['id' => $taskData['id']])->setField([
      'status'          => 1,
      'exec_start_time' => date("Y-m-d H:i:s")
    ]);
//  用例数据
    $thisData = M('Single')->where(['isrecovery' => 0])->find($taskData['mid']);
    $postParms = [];
    if ($thisData['nlp'] != '') {
      $postParms['asrToNlp'] = $thisData['nlp'];
    } else if ($thisData['arc'] != '') {

      \Think\Log::write('arc::：' . file_get_contents('.' . $thisData['arc']), 'info');

      $postParms['asr'] = @base64_encode(@file_get_contents(__DIR__ . '/../../..' . $thisData['arc'], 0));
      if (!$postParms['asr']) {
        $single->where(['id' => $thisData['id']])->setField(['status' => 0]);
        \Think\Log::write('任务失败,失败原因：asr文件读取错误！', 'error');
        M('ExecHistory')->where(['id' => $taskData['id']])->setField([
          'status'        => 3,
          'exec_end_time' => date("Y-m-d H:i:s"),
          'exec_content'  => json_encode([
            'is_success' => false,
            'msg'        => 'asr文件读取错误',
            'content'    => [
              'IP'   => $taskData['ip'],
              'port' => $taskData['port'],
              'arc'  => $thisData['arc'],
            ]
          ], JSON_UNESCAPED_UNICODE)
        ]);
        return [
          'isSuccess' => false,
          'data'      => $taskData
        ];
      }
    }
    $HttpClient = new \Leaps\HttpClient\Adapter\Curl();
    $HttpClient->setOption(CURLOPT_TIMEOUT, 10);
    $HttpClient->setHeader('Content-Type', 'application/x-www-form-urlencoded');

    $response = $HttpClient->post('http://' . $taskData['ip'] . ':' . $taskData['port'].'/asrToNlp', $postParms);


    $single->where(['id' => $thisData['id']])->setField(['status' => 0]);
    if (!$response->isOk()) {
      \Think\Log::write('任务失败,失败原因：http请求失败！', 'error');
      M('ExecHistory')->where(['id' => $taskData['id']])->setField([
        'status'        => 3,
        'exec_end_time' => date("Y-m-d H:i:s"),
        'exec_content'  => json_encode([
          'is_success' => false,
          'msg'        => 'http请求失败',
          'content'    => [
            'IP'         => $taskData['ip'],
            'port'       => $taskData['port'],
            'StatusCode' => $response->getStatusCode(),
            'Header'     => $response->getRawHeader(),
            'Content'    => $response->getContent()
          ]
        ], JSON_UNESCAPED_UNICODE)
      ]);
      return [
        'isSuccess' => false,
        'data'      => $taskData
      ];
    }
    $resData = contentAsArray($response->getContent());

    if (empty($resData)) {
      \Think\Log::write('任务失败,失败原因：http请求 未返回有效数据！', 'error');
      M('ExecHistory')->where(['id' => $taskData['id']])->setField([
        'status'        => 3,
        'exec_end_time' => date("Y-m-d H:i:s"),
        'exec_content'  => json_encode([
          'is_success' => false,
          'msg'        => 'http请求 未返回有效数据',
          'content'    => [
            'IP'         => $taskData['ip'],
            'port'       => $taskData['port'],
            'StatusCode' => $response->getStatusCode(),
            'Header'     => $response->getRawHeader(),
            'Content'    => $resData
          ]
        ], JSON_UNESCAPED_UNICODE)
      ]);
      return [
        'isSuccess' => false,
        'data'      => $taskData
      ];
    }


    if (!judged_all($resData, $thisData['validates'])) {
      \Think\Log::write('任务失败,失败原因：判定条件不通过！', 'error');
      M('ExecHistory')->where(['id' => $taskData['id']])->setField([
        'status'        => 3,
        'exec_end_time' => date("Y-m-d H:i:s"),
        'exec_content'  => json_encode([
          'is_success' => false,
          'msg'        => '判定条件不通过',
          'content'    => $resData
        ], JSON_UNESCAPED_UNICODE)
      ]);
      return [
        'isSuccess' => false,
        'data'      => $taskData
      ];
    }

    \Think\Log::write('任务成功！', 'info');
    M('ExecHistory')->where(['id' => $taskData['id']])->setField([
      'status'        => 2,
      'exec_end_time' => date("Y-m-d H:i:s"),
      'exec_content'  => json_encode([
        'is_success' => true,
        'msg'        => '任务成功',
        'content'    => $resData
      ], JSON_UNESCAPED_UNICODE)
    ]);
    return [
      'isSuccess' => true,
      'data'      => $taskData
    ];
  }

  /**
   * 组任务
   * @desc 组任务运行
   */
  private function GtaskRun($taskData) {
    //  {"leixin":0,"mid":"82","ip":"121.42.0.84","port":"8080","create_time":1467108919,"id":52}
//  {"isgroup":1,"mid":"19","uid":"1","ip":"121.42.0.84","port":"","create_time":"2016-07-12 16:35:08","id":118}
    \Think\Log::write('组任务数据：' . json_encode($taskData), 'info');
//设置组状态
    M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 1]);
    //设置任务状态 和执行时间
    M('ExecHistory')->where(['id' => $taskData['id']])->setField([
      'status'          => 1,
      'exec_start_time' => date("Y-m-d H:i:s")
    ]);

    $GsingleData = M('GroupSingle')
      ->field('a.id,a.tid,a.name,a.nlp,a.arc,a.validates,a.create_time,a.isrecovery,b.uid,b.ispublic,b.name')
      ->join(' a RIGHT JOIN __GROUP__ b ON a.tid = b.id')
      ->where(['tid' => $taskData['mid']])
      ->where(['a.isrecovery' => 0])
      ->where(['b.isrecovery' => 0])
      ->order(['create_time' => 'desc'])
      ->select();


    if (!is_array($GsingleData) || count($GsingleData) == 0) {
      \Think\Log::write('组任务失败,失败原因：无相关用例！', 'error');
      M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 0]);
      M('ExecHistory')->where(['id' => $taskData['id']])->setField([
        'status'        => 3,
        'exec_end_time' => date("Y-m-d H:i:s"),
        'exec_content'  => json_encode([
          'is_success' => false,
          'msg'        => '无相关用例'
        ], JSON_UNESCAPED_UNICODE)
      ]);
      return [
        'isSuccess' => false,
        'data'      => $taskData
      ];
    }


    $HttpClient = new \Leaps\HttpClient\Adapter\Curl();
    $HttpClient->setOption(CURLOPT_TIMEOUT, 10);
    $HttpClient->setHeader('Content-Type', 'application/x-www-form-urlencoded');

    $isSuccess = true;
    foreach ($GsingleData as $key => $thisData) {
      $exec_start_time = date("Y-m-d H:i:s");

      $postParms = [];
      if ($thisData['nlp'] != '') {
        $postParms['asrToNlp'] = $thisData['nlp'];
      } else if ($thisData['arc'] != '') {
        \Think\Log::write('arc::：' . file_get_contents('.' . $thisData['arc']), 'info');
        $postParms['asr'] = @base64_encode(@file_get_contents(__DIR__ . '/../../..' . $thisData['arc'], 0));
        if (!$postParms['asr']) {
          \Think\Log::write('组单例' . $thisData['name'] . '失败,失败原因：asr文件读取错误！', 'error');

          M('GroupExecHistory')->add([
            'exec_history_id' => $taskData['id'],
            'group_id'        => $taskData['mid'],
            'single_id'       => $GsingleData[$key]['id'],
            'issuccess'       => 0,
            'exec_content'    => json_encode([
              'msg'     => 'asr文件读取错误',
              'content' => [
                'IP'   => $taskData['ip'],
                'port' => $taskData['port'],
                'arc'  => $thisData['arc'],
              ]
            ], JSON_UNESCAPED_UNICODE),
            'exec_start_time' => $exec_start_time,
            'exec_end_time'   => date("Y-m-d H:i:s"),
          ]);
          $isSuccess = false;
          continue;
        }
      }

      $response = $HttpClient->post('http://' . $taskData['ip'] . ':' . $taskData['port'].'/asrToNlp', $postParms);
//      $_content = $response->getContentAsArray();
      $_content = contentAsArray($response->getContent());
      if (!$response->isOk()) {
        \Think\Log::write('组单例' . $thisData['name'] . '失败,失败原因：http请求失败！', 'error');
        M('GroupExecHistory')->add([
          'exec_history_id' => $taskData['id'],
          'group_id'        => $taskData['mid'],
          'single_id'       => $GsingleData[$key]['id'],
          'issuccess'       => 0,
          'exec_content'    => json_encode([
            'msg'     => 'http请求失败',
            'content' => [
              'IP'         => $taskData['ip'],
              'port'       => $taskData['port'],
              'StatusCode' => $response->getStatusCode(),
              'Header'     => $response->getRawHeader(),
              'Content'    => $_content
            ]
          ], JSON_UNESCAPED_UNICODE),
          'exec_start_time' => $exec_start_time,
          'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
        $isSuccess = false;
        continue;
      }
      if (empty($_content)) {

        \Think\Log::write('组单例' . $thisData['name'] . '失败,失败原因：http请求 未返回有效数据！', 'error');
        M('GroupExecHistory')->add([
          'exec_history_id' => $taskData['id'],
          'group_id'        => $taskData['mid'],
          'single_id'       => $GsingleData[$key]['id'],
          'issuccess'       => 0,
          'exec_content'    => json_encode([
            'msg'     => 'http请求 未返回有效数据',
            'content' => [
              'IP'         => $taskData['ip'],
              'port'       => $taskData['port'],
              'StatusCode' => $response->getStatusCode(),
              'Header'     => $response->getRawHeader(),
              'Content'    => $_content
            ]
          ], JSON_UNESCAPED_UNICODE),
          'exec_start_time' => $exec_start_time,
          'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
        $isSuccess = false;
        continue;

      }

      if (!judged_all($_content, $thisData['validates'])) {

        \Think\Log::write('组单例' . $thisData['name'] . '失败,失败原因：判定条件不通过！', 'error');
        M('GroupExecHistory')->add([
          'exec_history_id' => $taskData['id'],
          'group_id'        => $taskData['mid'],
          'single_id'       => $GsingleData[$key]['id'],
          'issuccess'       => 0,
          'exec_content'    => json_encode([
            'msg'     => '判定条件不通过',
            'content' => $_content
          ], JSON_UNESCAPED_UNICODE),
          'exec_start_time' => $exec_start_time,
          'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
        $isSuccess = false;
        continue;
      }
      \Think\Log::write('组单例' . $thisData['name'] . '任务执行成功！', 'info');
      M('GroupExecHistory')->add([
        'exec_history_id' => $taskData['id'],
        'group_id'        => $taskData['mid'],
        'single_id'       => $GsingleData[$key]['id'],
        'issuccess'       => 1,
        'exec_content'    => json_encode([
          'msg'     => '执行成功',
          'content' => $_content
        ], JSON_UNESCAPED_UNICODE),
        'exec_start_time' => $exec_start_time,
        'exec_end_time'   => date("Y-m-d H:i:s"),
      ]);

    }

    M('Group')->where(['id' => $taskData['mid']])->setField(['status' => 0]);
    \Think\Log::write('组任务执行完成！', 'info');

    M('ExecHistory')->where(['id' => $taskData['id']])->setField([
      'status'        => $isSuccess ? 2 : 3,
      'exec_end_time' => date("Y-m-d H:i:s"),
      'exec_content'  => json_encode([
        'is_success' => $isSuccess ? true : false,
        'msg'        => $isSuccess ? '任务成功' : '任务失败',
      ], JSON_UNESCAPED_UNICODE)
    ]);

    return [
      'isSuccess' => $isSuccess,
      'data'      => $taskData
    ];
  }
}
