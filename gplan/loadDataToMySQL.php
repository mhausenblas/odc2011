<?php

set_time_limit(0);
ini_set('display_errors', '1');

include 'db.php';
include 'functions.php';

$GP = new GP();

foreach($GP->data as $data => $tableName) {
    $GP->loadDataToMySQL($GP->dataDirectory.$data, $tableName);
}

?>
