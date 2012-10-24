-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2012 at 10:50 AM
-- Server version: 5.5.27-log
-- PHP Version: 5.4.6

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
-- Table structure for table `admin_alerts`
--

CREATE TABLE IF NOT EXISTS `admin_alerts` (
  `for` char(5) CHARACTER SET big5 COLLATE big5_bin NOT NULL,
  `new_account_blocks` int(10) unsigned NOT NULL DEFAULT '0',
  `new_ip_blocks` int(10) unsigned NOT NULL DEFAULT '0',
  `last_alert` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
