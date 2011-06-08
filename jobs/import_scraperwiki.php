<?php

include dirname(__FILE__) . '/../config.inc.php';
include dirname(__FILE__) . '/../lib/db.class.php';
include dirname(__FILE__) . '/../lib/planning.class.php';
$db = new DB($config);
$planning = new Planning($db);

echo date("Y-m-d H:i:s")." # Importing Scraper Wiki\n";

date_default_timezone_set('Eire');

if ($argc > 2 || ($argc == 2 && $argv[1] != '--initial')) {
  echo "Usage: import_scraperwiki.php [--initial]\n";
  echo "  By default, only recent applicaitons will be imported.\n";
  echo "  In --initial mode, all applications will be imported.\n";
}

$initial_mode = ($argc == 2 && $argv[1] == '--initial');
if ($initial_mode) {
  echo "Running in Initial Mode\n";
}

$scraper_wikis = array("2ndeplan41_1", "irish_planning_applications");

$rows_inserted = 0;
$rows_skipped = 0;

$query = "SELECT county, appref, date, url, address, applicant, details, lat, lng FROM swdata";
if (!$initial_mode) {
  $max_age_days = 14;
  $start = date('Y-m-d', time() - $max_age_days * 24 * 60 * 60);
  $query .= " WHERE date >= '$start'";
}

foreach ($scraper_wikis as $scraper_wiki) {
  $data_url = 'http://api.scraperwiki.com/api/1.0/datastore/sqlite?format=jsondict&name='.$scraper_wiki.'&query=' . urlencode($query);
  $data = json_decode(file_get_contents($data_url));

  foreach ($data as $app) {
    if (empty($app->appref)) continue;

    if ($app->county == "WaterfordCity") {
      $app->county = "Waterford";
    }
    if ($app->county == "Limerick") {
      $app->county = "LimerickCity";
    }
    if ($app->county == "South Tipperary") {
      $app->county = "STipperary";
    }
    if ($app->county == "North Tipperary") {
      $app->county = "NTipperary";
    }

    $council_id = $planning->get_council_id($app->county);
    if (!$council_id) {
      echo date("Y-m-d H:i:s")." # Council not found: " .$app->county.PHP_EOL;
      continue;
    }

    $application = array(
        'app_ref' => $app->appref,
        'council_id' => $council_id,
        'lat' => $app->lat,
        'lng' => $app->lng,
        'received_date' => $app->date,
        'url' => $app->url,
        'address1' => $app->address,
        'applicant1' => $app->applicant,
        'details' => $app->details,
    );
    // @@@ TODO push this back to the ePlan41 scraper ... it happens when converting 0/0 easting/northing to WGS84
    if ($application['lng'] < -10.6) {
      $application['lat'] = null;
      $application['lng'] = null;
    }
    // @@@ TODO Should actually update the record
    if ($planning->application_exists($application)) {
      $rows_skipped++;
      continue;
    }
    if ($planning->add_application($application)) {
      $rows_inserted += 1;
    }
  }
}

echo date("Y-m-d H:i:s")." # $rows_inserted applications inserted from ScraperWiki\n";
echo date("Y-m-d H:i:s")." # $rows_skipped applications skipped from ScraperWiki\n";
