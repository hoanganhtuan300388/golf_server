ALTER TABLE `t_user_acc_inf` CHANGE `sex` `sex` TINYINT(2) NULL DEFAULT '0' COMMENT '性別 0: 未選択, 1. 男性, 2. 女性, 3. その他';
UPDATE `t_user_acc_inf` SET `sex`=0 WHERE `sex` IS NULL;