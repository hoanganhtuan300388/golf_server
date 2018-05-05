ALTER TABLE `m_course` CHANGE `service_status` `service_status` TINYINT(2) NOT NULL DEFAULT '1' COMMENT 'サービス状態:１：公開中　　　２：閉鎖中　　　３：破棄済';
UPDATE `m_course` SET `service_status`=1;
ALTER TABLE `m_hole` CHANGE `service_status` `service_status` TINYINT(2) NOT NULL DEFAULT '1' COMMENT 'サービス状態:１：公開中　　　２：閉鎖中　　　３：破棄済';
UPDATE `m_hole` SET `service_status`=1;