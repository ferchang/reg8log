-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2012 at 11:24 PM
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
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(8) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '',
  `username` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `password_hash` varbinary(51) DEFAULT NULL,
  `email` varchar(60) CHARACTER SET ascii DEFAULT NULL,
  `gender` char(1) CHARACTER SET ascii DEFAULT NULL,
  `autologin_key` char(43) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `banned` int(11) unsigned NOT NULL DEFAULT '0',
  `last_ch_email_try` int(10) unsigned NOT NULL DEFAULT '0',
  `ch_pswd_tries` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_ch_pswd_try` int(10) unsigned NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`auto`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
