-- Adminer 3.2.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `gplan`;
CREATE DATABASE `gplan` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gplan`;

DROP TABLE IF EXISTS `applicationstatus`;
CREATE TABLE `applicationstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text CHARACTER SET ucs2 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `authorities`;
CREATE TABLE `authorities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `county` tinyint(4) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `geometry_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `counties`;
CREATE TABLE `counties` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(127) NOT NULL,
  `description` varchar(127) NOT NULL,
  `coord` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `decisioncodes`;
CREATE TABLE `decisioncodes` (
  `id` varchar(127) NOT NULL,
  `name` varchar(127) NOT NULL,
  `description` varchar(127) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `localauthoritybounds`;
CREATE TABLE `localauthoritybounds` (
  `contact_name` varchar(255) NOT NULL,
  `local_authority` varchar(255) NOT NULL,
  `county` varchar(127) NOT NULL,
  `mi_style` varchar(127) NOT NULL,
  `mi_prinx` varchar(127) NOT NULL,
  `geometry_text` text NOT NULL,
  `id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `metadata`;
CREATE TABLE `metadata` (
  `id` int(11) NOT NULL,
  `file_number` varchar(16) NOT NULL,
  `geometry_xlo` double(18,15) NOT NULL,
  `geometry_ylo` double(18,15) NOT NULL,
  `geometry_xhi` double(18,15) NOT NULL,
  `geometry_yhi` double(18,15) NOT NULL,
  `coords` text NOT NULL,
  `forename` varchar(255) NOT NULL,
  `surename` varchar(255) NOT NULL,
  `application_name` varchar(255) NOT NULL,
  `received_date` datetime NOT NULL,
  `decision_date` datetime NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `address3` varchar(255) NOT NULL,
  `location_key` varchar(127) NOT NULL,
  `dec_code` char(1) NOT NULL,
  `status_code` tinyint(4) NOT NULL,
  `development_description` text NOT NULL,
  `decision_code` varchar(127) NOT NULL,
  `application_status` varchar(255) NOT NULL,
  `county` tinyint(4) NOT NULL,
  `townland` int(11) NOT NULL,
  `authority` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `townlands`;
CREATE TABLE `townlands` (
  `townland` varchar(127) NOT NULL,
  `id` int(11) NOT NULL,
  `geometry_xlo` double(18,15) NOT NULL,
  `geometry_ylo` double(18,15) NOT NULL,
  `geometry_xhi` double(18,15) NOT NULL,
  `geometry_yhi` double(18,15) NOT NULL,
  `coords` text NOT NULL,
  `authority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2011-05-26 03:25:20
