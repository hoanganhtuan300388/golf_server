ALTER TABLE `t_user_round_rlt` ADD `recovery_sandsave_rate` DOUBLE NULL AFTER `setting_calibration_flg`;
ALTER TABLE `t_user_round_rlt` ADD `distance_deviation` DOUBLE NULL AFTER `recovery_sandsave_rate`;
ALTER TABLE `t_user_round_rlt` ADD `bearing_deviation` DOUBLE NULL AFTER `distance_deviation`;