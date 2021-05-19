-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2021 at 09:04 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coronentry`
--

-- --------------------------------------------------------

--
-- Table structure for table `entrypoints`
--

CREATE TABLE `entrypoints` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `entry_class` enum('Low','Intermediate','High','') NOT NULL,
  `entry_code` varchar(255) NOT NULL,
  `max_user_count` int(11) UNSIGNED DEFAULT NULL,
  `current_user_count` int(11) NOT NULL,
  `fk_placeid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entrypoints`
--

INSERT INTO `entrypoints` (`id`, `name`, `entry_class`, `entry_code`, `max_user_count`, `current_user_count`, `fk_placeid`) VALUES
(1, 'ESP32', 'Low', 'ep01', 1, 0, 2),
(2, 'Door209', 'High', 'ep02', NULL, 0, 2),
(3, 'Door112', 'Low', 'ep03', NULL, 0, 1),
(4, 'Door414', 'Low', 'ep04', NULL, 0, 1),
(5, 'Door123', 'High', 'ep77', NULL, 0, 1),
(7, 'Door420', 'Low', 'ep66', NULL, 0, 1),
(8, 'IoT Room', 'Low', 'ep88', NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`) VALUES
(1, 'Ktu'),
(2, '11 Bendrabutis');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ep_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `exit_date` datetime DEFAULT NULL,
  `bodytemp` tinyint(1) NOT NULL,
  `fk_placeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `user_id`, `ep_id`, `date`, `exit_date`, `bodytemp`, `fk_placeid`) VALUES
(55, 74, 1, '2021-05-18 14:05:41', '2021-05-18 14:06:05', 1, 2),
(56, 74, 1, '2021-05-18 14:18:12', NULL, 0, 2),
(57, 74, 1, '2021-05-18 14:22:15', NULL, 0, 2),
(58, 74, 1, '2021-05-18 14:24:25', NULL, 0, 2),
(59, 74, 1, '2021-05-18 14:26:00', '2021-05-18 14:26:27', 1, 2),
(61, 75, 3, '2021-05-19 21:13:19', '2021-05-19 21:14:38', 1, 1),
(62, 75, 7, '2021-05-19 21:16:50', '2021-05-19 21:17:48', 1, 1),
(63, 75, 7, '2021-05-19 21:23:33', '2021-05-19 21:24:03', 1, 1),
(64, 75, 4, '2021-05-19 21:29:54', NULL, 0, 1),
(65, 76, 4, '2021-05-19 21:37:09', '2021-05-19 21:37:16', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `entry_class` enum('Low','Intermediate','High','') NOT NULL,
  `user_type` enum('Default','Admin','') NOT NULL,
  `fk_placeid` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `user_code`, `name`, `password`, `entry_class`, `user_type`, `fk_placeid`) VALUES
(1, 'domkel@ktu.edu', 'usr123', 'Domantas', '9b8769a4a742959a2d0298c36fb70623f2dfacda8436237df08d8dfd5b37374c', 'High', 'Admin', 2),
(2, 'admin@bak.lt', 'adm123', 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'High', 'Admin', 1),
(74, 'vard@gmail.com', 'b030m', 'Vardenis', '761394478fffd98ebac02ef59bb61ce7da9cfb32103f0bcc0efd6d1062803f4d', 'Intermediate', 'Default', 2),
(75, 'jonas.j@gmail.com', 'rnlb14', 'Jonas', '179aa54c5b82ffad3cb7a72d0b3dc68f896145589d6f80b4b9a49a14f653c94b', 'Low', 'Default', 1),
(76, 'stanislovas.steponas@gmail.com', 'raw1z4', 'Stanislovas', '3d71f0b99b86d2385672b8b3488abc234fc3bafc62b77e9dfb2b1b27480b5f39', 'High', 'Default', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entrypoints`
--
ALTER TABLE `entrypoints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entrypoints`
--
ALTER TABLE `entrypoints`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
