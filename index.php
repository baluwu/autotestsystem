<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
if(!class_exists('swoole_server')){
    die('必须安装swoole模块');
}
if(!class_exists('redis')){
    die('必须安装redis模块');
}
if(!function_exists('ldap_connect')){
//  die('必须安装ldap模块');
}
if (!function_exists('gettext')) {
    die('必须安装gettext模块');
}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',false);
define('ABS_ROOT', dirname(__FILE__));

// 定义应用目录
define('APP_PATH','./App/');

function DB() { var_dump(func_get_args()); exit; }

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单

