<?php

include dirname(__FILE__) . '/common.php';

$importer_name = 'DublinCity';
run();

function create_app($row) {
    $status = get_decision_and_status($row['Decision']);
    return array(
        'app_ref' => $row['Planning Application Reference'],
        'council_id' => 3,   // Dublin City
        // Some applications don't have an application date,
        // e.g., D0685/11. We'll use the registration date.
        'received_date' => ($row['Application Date'] ? get_unformatted_date($row['Application Date']) : get_unformatted_date($row['Registration Date'])),
        'decision_date' => ($row['Decision Date'] ? get_unformatted_date($row['Decision Date']) : null),
        'address1' => $row['Main Location'],
        'decision' => $status[0],
        'status' => $status[1],
        'details' => $row['Proposal'],
        'url' => $row['url'],
    );
}

function get_decision_and_status($s) {
    if (!$s) return array('N', 1);
    if (preg_match('/(grant|approved)/i', $s)) return array('C', 9);
    if (preg_match('/invalid/i', $s)) return array('N', 0);
    if (preg_match('/refuse/i', $s)) return array('R', 9);
    if (preg_match('/additional information/i', $s)) return array('N', 2);
    if (preg_match('/withdrawn/i', $s)) return array('N', 8);
    return array('D', 3);
}

function get_unformatted_date($date) {
    $months = array(
        'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
        'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12',
    );
    if (!$months[substr($date, 3, 3)]) throw new Exception("Date format: $date");
    return substr($date, 7) . '-' . $months[substr($date, 3, 3)] . '-' . substr($date, 0, 2);
}
