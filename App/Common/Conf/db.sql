
CREATE TABLE `sys_audio_uploads` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `source` TINYINT(4) NOT NULL COMMENT '来源 0: 本地上传 1: 录音上传',
    `name` VARCHAR(255) NOT NULL COMMENT '文件名称',
    `path` VARCHAR(255) NOT NULL COMMENT '文件路径',
    `len` INT(10) DEFAULT NULL COMMENT '时长',
    `uploader` VARCHAR(32) NOT NULL DEFAULT 'admin' COMMENT '上传者',
    `when` DATETIME NOT NULL COMMENT '上传时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_name` (`name`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `sys_exec_history`
ADD COLUMN `exec_type` tinyint(2) NULL DEFAULT 1 COMMENT '执行类型 1 正常执行 2 编辑用例执行自检测' ;
