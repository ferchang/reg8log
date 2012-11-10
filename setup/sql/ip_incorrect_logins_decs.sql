-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2012 at 08:15 PM
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
-- Table structure for table `ip_incorrect_logins_decs`
--

CREATE TABLE IF NOT EXISTS `ip_incorrect_logins_decs` (
  `ip` varbinary(16) NOT NULL,
  `account_auto` int(10) unsigned NOT NULL,
  `num_dec` int(10) unsigned NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  KEY `ip` (`ip`,`account_auto`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
