<?php

require_once dirname(__FILE__) . '/common.php';

$scraper_name = 'DublinCity';
$site_url = 'http://www.dublincity.ie/swiftlg/apas/run';
$crawl_delay = 1;

run();

/**
 * Executes a search and returns all modified applications.
 * @param $start_date Inclusive, in YYYY-MM-DD format
 * @param $end_date Inclusive, in YYYY-MM-DD format
 * @return Array of applications
 */
function get_changed_applications($start_date, $end_date) {
    $apps = get_applications($start_date, $end_date);
    $apps += get_applications($start_date, $end_date, 'Determined');
    return $apps;
}

/**
 * Executes a search and returns applications.
 * @param $start_date Inclusive, in YYYY-MM-DD format
 * @param $end_date Inclusive, in YYYY-MM-DD format
 * @param $status One of "Registered" (default), "Determined"
 * @return Array of applications
 */
function get_applications($start_date, $end_date, $status = 'Registered') {
    $app_urls = get_application_urls($start_date, $end_date, ($status == 'Registered') ? 'reg' : 'dec');
    $apps = array();
    foreach ($app_urls as $url) {
        fputs(STDERR, "Fetching application " . (count($apps) + 1) .
                " of " . count($app_urls) . "\n");
        $apps[] = get_application_details($url);
        polite_delay();
    }
    return $apps;
}

function get_application_urls($date1, $date2, $datefield_prefix = 'reg') {
    fputs(STDERR, "Searching for applications between $date1 and $date2 with status '$datefield_prefix'\n");
    global $site_url;
    $date1 = get_formatted_date($date1);
    $date2 = get_formatted_date($date2);
    $url = "$site_url/Wphappcriteria.showApplications?"
        . "{$datefield_prefix}fromdate=$date1&{$datefield_prefix}todate=$date2&DispResultsAs=wphappsresweek1";
    $html = file_get_html($url);
    $next_pages = array();
    foreach ($html->find("a") as $link) {
        if (!preg_match('/^(WPHAPPSEARCHRES.displayResultsURL.*)&BackURL=/', $link->href, $match)) continue;
        $next_pages[] = "$site_url/$match[1]";
    }
    $urls = array();
    $done = 1;
    $total = count($next_pages) + 1;
    while (true) {
        foreach ($html->find("td[class='tablebody'] a") as $link) {
            if (!preg_match('/^(.*?theApnID=.*?)&/', $link->href, $match)) {
                throw new Exception("Bad detail URL $link->href --- found on page $url");
            }
            $urls[] = "$site_url/" . str_replace(' ', '%20', $match[1]);
        }
        if (!$next_pages) break;
        polite_delay();
        $done++;
        fputs(STDERR, "Fetching result page $done of $total\n");
        $html = file_get_html(array_shift($next_pages));
    }
    return $urls;
}

function get_formatted_date($date) {
    $months = array(
        '01' => 'jan', '02' => 'feb', '03' => 'mar', '04' => 'apr', '05' => 'may', '06' => 'jun',
        '07' => 'jul', '08' => 'aug', '09' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dec',
    );
    return substr($date, 8) . '-' . $months[substr($date, 5, 2)] . '-' . substr($date, 0, 4);
}

function get_application_details($url) {
    $html = file_get_html($url . '&theTabNo=2');  // Tab 2 has the decision
    $app = array();
    $app['url'] = $url;
    $app['scrape_date'] = date('c');
    $key = null;
    foreach ($html->find('table[0] td') as $td) {
        $value = clean_html(trim($td->plaintext));
        if ($key) {
            $app[$key] = $value;
            if ($key == 'Status') break;
            $key = null;
        } else if ($b = $td->find('b', 0)) {
            $key = trim($value, ':');
        }
    }
    $app['Proposal'] = trim(str_replace('View full text', '', $app['Proposal']));
    $app['Decision'] = trim(str_replace('Decision:&nbsp;', '', $html->find('.tabContent p', 0)->plaintext));
    return $app;
}
