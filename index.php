<?php
ini_set('display_errors', '1');

include 'config.inc.php';
include 'lib/db.inc.php';
include 'lib/functions.inc.php';
require_once 'lib/response.class.php';
require_once 'lib/request.class.php';

$request = new Request();
$response = new Response($config['site_base'], $request->uri);

if ($request->matches('/^$/')) {
  include 'templates/homepage.php';
  exit;
} else if ($request->matches('/^(latest|all|near)/')) {
  $GP = new GP();
  $GP->sendAPIResponse();
} else {
  $response->error(404);
}

?>
