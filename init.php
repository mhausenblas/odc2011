<?php
ini_set('display_errors', '1');

define('APP_ROOT', dirname(__FILE__) . '/');
include APP_ROOT . 'config.inc.php';
require_once APP_ROOT . 'lib/db.class.php';
require_once APP_ROOT . 'lib/planning.class.php';
require_once APP_ROOT . 'lib/response.class.php';
require_once APP_ROOT . 'lib/request.class.php';
require_once APP_ROOT . 'lib/site.class.php';
require_once APP_ROOT . 'lib/http_exception.class.php';

$db = new DB($config);
$planning = new Planning($db);
