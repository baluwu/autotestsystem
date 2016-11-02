<?php
return [
  //设置可访问目录
  'MODULE_ALLOW_LIST'    => ['Admin'],
  //设置默认目录
  'DEFAULT_MODULE'       => 'Admin',
  //设置模版后缀
  'TMPL_TEMPLATE_SUFFIX' => '.tpl',
  //设置默认主题目录
  'DEFAULT_THEME'        => 'Default',
//数据库配置
  'DB_TYPE'              => 'mysql',     // 数据库类型
  'DB_HOST'              => '127.0.0.1', // 服务器地址
  'DB_NAME'              => 'rokid_ats',          // 数据库名
  'DB_USER'              => 'root',      // 用户名
  'DB_PWD'               => 'root',          // 密码
  'DB_PORT'              => '3306',        // 端口
  'DB_PREFIX'            => 'sys_',    // 数据库表前缀
  'DB_FIELDS_CACHE'      => true,        // 启用字段缓存
  'DB_CHARSET'           => 'utf8',      // 数据库编码默认采用utf8
  'DB_DEPLOY_TYPE'       => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
  'DB_RW_SEPARATE'       => false,       // 数据库读写是否分离 主从式有效
  'DB_MASTER_NUM'        => 1, // 读写分离后 主服务器数量
  'DB_SLAVE_NO'          => '', // 指定从服务器序号
  'DB_BIND_PARAM'        => false, // 数据库写入数据自动参数绑定
  'DB_DEBUG'             => true,  // 数据库调试模式 3.2.3新增
  'DB_LITE'              => true,  // 数据库Lite模式 3.2.3新增
  'DB_PARAMS'            => ['persist' => true],
  //URL模式
  'URL_MODEL'            => 2,

  /* SESSION设置 */
  'SESSION_AUTO_START'   => true,    // 是否自动开启Session
  'SESSION_OPTIONS'      => [
    'name'             => 'ATS_ID',                       //设置session名
    'expire'           => 3600 * 2,                          //SESSION保存2小时
    'use_trans_sid'    => 1,                               //跨页传递
    'use_only_cookies' => 0,                               //是否只开启基于cookies的session的会话方式
  ],
  /*
  'SESSION_TYPE'         => 'Redis',
  'SESSION_PREFIX'       => 'ATS:session:',
  'SESSION_REDIS_HOST'   => '127.0.0.1', //分布式Redis,默认第一个为主服务器
  'SESSION_REDIS_PORT'   => 6379,           //端口,如果相同只填一个,用英文逗号分隔
  'SESSION_REDIS_AUTH'   => 'FAFFDBYG',    //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
  'SESSION_REDIS_DB'     => 5,    //Redis 数据库
  */
  'UPLOAD_PATH'          => 'Uploads/',

  'SWOOLE_PORT'          => 3333,

  'REDIS_HOST'           => '127.0.0.1',
  'REDIS_PORT'           => 6379,
  'REDIS_AUTH'           => 'FAFFDBYG',
  'DATA_CACHE_TIMEOUT'   => 200,
  'DATA_CACHE_PREFIX'    => "ATS:",
  'REDIS_PCONNECT'       => true,
  'REDIS_DBNAME'         => 5,

  'SERVER_NAME'          => 'https://autest.loc',
  'PARALLEL_TASKS'       => 2,

  /*ldap配置*/
  'LDAP_ENABLED'         => 0, //1为开启，0为关闭
  'LDAP_HOST'            => 'ldap://ldap.rokid-inc.com', //ladp服务器
  'LDAP_PORT'            => '389', //ldap端口号
  'LDAP_UID'             => 'uid', //
  'LDAP_BIND_DN'         => 'DC=rokid,DC=com', //ldap绑定DN
  'LDAP_BN'              => 'cn=hejun,ou=socialpark,ou=customers,dc=rokid,dc=com',
  'LDAP_PASS'            => 'nbWH!79*RY', //ldap密码

];
