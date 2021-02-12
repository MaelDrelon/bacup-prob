-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2021 at 10:43 AM
-- Server version: 10.1.47-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MaelDrelonJeuCombat`
--

-- --------------------------------------------------------

--
-- Table structure for table `personnages`
--

CREATE TABLE `personnages` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `nom` varchar(50) NOT NULL,
  `degats` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `lvl` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `experience` smallint(4) UNSIGNED NOT NULL DEFAULT '0',
  `puissance` tinyint(2) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnages`
--

INSERT INTO `personnages` (`id`, `nom`, `degats`, `lvl`, `experience`, `puissance`) VALUES
(1, 'Mael', 9, 9, 10, 17),
(10, 'Dummy3', 0, 1, 0, 1),
(9, 'Dummy2', 0, 1, 0, 1),
(8, 'Dummy', 17, 1, 0, 1),
(11, 'Dummy4', 0, 1, 0, 1),
(12, 'Dummy5', 0, 1, 0, 1),
(13, 'Dummy6', 0, 1, 0, 1),
(14, 'Dummy7', 0, 1, 0, 1),
(15, 'Dummy8', 0, 1, 0, 1),
(16, 'Dummy9', 0, 1, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personnages`
--
ALTER TABLE `personnages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personnages`
--
ALTER TABLE `personnages`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
