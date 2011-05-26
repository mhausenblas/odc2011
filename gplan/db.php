<?php
$dbhost = 'localhost';
$dbusername = 'root';
$dbpasswd = 'mysql00';
$database_name = 'gplan';

/* Database Stuff, do not modify below this line */
$connection = mysql_pconnect("$dbhost","$dbusername","$dbpasswd") or die ("Couldn't connect to server.");
$db = mysql_select_db("$database_name", $connection) or die("Couldn't select database.");
$a = mysql_query("set names utf8") or die(mysql_error());
?>
