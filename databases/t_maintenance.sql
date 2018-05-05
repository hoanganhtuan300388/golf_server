-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 08, 2018 at 01:32 PM
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
-- Table structure for table `t_maintenance`
--

CREATE TABLE `t_maintenance` (
  `id` int(20) UNSIGNED NOT NULL,
  `title` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `create_by` int(20) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `update_by` int(20) UNSIGNED DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `delete_flg` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0: 公開中 1: 削除済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_maintenance`
--
ALTER TABLE `t_maintenance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_maintenance`
--
ALTER TABLE `t_maintenance`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;