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
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `councils` (`id`, `name`, `county_id`, `short_name`, `url`) VALUES
(7, 'Carlow County Council', 1, 'Carlow', ''),
(8, 'Cavan County Council', 2, 'Cavan', ''),
(9, 'Clare County Council', 3, 'Clare', ''),
(2, 'Cork City Council', 4, 'CorkCity', 'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id='),
(10, 'Cork County Council', 4, 'CorkCo', ''),
(14, 'Donegal County Council', 5, 'Donegal', ''),
(3, 'Dublin City Council', 6, 'DublinCity', ''),
(16, 'Dun Laoghaire Rathdown County Council', 6, 'DunLaoghaire', ''),
(17, 'Fingal County Council', 6, 'Fingal', ''),
(4, 'Galway City Council', 7, 'GalwayCity', ''),
(18, 'Galway County Council', 7, 'GalwayCo', ''),
(19, 'Kerry County Council', 8, 'Kerry', ''),
(20, 'Kildare County Council', 9, 'Kildare', ''),
(21, 'Kilkenny County Council', 10, 'Kilkenny', ''),
(22, 'Laois County Council', 11, 'Laois', 'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id='),
(23, 'Leitrim County Council', 12, 'Leitrim', ''),
(5, 'Limerick City Council', 13, 'LimerickCity', ''),
(24, 'Limerick County Council', 13, 'LimerickCo', ''),
(25, 'Longford County Council', 14, 'Longford', ''),
(26, 'Louth County Council', 15, 'Louth', ''),
(27, 'Mayo County Council', 16, 'Mayo', ''),
(28, 'Meath County Council', 17, 'Meath', ''),
(29, 'Monaghan County Council', 18, 'Monaghan', ''),
(33, 'North Tipperary County Council', 22, 'NTipperary', ''),
(30, 'Offaly County Council', 19, 'Offaly', ''),
(31, 'Roscommon County Council', 20, 'Roscommon', ''),
(32, 'Sligo County Council', 21, 'Sligo', ''),
(15, 'South Dublin County Council', 6, 'SouthDublin', ''),
(34, 'South Tipperary County Council', 22, 'STipperary', ''),
(6, 'Waterford City Council', 23, 'Waterford', 'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id='),
(35, 'Waterford County Council', 23, 'WaterfordCo', 'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id='),
(36, 'Westmeath County Council', 17, 'Westmeath', ''),
(37, 'Wexford County Council', 25, 'Wexford', ''),
(38, 'Wicklow County Council', 26, 'Wicklow', ''),
(39, 'Letterkenny Council', 5, 'Letterkenny', ''),
(40, 'Bundoran Town Council', 5, 'Bundoran', ''),
(41, 'Buncrana Town Council', 5, 'Buncrana', '');
