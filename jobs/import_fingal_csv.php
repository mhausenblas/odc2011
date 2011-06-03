<?php

include(dirname(__FILE__) . '/../config.inc.php');
include dirname(__FILE__) . '/../lib/db.inc.php';

echo date("Y-m-d H:i:s")." # Importing Fingal CSV\n";

$url = "http://data.fingal.ie/datasets/csv/Planning_Applications.csv";

function get_council_id($council) {
  $query = sprintf("SELECT id from councils WHERE short_name = '%s'", mysql_real_escape_string($council));
  $sql = mysql_query($query);
  if (mysql_num_rows($sql) == 0) {
    die(date("Y-m-d H:i:s")." Council not found: Fingal");
  }
  $result = mysql_fetch_object($sql);
  return $result->id;
}

function get_decision($fingal_status_code) {
  $code = strtolower($fingal_status_code);
  if ($code == 'decided') return array('D', 9);
  if (preg_match('/invai?lid or withdrawn/', $code)) return array('R', 8);
  if ($code == 'on appeal') return array('N', 5);
  if ($code == 'pending') return array('N', 1);
  return array(null, null);
}

$council_id = get_council_id('Fingal');

$fingal_array = file($url);

// Planning_Reference,Registration_Date,Location,Description,Current_Status,More_Information,Coordinates

$rows_inserted = 0;

echo date("Y-m-d H:i:s")." # Loaded Fingal CSV\n".PHP_EOL;

foreach($fingal_array as $row_id => $row) {
  // Skip first row
  if ($row_id == 0) {
    continue;
  }
  $row = str_getcsv($row);

  // Skip anything that's already in there
  // @@@ TODO Should actually update the record
  $query = "SELECT * from applications WHERE council_id = $council_id AND app_ref = ".db_prep($row[0]);
  $sql = mysql_query($query);
  if (mysql_num_rows($sql) > 0) {
    continue;
  }

  $coordinates = isset($row[6]) ? explode(",", $row[6]) : array(null, null);

  //We need to have at least app_ref.
  if (is_null($row[0]) || empty($row[0])) {
    continue;
  }
  list($decision, $status) = get_decision($row[4]);
  // @@@ TODO Should we split the address and use address1/2/3/4?
  $query = "INSERT INTO applications SET
       app_ref = ".db_prep($row[0]).",
       council_id = ".db_prep($council_id).",
       lat = ".db_prep($coordinates[1]).",
       lng = ".db_prep($coordinates[0]).",
       received_date = ".db_prep($row[1]).",
       address1 = ".db_prep($row[2]).",
       details = ".db_prep($row[3]).",
       decision = ".db_prep($decision).",
       status = ".db_prep($status).",
       url = ".db_prep($row[5]);
  if (mysql_query($query)) {
    $rows_inserted += 1;
  } else {
    echo "= DB INSERT ERROR =".PHP_EOL.$query.PHP_EOL.mysql_error().PHP_EOL;
  }
}

echo date("Y-m-d H:i:s")." # $rows_inserted applications inserted for Fingal.\n";
