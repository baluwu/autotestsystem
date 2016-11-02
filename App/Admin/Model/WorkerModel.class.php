<?php
namespace Admin\Model;
use Think\Model;

class WorkerModel {

    public function freeWokingMachine($key) {
        REDIS()->sRemove('working.machine', $key);
    }

    public function removePendingCase($val) {
        REDIS()->sRemove('pending.case', $val);
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

            $taskData['secs'] = $secs < 5 ? 5 : $secs;

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
        tasklog('收到task进程finish消息:' . $task_id);
        if (!is_array($data)) return ;

        $taskData = $data['data'];
        $type = $taskData['type'];

        if ($data['free_machine']) {
            $this->freeWokingMachine($taskData['ip']);
        }

        if ($data['remove_pend']) {
            tasklog('removePendingCase:' . $taskData['src_string']);
            $this->removePendingCase($taskData['src_string']);
        }

        if ($type == 'IMME' || $type == 'TIMER') {
            unset($data['data']);
            $serv->send($taskData['fd'], json_encode($data));
            $serv->close($taskData['fd']);
        }
    }

    public function onWorkerStart($serv, $fd) {
        if (!$serv->taskworker) {
            /*启动定时扫描任务*/
            $serv->tick(10000, function() use ($serv, $mdl, $fd) {
                $redis = REDIS();
                $cases = $redis->sMembers('pending.case'); 
                $l = !empty($cases) ? count($cases) : 0;
                $l = min($l, C('PARALLEL_TASKS'));

                //运行中的机器
                $runningMachines = [];

                for ($x = 0; $x < $l; $x++) {
                    $taskData = json_decode($cases[$x], true);
                    $machine = $taskData['ip'];

                    //不重复执行相同机器的任务
                    if ($machine && !in_array($machine, $runningMachines) && !$redis->sIsMember('working.machine', $machine)) {
                        if ($taskData['type'] == 'PEND') {
                            $runningMachines[] = $machine;
                            $serv->task(json_encode($taskData));
                        }
                        //不是PEND类型的任务将被移出
                        else $this->removePendingCase($cases[$x]);
                    }
                }
            });
        }
    }
}
