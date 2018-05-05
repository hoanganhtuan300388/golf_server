ALTER TABLE `m_golf_field` CHANGE `update_at` `updated` DATETIME NULL DEFAULT NULL COMMENT '更新日時', CHANGE `insert_at` `created` DATETIME NULL DEFAULT NULL COMMENT '作成日時';
ALTER TABLE `m_course` CHANGE `insert_at` `created` DATETIME NULL DEFAULT NULL COMMENT '作成日時';
ALTER TABLE `m_course` ADD `updated` DATETIME NULL COMMENT '更新日付' AFTER `created`;
ALTER TABLE `m_hole` CHANGE `insert_at` `created` DATETIME NULL DEFAULT NULL COMMENT '作成日時';
ALTER TABLE `m_hole` ADD `updated` DATETIME NULL COMMENT '更新日付' AFTER `created`;
ALTER TABLE `m_green` CHANGE `version` `version` INT(20) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'バージョン', CHANGE `insert_at` `created` DATETIME NULL DEFAULT NULL COMMENT '作成日時';
ALTER TABLE `m_green` ADD `updated` DATETIME NULL COMMENT '更新日付' AFTER `created`;
ALTER TABLE `m_nation` ADD `国名(英語）` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `nation_name_en`;
ALTER TABLE `m_nation` CHANGE `国名(英語）` `nation_code` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国名(英語）';