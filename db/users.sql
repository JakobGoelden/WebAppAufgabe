-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 13, 2026 at 07:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `success` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `ip_address`, `login_time`, `success`) VALUES
(1, 2, '127.0.0.1', '2025-11-20 13:53:48', 1),
(2, 1, '127.0.0.1', '2025-11-26 13:47:04', 1),
(3, 2, '127.0.0.1', '2025-11-26 13:48:12', 1),
(4, 1, '127.0.0.1', '2025-11-26 14:07:21', 1),
(5, 1, '127.0.0.1', '2025-11-26 14:07:36', 0),
(6, 1, '127.0.0.1', '2025-11-26 14:19:41', 0),
(7, 1, '127.0.0.1', '2025-11-26 14:19:50', 1),
(8, 1, '127.0.0.1', '2026-04-08 15:23:58', 0),
(9, 1, '127.0.0.1', '2026-04-08 15:24:04', 0),
(10, 4, '127.0.0.1', '2026-04-08 15:25:23', 1),
(11, 4, '127.0.0.1', '2026-04-08 15:27:54', 1),
(12, 4, '127.0.0.1', '2026-04-08 15:28:45', 1),
(13, 4, '127.0.0.1', '2026-04-08 16:03:35', 1),
(14, 4, '127.0.0.1', '2026-04-08 16:08:38', 1),
(15, 4, '127.0.0.1', '2026-04-08 16:09:24', 1),
(16, 4, '127.0.0.1', '2026-04-13 11:53:08', 1),
(17, 4, '127.0.0.1', '2026-04-13 11:59:41', 1),
(18, 4, '127.0.0.1', '2026-04-13 12:01:03', 1),
(19, 4, '127.0.0.1', '2026-04-13 17:05:28', 1),
(20, 4, '127.0.0.1', '2026-04-13 17:21:37', 1),
(21, 4, '127.0.0.1', '2026-04-13 17:33:11', 1),
(22, 4, '127.0.0.1', '2026-04-13 17:34:13', 1),
(23, 4, '127.0.0.1', '2026-04-13 17:42:05', 1),
(24, 4, '127.0.0.1', '2026-04-13 17:44:12', 1),
(25, 4, '127.0.0.1', '2026-04-13 17:44:24', 1),
(26, 4, '127.0.0.1', '2026-04-13 17:48:21', 1),
(27, 4, '127.0.0.1', '2026-04-13 17:48:35', 1),
(28, 4, '127.0.0.1', '2026-04-13 17:49:50', 1),
(29, 4, '127.0.0.1', '2026-04-13 17:53:51', 1),
(30, 4, '127.0.0.1', '2026-04-13 17:59:19', 1),
(31, 5, '127.0.0.1', '2026-04-13 18:00:33', 1),
(32, 4, '127.0.0.1', '2026-04-13 18:14:48', 1),
(33, 5, '127.0.0.1', '2026-04-13 18:39:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `zeit` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `is_admin`, `zeit`) VALUES
(1, 'test', '$2y$12$eFjxUMSwntyH0dZLYjDorOkkcCRNdU544Uvys9lAdwLmkBi5CP.g.', 1, '2025-11-05'),
(2, 'moritz', '$2y$12$KPR5iw/.BseI.NfEIdTCGOHZvss7CyEWD7lPx73kDTza.hrnXzeUa', 0, '2025-11-06'),
(3, 'neuer User', '$2y$12$/8miYu10k5.CUDfxQWafZeWeFT7WSZhBwX9sKqQYo3OaU8rXxP1pm', 0, '2025-11-09'),
(4, 'maiktest', '$2y$10$DRH7HHvXgaD.3O4YPW56FO6OnkBpxmyIKfhaFheF4sbMyCQyRWGgy', 1, '2026-04-08'),
(5, 'moritz1', '$2y$10$WT5HkIBP026HR/loQNHTI.Y4RHUwjOlmAEZngbMCK53wyj9n2C6fO', 0, '2026-04-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
