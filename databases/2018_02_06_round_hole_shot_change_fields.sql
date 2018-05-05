ALTER TABLE `t_user_round_rlt` CHANGE `putting_gir_rate` `putting_gir_rate` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `t_user_round_rlt` CHANGE `recovery_scramble_rate` `recovery_scramble_rate` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `t_user_round_rlt` CHANGE `sum_putt_of_par_on` `sum_putt_of_par_on` INT NULL DEFAULT NULL;
ALTER TABLE `t_user_hole_rlt` CHANGE `drive_distance` `drive_distance` DOUBLE NULL DEFAULT NULL COMMENT 'ドライブ';
ALTER TABLE `t_user_shot_rlt` CHANGE `putt_num` `putt_num` INT NULL DEFAULT NULL COMMENT 'パット数';
ALTER TABLE `t_user_shot_rlt` CHANGE `hit_long` `hit_long` DOUBLE NULL DEFAULT NULL COMMENT '打つ位置LONG';