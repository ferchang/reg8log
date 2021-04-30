-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2013 at 07:43 AM
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
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(8) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '',
  `username` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `password_hash` char(60) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `email` varchar(60) CHARACTER SET ascii DEFAULT NULL,
  `gender` char(1) CHARACTER SET ascii DEFAULT NULL,
  `autologin_key` char(43) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `banned` int(11) unsigned NOT NULL DEFAULT '0',
  `last_ch_email_try` int(10) unsigned NOT NULL DEFAULT '0',
  `ch_pswd_tries` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_ch_pswd_try` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned DEFAULT '0',
  `last_logout` int(10) unsigned NOT NULL DEFAULT '0',
  `block_disable` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_protection` tinyint(4) NOT NULL DEFAULT '-1',
  `tie_login2ip` tinyint(1) NOT NULL DEFAULT '0',
  `autologin_expiration` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'unix timestamp',
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`auto`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
