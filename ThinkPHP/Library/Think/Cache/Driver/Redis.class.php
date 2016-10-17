<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Cache\Driver;
use Think\Cache;
defined('THINK_PATH') or exit();

/**
 * Redis缓存驱动
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 */
class Redis extends Cache {
  protected $dbname=0;
  /**
   * 架构函数
   * @param array $options 缓存参数
   * @access public
   */
  public function __construct($options=[]) {
    if ( !extension_loaded('redis') ) {
      E(L('_NOT_SUPPERT_').':redis');
    }
    if(empty($options)) {
      $options = [
        'host'          => C('REDIS_HOST') ? C('REDIS_HOST') : '127.0.0.1',
        'port'          => C('REDIS_PORT') ? C('REDIS_PORT') : 6379,
        'auth'          => C('REDIS_AUTH') ? C('REDIS_AUTH') : '',
        'timeout'       => C('DATA_CACHE_TIMEOUT') ? C('DATA_CACHE_TIMEOUT') : 300,
        'expire'       => C('DATA_CACHE_EXPIRE') ? C('DATA_CACHE_EXPIRE') : 300,
        'persistent'    => C('REDIS_PCONNECT') ? C('REDIS_PCONNECT') : false,
        'dbname'    => C('REDIS_DBNAME') ? C('REDIS_DBNAME') : 0,
      ];
    }
    $this->options =  $options;
    $this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
    $this->options['length'] =  isset($options['length'])?  $options['length']  :   0;
    $func = $options['persistent'] ? 'pconnect' : 'connect';
    $this->handler  = new \Redis;
    $options['timeout'] === false ?
      $this->handler->$func($options['host'], $options['port']) :
      $this->handler->$func($options['host'], $options['port'], $options['timeout']);

    if ($this->options['auth'] != '') {
      $this->handler->auth($this->options['auth']);
    }
    $this->switchDB(isset($options['dbname']) ? intval($options['dbname']) : 0);

  }

  /**
   * 读取缓存
   * @access public
   * @param string $name 缓存变量名
   * @return mixed
   */
  public function get($name, $dbname = null) {
    $this->switchDB($dbname);
    N('cache_read', 1);
    $value = $this->handler->get($this->options['prefix'] . $name);
    return $this->unformatValue($value);
  }

  /**
   * 写入缓存
   * @access public
   * @param string $name 缓存变量名
   * @param mixed $value  存储数据
   * @param integer $expire  有效时间（秒）
   * @return boolean
   */
  public function set($name, $value, $expire = null, $dbname = null) {
    $this->switchDB($dbname);
    N('cache_write',1);
    if(is_null($expire)) {
      $expire  =  $this->options['expire'];
    }
    $name   =   $this->options['prefix'].$name;
    //对数组/对象数据进行缓存处理，保证数据完整性
    $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
    if(is_int($expire)) {
      $result = $this->handler->setex($name, $expire, $value);
    }else{
      $result = $this->handler->set($name, $value);
    }
    if($result && $this->options['length']>0) {
      // 记录缓存队列
      $this->queue($name);
    }
    return $result;
  }

  /**
   * 删除缓存
   * @access public
   * @param string $name 缓存变量名
   * @return boolean
   */
  public function rm($name, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->delete($this->options['prefix'].$name);
  }
  /**
   * 检测是否存在key,若不存在则赋值value
   */
  public function setnx($key, $value, $dbname = null) {
    $this->switchDB($dbname, $dbname = null);
    return $this->handler->setnx($this->formatKey($key), $this->formatValue($value));
  }

  public function lPush($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->lPush($this->formatKey($key), $this->formatValue($value));
  }

  public function rPush($key, $value, $dbname = null) {
    $this->switchDB($dbname, $dbname = null);
    return $this->handler->rPush($this->formatKey($key), $this->formatValue($value));
  }

  public function lPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->lPop($this->formatKey($key));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  public function rPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->rPop($this->formatKey($key));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  //---------------------------------------------------string类型-------------------------------------------------
  /**
   * 将value 的值赋值给key,生存时间为永久 并根据名称自动切换库
   */
  public function set_forever($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->set($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 获取value 并根据名称自动切换库
   */
  public function get_forever($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->get($this->formatKey($key));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * 存入一个有实效性的键值队
   */
  public function set_time($key, $value, $expire = 600, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->setex($this->formatKey($key), $expire, $this->formatValue($value));
  }

  /**
   * 统一get/set方法,对于set_Time使用get_Time
   */
  public function get_time($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->get($this->formatKey($key));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * 得到一个key的生存时间
   */
  public function get_time_ttl($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->ttl($this->formatKey($key));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * 批量插入k-v,请求的v需要是一个数组 如下格式
   * array('key0' => 'value0', 'key1' => 'value1')
   */
  public function set_list($value, $dbname = null) {
    $this->switchDB($dbname);
    $data = [];
    foreach ($value as $k => $v) {
      $data[$this->formatKey($k)] = $this->formatValue($v);
    }
    return $this->handler->mset($data);
  }

  /**
   * 批量获取k-v,请求的k需要是一个数组
   */
  public function get_list($key, $dbname = null) {
    $this->switchDB($dbname);
    $data = [];
    foreach ($key as $k => $v) {
      $data[] = $this->formatKey($v);
    }
    $rs = $this->handler->mget($data);
    foreach ($rs as $k => $v) {
      $rs[$k] = $this->unformatValue($v);
    }
    return $rs;
  }

  /**
   * 判断key是否存在。存在 true 不在 false
   */
  public function get_exists($key, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->exists($this->formatKey($key));
  }

  /**
   * 返回原来key中的值，并将value写入key
   */
  public function get_getSet($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->getSet($this->formatKey($key), $this->formatValue($value));
    return $value !== FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * string，名称为key的string的值在后面加上value
   */
  public function set_append($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->append($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 返回原来key中的值，并将value写入key
   */
  public function get_strlen($key, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->strlen($this->formatKey($key));
  }

  /**
   * 自动增长
   * value为自增长的值默认1
   */
  public function get_incr($key, $value = 1, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->incr($this->formatKey($key), $value);
  }

  /**
   * 自动减少
   * value为自减少的值默认1
   */
  public function get_decr($key, $value = 1, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->decr($this->formatKey($key), $value);
  }
  //------------------------------------------------List类型-------------------------------------------------

  /**
   * 写入队列左边 并根据名称自动切换库
   */
  public function set_lPush($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->lPush($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 写入队列左边 如果value已经存在，则不添加 并根据名称自动切换库
   */
  public function set_lPushx($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->lPushx($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 写入队列右边 并根据名称自动切换库
   */
  public function set_rPush($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->rPush($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 写入队列右边 如果value已经存在，则不添加 并根据名称自动切换库
   */
  public function set_rPushx($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->rPushx($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 读取队列左边
   */
  public function get_lPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->lPop($this->formatKey($key));
    return $value != FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * 读取队列右边
   */
  public function get_rPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->rPop($this->formatKey($key));
    return $value != FALSE ? $this->unformatValue($value) : NULL;
  }

  /**
   * 读取队列左边 如果没有读取到阻塞一定时间 并根据名称自动切换库
   */
  public function get_blPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->blPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
    return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
  }

  /**
   * 读取队列右边 如果没有读取到阻塞一定时间 并根据名称自动切换库
   */
  public function get_brPop($key, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->brPop($this->formatKey($key), DI()->config->get('app.redis.blocking'));
    return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
  }

  /**
   * 名称为key的list有多少个元素
   */
  public function get_lSize($key, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->lSize($this->formatKey($key));
  }

  /**
   * 返回名称为key的list中指定位置的元素
   */
  public function set_lSet($key, $index, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->lSet($this->formatKey($key), $index, $this->formatValue($value));
  }

  /**
   * 返回名称为key的list中指定位置的元素
   */
  public function get_lGet($key, $index, $dbname = null) {
    $this->switchDB($dbname);
    $value = $this->handler->lGet($this->formatKey($key), $index);
    return $value != FALSE ? $this->unformatValue($value[1]) : NULL;
  }

  /**
   * 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）
   */
  public function get_lRange($key, $start, $end, $dbname = null) {
    $this->switchDB($dbname);
    $rs = $this->handler->lRange($this->formatKey($key), $start, $end);
    foreach ($rs as $k => $v) {
      $rs[$k] = $this->unformatValue($v);
    }
    return $rs;
  }

  /**
   * 截取名称为key的list，保留start至end之间的元素
   */
  public function get_lTrim($key, $start, $end, $dbname = null) {
    $this->switchDB($dbname);
    $rs = $this->handler->lTrim($this->formatKey($key), $start, $end);
    foreach ($rs as $k => $v) {
      $rs[$k] = $this->unformatValue($v);
    }
    return $rs;
  }

  //未实现 lRem lInsert  rpoplpush

  //----------------------------------------------------set类型---------------------------------------------------
  //----------------------------------------------------zset类型---------------------------------------------------

  /**
   * Add one or more members to a sorted set or update its score if it already exists
   */
  public function zAdd($key, $value, $name, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->zAdd($this->formatKey($key), $value, $name);
  }

  /**
   * Returns the score of a given member in the specified sorted set.
   */
  public function zScore($key,  $name, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->zScore($this->formatKey($key), $name);
  }

  /**
   * 取值 递增排列
   * Returns a range of elements from the ordered set stored at the specified key, with values in the range [start, end].
   */
  public function zRange($key, $start, $end, $withscores = false, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->zRange($this->formatKey($key), $start, $end, $withscores); // array(val0, val1, val5)
  }

  /**
   * 取值 递减排列
   * @param $key
   * @param $start
   * @param $end
   * @param bool $withscores
   * @param int $dbname
   * @return array
   */
  public function zRevRange($key, $start, $end, $withscores = false, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->zRevRange($this->formatKey($key), $start, $end, $withscores); // array(val0, val1, val5)
  }

  /**
   * Returns the elements of the sorted set stored at the specified key which have scores in the range [start,end]. Adding a parenthesis before start or end excludes it from the range. +inf and -inf are also valid limits. zRevRangeByScore returns the same items in reverse order, when the start and end parameters are swapped.
   *
   * $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1)); /* array('val2' => 2)
   */
  /**
   * @param $key
   * @param $start
   * @param $end
   * @param int $scores
   * @param array $limit
   * @param int $dbname
   * @return array
   */
  public function zRangeByScore($key, $start, $end, $scores = 0, $limit = [], $dbname = null) {
    $this->switchDB($dbname);
    $where = [];
    if ($scores) $where['withscores'] = $scores;
    if (count($limit) == 2) $where['limit'] = $limit;
    return $this->handler->zRangeByScore($this->formatKey($key), $start, $end, $where); // array(val0, val1, val5)
  }

  //----------------------------------------------------Hash类型---------------------------------------------------



//---------------------------------------------------- redis 集合操作---------------------------------------------------

  /**
   * 将member元素加入到集合key当中。
   */
  public function sAdd($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->sAdd($this->formatKey($key), $this->formatValue($value));
  }
  /**
   * 判断member元素是否是集合的成员。
   */
  public function sIsMember($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->sismember($this->formatKey($key), $this->formatValue($value));
  }

  /**
   * 删除 member元素
   */
  public function sRemove($key, $value, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->sRemove($this->formatKey($key), $this->formatValue($value));
  }



  //----------------------------------------------------通用方法---------------------------------------------------
  /**
   * 设定一个key的活动时间（s）
   */
  public function setTimeout($key, $time = 600, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->setTimeout($this->formatKey($key), $time);
  }

  /**
   * 返回key的类型值
   */
  public function type($key, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->type($this->formatKey($key));
  }

  /**
   * key存活到一个unix时间戳时间
   */
  public function expireAt($key, $time = 600, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->expireAt($this->formatKey($key), $time);
  }

  /**
   * 随机返回key空间的一个key
   */
  public function randomKey($dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->randomKey();
  }

  /**
   * 返回满足给定pattern的所有key
   */
  public function keys($key, $pattern, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->keys($this->formatKey($key), $pattern);
  }

  /**
   * 查看现在数据库有多少key
   */
  public function dbSize($dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->dbSize();
  }

  /**
   * 转移一个key到另外一个数据库
   */
  public function move($key, $db, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->move($this->formatKey($key), $db);
  }

  /**
   * 给key重命名
   */
  public function rename($key, $key2, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->rename($this->formatKey($key), $key2);
  }

  /**
   * 给key重命名 如果重新命名的名字已经存在，不会替换成功
   */
  public function renameNx($key, $key2, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->renameNx($key, $key2);
  }

  /**
   * 删除键值 并根据名称自动切换库(对所有通用)
   */
  public function del($key, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->del($this->formatKey($key));
  }

  /**
   * 订阅
   */
  public function subscribe($channels,$fn, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->subscribe($channels,$fn);
  }/**
 * 订阅
 */
  public function publish($channels,$msg, $dbname = null) {
    $this->switchDB($dbname);
    return $this->handler->publish($channels,$msg);
  }
  /**
   * 返回redis的版本信息等详情
   */
  public function info() {
    return $this->handler->info();
  }

  /**
   * 切换DB并且获得操作实例
   */
  public function get_redis($dbname = null) {
    $this->switchDB($dbname);
    return $this->handler;
  }

  /**
   * 查看连接状态
   */
  public function ping() {
    return $this->handler->ping();
  }


  /**
   * 内部切换Redis-DB 如果已经在某个DB上则不再切换
   */
  //todo 按组选择库
  protected function switchDB($name) {
    if($name==null)return;
    if (is_int($name)) {
      $db = $name;
    } else {
      $db=0;
    }
    $this->handler->select($db);
  }
  protected function formatKey($key) {
    return $this->options['prefix'] . $key;
  }
  protected function formatValue($value) {
    return  (is_object($value) || is_array($value)) ? @json_encode($value) : $value;
  }

  protected function unformatValue($value) {
    $jsonData  = @json_decode( $value, true );
    return ($jsonData === NULL) ? $value : $jsonData;
  }
  /**
   * 清空当前数据库
   * @access public
   * @return boolean
   */
  public function clear() {
    return $this->handler->flushDB();
  }
  /**
   * 清空所有数据库
   */
  public function flushAll() {
    return $this->handler->flushAll();
  }
  /**
   * 选择从服务器
   */
  public function slaveof($host, $port) {
    return $this->handler->slaveof($host, $port);
  }
  /**
   * 将数据同步保存到磁盘
   */
  public function save() {
    return $this->handler->save();
  }

  /**
   * 将数据异步保存到磁盘
   */
  public function bgsave() {
    return $this->handler->bgsave();
  }

  /**
   * 返回上次成功将数据保存到磁盘的Unix时戳
   */
  public function lastSave() {
    return $this->handler->lastSave();
  }

  /**
   * 使用aof来进行数据库持久化
   */
  public function bgrewriteaof() {
    return $this->handler->bgrewriteaof();
  }
}
