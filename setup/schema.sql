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

INSERT INTO `councils` (`id`, `name`, `county_id`, `short_name`, `website_home`, `website_lookup`, `website_system`, `googlemaps_lowres`, `lat_lo`, `lng_lo`, `lat_hi`, `lng_hi`) VALUES
(2, 'Cork City Council',    4,  'CorkCity', 'http://planning.corkcity.ie/InternetEnquiry/', 'http://planning.corkcity.ie/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic',    0,  51.835, -8.581, 51.879, -8.415),
(3, 'Dublin City Council',  6,  'DublinCity',   'http://www.dublincity.ie/swiftlg/apas/run/wchvarylogin.display',   'http://www.dublincity.ie/swiftlg/apas/run/WPHAPPDETAIL.DisplayUrl?theApnID=',  'Swift',    0,  53.249, -6.207, 53.373, -6.083),
(4, 'Galway City Council',  7,  'GalwayCity',   'http://gis.galwaycity.ie/ePlan/InternetEnquiry/',  'http://gis.galwaycity.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',    'ePlan classic',    0,  53.195, -9.06,  53.274, -8.954),
(5, 'Limerick City Council',    13, 'LimerickCity', 'http://www.limerickcity.ie/ePlan/',    'http://www.limerickcity.ie/ePlan/FileRefDetails.aspx?LASiteID=0&file_number=', 'ePlan 4.1',    1,  52.581, -8.664, 52.664, -8.53),
(6, 'Waterford City Council',   23, 'Waterford',    'http://www.waterfordcity.ie/ePlan/InternetEnquiry/',   'http://www.waterfordcity.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=', 'ePlan classic',    1,  52.207, -7.124, 52.249, -6.954),
(7, 'Carlow County Council',    1,  'Carlow',   'http://apps.countycarlow.ie/ePlan41/', 'http://apps.countycarlow.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.456, -7, 52.913, -6.249),
(8, 'Cavan County Council', 2,  'Cavan',    'http://www.cavancoco.ie/eplan41/', 'http://www.cavancoco.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.747, -7.5,   54.08,  -6.58),
(9, 'Clare County Council', 3,  'Clare',    'http://www.clarecoco.ie/planning/planning-applications/search-planning-applications/', 'http://www.clarecoco.ie/planning/planning-applications/search-planning-applications/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.539, -9.954, 53.126, -8.332),
(10,    'Cork County Council',  4,  'CorkCo',   NULL,   NULL,   NULL,   1,  51.4399,    -9.83,  52.348, -8.332),
(14,    'Donegal County Council',   5,  'Donegal',  'http://www.donegal.ie/DCC/iplaninternet/internetenquiry/rpt_querybysurforrecloc.asp',  'http://www.donegal.ie/DCC/iplaninternet/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic plus',   0,  54.5561,    -8.83,  55.3735,    -6.913),
(15,    'South Dublin County Council',  6,  'SouthDublin',  NULL,   NULL,   NULL,   0,  53.166,  -6.249,  53.3735,  -5.9545),
(16,    'Dun Laoghaire-Rathdown County Council',    6,  'DunLaoghaire', NULL,   NULL,   NULL,   53.166,  -6.4565,  53.2905,  -6.2075),
(17,    'Fingal County Council',    6,  'Fingal',   'http://planning.fingalcoco.ie/swiftlg/apas/run/wphappcriteria.display',    'http://planning.fingalcoco.ie/swiftlg/apas/run/WPHAPPDETAIL.DisplayUrl?theApnID=', 'Swift',    53.394,  -6.3735,  53.6225,  -5.9296),
(18,    'Galway County Council',    7,  'GalwayCo', NULL,   NULL,   NULL,   1,  53.166, -10.08, 53.664, -8.08),
(19,    'Kerry County Council', 8,  'Kerry',    'http://atomik.kerrycoco.ie/ePlan/InternetEnquiry/rpt_querybysurforrecloc.asp', 'http://atomik.kerrycoco.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',  'ePlan classic plus',   0,  51.7,   -10.415,    52.539, -9.166),
(20,    'Kildare County Council',   9,  'Kildare',  NULL,   NULL,   NULL,   1,  52.871, -7.1,   53.415, -6.332),
(21,    'Kilkenny County Council',  10, 'Kilkenny', NULL,   NULL,   NULL,   1,  52.207, -7.664, 52.83,  -6.954),
(22,    'Laois County Council', 11, 'Laois',    'http://www.laois.ie/eplan41/', 'http://www.laois.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.747, -7.7885,    53.166, -6.83),
(23,    'Leitrim County Council',   12, 'Leitrim',  'http://193.178.1.87/ePlan41/', 'http://193.178.1.87/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.788, -8.207, 54.166, -7.581),
(24,    'Limerick County Council',  13, 'LimerickCo',   'http://www.lcc.ie/ePlan/InternetEnquiry/', 'http://www.lcc.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic',    1,  52.249, -9.332, 52.705, -8.207),
(25,    'Longford County Council',  14, 'Longford', 'http://www.longfordcoco.ie/eplan41/',  'http://www.longfordcoco.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',   'ePlan 4.1',    1,  53.5,   -8.05,  53.913, -7.332),
(26,    'Louth County Council', 15, 'Louth',    'http://www.louthcoco.ie/ePlan41/', 'http://www.louthcoco.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.688, -6.622, 54.107, -6.02),
(27,    'Mayo County Council',  16, 'Mayo', NULL,   NULL,   NULL,   1,  53.581, -10.332,    54.107, -8.705),
(28,    'Meath County Council', 17, 'Meath',    'http://www.meath.ie/ePlan41/', 'http://www.meath.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.348, -7.415, 53.871, -6.124),
(29,    'Monaghan County Council',  18, 'Monaghan', 'http://www.monaghan.ie/ePlan41/',  'http://www.monaghan.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',   'ePlan 4.1',    1,  53.871, -7.29,  54.39,  -6.539),
(30,    'Offaly County Council',    19, 'Offaly',   'http://www.offaly.ie/eplan/',  'http://www.offaly.ie/eplan/FileRefDetails.aspx?LASiteID=0&file_number=',   'ePlan 4.1',    1,  52.913, -8.116, 53.415, -6.83),
(31,    'Roscommon County Council', 20, 'Roscommon',    'http://www.roscommoncoco.ie/eplan/',   'http://www.roscommoncoco.ie/eplan/FileRefDetails.aspx?LASiteID=0&file_number=',    'ePlan 4.1',    1,  53.456, -8.83,  54.03,  -7.913),
(32,    'Sligo County Council', 21, 'Sligo',    'http://www.sligococo.ie/ePlan41/', 'http://www.sligococo.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.871, -9.182, 54.415, -8.166),
(33,    'North Tipperary County Council',   22, 'NTipperary',   'http://www.tipperarynorth.ie/ePlan40/',    'http://www.tipperarynorth.ie/ePlan40/FileRefDetails.aspx?LASiteID=0&file_number=', 'ePlan 4',  1,  52.3735,  -8.5,  53.1328,  -7.6225),
(34,    'South Tipperary County Council',   22, 'STipperary',   'http://www.southtippcoco.ie/eplan41/', 'http://www.southtippcoco.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.1992,  -8.249,  52.747,  -7.2905),
(35,    'Waterford County Council', 23, 'WaterfordCo',  NULL,   'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=', NULL,   1,  51.913, -8.116, 52.29,  -7),
(36,    'Westmeath County Council', 17, 'Westmeath',    'http://www.westmeathcoco.ie/ePlan41/', 'http://www.westmeathcoco.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.249, -7.913, 53.747, -6.879),
(37,    'Wexford County Council',   25, 'Wexford',  NULL,   NULL,   NULL,   1,  52.124, -6.8715,    52.788, -6.03),
(38,    'Wicklow County Council',   26, 'Wicklow',  'http://www.wicklow.ie/eplan41/',   'http://www.wicklow.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',    'ePlan 4.1',    1,  52.705, -6.664, 53.19,  -5.788),
(39,    'Letterkenny Council',  5,  'Letterkenny',  'http://www.donegal.ie/letterkenny_eplan/internetenquiry/rpt_querybysurforrecloc.asp',  'http://www.donegal.ie/letterkenny_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic plus',   0,  54.904, -7.747, 54.933, -7.705),
(40,    'Bundoran Town Council',    5,  'Bundoran', 'http://www.donegal.ie/bundoran_eplan/internetenquiry/rpt_querybysurforrecloc.asp', 'http://www.donegal.ie/bundoran_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',  'ePlan classic plus',   0,  54.435, -8.311, 54.477, -8.249),
(41,    'Buncrana Town Council',    5,  'Buncrana', 'http://www.donegal.ie/buncrana_eplan/internetenquiry/rpt_querybysurforrecloc.asp', 'http://www.donegal.ie/buncrana_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',  'ePlan classic plus',   0,  55.103, -7.456, 55.124, -7.415);

-- 2011-06-05 18:39:25
