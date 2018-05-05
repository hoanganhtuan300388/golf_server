-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 01, 2018 at 01:40 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Table structure for table `m_notice_category`
--

CREATE TABLE `m_notice_category` (
  `id` int(20) UNSIGNED NOT NULL,
  `category_name` varchar(128) NOT NULL COMMENT 'カテゴリ名',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flg` tinyint(2) NOT NULL DEFAULT '0' COMMENT '削除フラグ 0: 公開中 1: 削除済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m_notice_category`
--

INSERT INTO `m_notice_category` (`id`, `category_name`, `created`, `updated`, `delete_flg`) VALUES
(1, 'ニュース', '2018-03-01 00:00:00', '2018-03-01 00:00:00', 0),
(2, 'メンテナンス', '2018-03-01 00:00:00', '2018-03-01 00:00:00', 0),
(3, 'その他', '2018-03-01 00:00:00', '2018-03-01 00:00:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_notice_category`
--
ALTER TABLE `m_notice_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--
ALTER TABLE `t_notice` ADD `category_id` INT(20) UNSIGNED NOT NULL COMMENT 'カテゴリID' AFTER `id`;
ALTER TABLE `t_help` ADD `order_by` INT(10) UNSIGNED NULL COMMENT '表示順番' AFTER `public_flg`;

--
-- AUTO_INCREMENT for table `m_notice_category`
--
ALTER TABLE `m_notice_category`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
