<?php
namespace Admin\Model;
use Think\Model;

class WorkerModel {

    public function freeWokingMachine($key) {
        REDIS()->sRemove('working.machine', $key);
    }

    public function removePendingCase($val) {
        REDIS()->sRem('pending.case', $val);
    }

    public function onReceive($serv, $fd, $from_id, $strData) {
        $taskData = json_decode($strData, true);
        $taskData['fd'] = $fd;

        tasklog('接到任务,数据:' . $strData);

        //定时任务 执行一次
        if ($taskData['type'] && $taskData['type'] == 'TIMER') {
            $runAt = $taskData['run_at'];

            if (!$runAt) {
                $serv->send($fd, json_encode([ 'isSuccess' => false, 'msg' => '缺少时间参数']));
                return $serv->close($fd);
            }

            $secs = strtotime($runAt) - time();
            tasklog('RUNAT:' . $runAt . ', SECS:' . $secs);
            if ($secs < 10) {
                $serv->send($fd, json_encode([ 'isSuccess' => false, 'msg' => '时间设置不合法(>当前时间+10秒)']));
                return $serv->close($fd);
            }

            $taskData['secs'] = $secs;

            return  $serv->task(json_encode($taskData));
        }
        else {
            $serv->task(json_encode($taskData));
        }
    }
    
    public function onConnect($serv, $fd) {
        tasklog('异步任务连接');
    }

    public function onFinish($serv, $task_id, $data) {
        tasklog('收到task进程finish消息');
        $taskData = $data['data'];
        $type = $taskData['type'] ?? 'IMME';
        
        $this->freeWokingMachine($taskData['ip']);

        if ($type == 'TIMER') {
            $this->removePendingCase($taskData['src_string']);
        }

        $serv->send($taskData['fd'], json_encode($data));
        $serv->close($taskData['fd']);
    }

    public function onWorkerStart($serv, $fd) {
        tasklog('工作线程开启,fd=', $fd);
    }
}
