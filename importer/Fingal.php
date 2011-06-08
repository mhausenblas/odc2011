<?php

include dirname(__FILE__) . '/common.php';

# Fingal data online: http://data.fingal.ie/datasets/csv/Planning_Applications.csv
$importer_name = 'Fingal';
run();

function create_app($row) {
    $coordinates = isset($row['Coordinates']) ? explode(",", $row['Coordinates']) : array(null, null);
    list($decision, $status) = get_decision($row['Current_Status']);
    return array(
        'app_ref' => $row['Planning_Reference'],
        'council_id' => 17,   // Fingal
        'lat' => $coordinates[1],
        'lng' => $coordinates[0],
        'received_date' => $row['Registration_Date'],
        'address1' => $row['Location'],
        'details' => $row['Description'],
        'decision' => $decision,
        'status' => $status,
        'url' => $row['More_Information'],
    );
}

function get_decision($fingal_status_code) {
    $code = strtolower($fingal_status_code);
    if ($code == 'decided') return array('D', 9);
    if (preg_match('/invai?lid or withdrawn/', $code)) return array('R', 8);
    if ($code == 'on appeal') return array('N', 5);
    if ($code == 'pending') return array('N', 1);
    return array(null, null);
}
