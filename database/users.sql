-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 30. Jun 2026 um 21:57
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `users`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `zeit` date NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `is_admin`, `zeit`, `email`) VALUES
(1, 'test', '$2y$12$eFjxUMSwntyH0dZLYjDorOkkcCRNdU544Uvys9lAdwLmkBi5CP.g.', 1, '2025-11-05', NULL),
(2, 'moritz', '$2y$12$KPR5iw/.BseI.NfEIdTCGOHZvss7CyEWD7lPx73kDTza.hrnXzeUa', 0, '2025-11-06', NULL),
(3, 'neuer User', '$2y$12$/8miYu10k5.CUDfxQWafZeWeFT7WSZhBwX9sKqQYo3OaU8rXxP1pm', 0, '2025-11-09', NULL),
(4, 'maiktest', '$2y$10$DRH7HHvXgaD.3O4YPW56FO6OnkBpxmyIKfhaFheF4sbMyCQyRWGgy', 1, '2026-04-08', NULL),
(5, 'moritz1', '$2y$10$WT5HkIBP026HR/loQNHTI.Y4RHUwjOlmAEZngbMCK53wyj9n2C6fO', 0, '2026-04-13', NULL),
(6, 'cyber secuirty', '$2y$10$tVzZBnutZ2DsZph4BYRTke/qxLo0DGrKYcPzZHRFSr7mOJHyZwJp2', 1, '2026-04-13', NULL),
(9, 'test123', '$2y$10$JY67o/ObOgI23TRclAJ.nOWe4Rw5rs6BMkW2sNlvQq1DFJt1SG6E2', 0, '2026-06-24', NULL),
(10, 'test12', '$2y$10$jzBAYaz.WbOgmoTIxtLWDu4x4xaOZpvYWOwQo2fMxvUXeDd2r28CC', 0, '2026-06-24', NULL),
(11, 'UdoErdmann', '$2y$10$4.olx73lYUeuHt9gaUuTN.S13yTxHcd0JrpqQptvVxIy2FAp3sDn2', 0, '2026-06-24', NULL),
(12, 'UdoAdmin', '$2y$10$Zmn6Nj20zrCfEAu/Cy1J9ey4TumkmzqIJmv7KKkpTJDHBoyvHxNuW', 1, '2026-06-24', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
