ALTER TABLE `t_user_hole_rlt` CHANGE `status` `status` TINYINT(2) NOT NULL DEFAULT '1' COMMENT 'ステータス1 : プレイ中　　　　　 2: プレー終了';
ALTER TABLE `t_user_round_rlt` ADD `type` TINYINT(2) NULL COMMENT '1: course_1 2: course_2' AFTER `status`;