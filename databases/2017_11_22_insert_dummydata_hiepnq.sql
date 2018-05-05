INSERT INTO `t_billing_inf`(`id`, `user_account_id`, `billing_type`,  `billing_update_flg`,  `delete_flg`, `device_os`) VALUES (1,1,1,0,0,1);
INSERT INTO `t_user_acc_inf`(`id`, `player_name`, `email`, `password`, `sex`, `delete_flg`) VALUES (1,'hiep','hiep@gmail.com','123adasd',1,0);
INSERT INTO `t_notice`(`id`, `title`, `body`, `type`, `public_flg`, `delete_flg`) VALUES (1,'title1','body1',1,1,0);
INSERT INTO `t_golf_update_inf`(`id`, `update_golf_field_id`, `update_course_id`, `update_hole_id`, `version`, `status`, `delete_flg`) VALUES (1,1,1,1,'abc',1,0);
INSERT INTO `t_server_setting`(`id`, `constant_key`, `constant_value`, `status`) VALUES (1,1,'abc',1);