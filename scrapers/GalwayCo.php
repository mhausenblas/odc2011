<?php

require_once dirname(__FILE__) . '/common.php';

$scraper_name = 'GalwayCo';
$site_url = 'http://www.galway.ie/PlanningSearch';
$crawl_delay = 3;
$extra_help = 
    "  php $scraper_name.php --old\n" .
    "    Scrapes all old applications 1960-1991 (few)\n";

if ($argc == 2 && $argv[1] == '--old') {
    $apps = get_applications("1960-01-01", "1991-12-31");
    write_csv($apps); 
} else {
    run();
}

/**
 * Executes a search and returns all modified applications.
 * @param $start_date Inclusive, in YYYY-MM-DD format
 * @param $end_date Inclusive, in YYYY-MM-DD format
 * @return Array of applications
 */
function &get_changed_applications($start_date, $end_date) {
    $apps = get_applications($start_date, $end_date);
    $apps += get_applications($start_date, $end_date, 'Validated');
    $apps += get_applications($start_date, $end_date, 'Decided');
    return $apps;
}

/**
 * Executes a search and returns applications.
 * @param $start_date Inclusive, in YYYY-MM-DD format
 * @param $end_date Inclusive, in YYYY-MM-DD format
 * @param $status One of "Received" (default), "Validated", "Due", "Extended", "Decided"
 * @return Array of applications
 */
function &get_applications($start_date, $end_date, $status = 'Received') {
    $statii = array(
        'Received' => 'received_date',
        'Validated' => 'valid_applic_date',
        'Due' => 'due_date',
        'Extended' => 'extend_agree_date',
        'Decided' => 'decision_m_o_date',
    );
    if (empty($statii[$status])) {
        throw new Exception("Unknown status '$status'; must be one of 'Received', 'Validated', 'Decided' etc");
    }
    if (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $start_date, $d1)) {
        throw new Exception("Not in YYYY-MM-DD format: $start_date");
    }
    if (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $end_date, $d2)) {
        throw new Exception("Not in YYYY-MM-DD format: $end_date");
    }
    global $site_url;
    $url = "$site_url/PlanningApps.taf?_function=list&_type=adv";
    $postvars = array(
        'datefield' => $statii[$status],
        'date1' => "$d1[3]/$d1[2]/$d1[1]",
        'date2' => "$d2[3]/$d2[2]/$d2[1]",
    );
    $html = http_get_post_response($url, $postvars);
    $dom = str_get_html($html);
    $results = array();
    $rowcount = 0;
    foreach ($dom->find('table', 2)->find('tr') as $row) {
        // skip spacer rows, sum row, and description rows
        if (trim($row->plaintext) == '') continue; 
        if (preg_match('/^total rows/i', trim($row->plaintext))) continue;
        if (preg_match('/^description/i', trim($row->plaintext))) continue;
        $rowcount++;
        if ($rowcount == 1) continue; // skip header row
        $app = array();
        $app['scrape_date'] = date('c');
        $app['AppNo'] = clean_html($row->find('td', 1)->plaintext);
        $app['PlanningDocumentsLink'] = $row->find('td', 1)->find('a', 0)->href;
        $app['Applicant'] = clean_html($row->find('td', 2)->plaintext);
        preg_match('/^(.*?)(?:\s+Easting:\s+(\d+)\s+Northing:\s+(\d+)\s*)?$/s',
                clean_html($row->find('td', 3)->plaintext), $match);
        $app['Location'] = empty($match[1]) ? '' : $match[1];
        $app['LocationEasting'] = empty($match[2]) ? '' : $match[2];
        $app['LocationNorthing'] = empty($match[3]) ? '' : $match[3];
        $app['AppType'] = clean_html($row->find('td', 4)->plaintext);
        $app['ReceivedDate'] = clean_html($row->find('td', 5)->plaintext);
        $app['ValidatedDate'] = clean_html($row->find('td', 6)->plaintext);
        $app['DecisionDue'] = clean_html($row->find('td', 7)->plaintext);
        $app['ExtensionRequested'] = clean_html($row->find('td', 8)->plaintext);
        $app['FinalDecisionDate'] = clean_html($row->find('td', 9)->plaintext);
        $app['Status'] = clean_html($row->find('td', 10)->plaintext);
        $app['FinalDecision'] = clean_html($row->find('td', 11)->plaintext);
        $app['Description'] = clean_html($row->next_sibling()->find('td', 1)->
                find('a', 0)->find('text', 0)->plaintext);
        $app['MapLink'] = $row->next_sibling()->find('a[href]', 0)->href;
        $app['Link'] = "$site_url/PlanningApps.taf?_function=list&File_Number=$app[AppNo]";
        $results[$app['AppNo']] = $app;
    }
    $dom->clear();
    unset($dom);
    unset($html);
    polite_delay();
    return $results;
}
