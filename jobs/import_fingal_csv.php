<?php

include dirname(__FILE__) . '/../config.inc.php';
include dirname(__FILE__) . '/../lib/db.class.php';
include dirname(__FILE__) . '/../lib/planning.class.php';
$db = new DB($config);
$planning = new Planning($db);

echo date("Y-m-d H:i:s")." # Importing Fingal CSV\n";

$url = "http://data.fingal.ie/datasets/csv/Planning_Applications.csv";

function get_decision($fingal_status_code) {
  $code = strtolower($fingal_status_code);
  if ($code == 'decided') return array('D', 9);
  if (preg_match('/invai?lid or withdrawn/', $code)) return array('R', 8);
  if ($code == 'on appeal') return array('N', 5);
  if ($code == 'pending') return array('N', 1);
  return array(null, null);
}

$council_id = $planning->get_council_id('Fingal');
if (!$council_id) {
  die(date("Y-m-d H:i:s")." Council not found: Fingal");
}

$fingal_array = file($url);

// Planning_Reference,Registration_Date,Location,Description,Current_Status,More_Information,Coordinates

$rows_inserted = 0;
$rows_skipped = 0;

echo date("Y-m-d H:i:s")." # Loaded Fingal CSV\n".PHP_EOL;

foreach($fingal_array as $row_id => $row) {
  // Skip first row
  if ($row_id == 0) continue;

  $row = str_getcsv($row);

  if (empty($row[0])) continue;

  // @@@ TODO Should we split the address and use address1/2/3/4?
  $coordinates = isset($row[6]) ? explode(",", $row[6]) : array(null, null);
  list($decision, $status) = get_decision($row[4]);

  $application = array(
      'app_ref' => $row[0],
      'council_id' => $council_id,
      'lat' => $coordinates[1],
      'lng' => $coordinates[0],
      'received_date' => $row[1],
      'address1' => $row[2],
      'details' => $row[3],
      'decision' => $decision,
      'status' => $status,
      'url' => $row[5],
  );
  // Skip anything that's already in the DB
  // @@@ TODO Should actually update the record
  if ($planning->application_exists($application)) {
    $rows_skipped++;
    continue;
  }

  if ($planning->add_application($application)) {
    $rows_inserted++;
  }
}

echo date("Y-m-d H:i:s")." # $rows_inserted applications inserted for Fingal.\n";
echo date("Y-m-d H:i:s")." # $rows_skipped applications skipped for Fingal.\n";
