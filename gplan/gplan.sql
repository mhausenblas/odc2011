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
  `description` text NOT NULL
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

INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (10,	'Cork County Council',	'',	4,	'',	'MULTIPOLYGON (((113284.879 79320.05') ON DUPLICATE KEY UPDATE `id` = 10, `name` = 'Cork County Council', `description` = '', `county` = 4, `url` = '', `geometry_text` = 'MULTIPOLYGON (((113284.879 79320.05';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (14,	'Donegal County Council',	'',	5,	'',	'MULTIPOLYGON (((179205.893 358382.8375') ON DUPLICATE KEY UPDATE `id` = 14, `name` = 'Donegal County Council', `description` = '', `county` = 5, `url` = '', `geometry_text` = 'MULTIPOLYGON (((179205.893 358382.8375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (15,	'South Dublin County Council',	'',	6,	'',	'POLYGON ((314146.655 220941.25825') ON DUPLICATE KEY UPDATE `id` = 15, `name` = 'South Dublin County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'POLYGON ((314146.655 220941.25825';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (16,	'Dun Laoghaire Rathdown County Council',	'',	6,	'',	'MULTIPOLYGON (((319396.1953125 232574.21875') ON DUPLICATE KEY UPDATE `id` = 16, `name` = 'Dun Laoghaire Rathdown County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((319396.1953125 232574.21875';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (17,	'Fingal County Council',	'',	6,	'',	'MULTIPOLYGON (((301576.747 235809.9025') ON DUPLICATE KEY UPDATE `id` = 17, `name` = 'Fingal County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((301576.747 235809.9025';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (18,	'Galway County Council',	'',	7,	'',	'GEOMETRYCOLLECTION (LINESTRING (89577.4375 263825.34375') ON DUPLICATE KEY UPDATE `id` = 18, `name` = 'Galway County Council', `description` = '', `county` = 7, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (89577.4375 263825.34375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (19,	'Kerry County Council',	'',	8,	'',	'MULTIPOLYGON (((79645.403716814253 112157.76815992236') ON DUPLICATE KEY UPDATE `id` = 19, `name` = 'Kerry County Council', `description` = '', `county` = 8, `url` = '', `geometry_text` = 'MULTIPOLYGON (((79645.403716814253 112157.76815992236';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (2,	'Cork City Council',	'',	4,	'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id=',	'') ON DUPLICATE KEY UPDATE `id` = 2, `name` = 'Cork City Council', `description` = '', `county` = 4, `url` = 'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id=', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (20,	'Kildare County Council',	'',	9,	'',	'POLYGON ((261208.1345 237216.19075') ON DUPLICATE KEY UPDATE `id` = 20, `name` = 'Kildare County Council', `description` = '', `county` = 9, `url` = '', `geometry_text` = 'POLYGON ((261208.1345 237216.19075';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (21,	'Kilkenny County Council',	'',	10,	'',	'POLYGON ((272057.2635 139461.034') ON DUPLICATE KEY UPDATE `id` = 21, `name` = 'Kilkenny County Council', `description` = '', `county` = 10, `url` = '', `geometry_text` = 'POLYGON ((272057.2635 139461.034';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (22,	'Laois County Council',	'',	11,	'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id=',	'GEOMETRYCOLLECTION (LINESTRING (271446.640625 181500.484375') ON DUPLICATE KEY UPDATE `id` = 22, `name` = 'Laois County Council', `description` = '', `county` = 11, `url` = 'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id=', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (271446.640625 181500.484375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (23,	'Leitrim County Council',	'',	12,	'',	'POLYGON ((205719.62025 285814.30275') ON DUPLICATE KEY UPDATE `id` = 23, `name` = 'Leitrim County Council', `description` = '', `county` = 12, `url` = '', `geometry_text` = 'POLYGON ((205719.62025 285814.30275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (24,	'Limerick County Council',	'',	13,	'',	'POLYGON ((187801.243 115315.92525') ON DUPLICATE KEY UPDATE `id` = 24, `name` = 'Limerick County Council', `description` = '', `county` = 13, `url` = '', `geometry_text` = 'POLYGON ((187801.243 115315.92525';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (25,	'Longford County Council',	'',	14,	'',	'POLYGON ((205719.62025 285814.30275') ON DUPLICATE KEY UPDATE `id` = 25, `name` = 'Longford County Council', `description` = '', `county` = 14, `url` = '', `geometry_text` = 'POLYGON ((205719.62025 285814.30275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (26,	'Louth County Council',	'',	15,	'',	'MULTIPOLYGON (((317223.15625 284636.765625') ON DUPLICATE KEY UPDATE `id` = 26, `name` = 'Louth County Council', `description` = '', `county` = 15, `url` = '', `geometry_text` = 'MULTIPOLYGON (((317223.15625 284636.765625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (27,	'Mayo County Council',	'',	16,	'',	'MULTIPOLYGON (((69918 342137.25') ON DUPLICATE KEY UPDATE `id` = 27, `name` = 'Mayo County Council', `description` = '', `county` = 16, `url` = '', `geometry_text` = 'MULTIPOLYGON (((69918 342137.25';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (28,	'Meath County Council',	'',	17,	'',	'GEOMETRYCOLLECTION (LINESTRING (281391.09375 297158.703125') ON DUPLICATE KEY UPDATE `id` = 28, `name` = 'Meath County Council', `description` = '', `county` = 17, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (281391.09375 297158.703125';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (29,	'Monaghan County Council',	'',	18,	'',	'GEOMETRYCOLLECTION (LINESTRING (292116.65625 311539.15625') ON DUPLICATE KEY UPDATE `id` = 29, `name` = 'Monaghan County Council', `description` = '', `county` = 18, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (292116.65625 311539.15625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (3,	'Dublin City Council',	'',	6,	'',	'MULTIPOLYGON (((320104.3671875 230616.2265625') ON DUPLICATE KEY UPDATE `id` = 3, `name` = 'Dublin City Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((320104.3671875 230616.2265625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (30,	'Offaly County Council',	'',	19,	'',	'POLYGON ((195517.38475 212249.2895') ON DUPLICATE KEY UPDATE `id` = 30, `name` = 'Offaly County Council', `description` = '', `county` = 19, `url` = '', `geometry_text` = 'POLYGON ((195517.38475 212249.2895';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (31,	'Roscommon County Council',	'',	20,	'',	'POLYGON ((205333.10275 286000.65375') ON DUPLICATE KEY UPDATE `id` = 31, `name` = 'Roscommon County Council', `description` = '', `county` = 20, `url` = '', `geometry_text` = 'POLYGON ((205333.10275 286000.65375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (32,	'Sligo County Council',	'',	21,	'',	'MULTIPOLYGON (((170704.96875 358559.515625') ON DUPLICATE KEY UPDATE `id` = 32, `name` = 'Sligo County Council', `description` = '', `county` = 21, `url` = '', `geometry_text` = 'MULTIPOLYGON (((170704.96875 358559.515625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (33,	'North Tipperary County Council',	'',	22,	'',	'GEOMETRYCOLLECTION (LINESTRING (166371.96875 166866.28125') ON DUPLICATE KEY UPDATE `id` = 33, `name` = 'North Tipperary County Council', `description` = '', `county` = 22, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (166371.96875 166866.28125';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (34,	'South Tipperary County Council',	'',	22,	'',	'GEOMETRYCOLLECTION (LINESTRING (192899.53125 106030.765625') ON DUPLICATE KEY UPDATE `id` = 34, `name` = 'South Tipperary County Council', `description` = '', `county` = 22, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (192899.53125 106030.765625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (35,	'Waterford County Council',	'',	23,	'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=',	'GEOMETRYCOLLECTION (LINESTRING (257401.40625 100115.265625') ON DUPLICATE KEY UPDATE `id` = 35, `name` = 'Waterford County Council', `description` = '', `county` = 23, `url` = 'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (257401.40625 100115.265625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (36,	'Westmeath County Council',	'',	17,	'',	'POLYGON ((203125.16775 242375.40175') ON DUPLICATE KEY UPDATE `id` = 36, `name` = 'Westmeath County Council', `description` = '', `county` = 17, `url` = '', `geometry_text` = 'POLYGON ((203125.16775 242375.40175';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (37,	'Wexford County Council',	'',	25,	'',	'MULTIPOLYGON (((271148.765625 127059.09375') ON DUPLICATE KEY UPDATE `id` = 37, `name` = 'Wexford County Council', `description` = '', `county` = 25, `url` = '', `geometry_text` = 'MULTIPOLYGON (((271148.765625 127059.09375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (38,	'Wicklow County Council',	'',	26,	'',	'POLYGON ((326780.24025 219467.78225') ON DUPLICATE KEY UPDATE `id` = 38, `name` = 'Wicklow County Council', `description` = '', `county` = 26, `url` = '', `geometry_text` = 'POLYGON ((326780.24025 219467.78225';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (4,	'Galway City Council',	'',	7,	'',	'') ON DUPLICATE KEY UPDATE `id` = 4, `name` = 'Galway City Council', `description` = '', `county` = 7, `url` = '', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (5,	'Limerick City Council',	'',	13,	'',	'') ON DUPLICATE KEY UPDATE `id` = 5, `name` = 'Limerick City Council', `description` = '', `county` = 13, `url` = '', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (6,	'Waterford City Council',	'',	23,	'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id=',	'') ON DUPLICATE KEY UPDATE `id` = 6, `name` = 'Waterford City Council', `description` = '', `county` = 23, `url` = 'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id=', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (7,	'Carlow County Council',	'',	1,	'',	'POLYGON ((267210.31025 173554.03275') ON DUPLICATE KEY UPDATE `id` = 7, `name` = 'Carlow County Council', `description` = '', `county` = 1, `url` = '', `geometry_text` = 'POLYGON ((267210.31025 173554.03275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (8,	'Cavan County Council',	'',	2,	'',	'POLYGON ((222720.9955 311022.311') ON DUPLICATE KEY UPDATE `id` = 8, `name` = 'Cavan County Council', `description` = '', `county` = 2, `url` = '', `geometry_text` = 'POLYGON ((222720.9955 311022.311';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (9,	'Clare County Council',	'',	3,	'',	'MULTIPOLYGON (((132472.38375 210462.9285') ON DUPLICATE KEY UPDATE `id` = 9, `name` = 'Clare County Council', `description` = '', `county` = 3, `url` = '', `geometry_text` = 'MULTIPOLYGON (((132472.38375 210462.9285';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (10,	'Cork County Council',	'',	4,	'',	'MULTIPOLYGON (((113284.879 79320.05') ON DUPLICATE KEY UPDATE `id` = 10, `name` = 'Cork County Council', `description` = '', `county` = 4, `url` = '', `geometry_text` = 'MULTIPOLYGON (((113284.879 79320.05';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (14,	'Donegal County Council',	'',	5,	'',	'MULTIPOLYGON (((179205.893 358382.8375') ON DUPLICATE KEY UPDATE `id` = 14, `name` = 'Donegal County Council', `description` = '', `county` = 5, `url` = '', `geometry_text` = 'MULTIPOLYGON (((179205.893 358382.8375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (15,	'South Dublin County Council',	'',	6,	'',	'POLYGON ((314146.655 220941.25825') ON DUPLICATE KEY UPDATE `id` = 15, `name` = 'South Dublin County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'POLYGON ((314146.655 220941.25825';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (16,	'Dun Laoghaire Rathdown County Council',	'',	6,	'',	'MULTIPOLYGON (((319396.1953125 232574.21875') ON DUPLICATE KEY UPDATE `id` = 16, `name` = 'Dun Laoghaire Rathdown County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((319396.1953125 232574.21875';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (17,	'Fingal County Council',	'',	6,	'',	'MULTIPOLYGON (((301576.747 235809.9025') ON DUPLICATE KEY UPDATE `id` = 17, `name` = 'Fingal County Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((301576.747 235809.9025';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (18,	'Galway County Council',	'',	7,	'',	'GEOMETRYCOLLECTION (LINESTRING (89577.4375 263825.34375') ON DUPLICATE KEY UPDATE `id` = 18, `name` = 'Galway County Council', `description` = '', `county` = 7, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (89577.4375 263825.34375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (19,	'Kerry County Council',	'',	8,	'',	'MULTIPOLYGON (((79645.403716814253 112157.76815992236') ON DUPLICATE KEY UPDATE `id` = 19, `name` = 'Kerry County Council', `description` = '', `county` = 8, `url` = '', `geometry_text` = 'MULTIPOLYGON (((79645.403716814253 112157.76815992236';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (2,	'Cork City Council',	'',	4,	'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id=',	'') ON DUPLICATE KEY UPDATE `id` = 2, `name` = 'Cork City Council', `description` = '', `county` = 4, `url` = 'http://planning.corkcity.ie/idocs/listFiles.aspx?catalog=planning&id=', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (20,	'Kildare County Council',	'',	9,	'',	'POLYGON ((261208.1345 237216.19075') ON DUPLICATE KEY UPDATE `id` = 20, `name` = 'Kildare County Council', `description` = '', `county` = 9, `url` = '', `geometry_text` = 'POLYGON ((261208.1345 237216.19075';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (21,	'Kilkenny County Council',	'',	10,	'',	'POLYGON ((272057.2635 139461.034') ON DUPLICATE KEY UPDATE `id` = 21, `name` = 'Kilkenny County Council', `description` = '', `county` = 10, `url` = '', `geometry_text` = 'POLYGON ((272057.2635 139461.034';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (22,	'Laois County Council',	'',	11,	'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id=',	'GEOMETRYCOLLECTION (LINESTRING (271446.640625 181500.484375') ON DUPLICATE KEY UPDATE `id` = 22, `name` = 'Laois County Council', `description` = '', `county` = 11, `url` = 'http://www.laois.ie/idocs/listFiles.aspx?catalog=planning&id=', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (271446.640625 181500.484375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (23,	'Leitrim County Council',	'',	12,	'',	'POLYGON ((205719.62025 285814.30275') ON DUPLICATE KEY UPDATE `id` = 23, `name` = 'Leitrim County Council', `description` = '', `county` = 12, `url` = '', `geometry_text` = 'POLYGON ((205719.62025 285814.30275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (24,	'Limerick County Council',	'',	13,	'',	'POLYGON ((187801.243 115315.92525') ON DUPLICATE KEY UPDATE `id` = 24, `name` = 'Limerick County Council', `description` = '', `county` = 13, `url` = '', `geometry_text` = 'POLYGON ((187801.243 115315.92525';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (25,	'Longford County Council',	'',	14,	'',	'POLYGON ((205719.62025 285814.30275') ON DUPLICATE KEY UPDATE `id` = 25, `name` = 'Longford County Council', `description` = '', `county` = 14, `url` = '', `geometry_text` = 'POLYGON ((205719.62025 285814.30275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (26,	'Louth County Council',	'',	15,	'',	'MULTIPOLYGON (((317223.15625 284636.765625') ON DUPLICATE KEY UPDATE `id` = 26, `name` = 'Louth County Council', `description` = '', `county` = 15, `url` = '', `geometry_text` = 'MULTIPOLYGON (((317223.15625 284636.765625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (27,	'Mayo County Council',	'',	16,	'',	'MULTIPOLYGON (((69918 342137.25') ON DUPLICATE KEY UPDATE `id` = 27, `name` = 'Mayo County Council', `description` = '', `county` = 16, `url` = '', `geometry_text` = 'MULTIPOLYGON (((69918 342137.25';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (28,	'Meath County Council',	'',	17,	'',	'GEOMETRYCOLLECTION (LINESTRING (281391.09375 297158.703125') ON DUPLICATE KEY UPDATE `id` = 28, `name` = 'Meath County Council', `description` = '', `county` = 17, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (281391.09375 297158.703125';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (29,	'Monaghan County Council',	'',	18,	'',	'GEOMETRYCOLLECTION (LINESTRING (292116.65625 311539.15625') ON DUPLICATE KEY UPDATE `id` = 29, `name` = 'Monaghan County Council', `description` = '', `county` = 18, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (292116.65625 311539.15625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (3,	'Dublin City Council',	'',	6,	'',	'MULTIPOLYGON (((320104.3671875 230616.2265625') ON DUPLICATE KEY UPDATE `id` = 3, `name` = 'Dublin City Council', `description` = '', `county` = 6, `url` = '', `geometry_text` = 'MULTIPOLYGON (((320104.3671875 230616.2265625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (30,	'Offaly County Council',	'',	19,	'',	'POLYGON ((195517.38475 212249.2895') ON DUPLICATE KEY UPDATE `id` = 30, `name` = 'Offaly County Council', `description` = '', `county` = 19, `url` = '', `geometry_text` = 'POLYGON ((195517.38475 212249.2895';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (31,	'Roscommon County Council',	'',	20,	'',	'POLYGON ((205333.10275 286000.65375') ON DUPLICATE KEY UPDATE `id` = 31, `name` = 'Roscommon County Council', `description` = '', `county` = 20, `url` = '', `geometry_text` = 'POLYGON ((205333.10275 286000.65375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (32,	'Sligo County Council',	'',	21,	'',	'MULTIPOLYGON (((170704.96875 358559.515625') ON DUPLICATE KEY UPDATE `id` = 32, `name` = 'Sligo County Council', `description` = '', `county` = 21, `url` = '', `geometry_text` = 'MULTIPOLYGON (((170704.96875 358559.515625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (33,	'North Tipperary County Council',	'',	22,	'',	'GEOMETRYCOLLECTION (LINESTRING (166371.96875 166866.28125') ON DUPLICATE KEY UPDATE `id` = 33, `name` = 'North Tipperary County Council', `description` = '', `county` = 22, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (166371.96875 166866.28125';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (34,	'South Tipperary County Council',	'',	22,	'',	'GEOMETRYCOLLECTION (LINESTRING (192899.53125 106030.765625') ON DUPLICATE KEY UPDATE `id` = 34, `name` = 'South Tipperary County Council', `description` = '', `county` = 22, `url` = '', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (192899.53125 106030.765625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (35,	'Waterford County Council',	'',	23,	'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=',	'GEOMETRYCOLLECTION (LINESTRING (257401.40625 100115.265625') ON DUPLICATE KEY UPDATE `id` = 35, `name` = 'Waterford County Council', `description` = '', `county` = 23, `url` = 'http://193.178.2.69/idocsweb/listFiles.aspx?catalog=planning&id=', `geometry_text` = 'GEOMETRYCOLLECTION (LINESTRING (257401.40625 100115.265625';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (36,	'Westmeath County Council',	'',	17,	'',	'POLYGON ((203125.16775 242375.40175') ON DUPLICATE KEY UPDATE `id` = 36, `name` = 'Westmeath County Council', `description` = '', `county` = 17, `url` = '', `geometry_text` = 'POLYGON ((203125.16775 242375.40175';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (37,	'Wexford County Council',	'',	25,	'',	'MULTIPOLYGON (((271148.765625 127059.09375') ON DUPLICATE KEY UPDATE `id` = 37, `name` = 'Wexford County Council', `description` = '', `county` = 25, `url` = '', `geometry_text` = 'MULTIPOLYGON (((271148.765625 127059.09375';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (38,	'Wicklow County Council',	'',	26,	'',	'POLYGON ((326780.24025 219467.78225') ON DUPLICATE KEY UPDATE `id` = 38, `name` = 'Wicklow County Council', `description` = '', `county` = 26, `url` = '', `geometry_text` = 'POLYGON ((326780.24025 219467.78225';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (4,	'Galway City Council',	'',	7,	'',	'') ON DUPLICATE KEY UPDATE `id` = 4, `name` = 'Galway City Council', `description` = '', `county` = 7, `url` = '', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (5,	'Limerick City Council',	'',	13,	'',	'') ON DUPLICATE KEY UPDATE `id` = 5, `name` = 'Limerick City Council', `description` = '', `county` = 13, `url` = '', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (6,	'Waterford City Council',	'',	23,	'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id=',	'') ON DUPLICATE KEY UPDATE `id` = 6, `name` = 'Waterford City Council', `description` = '', `county` = 23, `url` = 'http://www.waterfordcity.ie/idocsweb/listFiles.aspx?catalog=planning&id=', `geometry_text` = '';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (7,	'Carlow County Council',	'',	1,	'',	'POLYGON ((267210.31025 173554.03275') ON DUPLICATE KEY UPDATE `id` = 7, `name` = 'Carlow County Council', `description` = '', `county` = 1, `url` = '', `geometry_text` = 'POLYGON ((267210.31025 173554.03275';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (8,	'Cavan County Council',	'',	2,	'',	'POLYGON ((222720.9955 311022.311') ON DUPLICATE KEY UPDATE `id` = 8, `name` = 'Cavan County Council', `description` = '', `county` = 2, `url` = '', `geometry_text` = 'POLYGON ((222720.9955 311022.311';
INSERT INTO `authorities` (`id`, `name`, `description`, `county`, `url`, `geometry_text`) VALUES (9,	'Clare County Council',	'',	3,	'',	'MULTIPOLYGON (((132472.38375 210462.9285') ON DUPLICATE KEY UPDATE `id` = 9, `name` = 'Clare County Council', `description` = '', `county` = 3, `url` = '', `geometry_text` = 'MULTIPOLYGON (((132472.38375 210462.9285';

DROP TABLE IF EXISTS `counties`;
CREATE TABLE `counties` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(127) NOT NULL,
  `description` varchar(127) NOT NULL,
  `coord` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (1,	'Carlow',	'',	'') ON DUPLICATE KEY UPDATE `id` = 1, `name` = 'Carlow', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (10,	'Kilkenny',	'',	'') ON DUPLICATE KEY UPDATE `id` = 10, `name` = 'Kilkenny', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (11,	'Laois',	'',	'') ON DUPLICATE KEY UPDATE `id` = 11, `name` = 'Laois', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (12,	'Leitrim',	'',	'') ON DUPLICATE KEY UPDATE `id` = 12, `name` = 'Leitrim', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (13,	'Limerick',	'',	'') ON DUPLICATE KEY UPDATE `id` = 13, `name` = 'Limerick', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (14,	'Longford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 14, `name` = 'Longford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (15,	'Louth',	'',	'') ON DUPLICATE KEY UPDATE `id` = 15, `name` = 'Louth', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (16,	'Mayo',	'',	'') ON DUPLICATE KEY UPDATE `id` = 16, `name` = 'Mayo', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (17,	'Meath',	'',	'') ON DUPLICATE KEY UPDATE `id` = 17, `name` = 'Meath', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (18,	'Monaghan',	'',	'') ON DUPLICATE KEY UPDATE `id` = 18, `name` = 'Monaghan', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (19,	'Offaly',	'',	'') ON DUPLICATE KEY UPDATE `id` = 19, `name` = 'Offaly', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (2,	'Cavan',	'',	'') ON DUPLICATE KEY UPDATE `id` = 2, `name` = 'Cavan', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (20,	'Roscommon',	'',	'') ON DUPLICATE KEY UPDATE `id` = 20, `name` = 'Roscommon', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (21,	'Sligo',	'',	'') ON DUPLICATE KEY UPDATE `id` = 21, `name` = 'Sligo', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (22,	'Tipperary',	'',	'') ON DUPLICATE KEY UPDATE `id` = 22, `name` = 'Tipperary', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (23,	'Waterford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 23, `name` = 'Waterford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (24,	'Westmeath',	'',	'') ON DUPLICATE KEY UPDATE `id` = 24, `name` = 'Westmeath', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (25,	'Wexford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 25, `name` = 'Wexford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (26,	'Wicklow',	'',	'') ON DUPLICATE KEY UPDATE `id` = 26, `name` = 'Wicklow', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (3,	'Clare',	'',	'') ON DUPLICATE KEY UPDATE `id` = 3, `name` = 'Clare', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (4,	'Cork',	'',	'') ON DUPLICATE KEY UPDATE `id` = 4, `name` = 'Cork', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (5,	'Donegal',	'',	'') ON DUPLICATE KEY UPDATE `id` = 5, `name` = 'Donegal', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (6,	'Dublin',	'',	'') ON DUPLICATE KEY UPDATE `id` = 6, `name` = 'Dublin', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (7,	'Galway',	'',	'') ON DUPLICATE KEY UPDATE `id` = 7, `name` = 'Galway', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (8,	'Kerry',	'',	'') ON DUPLICATE KEY UPDATE `id` = 8, `name` = 'Kerry', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (9,	'Kildare',	'',	'') ON DUPLICATE KEY UPDATE `id` = 9, `name` = 'Kildare', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (1,	'Carlow',	'',	'') ON DUPLICATE KEY UPDATE `id` = 1, `name` = 'Carlow', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (10,	'Kilkenny',	'',	'') ON DUPLICATE KEY UPDATE `id` = 10, `name` = 'Kilkenny', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (11,	'Laois',	'',	'') ON DUPLICATE KEY UPDATE `id` = 11, `name` = 'Laois', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (12,	'Leitrim',	'',	'') ON DUPLICATE KEY UPDATE `id` = 12, `name` = 'Leitrim', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (13,	'Limerick',	'',	'') ON DUPLICATE KEY UPDATE `id` = 13, `name` = 'Limerick', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (14,	'Longford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 14, `name` = 'Longford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (15,	'Louth',	'',	'') ON DUPLICATE KEY UPDATE `id` = 15, `name` = 'Louth', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (16,	'Mayo',	'',	'') ON DUPLICATE KEY UPDATE `id` = 16, `name` = 'Mayo', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (17,	'Meath',	'',	'') ON DUPLICATE KEY UPDATE `id` = 17, `name` = 'Meath', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (18,	'Monaghan',	'',	'') ON DUPLICATE KEY UPDATE `id` = 18, `name` = 'Monaghan', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (19,	'Offaly',	'',	'') ON DUPLICATE KEY UPDATE `id` = 19, `name` = 'Offaly', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (2,	'Cavan',	'',	'') ON DUPLICATE KEY UPDATE `id` = 2, `name` = 'Cavan', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (20,	'Roscommon',	'',	'') ON DUPLICATE KEY UPDATE `id` = 20, `name` = 'Roscommon', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (21,	'Sligo',	'',	'') ON DUPLICATE KEY UPDATE `id` = 21, `name` = 'Sligo', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (22,	'Tipperary',	'',	'') ON DUPLICATE KEY UPDATE `id` = 22, `name` = 'Tipperary', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (23,	'Waterford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 23, `name` = 'Waterford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (24,	'Westmeath',	'',	'') ON DUPLICATE KEY UPDATE `id` = 24, `name` = 'Westmeath', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (25,	'Wexford',	'',	'') ON DUPLICATE KEY UPDATE `id` = 25, `name` = 'Wexford', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (26,	'Wicklow',	'',	'') ON DUPLICATE KEY UPDATE `id` = 26, `name` = 'Wicklow', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (3,	'Clare',	'',	'') ON DUPLICATE KEY UPDATE `id` = 3, `name` = 'Clare', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (4,	'Cork',	'',	'') ON DUPLICATE KEY UPDATE `id` = 4, `name` = 'Cork', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (5,	'Donegal',	'',	'') ON DUPLICATE KEY UPDATE `id` = 5, `name` = 'Donegal', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (6,	'Dublin',	'',	'') ON DUPLICATE KEY UPDATE `id` = 6, `name` = 'Dublin', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (7,	'Galway',	'',	'') ON DUPLICATE KEY UPDATE `id` = 7, `name` = 'Galway', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (8,	'Kerry',	'',	'') ON DUPLICATE KEY UPDATE `id` = 8, `name` = 'Kerry', `description` = '', `coord` = '';
INSERT INTO `counties` (`id`, `name`, `description`, `coord`) VALUES (9,	'Kildare',	'',	'') ON DUPLICATE KEY UPDATE `id` = 9, `name` = 'Kildare', `description` = '', `coord` = '';

DROP TABLE IF EXISTS `decisioncodes`;
CREATE TABLE `decisioncodes` (
  `id` varchar(127) NOT NULL,
  `name` varchar(127) NOT NULL,
  `description` varchar(127) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `localauthoritybounds`;
CREATE TABLE `localauthoritybounds` (
  `contact_name` varchar(255) NOT NULL,
  `authority` varchar(255) NOT NULL,
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
  `geometry_xlo` double(13,10) NOT NULL,
  `geometry_ylo` double(13,10) NOT NULL,
  `geometry_xhi` double(13,10) NOT NULL,
  `geometry_yhi` double(13,10) NOT NULL,
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


DROP TABLE IF EXISTS `planning`;
CREATE TABLE `planning` (
  `id` varchar(16) NOT NULL,
  `registration_date` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `coords` text NOT NULL
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


-- 2011-06-01 11:59:38
