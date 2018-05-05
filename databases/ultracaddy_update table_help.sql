-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 19, 2017 at 07:29 AM
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
-- Table structure for table `m_help_category`
--

CREATE TABLE `m_help_category` (
  `id` int(20) UNSIGNED NOT NULL,
  `category_name` varchar(128) NOT NULL COMMENT 'カテゴリ名',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flg` tinyint(2) NOT NULL DEFAULT '0' COMMENT '削除フラグ 0: 公開中 1: 削除済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_help`
--

CREATE TABLE `t_help` (
  `id` int(20) UNSIGNED NOT NULL,
  `title` varchar(128) NOT NULL COMMENT '質問',
  `body` text NOT NULL COMMENT '回答内容',
  `category_id` int(20) UNSIGNED NOT NULL COMMENT 'カテゴリID',
  `public_flg` tinyint(2) NOT NULL COMMENT '状態 =>０：無効 １：有効',
  `created_by` int(20) UNSIGNED DEFAULT NULL COMMENT '作成者',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `updated_by` int(20) UNSIGNED DEFAULT NULL COMMENT '更新者',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flg` tinyint(2) NOT NULL DEFAULT '0' COMMENT '削除フラグ 0: 公開中 1: 削除済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_help_category`
--
ALTER TABLE `m_help_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_help`
--
ALTER TABLE `t_help`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_help_category`
--
ALTER TABLE `m_help_category`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_help`
--
ALTER TABLE `t_help`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
