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
-- Tabellstruktur `janax_user`
--

CREATE TABLE IF NOT EXISTS `janax_user` (
`id` int(11) NOT NULL,
  `acronym` varchar(20) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `presentation` text,
  `xp` int(200) NOT NULL,
  `bestlap` varchar(80) DEFAULT NULL,
  `numlaps` int(20) DEFAULT '0',
  `web` varchar(200) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `active` datetime DEFAULT NULL,
  `gravatar` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `janax_user`
--



--
-- Index för dumpade tabeller
--

--
-- Index för tabell `janax_user`
--
ALTER TABLE `janax_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `janax_user`
--
ALTER TABLE `janax_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
