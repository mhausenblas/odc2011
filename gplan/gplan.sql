-- Adminer 3.2.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `gplan` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gplan`;

CREATE TABLE IF NOT EXISTS `applications` (
  `app_ref` varchar(20) NOT NULL,
  `lat` varchar(25) DEFAULT NULL,
  `lng` varchar(25) DEFAULT NULL,
  `applicant1` text,
  `applicant2` text,
  `applicant3` text,
  `received_date` date DEFAULT NULL,
  `decision_date` date DEFAULT NULL,
  `address1` text,
  `address2` text,
  `address3` text,
  `address4` text,
  `decision` text,
  `status` text,
  `council_id` int(2) NOT NULL,
  `details` text,
  `applicant` text,
  `address` text,
  `url` text,
  `coordinates` varchar(50) DEFAULT NULL,
  `tweet_id` varchar(25) DEFAULT NULL,
  KEY `council_id` (`app_ref`,`council_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `applicationstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `authorities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `county` tinyint(4) DEFAULT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `geometry_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `counties` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(127) DEFAULT NULL,
  `description` varchar(127) DEFAULT NULL,
  `coords` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `decisioncodes` (
  `id` varchar(127) NOT NULL,
  `name` varchar(127) DEFAULT NULL,
  `description` varchar(127) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `localauthoritybounds` (
  `contact_name` varchar(255) NOT NULL,
  `authority` varchar(255) NOT NULL,
  `county` varchar(127) NOT NULL,
  `mi_style` varchar(127) DEFAULT NULL,
  `mi_prinx` varchar(127) DEFAULT NULL,
  `geometry_text` text,
  `id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `townlands` (
  `townland` varchar(127) NOT NULL,
  `id` int(11) NOT NULL,
  `geometry_xlo` double(13,10) DEFAULT NULL,
  `geometry_ylo` double(13,10) DEFAULT NULL,
  `geometry_xhi` double(13,10) DEFAULT NULL,
  `geometry_yhi` double(13,10) DEFAULT NULL,
  `coords` text,
  `authority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2011-06-03 10:54:11
