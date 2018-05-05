-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2017 at 03:12 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ultracaddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_user_golf_history`
--

CREATE TABLE `t_user_golf_history` (
  `id` int(20) UNSIGNED NOT NULL,
  `user_account_id` int(20) UNSIGNED NOT NULL COMMENT 'アカウントID',
  `m_field_id` int(20) UNSIGNED NOT NULL COMMENT 'ゴルフ場ID',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日付',
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ0: 公開中 1: 削除済'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_user_golf_history`
--
ALTER TABLE `t_user_golf_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_user_golf_history`
--
ALTER TABLE `t_user_golf_history`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `t_billing_inf` ADD `combined_object_flg` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '合算処理 0: 合算対象外, 1: 合算対象' AFTER `billing_end_at`;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
