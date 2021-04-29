-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2021 at 12:37 AM
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
-- Table structure for table `entrypoint`
--

CREATE TABLE `entrypoint` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `entry_class` enum('Low','Intermediate','High','') NOT NULL,
  `entry_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entrypoint`
--

INSERT INTO `entrypoint` (`id`, `name`, `entry_class`, `entry_code`) VALUES
(1, 'ESP32', 'High', 'ep01');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ep_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `user_id`, `ep_id`, `date`) VALUES
(1, 1, 1, '2021-04-18 20:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `entry_class` enum('Low','Intermediate','High','') NOT NULL,
  `user_type` enum('Default','Admin','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `user_code`, `name`, `password`, `entry_class`, `user_type`) VALUES
(1, 'domkel@ktu.edu', 'usr123', 'Domantas', 'password123', 'High', 'Default'),
(2, 'admin@bak.lt', 'adm123', 'admin', 'admin123', 'High', 'Admin'),
(54, '', 'hxruvg', '', 'a', 'High', 'Default'),
(55, 'tst', 'hvv4yo', 'tst', 'aa', 'Low', 'Default'),
(56, 'a@gmail.com', '6ftt4', 'a', 'a', 'Low', 'Default'),
(57, 'tst@gmail.com', '40uwf', 'tst', 'ss', 'Low', 'Default'),
(58, 'ttsts@gmail.com', 'm8ijpu', 'tstyy', '12', 'High', 'Default');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entrypoint`
--
ALTER TABLE `entrypoint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entrypoint`
--
ALTER TABLE `entrypoint`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
