<?php
namespace Admin\Controller;
use Think\Controller;

class DiffController extends Controller{
    public function diff() {
        $pams = explode('/', $_SERVER['PATH_INFO']);

        if (count($pams) != 4) {
            E('常数错误');
        }

        list(, , $id1, $id2) = $pams;
        $single_ids = [];
        $id1 = addslashes($id1);
        $id2 = addslashes($id2);

        $data = [ 'bd' => [], 'hd' => [] ];

        $execHistory = M('ExecHistory')->where(['id' => ['IN', [$id1, $id2]]])->select(); 
        foreach ($execHistory as $key => &$his) {
            unset($his['exec_content']);
            unset($his['uid']);
            $his['status'] = ['0' => '待执行', '1' => '执行中', '2' => '成功', 3 => '失败'][$his['status']];

            if ($his['isgroup'] != '2') {
                unset($his['ver']);
                unset($his['description']);
                unset($his['task_name']);
                unset($his['exec_plan_time']);
            }

            $details = M('GroupExecHistory')->where(['exec_history_id' => $his['id']])->order(['single_id' => 'ASC'])->select();
            $details = self::arrayKeyReplace($details, 'single_id');

            $k = $key == 0 ? 'left' : 'right';

            $data['bd'][$k] = [];
            foreach ($details as $ikey => &$res) {
                $res['exec_content'] = json_decode($res['exec_content'], true);
                unset($res['id']);
                unset($res['exec_history_id']);
                $res['issuccess'] = $res['issuccess'] ? '成功' : '失败';
                $single_ids[] = $res['single_id'];
                
                $data['bd'][$k][$ikey] = $res;
            }

            unset($his['mid']);
            unset($his['id']);
            unset($his['isgroup']);

            $data['hd'][$k] = $his;
        }

        $single_ids = array_unique($single_ids);

        $single_names = [];
        if (!empty($single_ids)) {
            $single_names = M('GroupSingle')->field('id, name')->where(['id' => ['IN', $single_ids]])->select();
            $single_names = self::arrayKeyReplace($single_names, 'id');
        }

        $group_names = [];
        if (!empty($single_ids)) {
            $tba = C('DB_PREFIX') . 'group_single';
            $tbb = C('DB_PREFIX') . 'group';
            $sql = "SELECT a.id id, b.`name` name FROM `{$tba}` a JOIN `{$tbb}` b on a.tid=b.id WHERE a.id IN(" . implode(',', $single_ids) . ")";
            $group_names = M()->query($sql);
            $group_names = self::arrayKeyReplace($group_names, 'id');
        }

        foreach ($data['bd'] as $key => &$lists) {
            foreach($lists as $sid => &$single) {
                $path = ['path' => $group_names[$sid]['name'] . ' / ' . $single_names[$sid]['name'] ];
                $single = array_merge($path, $single);
                unset($single['single_id']);
                unset($single['group_id']);
            }
        }

        $this->assign('data', $data);
        $this->assign('right_bd', $data['bd']['right']);
        $this->assign('data_string', json_encode($data));
		$this->display();
    }

    public static function arrayKeyReplace($arr, $key) {
        $result = array();
        foreach ($arr as $val) {
            if (isset($val[$key])) {
                $result[$val[$key]] = $val;
            }
        }

        return $result;
    }


}
