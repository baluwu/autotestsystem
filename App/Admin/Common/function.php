<?php
include_once 'taskFunction.php';
//写入日志
function logs($act,$status=FALSE){
	$log_data = [
			'uid'=>session('admin')['id'],
			'act'=> $act,
			'status'=>$status?1:0,
			'operate_time' => REQUEST_TIME
	];
	$isadded=D('logs')->addLog($log_data);
  if(!$isadded)\Think\Log::write('日志写入失败,errorCode:' . $isadded);
}



/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function REDIS() {
    static $REDIS   =   '';
    $class  =    'Think\\Cache\\Driver\\Redis';
    if(class_exists($class))
        $REDIS = new $class;
    return $REDIS;
}

/**
 * 得到当前页面的网址
 */
function get_now_url() {

    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") {
      $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }


  return urlencode($pageURL);
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'wavatar', $r = 'g', $img = false, $atts = [] ) {
  $url = 'http://cn.gravatar.com/avatar/';
  $url .= md5(strtolower(trim($email)));
  $url .= "?s=$s&d=$d&r=$r";
  if ($img) {
    $url = '<img src="' . $url . '"';
    foreach ($atts as $key => $val)
      $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
  }
  return $url;
}

function formartJson($json){
  $json=str_replace('\\','',$json);
  dump($json);
  return $json;
}

function contentAsArray($content){
  return @json_decode ( ( string )$content, true );
}




/**
 * 抛出异常处理
 * @param string $msg 异常消息
 * @param integer $code 异常代码 默认为0
 * @throws Think\Exception
 * @return void
 */
function ERR($msg, $code=0) {
  \Think\Log::write('错误码：'+$code+' 描述：' . $msg, 'error');
  if (true ===  IS_AJAX) {// AJAX提交
    $data['msg'] = $msg;
    $data['error'] = $code;
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data, 0));
  }
  Think\Think::halt($msg);        // 异常类型不存在则输出错误信息字串
}

/**
 * 格式化树形节点数据
 *
 * @param $data
 * @param array $def
 */
function fmt_tree_data ( $data, $def = array() )
{
    if( empty($data) ) {
        return $data;
    }

    foreach( $data as &$v ) {
        $v['pId'] = $v['pid'];
        $v['open'] = false;
    }

    return $data;
}

function arrayGroup($arr, $key) {
    $result = FALSE;
    foreach ($arr as $val) {
        if (isset($val[$key]) && isset($result[$val[$key]])) {
            $result[$val[$key]][] = $val;
        }
        else $result[$val[$key]] = [$val];
    }

    return $result;
}

function canModify($id) {
    $group_id = session('admin')['group_id'];
    $is_super =  $group_id == 1;
    if ($is_super) return true;

    $uid = session('admin')['id'];
    $item_uid = M('ManageGroupClassify')->where(['id' => $id])->getField('uid');

    return $uid == $item_uid;
}

function canModifySingle($id) {
    $group_id = session('admin')['group_id'];
    $is_super =  $group_id == 1;
    if ($is_super) return true;

    $uid = session('admin')['id'];
    $item_uid = M('GroupSingle')->where(['id' => $id])->getField('uid');

    return $uid == $item_uid;
}

function isSuper() { return session('admin')['group_id'] == 1; }
function isLeader() { return session('admin')['group_id'] == 3; }


