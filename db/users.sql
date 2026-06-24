-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2026 at 08:51 AM
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
(33, 5, '127.0.0.1', '2026-04-13 18:39:20', 1),
(34, 5, '127.0.0.1', '2026-04-13 22:18:05', 1),
(35, 4, '127.0.0.1', '2026-04-13 22:26:30', 1),
(36, 5, '127.0.0.1', '2026-04-13 22:50:39', 1),
(37, 5, '127.0.0.1', '2026-04-13 23:02:10', 1),
(38, 4, '127.0.0.1', '2026-04-13 23:02:53', 1),
(39, 5, '127.0.0.1', '2026-04-13 23:05:02', 1),
(40, 4, '127.0.0.1', '2026-04-14 14:13:40', 1),
(41, 6, '127.0.0.1', '2026-04-14 14:14:12', 1),
(42, 6, '127.0.0.1', '2026-04-14 14:15:20', 1),
(43, 6, '127.0.0.1', '2026-04-14 14:16:20', 1),
(44, 9, '127.0.0.1', '2026-04-14 14:41:54', 1),
(45, 6, '127.0.0.1', '2026-04-15 16:21:27', 1),
(46, 6, '192.168.87.142', '2026-04-15 16:36:29', 1),
(47, 4, '127.0.0.1', '2026-04-29 14:13:42', 1),
(48, 4, '127.0.0.1', '2026-04-29 14:40:52', 1),
(49, 4, '127.0.0.1', '2026-04-29 14:49:31', 1),
(50, 4, '127.0.0.1', '2026-04-29 15:33:15', 1),
(51, 4, '127.0.0.1', '2026-05-06 13:42:56', 1),
(52, 4, '127.0.0.1', '2026-05-06 13:51:42', 1),
(53, 4, '127.0.0.1', '2026-05-06 13:57:31', 1),
(54, 4, '127.0.0.1', '2026-05-06 14:03:06', 1),
(55, 4, '127.0.0.1', '2026-05-06 14:05:23', 1),
(56, 4, '127.0.0.1', '2026-05-06 14:06:49', 1),
(57, 4, '127.0.0.1', '2026-05-06 14:10:34', 1),
(58, 4, '127.0.0.1', '2026-05-06 14:14:41', 1),
(59, 4, '127.0.0.1', '2026-05-06 14:16:30', 1),
(60, 4, '127.0.0.1', '2026-05-06 14:19:26', 1),
(61, 4, '127.0.0.1', '2026-05-06 14:21:05', 1),
(62, 4, '127.0.0.1', '2026-05-06 14:25:11', 1),
(63, 4, '127.0.0.1', '2026-05-06 14:46:28', 1),
(64, 4, '127.0.0.1', '2026-05-06 14:51:58', 1),
(65, 4, '127.0.0.1', '2026-05-06 14:57:39', 1),
(66, 4, '127.0.0.1', '2026-05-06 14:59:56', 1),
(67, 4, '127.0.0.1', '2026-05-06 15:29:20', 0),
(68, 4, '127.0.0.1', '2026-05-06 15:29:21', 0),
(69, 4, '127.0.0.1', '2026-05-06 15:29:24', 0),
(70, 4, '127.0.0.1', '2026-05-06 15:29:25', 0),
(71, 4, '127.0.0.1', '2026-05-06 15:29:28', 0),
(72, 4, '127.0.0.1', '2026-05-06 15:47:04', 1),
(73, 4, '127.0.0.1', '2026-05-06 15:49:59', 1),
(74, 4, '127.0.0.1', '2026-05-06 15:50:59', 1),
(75, 4, '127.0.0.1', '2026-05-06 15:52:31', 1),
(76, 4, '127.0.0.1', '2026-05-06 17:19:55', 1),
(77, 4, '127.0.0.1', '2026-05-06 17:24:14', 1),
(78, 4, '127.0.0.1', '2026-05-06 17:34:08', 1),
(79, 4, '127.0.0.1', '2026-05-06 17:34:33', 1),
(80, 4, '127.0.0.1', '2026-05-06 17:41:42', 1),
(81, 4, '127.0.0.1', '2026-05-06 17:44:28', 1),
(82, 4, '127.0.0.1', '2026-05-06 17:48:28', 1),
(83, 4, '127.0.0.1', '2026-05-07 10:48:55', 1),
(84, 4, '127.0.0.1', '2026-05-07 10:52:24', 1),
(85, 4, '127.0.0.1', '2026-05-07 10:53:10', 1),
(86, 4, '127.0.0.1', '2026-05-07 10:55:50', 1),
(87, 4, '127.0.0.1', '2026-05-07 14:50:12', 1),
(88, 4, '127.0.0.1', '2026-05-07 15:17:11', 1),
(89, 4, '127.0.0.1', '2026-05-20 13:38:08', 1),
(90, 9, '127.0.0.1', '2026-05-20 14:04:37', 1),
(91, 4, '127.0.0.1', '2026-05-20 14:39:15', 1),
(92, 4, '127.0.0.1', '2026-05-20 15:12:23', 1),
(93, 4, '127.0.0.1', '2026-05-20 15:22:31', 1),
(94, 4, '127.0.0.1', '2026-06-03 13:36:56', 1),
(95, 4, '127.0.0.1', '2026-06-03 13:54:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `register_logs`
--

CREATE TABLE `register_logs` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `zeit` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `is_admin`, `zeit`) VALUES
(1, 'test', NULL, '$2y$12$eFjxUMSwntyH0dZLYjDorOkkcCRNdU544Uvys9lAdwLmkBi5CP.g.', 1, '2025-11-05'),
(2, 'moritz', NULL, '$2y$12$KPR5iw/.BseI.NfEIdTCGOHZvss7CyEWD7lPx73kDTza.hrnXzeUa', 0, '2025-11-06'),
(3, 'neuer User', NULL, '$2y$12$/8miYu10k5.CUDfxQWafZeWeFT7WSZhBwX9sKqQYo3OaU8rXxP1pm', 0, '2025-11-09'),
(4, 'maiktest', NULL, '$2y$10$DRH7HHvXgaD.3O4YPW56FO6OnkBpxmyIKfhaFheF4sbMyCQyRWGgy', 1, '2026-04-08'),
(6, 'moritz1', NULL, '$2y$10$T.V1.JVSGDx3blFNFKkD6ekTNKVaPZWLmiFVDALkc0qpLXX2QvBNK', 0, '2026-04-14'),
(7, 'testt', NULL, '$2y$10$ThNUyJuU1yIlAI0aNa/Lg.6zAG74pprltrUNwalfp2R3D7VndWbgK', 0, '2026-04-14'),
(8, 'testttt', NULL, '$2y$10$YK0K5eMidGVgbbhejXGYxeOKcURxMOHO9X7D52ePOUWNR3Sv77pyO', 0, '2026-04-14'),
(9, 'tamam', NULL, '$2y$10$DcDRSIOmx7Szki2mMaKIfufHV0jwoz0gEsWMX/c2o8qtvQtdK888.', 0, '2026-04-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_logs`
--
ALTER TABLE `register_logs`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `register_logs`
--
ALTER TABLE `register_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
