<?php
namespace Admin\Model;
use Think\Model;

class WorkerModel {
    public function freeWokingMachine($key) {
        REDIS()->sRemove('working.machine', $key);
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

            $secs = $runAt - time();

            if ($secs < 10) {
                $serv->send($fd, json_encode([ 'isSuccess' => false, 'msg' => '时间设置不合法(>当前时间+10秒)']));
                return $serv->close($fd);
            }

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
        tasklog('TASK-TYPE:' . $type);
        if ($type == 'IMME' || $type == 'TIMER') {
            unset($data['data']);
            $serv->send($taskData['fd'], json_encode($data));
            $serv->close($taskData['fd']);
        }
    }

    public function onWorkerStart($serv, $fd) {
        if (!$serv->taskworker) {
            //扫描任务队列
            $serv->tick(10000, function() use ($serv, $fd) {
                $redis = REDIS();
                $tasks = M('Task')->order(['create_time' => 'ASC'])->limit(0, 10)->select();    

                $tl = count($tasks);
                $vl = min($tl, C('PARALLEL_TASKS'));

                $rt = 0;
                $runningMachines = [];

                for ($x = 0; $x < $tl && $rt < $vl; $x++) {
                    $task = $tasks[$x];
                    if (!$task['run_at'] || !$task['ip']) {
                        continue;
                    }

                    $machine = $task['ip'];

                    //不重复执行相同机器的任务
                    if ($machine && 
                        !in_array($machine, $runningMachines) && 
                        ($task['run_at'] < time() || ($task['run_at'] - time() <= 30))  &&
                        !$redis->sIsMember('working.machine', $machine))
                    {

                        $task['type'] = 'TASK';
                        $task['isgroup'] = 2;
                        $runningMachines[] = $machine;
                        $serv->task(json_encode($task));
                        $rt++;
                    }
                }
            });
        }
    }
}
