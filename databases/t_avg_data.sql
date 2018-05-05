-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 02, 2018 at 11:43 AM
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
-- Table structure for table `t_avg_data`
--

CREATE TABLE `t_avg_data` (
  `id` int(20) UNSIGNED NOT NULL,
  `round_type` tinyint(2) NOT NULL COMMENT 'ラウンドタイプ',
  `avg_score` int(20) NOT NULL,
  `avg_pat` float NOT NULL,
  `avg_fairway` float NOT NULL,
  `avg_gir` float NOT NULL,
  `avg_sandsave` float NOT NULL,
  `avg_scramble` float NOT NULL,
  `created` datetime NOT NULL COMMENT '作成日時',
  `updated` datetime NOT NULL COMMENT '更新日付',
  `delete_flg` tinyint(2) NOT NULL DEFAULT '0' COMMENT '削除フラグ 0: 公開中 1: 削除済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_avg_data`
--
ALTER TABLE `t_avg_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `round_type` (`round_type`,`avg_score`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_avg_data`
--
ALTER TABLE `t_avg_data`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
