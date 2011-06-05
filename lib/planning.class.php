<?php

class Planning {
    var $db;
    var $application_columns = array('app_ref', 'council_id', 'lat', 'lng',
        'applicant1', 'applicant2', 'applicant3', 'received_date', 'decision_date',
        'address1', 'address2', 'address3', 'address4',
        'decision', 'status', 'details', 'url', 'tweet_id');

    function __construct($db) {
        $this->db = $db;
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
        return $this->db->select_rows($sql);
    }

    function get_first_year() {
        return $this->db->select_value('SELECT YEAR(MIN(received_date)) FROM applications');
    }

    function get_council_stats() {
        $query = <<<EOT
            SELECT count(*) AS count,
                   YEAR(received_date) AS year,
                   IF(lat IS NOT NULL AND lng IS NOT NULL, 1, 0) AS coordinates,
                   council_id, councils.name
            FROM applications, councils
            WHERE applications.council_id = councils.id
            GROUP BY year(received_date), council_id, lat IS NOT NULL AND lng IS NOT NULL
EOT;
        $yearly_data = $this->db->select_rows($query);
        $query = <<<EOT
            SELECT count(*) AS count,
                   IF(lat IS NOT NULL AND lng IS NOT NULL, 1, 0) AS coordinates,
                   council_id, councils.name
            FROM applications, councils
            WHERE applications.council_id = councils.id
              AND applications.received_date > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY council_id, lat IS NOT NULL AND lng IS NOT NULL
EOT;
        $recent_data = $this->db->select_rows($query);
        $result = array();
        $first_year = $this->get_first_year();
        foreach ($this->db->select_list('SELECT name FROM councils ORDER BY name') as $council) {
            $result[$council] = array();
            for ($year = $first_year; $year <= date('Y'); $year++) {
                $result[$council][$year] = array(0, 0);
            }
            $result[$council]['recent'] = array(0, 0);
        }
        foreach ($yearly_data as $row) {
            $result[$row['name']][$row['year']][$row['coordinates']] = $row['count'];
        }
        foreach ($recent_data as $row) {
            $result[$row['name']]['recent'][$row['coordinates']] = $row['count'];
        }
        return $result;
    }

    function get_council_list() {
        $rows = $this->db->select_rows("SELECT id, short_name, name FROM councils");
        $result = array();
        foreach ($rows as $row) {
            $result[$row['id']] = array('short' => $row['short_name'], 'name' => $row['name']);
        }
        return $result;
    }

    function get_council_id($shortname) {
        $query = sprintf("SELECT id from councils WHERE short_name = '%s'", $this->db->escape($shortname));
        return $this->db->select_value($query);
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
}
