-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2023 at 05:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mfaproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned_ips`
--

CREATE TABLE `banned_ips` (
  `userid` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `performing` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `other_info` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `ip` varchar(30) NOT NULL,
  `consent` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mfa_authentication`
--

CREATE TABLE `mfa_authentication` (
  `userid` varchar(5000) NOT NULL,
  `passphrase` varchar(5000) NOT NULL,
  `grid` int(11) NOT NULL,
  `pictures_list` varchar(5000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mfa_security`
--

CREATE TABLE `mfa_security` (
  `userid` int(10) NOT NULL,
  `last_login` date NOT NULL,
  `attempt` int(11) NOT NULL,
  `lock_before` int(3) NOT NULL,
  `lock_time` int(11) NOT NULL,
  `account_locked` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(30) NOT NULL,
  `locked_data_time` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `multifactor`
--

CREATE TABLE `multifactor` (
  `userid` int(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `lastlogin` date NOT NULL,
  `folder_value` varchar(5000) NOT NULL,
  `image_selected` varchar(5000) NOT NULL,
  `setup_step` int(11) NOT NULL,
  `backup_code` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(4000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `multifactor` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `mfa_authentication`
--
ALTER TABLE `mfa_authentication`
  ADD UNIQUE KEY `passphrase` (`passphrase`) USING HASH;

--
-- Indexes for table `mfa_security`
--
ALTER TABLE `mfa_security`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `multifactor`
--
ALTER TABLE `multifactor`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
