-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2014 at 03:22 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `reg8log`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_change`
--

CREATE TABLE IF NOT EXISTS `email_change` (
  `auto` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` char(8) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `email` varchar(60) CHARACTER SET ascii NOT NULL,
  `emails_sent` tinyint(3) unsigned NOT NULL,
  `email_verification_key` varchar(22) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`auto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
