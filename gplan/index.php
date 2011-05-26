<?php
set_time_limit(0);
ini_set('display_errors', '1');
header('Content-type: text/html; charset=utf-8');

include 'db.php';
include 'functions.php';

echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#">
    <head>
        <title>GPlan</title>
    </head>

    <body>
<?php
    $GP = new GP();
    $GP->init();
?>
    </body>
</html>
