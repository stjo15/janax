-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 11 jan 2016 kl 18:19
-- Serverversion: 5.6.21
-- PHP-version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `mesidan`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `janax_tag`
--

CREATE TABLE IF NOT EXISTS `janax_tag` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `questions` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `janax_tag`
--

INSERT INTO `janax_tag` (`id`, `name`, `slug`, `questions`) VALUES
(1, 'News', 'news', 0),
(2, 'Technology', 'technology', 0),
(3, 'Purchase', 'purchase', 0),
(4, 'Design', 'design', 0),
(5, 'Performance', 'performance', 0),
(6, 'Accessories', 'accessories', 0),
(7, 'General', 'general', 0),
(8, 'Economy', 'economy', 0),
(9, 'Maintenance', 'maintenance', 0);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `janax_tag`
--
ALTER TABLE `janax_tag`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
