
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

ALTER TABLE `sys_exec_history` ADD COLUMN `exec_type` tinyint(2) NULL DEFAULT 1 COMMENT '执行类型 1 正常执行 2 编辑用例执行自检测' ;

ALTER TABLE `rokid_ats`.`sys_exec_history` ADD COLUMN `ver` VARCHAR(20) NULL COMMENT '版本' AFTER `exec_plan_time`, ADD COLUMN `description` VARCHAR(255) NULL COMMENT '注释' AFTER `ver`; 
ALTER TABLE `rokid_ats`.`sys_exec_history` ADD COLUMN `task_name` VARCHAR(255) NULL AFTER `description`; 

CREATE TABLE `sys_manage_group_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `pid` int(10) NOT NULL COMMENT '父分类ID',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `modify_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_manage_group_classify
-- ----------------------------
INSERT INTO `sys_manage_group_classify` VALUES ('1', '0', '随意勾选 1', '2016-10-30 22:12:47');
INSERT INTO `sys_manage_group_classify` VALUES ('2', '1', '随意勾选 1-1', '2016-10-30 22:13:00');
INSERT INTO `sys_manage_group_classify` VALUES ('3', '1', '随意勾选 1-2', '2016-10-30 22:13:11');
INSERT INTO `sys_manage_group_classify` VALUES ('4', '0', '随意勾选 2', '2016-10-30 22:13:22');
INSERT INTO `sys_manage_group_classify` VALUES ('5', '4', '随意勾选 2-1', '2016-10-30 22:13:36');
INSERT INTO `sys_manage_group_classify` VALUES ('6', '4', '随意勾选 2-2', '2016-10-30 22:14:08');

ALTER TABLE `sys_auth_group`
ADD COLUMN `classify`  char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用例组分类，逗号隔开';

ALTER TABLE `sys_group`
`classify`  char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用例组分类，逗号隔开';

CREATE TABLE `sys_task` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) DEFAULT NULL COMMENT '任务名称',
    `ver` varchar(20) NOT NULL COMMENT '版本',
    `description` varchar(255) DEFAULT NULL COMMENT '注释',
    `mid` varchar(255) DEFAULT NULL COMMENT '用例ids',
    `run_at` int(11) unsigned NOT NULL COMMENT '运行时间',
    `notify_email` varchar(128) DEFAULT NULL COMMENT '通知邮件',
    `ip` varchar(20) NOT NULL,
    `port` varchar(10) NOT NULL,
    `uid` int(10) unsigned NOT NULL,
    `create_time` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

//Leader用户组
//测试Leader用户属于该组
INSERT INTO `sys_auth_group` VALUES (3,'Leader',1,'1,2,5,6,7,8,9,10,11');
