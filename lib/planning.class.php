<?php

class Planning {
    var $db;
    var $application_columns = array('app_ref', 'council_id', 'lat', 'lng',
        'applicant1', 'applicant2', 'applicant3', 'received_date', 'decision_date',
        'address1', 'address2', 'address3', 'address4',
        'decision', 'status', 'details', 'url');
    var $council_list;

    function __construct($db) {
        $this->db = $db;
    }

    function get_application($app_ref, $council_id) {
        $row = $this->db->select_row(
            sprintf("SELECT * FROM applications WHERE app_ref='%s' AND council_id=%d", $this->db->escape($app_ref), $council_id));
        if (!$row) return null;
        return $this->clean_application($row);
    }

    function get_latest_applications($bounds) {
        list($lat_lo, $lng_lo, $lat_hi, $lng_hi) = $bounds;
        $query = 'SELECT *
                  FROM applications
                  WHERE (lat >= '.$lat_lo.' and lat <= '.$lat_hi.') and
                        (lng >= '.$lng_lo.' and lng <= '.$lng_hi.')
                  ORDER BY received_date DESC
                  LIMIT 100';
        return $this->get_applications($query);
    }

    function get_all_applications($bounds) {
        list($lat_lo, $lng_lo, $lat_hi, $lng_hi) = $bounds;
        $query = 'SELECT *
                  FROM applications
                  WHERE (lat >= '.$lat_lo.' and lat <= '.$lat_hi.') and
                        (lng >= '.$lng_lo.' and lng <= '.$lng_hi.')
                  LIMIT 250';
        return $this->get_applications($query);
    }

    function get_applications_near($centre) {
        list($lat, $lng) = $centre;
        $query = "SELECT * FROM applications
                  WHERE lat IS NOT NULL AND lng IS NOT NULL
                  ORDER BY ABS($lat-lat)+ABS($lng-lng) ASC
                  LIMIT 50";
        return $this->get_applications($query);
    }

    function get_applications($sql) {
        $apps = $this->db->select_rows($sql);
        $result = array();
        foreach ($apps as $app) {
            $result[] = $this->clean_application($app);
        }
        return $result;
    }

    function get_first_year() {
        return $this->db->select_value('SELECT YEAR(MIN(received_date)) FROM applications');
    }

    function get_council_stats() {
        $query = <<<EOT
            SELECT count(*) AS count,
                   YEAR(received_date) AS year,
                   IF(lat IS NOT NULL AND lng IS NOT NULL, 1, 0) AS coordinates,
                   council_id
            FROM applications
            GROUP BY year(received_date), council_id, lat IS NOT NULL AND lng IS NOT NULL
EOT;
        $yearly_data = $this->db->select_rows($query);
        $query = <<<EOT
            SELECT count(*) AS count,
                   IF(lat IS NOT NULL AND lng IS NOT NULL, 1, 0) AS coordinates,
                   council_id
            FROM applications
            WHERE applications.received_date > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY council_id, lat IS NOT NULL AND lng IS NOT NULL
EOT;
        $recent_data = $this->db->select_rows($query);
        $result = array();
        $first_year = $this->get_first_year();
        foreach ($this->db->select_list('SELECT id FROM councils ORDER BY name') as $council) {
            $result[$council] = array();
            for ($year = $first_year; $year <= date('Y'); $year++) {
                $result[$council][$year] = array(0, 0);
            }
            $result[$council]['recent'] = array(0, 0);
        }
        foreach ($yearly_data as $row) {
            $result[$row['council_id']][$row['year']][$row['coordinates']] = $row['count'];
        }
        foreach ($recent_data as $row) {
            $result[$row['council_id']]['recent'][$row['coordinates']] = $row['count'];
        }
        return $result;
    }

    function get_council_list() {
        if (!$this->council_list) {
            $rows = $this->db->select_rows("SELECT * FROM councils ORDER BY name");
            $result = array();
            foreach ($rows as $row) {
                $result[$row['id']] = array(
                    'short' => $row['short_name'],
                    'name' => $row['name'],
                    'region' => $row['region'],
                    'website' => $row['website_home'],
                    'lowres' => (bool) $row['googlemaps_lowres'],
                    'lookup' => $row['website_lookup'],
                    'system' => $row['website_system']
                );
                if (@$row['lat_lo']) {
                    $result[$row['id']]['lat_lo'] = $row['lat_lo'];
                    $result[$row['id']]['lat_hi'] = $row['lat_hi'];
                    $result[$row['id']]['lng_lo'] = $row['lng_lo'];
                    $result[$row['id']]['lng_hi'] = $row['lng_hi'];
                }
            }
            $this->council_list = $result;
        }
        return $this->council_list;
    }

    function get_latest_application_per_council() {
        $sql = "SELECT applications.*
FROM applications JOIN (
SELECT MAX(applications.received_date) as latest_date, applications.council_id
FROM applications
WHERE lat IS NOT NULL AND lng IS NOT NULL AND lng > -10.7
GROUP BY applications.council_id) as t1
ON applications.council_id = t1.council_id AND applications.received_date = t1.latest_date
WHERE applications.lat IS NOT NULL and lng IS NOT NULL AND lng > -10.7
ORDER BY app_ref DESC";
        $apps = $this->get_applications($sql);
        //We reverse for now so that the most recent app_ref code will be treated as latest for a council.
        $apps = array_reverse($apps);
        $result = array();
        foreach($apps as $app) {
            $result[$app['council_id']] = $app;
        }
        return $result;
    }

    function get_recent_applications($council_shortname = null) {
        $max_age_days = 28;
        $max_entries = 50;
        $start = date('Y-m-d', time() - $max_age_days * 24 * 60 * 60);
        $query = "SELECT * FROM applications WHERE received_date >= '$start'";
        if (!empty($council_shortname)) {
            $query .= " AND council_id=" . $this->get_council_id($council_shortname);
        }
        $query .= " ORDER BY received_date DESC, app_ref DESC LIMIT $max_entries";
        return $this->get_applications($query);
    }

    function get_county_list() {
        return $this->db->select_list("SELECT name FROM counties ORDER BY name");
    }

    function get_council_id($shortname) {
        $query = sprintf("SELECT id from councils WHERE short_name = '%s'", $this->db->escape($shortname));
        return $this->db->select_value($query);
    }

    function get_council_shortname($id) {
        $councils = $this->get_council_list();
        return $councils[$id]['short'];
    }

    function is_council_shortname($shortname) {
        return (bool) $this->get_council_id($shortname);
    }

    function application_exists($app) {
        $query = sprintf("SELECT COUNT(*) from applications WHERE council_id = %d AND app_ref = %s", $app['council_id'], $this->db->quote($app['app_ref']));
        return $this->db->select_value($query) > 0;
    }

    function add_application($app) {
        $query = "INSERT INTO applications SET";
        $clauses = array();
        foreach ($app as $key => $value) {
            if (!in_array($key, $this->application_columns)) {
                throw new DatabaseException("No column '$key' in applications table");
            }
            $clauses[] = " $key = " . $this->db->quote($value);
        }
        $query .= join(', ', $clauses);
        return $this->db->execute($query);
    }

    function update_application($app) {
        $query = "UPDATE applications SET";
        $clauses = array();
        foreach ($this->application_columns as $column) {
            $clauses[] = " $column = " . $this->db->quote(@$app[$column]);
        }
        $query .= join(', ', $clauses);
        $query .= sprintf(" WHERE app_ref='%s' AND council_id=%d", $this->db->escape($app['app_ref']), $app['council_id']);
        return $this->db->execute($query);
    }

    function clean_application($app) {
        $s = $app['details'];
        $s = $this->fix_html($s);
        $s = preg_replace('/[ \t]+/', ' ', $s);
        $s = trim($s);
        if (preg_match('/[a-z]/', $s)) {
            // Capitalize very first character
            $s = strtoupper(substr($s, 0, 1)) . substr($s, 1);
        } else {
            // This is all-caps text -- looks better if lowercased
            $s = $this->remove_all_caps($s);
        }
        $app['details'] = $s;
        $app['address1'] = $this->clean_address($app['address1']);
        $app['address2'] = $this->clean_address($app['address2']);
        $app['address3'] = $this->clean_address($app['address3']);
        $app['address4'] = $this->clean_address($app['address4']);
        $addr = array();
        if ($app['address1']) $addr[] = $app['address1'];
        if ($app['address2']) $addr[] = $app['address2'];
        if ($app['address3']) $addr[] = $app['address3'];
        $app['address'] = join("\n", $addr);
        if (empty($app['lat']) || empty($app['lng']) || $app['lng'] < -10.7) {
            // Bad data where "unknown coordinate" was interpreted as "Irish Grid Reference 0,0",
            // which when translated to WGS84 ends up as lat=-10.9
            $app['lat'] = null;
            $app['lng'] = null;
        } else {
            $app['lat'] = (float) $app['lat'];
            $app['lng'] = (float) $app['lng'];
        }
        if (empty($app['url'])) {
            $lookup = @$this->council_list[$app['council_li']]['lookup'];
            if ($lookup) $app['url'] = $lookup . $app['app_ref'];
        }
        $app['permalink'] = 'http://planning-apps.opendata.ie/'
                . $this->get_council_shortname($app['council_id'])
                . '#' . $app['app_ref'];
        if (empty($app['decision'])) $app['decision'] = 'N';
        return $app;
    }

    function clean_address($s) {
        $s = $this->fix_html($s);
        $s = $this->remove_all_caps($s, true);
        $s = preg_replace('/ *,+/', ',', $s);
        $s = preg_replace('/^\s*(.*?)[;,.\s]*$/', '\1', $s);
        return $s;
    }

    function fix_html($s) {
        $s = str_replace('&nbsp;', ' ', $s);
        $s = str_replace('&amp;', '&', $s);
        return $s;
    }

    function remove_all_caps($s, $to_title_case = false) {
        if (preg_match('/^[^a-z]*[A-Z][^a-z]*$/', $s)) {
            // There are no lowercase chars, but at least one uppercase
            if ($to_title_case) {
                return ucwords(strtolower($s));
            } else {
                return substr($s, 0, 1) . strtolower(substr($s, 1));
            }
        } else {
            return $s;
        }
    }

    function strip_county_name($address) {
        $terms = join("|", $this->get_county_list());
        if (preg_match("/^(.*?)(\s*[;,.])?\s+(Co(unty|\.)?\s+)?($terms)$/is", $address, $match)) {
            return $match[1];
        }
        return $address;
    }

    function _shorten($s, $max_length) {
        return substr($s, 0, $max_length -1) . '…';
    }

    function to_tweet($app, $link = null) {
        $tweet = ' ' . ($link ? $link : $app['permalink']);
        $chars_remaining = 140 - strlen($tweet);

        $address = $this->strip_county_name(str_replace("\n", ', ', @$app['address']));
        $address = preg_replace('/\s+\(.*?\)\s+/', ' ', $address);
        $details = $app['details'];
        $min_details_length = 40;

        if (strlen($address) + 2 + $min_details_length > $chars_remaining) {
            return $this->_shorten($address, $chars_remaining - 2 - $min_details_length) . ': ' . $this->_shorten($details, $min_details_length) . $tweet;
        }
        if (strlen($address) + 2 + strlen($details) > $chars_remaining) {
            return $address . ': ' . $this->_shorten($details, $chars_remaining - strlen($address) - 2) . $tweet;
        }
        return $address . ': ' . $details . $tweet;
    }

    function get_bitly_link($app, $bitly = null) {
        $l = $this->db->select_value(sprintf("SELECT bitly FROM bitly WHERE app_ref='%s' AND council_id=%d", $app['app_ref'], $app['council_id']));
        if ($l) return $l;
        if (!$bitly) return null;
        $l = $bitly->shorten($app['permalink']);
        if (!$l) return null;
        $this->db->execute(sprintf("INSERT INTO bitly SET bitly='%s', app_ref='%s', council_id=%d", $l, $app['app_ref'], $app['council_id']));
        return $l;
    }

    function tweet_application($app, $twitter, $bitly = null, $force = true) {
        $tweet_id = $this->db->select_value(sprintf("SELECT tweet_id FROM tweets WHERE app_ref='%s' AND council_id=%d", $this->db->escape($app['app_ref']), $app['council_id']));
        if ($tweet_id) return false;
        $link = $bitly ? $this->get_bitly_link($app, $bitly) : $app['permalink'];
        $tweet = $this->to_tweet($app, $link);
        $twitter_account = $this->get_council_shortname($app['council_id']) . 'Pln';
        $tweet_id = $twitter->tweet($tweet, $twitter_account);
        $query = sprintf("INSERT INTO tweets (app_ref, council_id, text, tweet_id, time) VALUES ('%s', %d, '%s', '%s', NOW())",
                $this->db->escape($app['app_ref']), $app['council_id'], $this->db->escape($tweet), $this->db->escape($tweet_id));
        $this->db->execute($query);
        return $tweet;
    }

    function geocode_application(&$app) {
        require_once dirname(__FILE__) . '/geocoder.class.php';
        $location = Geocoder::geocode(str_replace("\n", ", ", $app['address']));
        if (!$location) return false;
        $app['lat'] = $location['lat'];
        $app['lng'] = $location['lng'];
        return true;
    }

    function import_apps($apps, $geocode = false) {
        $report = array('added' => 0, 'skipped' => 0, 'updated' => 0, 'geocode_success' => 0, 'geocode_fail' => 0);
        foreach ($apps as $app) {
            if ($this->application_exists($app)) {
                $this->update_application($app);
                $report['updated']++;
                continue;
            }
            if ($geocode) {
                if ($this->geocode_application($app)) {
                    $report['geocode_success']++;
                } else {
                    $report['geocode_fail']++;
                }
            }
            $this->add_application($app);
            $report['added']++;
        }
        return $report;
    }
}
