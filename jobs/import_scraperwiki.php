<?php

include dirname(__FILE__) . '/../config.inc.php';
include dirname(__FILE__) . '/../lib/db.inc.php';

echo date("Y-m-d H:i:s")." # Importing Scraper Wiki\n";

date_default_timezone_set('Eire');

global $INITIAL_SCRAPERWIKI;

$scraper_wikis = array("2ndeplan41_1", "irish_planning_applications");

$rows_inserted = 0;

$whereClause = $tweetid = '';

if (!$INITIAL_SCRAPERWIKI) {
  $max_age_days = 14;
  $start = date('Y-m-d', time() - $max_age_days * 24 * 60 * 60);
  $whereClause = " WHERE date >= '$start'";
  $tweetid = ' tweetid = NULL';
}

foreach ($scraper_wikis as $scraper_wiki) {
  $query = "SELECT county, appref, date, url, address, applicant, details, lat, lng FROM swdata".$whereClause;
  $data_url = 'http://api.scraperwiki.com/api/1.0/datastore/sqlite?format=jsondict&name='.$scraper_wiki.'&query=' . urlencode($query);
  $data = json_decode(file_get_contents($data_url));

  foreach ($data as $app) {
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
    $query = "SELECT id from councils WHERE short_name = ".db_prep($app->county);
    $sql = mysql_query($query);
    if (mysql_num_rows($sql) == 0) {
      echo date("Y-m-d H:i:s")." # Council not found: " .$app->county.PHP_EOL;
      continue;
    }
    $result = mysql_fetch_object($sql);
    $council_id = $result->id;

    $query = "SELECT * from applications WHERE council_id = $council_id AND app_ref = ".db_prep($app->appref);
    $sql = mysql_query($query);
    if (mysql_num_rows($sql) > 0) {
      continue;
    }

    //We need to have at least app_ref.
    if (!is_null($app->appref) && !empty($app->appref)) {
      $query = "INSERT INTO applications SET
           app_ref = ".db_prep($app->appref).",
           council_id = ".db_prep($council_id).",
           lat = ".db_prep($app->lat).",
           lng = ".db_prep($app->lng).",
           received_date = ".db_prep($app->date).",
           url = ".db_prep($app->url).",
           address1 = ".db_prep($app->address).",
           applicant1 = ".db_prep($app->applicant).",
           details = ".db_prep($app->details).
           $tweetid;
      if (!mysql_query($query)) {
        echo "= DB INSERT ERROR =".PHP_EOL.$query.PHP_EOL.mysql_error().PHP_EOL;
      } else {
        $rows_inserted += 1;
      }
    }
  }
}

echo date("Y-m-d H:i:s")." # $rows_inserted applications inserted from ScraperWiki\n";
