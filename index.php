<?php
ini_set('display_errors', '1');

include 'config.inc.php';
include 'lib/db.inc.php';
include 'lib/functions.inc.php';
require_once 'lib/request.class.php';

$request = new Request();
if ($request->matches('/^$/')) {
  include 'templates/homepage.php';
  exit;
}

$GP = new GP();

$GP->sendAPIResponse();

?>
