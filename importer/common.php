<?php

include dirname(__FILE__) . '/../config.inc.php';
require_once dirname(__FILE__) . '/../lib/db.class.php';
require_once dirname(__FILE__) . '/../lib/planning.class.php';

$planning = new Planning(new DB($config));

function run() {
    global $argc, $argv, $planning;
    if ($argc == 3 && $argv[1] == '--geocode') {
        $geocode = true;
        $filename = $argv[2];
    } else if ($argc == 2) {
        $geocode = false;
        $filename = $argv[1];
    } else {
        global $importer_name;
        echo "Usage: php $importer_name.php [--geocode] dump.csv\n";
        die();
    }
    $apps = read_csv($filename);
    $report = $planning->import_apps($apps, $geocode);
    echo date('c') . " Added $report[added] and skipped $report[skipped] applications from $filename\n";
    if ($geocode) {
        echo date('c') . "   Geocoding succeeded for $report[geocode_success] and failed for $report[geocode_fail]\n";
    }
}

function read_csv($filename) {
    $apps = array();
    $f = fopen($filename, 'r');
    $header = fgetcsv($f, 0, ',', '"', '"');
    while ($row = fgetcsv($f, 0, ',', '"', '"')) {
        $assoc = array();
        foreach ($row as $i => $value) {
            $assoc[$header[$i]] = $value;
        }
        $app = create_app($assoc);
        $apps[] = $app;
    }
    fclose($f);
    return $apps;
}

