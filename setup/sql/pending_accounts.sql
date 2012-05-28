-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2012 at 04:20 PM
-- Server version: 5.1.43
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `reg8log`
--

-- --------------------------------------------------------

--
-- Table structure for table `pending_accounts`
--

CREATE TABLE IF NOT EXISTS `pending_accounts` (
  `auto` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `record_id` char(8) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `password_hash` varbinary(51) NOT NULL,
  `email` varchar(60) CHARACTER SET ascii NOT NULL,
  `gender` char(1) CHARACTER SET ascii NOT NULL,
  `emails_sent` tinyint(4) unsigned NOT NULL,
  `email_verification_key` varchar(22) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `admin_confirmed` tinyint(1) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `notify_user` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`auto`),
  UNIQUE KEY `username` (`username`),
  KEY `record_id` (`record_id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
