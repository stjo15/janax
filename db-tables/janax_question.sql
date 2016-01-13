-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Värd: blu-ray.student.bth.se
-- Tid vid skapande: 13 jan 2016 kl 21:58
-- Serverversion: 5.5.46-0+deb8u1-log
-- PHP-version: 5.6.14-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `stjo15`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `janax_question`
--

CREATE TABLE IF NOT EXISTS `janax_question` (
`id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `tag` varchar(250) NOT NULL,
  `tagslug` varchar(250) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `acronym` varchar(80) NOT NULL,
  `userid` int(11) NOT NULL,
  `answers` int(20) NOT NULL,
  `comments` int(20) NOT NULL,
  `web` varchar(200) NOT NULL,
  `gravatar` varchar(200) NOT NULL,
  `ip` varchar(80) NOT NULL,
  `updated` datetime NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `janax_question`
--

INSERT INTO `janax_question` (`id`, `title`, `slug`, `content`, `tag`, `tagslug`, `mail`, `acronym`, `userid`, `answers`, `comments`, `web`, `gravatar`, `ip`, `updated`, `timestamp`) VALUES
(6, 'How do I ask a question?', 'how-do-i-ask-a-question', 'This question is sample question to show the Q&A section of Janax Framework.', 'General', 'general', 'stalle.johansson@gmail.com', 'admin', 5, 1, 1, 'http://www.student.bth.se/~stjo15/javascript/Janax/webroot/', 'http://www.gravatar.com/avatar/12a91909d4b7acb466cac07a76e0fc51.jpg', '194.47.129.122', '2016-01-12 13:55:36', '2016-01-12 13:55:36');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `janax_question`
--
ALTER TABLE `janax_question`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `janax_question`
--
ALTER TABLE `janax_question`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
