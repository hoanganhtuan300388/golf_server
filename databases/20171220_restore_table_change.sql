ALTER TABLE `t_restore_inf` CHANGE `restore_type` `restore_type` TINYINT(2) NOT NULL COMMENT '復元種類 1：機種変更 (Change Device) 2：メール確認 (Mail confirm)';
ALTER TABLE `t_restore_inf` CHANGE `status` `status` TINYINT(2) NOT NULL DEFAULT '0' COMMENT 'ステータス 0: 発行済 1:復元済 2：無効';
ALTER TABLE `t_restore_inf` ADD `restore_email` VARCHAR(128) NULL AFTER `restore_type`;