 CREATE TABLE `sys_audio_uploads` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT '文件名称',
    `path` VARCHAR(255) NOT NULL COMMENT '文件路径',
    `uploader` VARCHAR(32) NOT NULL DEFAULT 'admin' COMMENT '上传者',
    `when` DATETIME NOT NULL COMMENT '上传时间',
    PRIMARY KEY(`id`),
    KEY idx_name(`name`) USING BTREE
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
