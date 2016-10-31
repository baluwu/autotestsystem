
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

CREATE TABLE `sys_manage_group_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `pid` int(10) NOT NULL COMMENT '父分类ID',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `modify_time` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
ADD COLUMN `classify`  char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户组分类';
