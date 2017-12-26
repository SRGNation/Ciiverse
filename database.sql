-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Dec 25, 2017 at 12:26 AM
-- Server version: 10.0.30-MariaDB-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `srgciive_ciiverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `owner` varchar(32) NOT NULL,
  `yeahs` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int(11) NOT NULL,
  `community_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `community_banner` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `community_picture` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `comm_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL,
  `rd_oly` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `community_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `screenshot` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `yeahs` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` int(11) NOT NULL,
  `ip` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ciiverseid` varchar(32) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `prof_desc` text NOT NULL,
  `password` varchar(128) NOT NULL,
  `user_token` varchar(260) NOT NULL,
  `pfp` varchar(2000) NOT NULL,
  `nnid` varchar(16) DEFAULT NULL,
  `mii_hash` varchar(32) DEFAULT NULL,
  `pfp_type` int(1) NOT NULL,
  `user_type` int(1) NOT NULL,
  `can_post_images` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yeahs`
--

CREATE TABLE `yeahs` (
  `yeah_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `owner` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`yeah_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
