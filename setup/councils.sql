SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DELETE FROM `councils`;
INSERT INTO `councils` (`id`, `name`, `county_id`, `short_name`, `website_home`, `website_lookup`, `website_system`, `googlemaps_lowres`, `lat_lo`, `lng_lo`, `lat_hi`, `lng_hi`) VALUES
(2, 'Cork City Council',    4,  'CorkCity', 'http://planning.corkcity.ie/InternetEnquiry/', 'http://planning.corkcity.ie/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic',    0,  51.835, -8.581, 51.92, -8.415),
(3, 'Dublin City Council',  6,  'DublinCity',   'http://www.dublincity.ie/swiftlg/apas/run/wchvarylogin.display',   'http://www.dublincity.ie/swiftlg/apas/run/WPHAPPDETAIL.DisplayUrl?theApnID=',  'Swift',    0,  53.316, -6.302, 53.365, -6.245),
(4, 'Galway City Council',  7,  'GalwayCity',   'http://gis.galwaycity.ie/ePlan/InternetEnquiry/',  'http://gis.galwaycity.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',    'ePlan classic',    0,  53.255, -9.06,  53.298, -9.00),
(5, 'Limerick City Council',    13, 'LimerickCity', 'http://www.limerickcity.ie/ePlan/',    'http://www.limerickcity.ie/ePlan/FileRefDetails.aspx?LASiteID=0&file_number=', 'ePlan 4.1',    1,  52.641, -8.675, 52.684, -8.552),
(6, 'Waterford City Council',   23, 'Waterford',    'http://www.waterfordcity.ie/ePlan/InternetEnquiry/',   'http://www.waterfordcity.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=', 'ePlan classic',    1,  52.237, -7.167, 52.265, -7.095),
(7, 'Carlow County Council',    1,  'Carlow',   'http://apps.countycarlow.ie/ePlan41/', 'http://apps.countycarlow.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.456, -7, 52.913, -6.249),
(8, 'Cavan County Council', 2,  'Cavan',    'http://www.cavancoco.ie/eplan41/', 'http://www.cavancoco.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.747, -7.5,   54.08,  -6.58),
(9, 'Clare County Council', 3,  'Clare',    'http://www.clarecoco.ie/planning/planning-applications/search-planning-applications/', 'http://www.clarecoco.ie/planning/planning-applications/search-planning-applications/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  52.539, -9.954, 53.126, -8.332),
(10,    'Cork County Council',  4,  'CorkCo',   NULL,   NULL,   NULL,   1,  51.4399,    -9.83,  52.348, -8.332),
(14,    'Donegal County Council',   5,  'Donegal',  'http://www.donegal.ie/DCC/iplaninternet/internetenquiry/rpt_querybysurforrecloc.asp',  'http://www.donegal.ie/DCC/iplaninternet/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic plus',   0,  54.5561,    -8.83,  55.3735,    -6.913),
(15,    'South Dublin County Council',  6,  'SouthDublin',  NULL,   NULL,   NULL,   0,  53.166,  -6.249,  53.3735,  -5.9545),
(16,    'Dun Laoghaire-Rathdown County Council',    6,  'DunLaoghaire', NULL,   NULL,   NULL,   0,  53.166,  -6.4565,  53.2905,  -6.2075),
(17,    'Fingal County Council',    6,  'Fingal',   'http://planning.fingalcoco.ie/swiftlg/apas/run/wphappcriteria.display',    'http://planning.fingalcoco.ie/swiftlg/apas/run/WPHAPPDETAIL.DisplayUrl?theApnID=', 'Swift',    0,  53.394,  -6.3735,  53.6225,  -5.9296),
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
(35,    'Waterford County Council', 23, 'WaterfordCo',  'http://eplan.waterfordcoco.ie/ePlan41/',   'http://eplan.waterfordcoco.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=', 'ePlan 4.1',   1,  51.913, -8.116, 52.29,  -7),
(36,    'Westmeath County Council', 17, 'Westmeath',    'http://www.westmeathcoco.ie/ePlan41/', 'http://www.westmeathcoco.ie/ePlan41/FileRefDetails.aspx?LASiteID=0&file_number=',  'ePlan 4.1',    1,  53.249, -7.913, 53.747, -6.879),
(37,    'Wexford County Council',   25, 'Wexford',  NULL,   NULL,   NULL,   1,  52.124, -6.8715,    52.788, -6.03),
(38,    'Wicklow County Council',   26, 'Wicklow',  'http://www.wicklow.ie/eplan41/',   'http://www.wicklow.ie/eplan41/FileRefDetails.aspx?LASiteID=0&file_number=',    'ePlan 4.1',    1,  52.705, -6.664, 53.19,  -5.788),
(39,    'Letterkenny Council',  5,  'Letterkenny',  'http://www.donegal.ie/letterkenny_eplan/internetenquiry/rpt_querybysurforrecloc.asp',  'http://www.donegal.ie/letterkenny_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',   'ePlan classic plus',   0,  54.944, -7.747, 54.953, -7.726),
(40,    'Bundoran Town Council',    5,  'Bundoran', 'http://www.donegal.ie/bundoran_eplan/internetenquiry/rpt_querybysurforrecloc.asp', 'http://www.donegal.ie/bundoran_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',  'ePlan classic plus',   0,  54.467, -8.286, 54.488, -8.239),
(41,    'Buncrana Town Council',    5,  'Buncrana', 'http://www.donegal.ie/buncrana_eplan/internetenquiry/rpt_querybysurforrecloc.asp', 'http://www.donegal.ie/buncrana_eplan/internetenquiry/rpt_ViewApplicDetails.asp?validFileNum=1&app_num_file=',  'ePlan classic plus',   0,  55.112, -7.468, 55.138, -7.437);

-- 2011-06-05 18:39:25
