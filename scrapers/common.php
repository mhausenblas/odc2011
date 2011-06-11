<?php

require_once dirname(__FILE__) . '/simple_html_dom.php';

ini_set('memory_limit', '1000M');

function run() {
    global $argc, $argv;
    if ($argc == 2 && $argv[1] == '--recent') {
        $week_ago = date('Y-m-d', time() - 7 * 24 * 60 * 60);
        $today = date('Y-m-d');
        $apps = get_changed_applications($week_ago, $today);
        write_csv($apps);
    } else if ($argc == 4 && $argv[1] == '--month' && is_numeric($argv[2]) && is_numeric($argv[3])) {
        $year = (int) $argv[2];
        $month = str_pad((int) $argv[3], 2, '0', STR_PAD_LEFT);
        $lastday = date('t', strtotime("$year-$month-01"));
        $apps = get_applications("$year-$month-01", "$year-$month-$lastday");
        write_csv(&$apps);
    } else if ($argc == 3 && $argv[1] == '--year' && is_numeric($argv[2])) {
        $year = (int) $argv[2];
        $apps = get_applications("$year-01-01", "$year-12-31");
        write_csv(&$apps);
    } else {
        global $scraper_name;
        echo "Usage:\n";
        echo "  php $scraper_name.php --recent\n";
        echo "    Scrapes this week's applications\n";
        echo "  php $scraper_name.php --month YYYY MM\n";
        echo "    Scrapes one month\n";
        if (!empty($extra_help)) echo $extra_help;
        die();
    }
}

function polite_delay() {
    global $crawl_delay;
    sleep($crawl_delay);
}

function write_csv(&$apps) {
    if (!$apps) return;
    $fields = array();
    foreach ($apps as $app) {
        $fields += array_keys($app);
    }
    fputcsv(STDOUT, $fields);
    foreach ($apps as $app) {
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

function http_get_post_response($url, $postvars=NULL) {
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

function clean_html($s) {
    return trim(html_entity_decode(str_replace('&nbsp;', ' ', $s), ENT_QUOTES, 'UTF-8'));
}
