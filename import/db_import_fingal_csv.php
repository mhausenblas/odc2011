<?php

$url = "http://data.fingal.ie/datasets/csv/Planning_Applications.csv";

if (!file_exists("config.inc.php")) {
  die("Copy config.inc.php.sample to config.inc.php first and insert your parameters.");
}

setup_database();

function setup_database() {
  include("config.inc.php");

  $db_connection = mysql_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD);
  mysql_query("CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE") or die (mysql_error());
  mysql_select_db($MYSQL_DATABASE, $db_connection);
  mysql_set_charset("utf8");

  $table_creation_query = "CREATE TABLE IF NOT EXISTS applications (app_ref varchar(20) NOT NULL,
council_id int(2) NOT NULL,
received_date date DEFAULT NULL,
decision_date date DEFAULT NULL,
url text DEFAULT NULL,
address text DEFAULT NULL,
address1 text DEFAULT NULL,
address2 text DEFAULT NULL,
address3 text DEFAULT NULL,
address4 text DEFAULT NULL,
applicant text DEFAULT NULL,
applicant1 text DEFAULT NULL,
applicant2 text DEFAULT NULL,
applicant3 text DEFAULT NULL,
details text DEFAULT NULL,
decision text DEFAULT NULL,
status text DEFAULT NULL,
lat varchar(25) DEFAULT NULL,
lng varchar(25) DEFAULT NULL,
coordinates varchar(50) DEFAULT NULL,
tweet_id varchar(25) DEFAULT NULL,
KEY council_id (app_ref,council_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  mysql_query($table_creation_query) or die(mysql_error());
}

function db_prep($data)
{
  if (isset($data) && ($data != ''))
  {
    return "'" . mysql_real_escape_string(trim($data)) . "'";
  }
  return "NULL";
}

$query = "SELECT id from councils WHERE short_name = 'Fingal'";
$sql = mysql_query($query);
if (mysql_num_rows($sql) == 0) {
  die("Council not found: Fingal");
}
$result = mysql_fetch_object($sql);
$council_id = $result->id;


$fingal_csv = file_get_contents($url);

$fingal_array = explode("\n", $fingal_csv);

// Planning_Reference,Registration_Date,Location,Description,Current_Status,More_Information,Coordinates

$rows_inserted = 0;

echo "Loaded Fingal CSV".PHP_EOL;

foreach($fingal_array as $row_id => $row) {
  if ($row_id == 0) {
    continue;
  }
  $row = str_getcsv($row);

  $query = "SELECT * from applications WHERE council_id = $council_id AND app_ref = ".db_prep($row[0]);
  $sql = mysql_query($query);
  if (mysql_num_rows($sql) > 0) {
    continue;
  }

  $coordinates = explode(",", $row[6]);

  $query = "INSERT INTO applications SET
       council_id = ".db_prep($council_id).",
       app_ref = ".db_prep($row[0]).",
       received_date = ".db_prep($row[1]).",
       address = ".db_prep($row[2]).",
       details = ".db_prep($row[3]).",
       status = ".db_prep($row[4]).",
       url = ".db_prep($row[5]).",
       coordinates = ".db_prep($row[6]).",
       lat = ".db_prep(trim($coordinates[1])).",
       lng = ".db_prep(trim($coordinates[0]));
  if (!mysql_query($query)) {
    echo "= DB INSERT ERROR =".PHP_EOL.$query.PHP_EOL.mysql_error().PHP_EOL;
  } else {
    $rows_inserted += 1;
  }
}

echo $rows_inserted. " applications inserted for Fingal.";
