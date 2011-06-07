<?php

include dirname(__FILE__) . '/../config.inc.php';
require_once dirname(__FILE__) . '/../lib/db.class.php';
require_once dirname(__FILE__) . '/../lib/planning.class.php';
require_once dirname(__FILE__) . '/../lib/geotools.class.php';

if ($argc != 2) {
    echo "Usage: import_GalwayCo.php dump.csv\n";
    die();
}
$filename = $argv[1];
$apps = read_GalwayCo_csv($filename);
$planning = new Planning(new DB($config));
$report = $planning->import_apps($apps);
echo date('c') . " Added $report[added] and skipped $report[skipped] applications from $filename\n";

function read_GalwayCo_csv($filename) {
    $apps = array();
    $f = fopen($filename, 'r');
    $header = fgetcsv($f, 0, ',', '"', '"');
    while ($row = fgetcsv($f, 0, ',', '"', '"')) {
        $raw_app = array();
        foreach ($row as $i => $value) {
            $raw_app[$header[$i]] = $value;
        }
        $app = clean_GalwayCo_app($raw_app);
        $apps[] = $app;
    }
    fclose($f);
    return $apps;
}

function clean_GalwayCo_app($raw_app) {
    if (empty($raw_app['AppNo'])) return false;
    if (empty($raw_app['ReceivedDate'])) return false;
    $location = GeoTools::grid_to_lat_lng($raw_app['LocationEasting'], $raw_app['LocationNorthing']);
    return array(
        'app_ref' => $raw_app['AppNo'],
        'council_id' => 18,
        'lat' => $location ? $location[0] : null,
        'lng' => $location ? $location[1] : null,
        'applicant1' => fix_GalwayCo_applicant($raw_app['Applicant']),
        'received_date' => fix_GalwayCo_date($raw_app['ReceivedDate']),
        'decision_date' => fix_GalwayCo_date($raw_app['FinalDecisionDate']),
        'address1' => fix_GalwayCo_address($raw_app['Location']),
        'decision' => get_decision_code($raw_app['FinalDecision']),
        'status' => get_status_code($raw_app['Status']),
        'details' => fix_GalwayCo_details($raw_app['Description']),
        'url' => $raw_app['Link'],
    );
}

function fix_GalwayCo_applicant($s) {
    // The scraper was broken for a while and left entities encoded; fix here
    // just in case
    $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
    // {Patrick} {O'Donnel} is shown as "O'Donnell, Patrick"
    // This has weird results if one of the fields is empty.
    // So we reverse it. If there's more than one comma, we
    // can't do it unambiguously, so better leave it.
    if (preg_match('/^(.*?), (.*)$/', $s, $match)) {
        $s = $match[2] . ' ' . $match[1];
    }
    $s = str_replace('- & -', '-&-', $s);  // "Barna-Golf- & -Country-Club"
    if (!preg_match('/ /', $s)) {          // "Coffey-Plant-Ltd."
        $s = str_replace('-', ' ', $s);
    }
    return rtrim($s, ', ');
}

function fix_GalwayCo_date($s) {
    if (!$s) return null;
    if (!preg_match('#^(\d\d)/(\d\d)/(\d\d)$#', $s, $match)) {
        trigger_error("Date format: '$s'", E_USER_WARN);
        return null;
    }
    return ($match[3] < 50 ? '20' : '19') . $match[3] . '-' . $match[2] . '-' . $match[1];
}

function fix_GalwayCo_address($s) {
    // The scraper was broken for a while and left entities encoded; fix here
    // just in case
    $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

    if ($s == 'NO ADDRESS') return null;
    $s = preg_replace('/\s+/', ' ', $s);
    $s = rtrim($s, ',');
    if (preg_match('/^(.*)\s+Co\.? Galway$/is', $s, $match)) {  // Corcullen Co. Galway
        $s = rtrim($match[1], ',');
    }
    $s = preg_replace('/([^\s])\(/', '\1', $s); // Carrownagower(Kiltulla)
    if (!preg_match('/ /', $s)) {          // "Townparrks-1st-Div-Tuam"
        $s = str_replace('-', ' ', $s);
    }
    return $s;
}

function fix_GalwayCo_details($s) {
    // The scraper was broken for a while and left entities encoded; fix here
    // just in case
    $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

    $s = preg_replace('/\s+/', ' ', $s);
    return $s;
}

function get_decision_code($s) {
    if (!$s) return 'N';
    if ($s == 'Granted - Unconditional') return 'U';
    if ($s == 'Granted - Conditional') return 'C';
    if ($s == 'Refused') return 'R';
    if ($s == 'The final decision will be made available 32 days after the due date of the initial decision.') return 'D';
    trigger_error("Unknown decision string: '$s'", E_USER_WARN);
    return 'D';
}

function get_status_code($s) {
    if ($s == 'Incomplete Application') return 0;
    if ($s == 'New Application') return 1;
    if ($s == 'Further Information Requested') return 2;
    if ($s == 'Decision Made') return 3;
    if ($s == 'Appealed') return 5;
    if ($s == 'Withdrawn') return 8;
    if ($s == 'Application Finalised') return 9;
    if ($s == 'Pre-Validation') return 10;
    if ($s == 'Deemed Withdrawn') return 11;
    if ($s == 'Appealed Financial') return 12;
    if ($s == 'Pending decision') return 13;
    if (!$s) return 14;
    trigger_error("Unknown status string: '$s'", E_USER_WARN);
    return 1;
}
