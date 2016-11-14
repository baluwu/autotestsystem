<?php
namespace Admin\Model;

use Think\Model;

//执行用户单例模型
class GroupExecHistoryModel extends Model {

  //用户表自动完成
  protected $_auto = [
    ['create_time', 'time', self::MODEL_INSERT, 'function'],
  ];

  public function getbyid($id){
    $r = $this
      ->field('single_id,exec_content,issuccess,exec_start_time,exec_end_time,name,validates,nlp,arc')
      ->join('ehr LEFT JOIN  __GROUP_SINGLE__  ON ehr.single_id = __GROUP_SINGLE__.id')
      ->where([
        'exec_history_id' => $id
      ])
      ->order(['exec_start_time' => 'asc'])
      ->select();

    $single_ids = [];
    foreach ($r as $el) {
        $single_ids[] = $el['single_id'];
    }

    $single_path_info = D('ManageGroupClassify')->getSinglepathInfo($single_ids);

    foreach ($r as &$el) {
        $el['path'] = $single_path_info[$el['single_id']] . ' / ' . $el['name'];
    }

    return $r;
  }
}
