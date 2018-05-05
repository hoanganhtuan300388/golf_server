ALTER TABLE `t_user_club_inf` DROP PRIMARY KEY;
ALTER TABLE `t_user_club_inf` ADD PRIMARY KEY( `id`, `user_account_id`);