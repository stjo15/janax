-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 11 jan 2016 kl 18:18
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
-- Tabellstruktur `janax_comment`
--

CREATE TABLE IF NOT EXISTS `janax_comment` (
`id` int(11) NOT NULL,
  `content` text NOT NULL,
  `mail` varchar(80) NOT NULL,
  `acronym` varchar(80) NOT NULL,
  `userid` int(11) NOT NULL,
  `pagekey` varchar(80) NOT NULL,
  `timestamp` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `ip` varchar(80) NOT NULL,
  `web` varchar(200) NOT NULL,
  `gravatar` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `janax_comment`
--

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `janax_comment`
--
ALTER TABLE `janax_comment`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `janax_comment`
--
ALTER TABLE `janax_comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
