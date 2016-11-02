<?php

return [
  //设置模版替换变量
  'TMPL_PARSE_STRING' => [
    '__EASYUI__'        => '/Public/' . MODULE_NAME . '/easyui-1.4.5',
    '__EASYUI_THEMES__' => 'bootstrap',
    '__CSS__'           => '/Public/' . MODULE_NAME . '/css',
    '__JS__'            => '/Public/' . MODULE_NAME . '/js',
    '__IMG__'           => '/Public/' . MODULE_NAME . '/img',
    '__UPLOADIFY__'     => '/Public/' . MODULE_NAME . '/Uploadify',
  ],

  'UPLOAD_PATH'       => '/Public/Uploads/',
  'SHOW_ERROR_MSG'       => true,

];
