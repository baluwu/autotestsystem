<?php
namespace Admin\Model;

use Think\Model;

//执行用户单例模型
class ExecHistoryModel extends Model {

  //用户表自动完成
  protected $_auto = [
    ['create_time', 'time', self::MODEL_INSERT, 'function'],
  ];

  //获单例列表
  public function getList($id, $isgroup, $page, $rows, $order, $sort,$where=[]) {

    $map = [];

    foreach ($where as $key => $value) {
        $map['e.' . $key] = $value;
    }

    //执行状态:0等待执行1执行中2执行成功3执行失败
    $map['e.status'] = ['in', '2,3'];

    $map['e.mid'] = ['eq', $id];

    $map['e.isgroup'] = ['eq', $isgroup];
	
	$map['e.exec_type'] = ['eq', 1];

    $obj = $this
      ->field('e.id,e.mid,e.uid,e.ip,e.port,e.status,e.exec_start_time,u.manager,u.nickname')
      ->join('e LEFT JOIN  __MANAGE__ u  ON e.uid = u.id')
      ->where($map)
      ->order([$order => $sort])
      ->limit($page, $rows)
      ->select();


    $total = $this->where($map)->join('e LEFT JOIN  __MANAGE__ u  ON e.uid = u.id')->count();
    return [
      'recordsTotal'    => $total,
      'recordsFiltered' => $total,
      'data'            => $obj ? $obj : []
    ];
  }

  //执行单例
  public function ExecuteSingle($mid, $ip, $port = 8080, $exec_type = 1) {
	if( $exec_type == 2 ) {
		return SyncExecuteSingle($mid, $ip, $port = 8080);
	}
    $idsAr = explode(',', $mid);
    $row = 0;
    $redis = REDIS();
    if (!$port) $port = 8080;
    foreach ($idsAr as $id) {
      //查redis   list   $id
//      $redis->sAdd('task:single', $id);
     if($redis->sIsMember('task:single',$id)){
       continue;
     }

//
//      $status = M('Single')->where(['id' => $id])->getField('status');
//      if ($status == 1) continue;
      $data = [
        'isgroup'     => 0,
        'mid'         => $id,
        'uid'         => session('admin')['id'],
        'ip'          => $ip,
        'port'        => $port,
        'create_time' => REQUEST_TIME
      ];
      $row = $this->add($data);
      if ($row) {
        $data['id'] = $row;
        AddTask($data);
      }
    }

    return $row;
  }
  
  //执行同步单例
  public function SyncExecuteSingle($mid, $ip, $port = 8080)
  {
	$idsAr = explode(',', $mid);
    $row = 0;
    $redis = REDIS();
    if (!$port) $port = 8080;
	$ret = array();
    foreach ($idsAr as $id) {
      //查redis   list   $id
//      $redis->sAdd('task:single', $id);
     if($redis->sIsMember('task:single',$id)){
       continue;
     }

//
//      $status = M('Single')->where(['id' => $id])->getField('status');
//      if ($status == 1) continue;
      $data = [
        'isgroup'     => 0,
        'mid'         => $id,
        'uid'         => session('admin')['id'],
        'ip'          => $ip,
        'port'        => $port,
        'create_time' => REQUEST_TIME
      ];
      $row = $this->add($data);
      if ($row) {
        $data['id'] = $row;
        $ret[] = SyncTask($data);
      }
    }

    return $ret;
  }

  //执行组单例
  public function ExecuteGroup($tid, $ip, $port) {
    $idsAr = explode(',', $tid);
    $row = 0;
    foreach ($idsAr as $value) {
      $status = M('group')->where(['id' => $value])->getField('status');
      if ($status == 1) continue;
      $data = [
        'isgroup'     => 1,
        'mid'         => $value,
        'uid'         => session('admin')['id'],
        'ip'          => $ip,
        'port'        => $port,
        'create_time' => REQUEST_TIME
      ];

      $row = $this->add($data);
      if ($row) {
        $data['id'] = $row;
        AddTask($data);
      }
    }

    return $row;
  }

  public function GetById($id,$isgroup=0) {
    return $this
      ->field('e.*,u.manager,u.nickname')
      ->join('e LEFT JOIN  __MANAGE__ u  ON e.uid = u.id')
      ->where(['e.id' => $id])
      ->where(['e.isgroup' => $isgroup])
      ->where(['e.status' => ['in', '2,3']])
      ->find();
  }

  /**
   * 多个id取值
   * @param $ids 1,2,3
   * @param int $isgroup
   * @return array
   */
  public function GetByIds($ids,$isgroup=0,$where=[]) {
    return $this
      ->field('e.*,u.manager,u.nickname')
      ->join('e LEFT JOIN  __MANAGE__ u  ON e.uid = u.id')
      ->where(['e.id' => ['in', $ids]])
      ->where(['e.isgroup' => $isgroup])
      ->where(['e.status' => ['in', '2,3']])
      ->where($where)
      ->order(['e.create_time' => 'desc'])
      ->select();
  }

  /**
   * 设置数据
   * @param $id
   * @param array $data
   * @return bool
   */
  public function setfields($id, $data = []) {
    return $this->where(['id' => $id])->setField($data);
  }
}
