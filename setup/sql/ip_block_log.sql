-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2013 at 06:23 AM
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
-- Table structure for table `ip_block_log`
--

CREATE TABLE IF NOT EXISTS `ip_block_log` (
  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varbinary(16) NOT NULL,
  `first_attempt` int(10) unsigned NOT NULL,
  `last_attempt` int(10) unsigned NOT NULL,
  `last_username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `unblocked` tinyint(1) NOT NULL DEFAULT '0',
  `block_threshold` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`auto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
