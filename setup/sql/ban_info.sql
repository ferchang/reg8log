-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2012 at 11:24 PM
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
-- Table structure for table `ban_info`
--

CREATE TABLE IF NOT EXISTS `ban_info` (
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `until` int(11) unsigned NOT NULL,
  `reason` varchar(400) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`username`),
  KEY `until` (`until`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
