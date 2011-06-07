<?php

include dirname(__FILE__) . "/../config.inc.php";
require dirname(__FILE__) . '/../lib/twitter-api/tmhOAuth.php';
require dirname(__FILE__) . '/../lib/db.class.php';
require dirname(__FILE__) . '/../lib/planning.class.php';
require dirname(__FILE__) . '/../lib/bitly.class.php';
require dirname(__FILE__) . '/../lib/twitter.class.php';

$db = new DB($config);
$planning = new Planning($db);
$bitly = new Bitly($config);
$twitter = new Twitter($config['twitter']);

foreach ($planning->get_council_list() as $council_id => $details) {
  $council = $details['short'];
  if ($council == 'GalwayCity') continue;  // That one's been running for a while already
  echo "# COUNCIL: ".$council.PHP_EOL;
  $twitter_account = $council . 'Pln';
  $apps = $planning->get_applications("SELECT * from applications WHERE council_id = $council_id AND tweet_id IS NULL ORDER BY received_date ASC, app_ref ASC LIMIT 1");
  foreach ($apps as $app) {
    $result = $planning->tweet_application($app, $twitter, $bitly, false);
    if (!$result) {
      echo date("Y-m-d H:i:s")." # Skipped $twitter_account: " . $app['app_ref'] . "\n";
    } else {
      echo date("Y-m-d H:i:s")." # Tweeted to $twitter_account: $result\n";
    }
  }
}
