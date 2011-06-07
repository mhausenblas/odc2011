<?php

require_once dirname(__FILE__) . '/simple_html_dom.php';

ini_set('memory_limit', '1000M');
$site_url = 'http://www.galway.ie/PlanningSearch';
$crawl_delay = 3;

if ($argc == 2 && $argv[1] == '--recent') {
    $week_ago = date('Y-m-d', time() - 7 * 24 * 60 * 60);
    $today = date('Y-m-d');
    $apps = get_applications($week_ago, $today, 'Received');
    sleep($crawl_delay);
    $apps += get_applications($week_ago, $today, 'Validated');
    sleep($crawl_delay);
    $apps += get_applications($week_ago, $today, 'Decided');
    write_csv($apps);
} else if ($argc == 4 && $argv[1] == '--month' && is_numeric($argv[2]) && is_numeric($argv[3])) {
    $year = $argv[2];
    $month = str_pad($argv[3], 2, '0', STR_PAD_LEFT);
    $lastday = date('t', strtotime("$year-$month-01"));
    $apps = get_applications("$year-$month-01", "$year-$month-$lastday", 'Received');
    write_csv(&$apps);
} else if ($argc == 2 && $argv[1] == '--old') {
    $apps = get_applications("1960-01-01", "1991-12-31", 'Received');
    write_csv($apps); 
} else {
    echo "Usage:\n";
    echo "  scrape_GalwayCo.php --recent\n";
    echo "    Scrapes this week's applications\n";
    echo "  scrape_GalwayCo.php --month YYYY MM\n";
    echo "    Scrapes one month; recommended back to 1992\n";
    echo "  scrape_GalwayCo.php --old\n";
    echo "    Scrapes old applications 1960-1991 (few)\n";
    die();
}

function write_csv(&$apps) {
    $fields = null;
    foreach ($apps as $app) {
        if (!$fields) {
            // If we're at very beginning of file, write out a header row
            $fields = array_keys($app);
            fputcsv(STDOUT, $fields);
        }
        $row = array();
        foreach ($fields as $key) {
            $row[] = @$app[$key];
        }
        fputcsv(STDOUT, $row);
        foreach ($app as $key => $value) {
            if (!in_array($key, $fields)) {
                trigger_error("Application field not in field list: $key", E_USER_WARNING);
            }
        }
    }
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
    $html = http_request($url, $postvars);
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
    return $results;
}

function clean_html($s) {
    return trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'));
}

/**
 * A function to use instead of scraperwiki::scrape for performing POST
 * requests and for dealing with cookies
 *
 * Arguments:
 * $url - a URL to which we will post form variables
 * $postvars - if not null, an array of keys and values containing form variables for POSTing
 */
function http_request($url, $postvars=NULL) {
    static $curl;
    if (empty($curl)) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Planning Explorer (http://planning-apps.opendata.ie)');
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, !empty($postvars));
    if ($postvars) {
        $fields = array();
        foreach($postvars as $key=>$value) {
            $fields[] = $key.'='.$value;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, join($fields, '&'));
    }
    $html = curl_exec($curl);
    return $html;
}
