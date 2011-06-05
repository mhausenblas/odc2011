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
  `low_lat` DOUBLE NOT NULL,
  `low_long` DOUBLE NOT NULL,
  `upp_lat` DOUBLE NOT NULL,
  `upp_long` DOUBLE NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `councils` (`id`, `name`, `county_id`, `short_name`, `url`,`low_lat`,`low_long`,`upp_lat`,`upp_long`) VALUES
(7, 'Carlow County Council', 1, 'Carlow', '', '52.456','-7.0','52.913','-6.249'),
(8, 'Cavan County Council', 2, 'Cavan', '','53.747','-7.5','54.08','-6.58'),
(9, 'Clare County Council', 3, 'Clare', '','52.539','-9.954','53.126','-8.332'),
(2, 'Cork City Council', 4, 'CorkCity', 'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id=','51.835','-8.581','51.879','-8.415'),
(10, 'Cork County Council', 4, 'CorkCo', '','51.4399','-9.83','52.348','-8.332'),
(14, 'Donegal County Council', 5, 'Donegal', '','54.5561','-8.83','55.3735','-6.913'),
(3, 'Dublin City Council', 6, 'DublinCity', '','53.249','-6.207','53.373','-6.083'),
(16, 'Dun Laoghaire Rathdown County Council', 6, 'DunLaoghaire', '','0.00','0.00','0.00','0.00'),
(17, 'Fingal County Council', 6, 'Fingal', '','0.00','0.00','0.00','0.00'),
(4, 'Galway City Council', 7, 'GalwayCity', '','53.195','-9.06','53.274','-8.954'),
(18, 'Galway County Council', 7, 'GalwayCo', '','53.166','-10.08','53.664','-8.08'),
(19, 'Kerry County Council', 8, 'Kerry', '','51.70','-10.415','52.539','-9.166'),
(20, 'Kildare County Council', 9, 'Kildare', '','52.871','-7.10','53.415','-6.332'),
(21, 'Kilkenny County Council', 10, 'Kilkenny', '','52.207','-7.664','52.83','-6.954'),
(22, 'Laois County Council', 11, 'Laois', 'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id=','52.747','-7.7885','53.166','-6.83'),
(23, 'Leitrim County Council', 12, 'Leitrim', '','53.788','-8.207','54.166','-7.581'),
(5, 'Limerick City Council', 13, 'LimerickCity', '','52.581','-8.664','52.664','-8.53'),
(24, 'Limerick County Council', 13, 'LimerickCo', '','52.249','-9.332','52.705','-8.207'),
(25, 'Longford County Council', 14, 'Longford', '','53.50','-8.05','53.913','-7.332'),
(26, 'Louth County Council', 15, 'Louth', '','53.688','-6.622','54.107','-6.02'),
(27, 'Mayo County Council', 16, 'Mayo', '','53.581','-10.332','54.107','-8.705'),
(28, 'Meath County Council', 17, 'Meath', '','53.348','-7.415','53.871','-6.124'),
(29, 'Monaghan County Council', 18, 'Monaghan', '','53.871','-7.290','54.390','-6.539'),
(33, 'North Tipperary County Council', 22, 'NTipperary', '','0.00','0.00','0.00','0.00'),
(30, 'Offaly County Council', 19, 'Offaly', '','52.913','-8.116','53.415','-6.83'),
(31, 'Roscommon County Council', 20, 'Roscommon', '','53.456','-8.83','54.03','-7.913'),
(32, 'Sligo County Council', 21, 'Sligo', '','53.871','-9.182','54.415','-8.166'),
(15, 'South Dublin County Council', 6, 'SouthDublin', '','0.00','0.00','0.00','0.00'),
(34, 'South Tipperary County Council', 22, 'STipperary', '0.00','0.00','0.00','0.00','0.00'),
(6, 'Waterford City Council', 23, 'Waterford', 
'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id=','52.207','-7.124','52.249','-6.954'),
(35, 'Waterford County Council', 23, 'WaterfordCo', 
'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=','51.913','-8.116','52.290','-7.0'),
(36, 'Westmeath County Council', 17, 'Westmeath', '','53.249','-7.913','53.747','-6.879'),
(37, 'Wexford County Council', 25, 'Wexford', '','52.124','-6.8715','52.788','-6.03'),
(38, 'Wicklow County Council', 26, 'Wicklow', '','52.705','-6.664','53.190','-5.788'),
(39, 'Letterkenny Council', 5, 'Letterkenny', '','54.904','-7.747','54.933','-7.705'),
(40, 'Bundoran Town Council', 5, 'Bundoran', '','54.435','-8.311','54.477','-8.249'),
(41, 'Buncrana Town Council', 5, 'Buncrana', '','55.103','-7.456','55.124','-7.415');
