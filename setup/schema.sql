SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `gplan` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gplan`;

CREATE TABLE IF NOT EXISTS `applications` (
  `app_ref` varchar(20) NOT NULL,
  `council_id` int(2) NOT NULL,
  `lat` double(13,10) DEFAULT NULL,
  `lng` double(13,10) DEFAULT NULL,
  `applicant1` text,
  `applicant2` text,
  `applicant3` text,
  `received_date` date NOT NULL,
  `decision_date` date DEFAULT NULL,
  `address1` text,
  `address2` text,
  `address3` text,
  `address4` text,
  `decision` char(1) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `details` text,
  `url` text,
  `tweet_id` varchar(25) DEFAULT '1',
  PRIMARY KEY `council_id` (`app_ref`,`council_id`),
  KEY `received_date` (`received_date`),
  KEY `lat` (`lat`),
  KEY `lng` (`lng`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `counties` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `counties` (`id`, `name`) VALUES
(1, 'Carlow'),
(2, 'Cavan'),
(3, 'Clare'),
(4, 'Cork'),
(5, 'Donegal'),
(6, 'Dublin'),
(7, 'Galway'),
(8, 'Kerry'),
(9, 'Kildare'),
(10,    'Kilkenny'),
(11,    'Laois'),
(12,    'Leitrim'),
(13,    'Limerick'),
(14,    'Longford'),
(15,    'Louth'),
(16,    'Mayo'),
(17,    'Meath'),
(18,    'Monaghan'),
(19,    'Offaly'),
(20,    'Roscommon'),
(21,    'Sligo'),
(22,    'Tipperary'),
(23,    'Waterford'),
(24,    'Westmeath'),
(25,    'Wexford'),
(26,    'Wicklow');
-- 2011-06-03 21:22:50


CREATE TABLE IF NOT EXISTS `councils` (
  `id` int(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `county_id` int(2) NOT NULL,
  `short_name` varchar(15) NOT NULL,
  `website_home` varchar(200) DEFAULT NULL,
  `website_lookup` varchar(200) DEFAULT NULL,
  `website_system` varchar(20) DEFAULT NULL,
  `googlemaps_lowres` tinyint(1) NOT NULL,
  `lat_lo` float NOT NULL,
  `lng_lo` float NOT NULL,
  `lat_hi` float NOT NULL,
  `lng_hi` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

