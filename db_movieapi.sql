-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 17, 2021 at 09:21 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_movieapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_movies`
--

DROP TABLE IF EXISTS `tbl_movies`;
CREATE TABLE IF NOT EXISTS `tbl_movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Movie ID (Primary Key)',
  `title` varchar(50) NOT NULL COMMENT 'Movie Title/Name',
  `description` text COMMENT 'Movie Description',
  `date` date DEFAULT NULL COMMENT 'Movie Release Date',
  `duration` time DEFAULT NULL COMMENT 'Movie Duration',
  `genre` varchar(50) DEFAULT NULL COMMENT 'Movie Genre',
  `favourite` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Movie Favourite (Favourite Y/N)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_movies`
--

INSERT INTO `tbl_movies` (`id`, `title`, `description`, `date`, `duration`, `genre`, `favourite`) VALUES
(2, 'The Greatest Showman', 'P T Barnum becomes a worldwide sensation in the show business. His imagination and innovative ideas take him to the top of his game.', '2017-09-16', '01:45:00', 'Musical/Drama', 'N'),
(1, 'The Great Gatsby', 'Nick Carraway, a World War I veteran who moves to New York with the hope of making it big, finds himself attracted to Jay Gatsby and his flamboyant lifestyle.', '2013-09-16', '02:22:00', 'Tragedy', 'N'),
(3, 'Titanic', 'Seventeen-year-old Rose hails from an aristocratic family and is set to be married. When she boards the Titanic, she meets Jack Dawson, an artist, and falls in love with him.', '1997-09-16', '03:14:00', 'Romance/Drama', 'N'),
(4, 'Mean Girls', 'Cady joins a new public school and befriends Janis and Damian. They warn her to avoid the Plastics, a group led by Regina, but things get worse when she falls in love with Aaron, Regina\'s ex-lover.', '2004-09-16', '01:37:00', 'Comedy/Teen', 'N'),
(5, 'Monsters, Inc.', 'In Monstropolis, best friends Sulley and Mike are the top scarers working at the Monsters, Inc. However, their lives are hugely disrupted when a human girl enters their world.', '2001-09-16', '01:36:00', 'Comedy/Fantasy', 'N'),
(6, 'Dumb And Dumber', 'Two good-hearted but incredibly stupid friends stumble upon a briefcase. Unknown to them, it contains money that is intended for abductors with connections to the mob.', '1994-09-16', '01:00:00', 'Comedy', 'N'),
(27, 'test', 'hello', '2021-09-17', '00:00:00', NULL, 'N'),
(26, 'test', 'test123', '2021-09-16', NULL, NULL, 'N'),
(28, 'test', 'test123', '2021-09-17', '01:00:00', NULL, 'N');

--
-- Triggers `tbl_movies`
--
DROP TRIGGER IF EXISTS `db_movieapi`;
DELIMITER $$
CREATE TRIGGER `db_movieapi` BEFORE INSERT ON `tbl_movies` FOR EACH ROW SET NEW.date = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `name` text COMMENT 'User Name',
  `username` varchar(100) DEFAULT NULL COMMENT 'User''s Username',
  `password` varchar(50) DEFAULT NULL COMMENT 'User''s Password',
  `favourites` varchar(100) DEFAULT NULL COMMENT 'User''s Favourite Movies',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `username`, `password`, `favourites`) VALUES
(1, 'Katie', 'katiepijohn', 'Password123', 'Monsters, Inc.\r\nMean Girls');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
