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
  $apps = $planning->get_applications("SELECT * from applications WHERE council_id = $council_id ORDER BY received_date DESC, app_ref ASC LIMIT 10");
  foreach ($apps as $app) {
    $result = $planning->tweet_application($app, $twitter, $bitly, true);
    if (!$result) {
      echo "# Skipped $twitter_account: " . $app['app_ref'] . "\n";
    } else {
      echo "# Tweeted to $twitter_account: $result\n";
    }
  }
}
$db->execute("UPDATE applications SET tweet_id = '-1'  WHERE tweet_id IS NULL");
