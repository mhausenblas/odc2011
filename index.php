<?php
ini_set('display_errors', '1');

include 'config.inc.php';

require_once 'lib/db.class.php';
require_once 'lib/planning.class.php';
require_once 'lib/response.class.php';
require_once 'lib/request.class.php';
require_once 'lib/site.class.php';
require_once 'lib/http_exception.class.php';

$db = new DB($config);
$planning = new Planning($db);
$request = new Request();
$response = new Response($config['site_base'], $request->uri);
$site = new Site($request->uri, $response, $planning);
set_exception_handler(array($site, 'exception_handler'));

if ($request->matches('/^$/')) {
  $site->action_home();
} else if ($q = $request->matches('/^(about|contact)$/')) {
  $page = $q[1];
  $titles = array(
      "about" => "About this site",
      "contact" => "Contact us",
  );
  $response->render("page-$page", array('title' => $titles[$page]));
} else if ($request->matches('/^stats$/')) {
  $site->action_stats();
} else if ($q = $request->matches('/^(latest|all)$/', array('bounds'))) {
  $site->action_api_area($q[1], $q['bounds']);
} else if ($q = $request->matches('/^near$/', array('center'))) {
  $site->action_api_near($q['center']);
} else if ($q = $request->matches('/^councils$/')) {
  $site->action_council_list();
} else {
  $response->error(404);
}

?>
