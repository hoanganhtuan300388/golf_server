ALTER TABLE t_user_shot_rlt DROP PRIMARY KEY;
ALTER TABLE `ultracaddy`.`t_user_shot_rlt` ADD PRIMARY KEY (`id`, `user_round_id`, `user_course_id`, `user_hole_id`);