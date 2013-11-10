-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2013 at 03:30 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amazon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` longtext NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `alias`, `name`, `desc`, `tag`) VALUES
(13, 'bbbbbb', 'BBBBBB', '', ''),
(12, 'aaaaa', 'AAAAA', '', ''),
(11, 'elektronik', 'Elektronik', 'Barang Elektronik', '');

-- --------------------------------------------------------

--
-- Table structure for table `category_sub`
--

CREATE TABLE IF NOT EXISTS `category_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `alias` varchar(225) NOT NULL,
  `name` varchar(225) NOT NULL,
  `desc` longtext NOT NULL,
  `tag` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `category_sub`
--

INSERT INTO `category_sub` (`id`, `category_id`, `alias`, `name`, `desc`, `tag`) VALUES
(1, 12, 'a-1', 'A 1', '1231111', '111,222'),
(3, 11, 'hp1', 'HP1', 'Handphone', ''),
(4, 12, 'a-2', 'A 2', '', ''),
(5, 13, 'b-1', 'B 1', '', ''),
(6, 13, 'b-2', 'B 2', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`id`, `name`, `desc`, `value`) VALUES
(2, 'Config 1', 'Penjelasan COnfig 1', 'asd'),
(4, '2', '3222', '4222'),
(5, '3', '4', '5');

-- --------------------------------------------------------

--
-- Table structure for table `page_static`
--

CREATE TABLE IF NOT EXISTS `page_static` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `desc` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `page_static`
--

INSERT INTO `page_static` (`id`, `name`, `alias`, `desc`) VALUES
(16, '1', '1', '3');

-- --------------------------------------------------------

--
-- Table structure for table `scrape`
--

CREATE TABLE IF NOT EXISTS `scrape` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_sub_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `store` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `library` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `scrape`
--

INSERT INTO `scrape` (`id`, `category_sub_id`, `name`, `store`, `link`, `library`, `is_active`) VALUES
(2, 3, '1', '1', '1', '1111', 1),
(3, 1, 'A', 'A', 'A', '-', 1),
(4, 5, 'B', 'B', 'B', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribe`
--

CREATE TABLE IF NOT EXISTS `subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `subscribe`
--

INSERT INTO `subscribe` (`id`, `email`, `is_active`) VALUES
(2, 'hallo', 0),
(3, 'satu@mail.com 2', 0),
(4, 'asd', 1),
(5, 'aaaa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type_id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `register_date` datetime NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type_id`, `email`, `fullname`, `passwd`, `address`, `register_date`, `is_active`) VALUES
(2, 1, 'her0satr@yahoo.com', 'Herry', '63f3060e14dccfe51a2d8d1ff34b3c9e', '-', '2013-10-17 03:17:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'Member');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
