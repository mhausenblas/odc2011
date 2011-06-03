<?php

$twitter_std_ending = "Pln";

if (!file_exists("config.inc.php")) {
  die("Copy config.inc.php.sample to config.inc.php first and insert your parameters.");
}

if (!file_exists("config_twitter.inc.php")) {
  die("No config_twitter.inc.php");
}

require 'twitter-api/tmhOAuth.php';
include("config_twitter.inc.php");
include("config.inc.php");

date_default_timezone_set('Eire');

$max_tweet_length = 140;
$max_app_details_length = 25;

setup_database();

function setup_database() {
  include("config.inc.php");

  $db_connection = mysql_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD);
  mysql_select_db($MYSQL_DATABASE, $db_connection) or die (mysql_error());
  mysql_set_charset("utf8");
}

function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
{
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&amp;longUrl='.urlencode($url).'&amp;login='.$login.'&amp;apiKey='.$appkey.'&amp;format='.$format.'&amp;history=1';

	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);

	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

function db_prep($data)
{
	if (isset($data) && ($data != ''))
	{
		return "'" . mysql_real_escape_string(trim($data)) . "'";
	}
	return "NULL";
}

function shorten($string, $max_length) {
  $shortened =  substr($string, 0, ($max_length-3));
  return $shortened."...";
}

function tweet($tweet, $council, $consumer_key, $consumer_secret, $user_token, $user_secret) {
  $tmhOAuth = new tmhOAuth(array(
    'consumer_key'    => $consumer_key,
    'consumer_secret' => $consumer_secret,
    'user_token'      => $user_token,
    'user_secret'     => $user_secret,
  ));

  $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
    'status' => $tweet
  ));

  if ($code == 200) {
    return $tmhOAuth->response['response'];
  }
}

function endsWith($string, $test)
{
  $strlen = strlen($string);
  $testlen = strlen($test);
  if ($testlen > $strlen) return false;
  return substr_compare($string, $test, -$testlen) === 0;
}

function clean_address($council, $address) {
  if (endsWith($council, "Tipperary")) {
    $council = "(Tipperary|$council|(South|North) Tipperary)";
  }
  $cleaned_address = str_replace("&nbsp;", "", $address);
  $cleaned_address = str_replace(",,", ",", $cleaned_address);
  $cleaned_address = trim(rtrim(trim($cleaned_address), ",;."));
  $cleaned_address = preg_replace("/((,\s*)?(Co\.?\s*|County\s*))?$council\s*$/i", "", $cleaned_address);
  $cleaned_address = rtrim(trim($cleaned_address), ".,;");
  $cleaned_address = $cleaned_address.": ";
  return $cleaned_address;
}

$result = mysql_query("SELECT id, short_name FROM councils") or die("Couldn't find councils table. Exiting.");
$councils = array();
while ($row = mysql_fetch_row($result)) {
  $councils[$row[0]] = $row[1];
}

foreach ($councils as $council_id => $council) {
  echo "COUNCIL: ".$council.PHP_EOL;
  $twitter_account = $council.$twitter_std_ending;

  $query = "SELECT * from applications WHERE council_id = $council_id AND tweet_id IS NULL ORDER BY received_date ASC LIMIT 0,1";
  $result = mysql_query($query);
  if (mysql_num_rows($result) == 0) {
    echo "No tweets for council ".$council.PHP_EOL;
    continue;
  }
  while ($application = mysql_fetch_object($result)) {
    $tweet_length = $max_tweet_length;

    $url_to_be_shortened = "http://planning-apps.opendata.ie/".$council."#".$application->app_ref;
    $bitly_url = " ".make_bitly_url($url_to_be_shortened, $BITLY_USERNAME, $BITLY_API_KEY, 'json');

    if (isset($application->address)) {
      $address = $application->address;
    } else {
      $address = trim($application->address1).", ".trim($application->address2).", ".trim($application->address3);
      if(strtolower($application->address1) != strtolower($application->address4)) {
        $address .= ", ".trim($application->address4);
      }
    }
    $address = clean_address($council, str_replace("\n", ', ', $address));

    $tweet_length = $tweet_length - strlen($bitly_url);
    $aplication_details = $application->details;

    if (($tweet_length - strlen($address) - strlen($aplication_details)) < 0) {
      if (($tweet_length - strlen($address) - $max_app_details_length) < 0) {
        $tweet_length = $tweet_length - $max_app_details_length;
        if (strlen($address) > $tweet_length) {
          $address = shorten($address, $tweet_length);
        }
        $aplication_details = shorten($aplication_details, $max_app_details_length);
      } else {
        $tweet_length = $tweet_length - strlen($address);
        $aplication_details = shorten($aplication_details, $tweet_length);
      }
    }

    $tweet = $address.$aplication_details.$bitly_url;

    echo $tweet." (".strlen($tweet).")".PHP_EOL;

    $tweeted = tweet($tweet, $council, $TWITTER_CONSUMER_KEY[$twitter_account], $TWITTER_CONSUMER_SECRET[$twitter_account], $TWITTER_USER_TOKEN[$twitter_account], $TWITTER_USER_SECRET[$twitter_account]);

    if (is_object($tweeted)) {
      $tweeted = get_object_vars($tweeted);
    }
    if (!is_array($tweeted)) {
      $tweeted = json_decode($tweeted, true);
    }
    if (is_array($tweeted)) {
      $tweet_id = $tweeted["id_str"];
      $query = "UPDATE applications SET
         tweet_id = ".db_prep($tweet_id)."
         WHERE app_ref = ".$application->app_ref;
      if (!mysql_query($query)) {
        echo "= DB UPDATE ERROR =".PHP_EOL.$query.PHP_EOL.mysql_error().PHP_EOL;
      }
    } else {
      die($tweeted.PHP_EOL."Twitter API problem: no array or object. Exiting.".PHP_EOL);
    }
  }
}