<?php
ini_set('display_errors', '1');

include 'db.php';
include 'functions.php';

$GP = new GP();

$GP->sendAPIResponse();

?>
