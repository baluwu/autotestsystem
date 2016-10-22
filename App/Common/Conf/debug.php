<?php
return [


  'SHOW_PAGE_TRACE'      => true,
  /* 日志设置 */
  'LOG_RECORD'           => true,   // 默认不记录日志
  'LOG_TYPE'             => 'File', // 日志记录类型 默认为文件方式
  'LOG_LEVEL'            => 'EMERG,ALERT,CRIT,ERR,WARN,NOTICE,INFO,DEBUG,SQL',// 允许记录的日志级别
  'LOG_FILE_SIZE'        => 2097152,  // 日志文件大小限制
  'LOG_EXCEPTION_RECORD' => true,    // 是否记录异常信息日志
];
