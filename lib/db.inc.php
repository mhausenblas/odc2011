<?php

setup_database();

function setup_database() {
  global $MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE;
  $db_connection = mysql_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD) or die ("Couldn't connect to server.");;
  mysql_select_db($MYSQL_DATABASE, $db_connection) or die("Couldn't select database.");;
  mysql_set_charset("utf8");
}

function db_prep($data) {
  $data = trim($data);
  if (isset($data) && ($data != '')) {
    return "'" . mysql_real_escape_string($data) . "'";
  }
  return "NULL";
}
