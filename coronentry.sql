-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2021 at 01:18 AM
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
  `fk_placeid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entrypoints`
--

INSERT INTO `entrypoints` (`id`, `name`, `entry_class`, `entry_code`, `fk_placeid`) VALUES
(1, 'ESP32', 'Low', 'ep01', 2),
(2, 'Door209', 'High', 'ep02', 2),
(3, 'Door112', 'High', 'ep03', 1),
(4, 'Door414', 'Low', 'ep04', 1),
(5, 'Door123', 'High', 'ep77', 1),
(7, 'Door420', 'High', 'ep66', 1),
(8, 'IoT Data', 'Low', 'ep', 1);

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE `place` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`id`, `name`) VALUES
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
  `fk_placeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `user_id`, `ep_id`, `date`, `fk_placeid`) VALUES
(1, 1, 2, '2021-04-18 20:57:00', 2),
(2, 1, 1, '2021-05-02 18:21:13', 2),
(7, 57, 3, '2021-05-04 14:00:51', 1),
(8, 2, 4, '2021-03-04 14:00:51', 1),
(10, 1, 1, '2021-05-12 02:10:56', 2),
(12, 1, 1, '2021-05-12 02:16:30', 2);

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
(1, 'domkel@ktu.edu', 'usr123', 'Domantas', 'password123', 'High', 'Admin', 2),
(2, 'admin@bak.lt', 'adm123', 'admin', 'admin123', 'High', 'Admin', 1),
(54, 'ru@yandex.ru', 'hxruvg', 'Rudolfas', 'a', 'High', 'Default', 2),
(55, 'tst', 'hvv4yo', 'tst', 'aa', 'Low', 'Default', 1),
(57, 'tst@gmail.com', '40uwf', 'tst', 'ss', 'Low', 'Default', 1),
(58, 'ttsts@gmail.com', 'm8ijpu', 'tstyy', '12', 'High', 'Default', 1),
(59, 'domkel@gmail.com', 'k9bkc', 'Domantas K', 'pass123', 'Low', 'Default', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entrypoints`
--
ALTER TABLE `entrypoints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
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
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
