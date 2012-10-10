-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2012 at 10:55 PM
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
-- Table structure for table `account_lockdown_log`
--

CREATE TABLE IF NOT EXISTS `account_lockdown_log` (
  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ext_auto` int(10) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `last_attempt` int(10) unsigned NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `unblocked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`auto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
