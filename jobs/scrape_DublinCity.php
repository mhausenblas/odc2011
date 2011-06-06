<?php

/*
Scrapes the Dublin City planning app at
http://www.dublincity.ie/swiftlg/apas/run/Wchweeklylist.displayPlanningLists

*/

require_once dirname(__FILE__) . '/../lib/simplehtmldom/simple_html_dom.php';
require_once dirname(__FILE__) . '/../config.inc.php';
require_once dirname(__FILE__) . '/../lib/db.class.php';
require_once dirname(__FILE__) . '/../lib/planning.class.php';
require_once dirname(__FILE__) . '/../lib/geocoder.class.php';
$db = new DB($config);
$planning = new Planning($db);

//scrape_period('2011-05-25', '2011-06-05', 'latest.csv');
//var_dump(get_application_details('2202/11'));
//var_dump(get_application_details('WEB1032/11'));
//var_dump(get_application_details('D0685/11'));

//$date1 = '2011-05-01';
//$date2 = '2011-05-31';
//var_dump(get_application_ids($date1, $date2));

if ($argc == 3) {
  $year = (int) $argv[1];
  $month = (int) $argv[2];
  if (!$year || !$month) {
    echo "Expected arguments: year month\n";
    die();
  }
  scrape_month($year, $month);
} else if ($argc == 2 && $argv[1] == '--import') {
  $date2 = date('Y-m-d');
  $date1 = date('Y-m-d', time() - 7 * 24 * 60 * 60);
  $applications = get_applications_in_period($date1, $date2);
  foreach ($applications as $app) {
    $app['tweet_id'] = null;
    $planning->add_application($app);
  }
} else {
  echo "Syntax:\n";
  echo "php DublinCity.php 2011 6\n";
  echo "  Scrapes June 2011 and saves everything to a CSV file.\n";
  echo "php DublinCity.php --import\n";
  echo "  Scrapes 7 most recent days and inserts into DB.\n";
  die();
}

function scrape_month($year, $month) {
  $s = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);
  $date1 = "$s-01";
  $date2 = "$s-" . str_pad(date('t', strtotime($date1)), 2, '0', STR_PAD_LEFT);
  scrape_period($date1, $date2, "DublinCity-$s.csv");
}

function scrape_period($date1, $date2, $csv_filename) {
  $applications = get_applications_in_period($date1, $date2);
  write_csv($applications, $csv_filename);
}

function write_csv($apps, $csv_filename) {
  global $planning;
  $fields = $planning->application_columns;
  $f = fopen($csv_filename, 'w');
  fputcsv($f, $fields);
  foreach ($apps as $app) {
    $row = array();
    foreach ($fields as $key) {
      $row[] = @$app[$key];
    }
    fputcsv($f, $row);
  }
  fclose($f);
  echo "# Writing " . count($apps) . " applications to $csv_filename\n";
}

function get_applications_in_period($date1, $date2) {
  $apprefs = get_application_ids($date1, $date2);
  $total = count($apprefs);
  $count = 0;
  $apps = array();
  foreach ($apprefs as $appref) {
    $count++;
    echo "# ($count of $total)\n";
    $apps[] = get_application_details($appref);
    polite_delay();
  }
  return $apps;
}

function get_decision_and_status($s) {
  if (preg_match('/(grant|approved)/i', $s)) return array('C', 9);
  if (preg_match('/invalid/i', $s)) return array('N', 0);
  if (preg_match('/refuse/i', $s)) return array('R', 9);
  if (preg_match('/additional information/i', $s)) return array('N', 2);
  if (preg_match('/withdrawn/i', $s)) return array('N', 8);
  return array('D', 3);
}

function get_application_details($appref) {
  echo "# Fetching app #$appref\n";
  $url = "http://www.dublincity.ie/swiftlg/apas/run/WPHAPPDETAIL.DisplayUrl?theApnID=$appref";
  $html = file_get_html($url . '&theTabNo=2');  // Tab 2 has the decision
  $app = array();
  $app['app_ref'] = $appref;
  $app['council_id'] = 3;   // Dublin City
  $app['url'] = $url;
  $app['tweet_id'] = '1';
  $state = null;
  foreach ($html->find('table[0] td') as $td) {
    $s = trim($td->plaintext);
    if ($s == 'Application Date:') {
      $state = 'appdate';
    } else if ($state == 'appdate') {
      if ($s) $app['received_date'] = get_unformatted_date($s);
      $state = null;
    } else if ($s == 'Registration Date:') {
      $state = 'regdate';
    } else if ($state == 'regdate') {
      // Some applications don't have an application date,
      // e.g., D0685/11. We'll use the registration date.
      if (!@$app['received_date'] && $s) $app['received_date'] = get_unformatted_date($s);
      $state = null;
    } else if ($s == 'Decision Date:') {
      $state = 'decdate';
    } else if ($state == 'decdate') {
      if ($s) {
        $app['decision_date'] = get_unformatted_date($s);
      } else {
        $app['decision'] = 'N';
      }
      $state = null;
    } else if ($s == 'Main Location:') {
      $state = 'loc';
    } else if ($state == 'loc') {
      $app['address1'] = $s;
      $state = null;
    } else if ($s == 'Proposal') {
      $state = 'proposal';
    } else if ($state == 'proposal') {
      $app['details'] = trim(str_replace('View full text', '', $s));
      $state = null;
    } else if (preg_match('/Decision:&nbsp;(.*)/', $s, $match)) {
      $status = get_decision_and_status($match[1]);
      $app['decision'] = $status[0];
      $app['status'] = $status[1];
    }
  }
  $location = Geocoder::geocode($app['address1']);
  if ($location) {
    $app['lat'] = $location['lat'];
    $app['lng'] = $location['lng'];
    echo "#   Geocoding OK\n";
  } else {
    echo "#   Geocoding failed\n";
  }
  return $app;
}

function get_unformatted_date($date) {
  $months = array(
      'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
      'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12',
  );
  if (!$months[substr($date, 3, 3)]) throw new Exception();
  return substr($date, 7) . '-' . $months[substr($date, 3, 3)] . '-' . substr($date, 0, 2);
}

function get_formatted_date($date) {
  $months = array(
      '01' => 'jan', '02' => 'feb', '03' => 'mar', '04' => 'apr', '05' => 'may', '06' => 'jun',
      '07' => 'jul', '08' => 'aug', '09' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dec',
  );
  return substr($date, 8) . '-' . $months[substr($date, 5, 2)] . '-' . substr($date, 0, 4);
}

function polite_delay() {
  sleep(1);
}

function get_application_ids($date1, $date2) {
  echo "# Searching from $date1 to $date2\n";
  $date1 = get_formatted_date($date1);
  $date2 = get_formatted_date($date2);
  $url = "http://www.dublincity.ie/swiftlg/apas/run/Wphappcriteria.showApplications?"
      . "regfromdate=$date1&regtodate=$date2&DispResultsAs=wphappsresweek1";
  $html = file_get_html($url);
  $next_pages = array();
  foreach ($html->find("a") as $link) {
    if (!preg_match('/^(WPHAPPSEARCHRES.displayResultsURL.*)&BackURL=/', $link->href, $match)) continue;
    $next_pages[] = 'http://www.dublincity.ie/swiftlg/apas/run/' . $match[1];
  }
  echo "#   Found " . (count($next_pages) + 1) . " pages of results\n";
  $ids = array();
  $done = 1;
  while (true) {
    foreach ($html->find("td[class='tablebody'] a") as $link) {
      if (!preg_match('/theApnID=(.*?)&/', $link->href, $match)) {
        throw new Exception("Bad detail URL $link->href --- found on page $url");
      }
      if (strpos($match[1], 'Sub')) continue; // Skip 'sub-applications'
      $ids[] = $match[1];
    }
    if (!$next_pages) break;
    polite_delay();
    $html = file_get_html(array_shift($next_pages));
    $done++;
    echo "#   Fetching page $done\n";
  }
  echo "#   Found " . count($ids) . " applications\n";
  return $ids;
}
