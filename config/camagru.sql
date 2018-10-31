-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 31, 2018 at 06:07 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camagru`
--

-- --------------------------------------------------------

--
-- Table structure for table `stickers`
--

CREATE TABLE `stickers` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `image` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('verify_email','reset_password','delete_account','activate_account') NOT NULL,
  `data` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uname` varchar(25) NOT NULL,
  `email` varchar(36) NOT NULL,
  `email_valid` tinyint(1) NOT NULL DEFAULT '0',
  `sha` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `reg_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_token` varchar(40) NOT NULL,
  `recieve_updates` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uname`, `email`, `email_valid`, `sha`, `active`, `reg_on`, `session_token`, `recieve_updates`) VALUES
(1, 'JeffTheKiller', 'killer@email.com', 1, '$2y$10$/vUzPm.rFOgUYMTKeh.SIewKljjKTiuJ67GGTYvDCM63TcBf29Gd6', 1, '2018-10-31 20:01:31', 'b7f252134ee8698d31d1bed00677397d7306ac2b', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stickers`
--
ALTER TABLE `stickers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tokens_ibfk_1` (`user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stickers`
--
ALTER TABLE `stickers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
