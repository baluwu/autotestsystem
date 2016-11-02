<?php
namespace Admin\Model;
use Think\Model;

class CmdModel {
    /* 增加用例组用例的执行记录 */
    public function addGroupSingleExecHistory($td, $is_succ, $msg, $http = null) {
        tasklog('组单例执行' . ($is_succ ? '成功' : ('失败:' . $msg)), $is_succ ? 'INFO' : 'ERROR');
        M('GroupExecHistory')->add([
            'exec_history_id' => $td['id'],
            'group_id'        => $td['mid'],
            'single_id'       => $td['single_id'],
            'issuccess'       => $is_succ,
            'exec_content'    => json_encode([
                'msg'     => $msg,
                'content' => [
                    'IP'   => $td['ip'] ?? '',
                    'port' => $td['port'] ?? '',
                    'arc'  => $td['arc'] ?? '',
                    'StatusCode' => $http ? $http->getStatusCode() : '',
                    'Header'     => $http ? $http->getRawHeader() : '',
                    'Content'    => $http ? $http->getContent() : ''
                ]
            ], JSON_UNESCAPED_UNICODE),
            'exec_start_time' => $td['stime'],
            'exec_end_time'   => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * 在执行用例时, 写记录
     * 执行单个用例时，写入sys_exec_history
     * 执行用例组单例时, 写入sys_group_exec_history
     */
    public function addHistoryWhenExecSingle($td, $is_succ, $msg, $resp = null) {
        if (!$td['is_group']) {
            return $this->addExecHistory($td, $is_succ, $msg, $resp);
        }

        $this->addGroupSingleExecHistory($td, $is_succ, $msg, $resp);
    }

    public function setExecHistoryStatus($id, $status) {
        M('ExecHistory')->where(['id' => $id])->setField([
            'status'          => $status,
            'exec_start_time' => date("Y-m-d H:i:s")
        ]);
    }

    public function setGroupStatus($id, $status) {
        M('Group')->where(['id' => $id])->setField(['status' => $status]);
    }

}
