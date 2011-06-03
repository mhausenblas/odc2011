<?php

if (!file_exists(dirname(__FILE__) . '/config.inc.php')) {
  die("Copy config.inc.php.sample to config.inc.php first and insert your parameters.");
}

date_default_timezone_set('Eire');

setup_database();

function setup_database() {
  include(dirname(__FILE__) . '/config.inc.php');

  $db_connection = mysql_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD);
  mysql_query("CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE") or die (mysql_error());
  mysql_select_db($MYSQL_DATABASE, $db_connection);
  mysql_set_charset("utf8");

  $table_creation_query = file_get_contents(dirname(__FILE__) . '/applications.sql');

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

$scraper_wikis = array("2ndeplan41_1", "irish_planning_applications");

$rows_inserted = 0;

foreach ($scraper_wikis as $scraper_wiki) {
  /*
  $max_age_days = 28;
  $max_entries = 10;
  $start = date('Y-m-d', time() - $max_age_days * 2    //We need to have at least app_ref.
    if (!is_null($app->appref) && !empty($app->appref)) {
4 * 60 * 60);
  */
  $query = "SELECT county, appref, date, url, address, applicant, details, lat, lng FROM swdata";
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
      echo date("Y-m-d H:i:s")." Council not found: " .$app->county.PHP_EOL;
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
             council_id = ".db_prep($council_id).",
             app_ref = ".db_prep($app->appref).",
             received_date = ".db_prep($app->date).",
             url = ".db_prep($app->url).",
             address = ".db_prep($app->address).",
             applicant = ".db_prep($app->applicant).",
             details = ".db_prep($app->details).",
             lat = ".db_prep($app->lat).",
             lng = ".db_prep($app->lng);
        if (!mysql_query($query)) {
          echo "= DB INSERT ERROR =".PHP_EOL.$query.PHP_EOL.mysql_error().PHP_EOL;
        } else {
          $rows_inserted += 1;
        }
    }
  }
}

echo date("Y-m-d H:i:s")." # $rows_inserted applications inserted.\n";
