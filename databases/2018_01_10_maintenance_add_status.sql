ALTER TABLE `t_maintenance` ADD `status` TINYINT(2) NULL DEFAULT '0' COMMENT 'ステータス ０：無効 １：有効 ' AFTER `end_at`;