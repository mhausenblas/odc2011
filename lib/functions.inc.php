<?php
/**
 * Basic CSV cleaning/checking and loading into database
 *
 * PHP version 5
 *
 * @category  Base
 * @package   GP
 * @author    Sarven Capadisli <sarven.capadisli@deri.org>
 * @copyright 2010 Digital Enterprise Research Institute
 * @license   
 * @link      http://deri.org/
 */


class GP {
    var $dataDirectory;
    var $data;
    var $requestedPath;
    var $requestedQuery;
    var $requestedData;

    function __construct() {
        //Using arrays for query paramaters for extensibility
        $this->apiElements = array(
            'latest' => array('bounds'),
            'all' => array('bounds'),
            'near' => array('center') //How about we use en-uk's "centre"?
        );
    }

    function sendAPIResponse()
    {
        $this->getHTTPRequest();
        $this->getRequestedData();
        $this->returnJSON();
    }


    function getRequestedData()
    {
        $paths   = $this->requestedPath;
        $queries = $this->requestedQuery;

        $apiElement = null;

        //See if our path is in allowed API functions. Use the first match.
        foreach($paths as $path) {
            if (array_key_exists($path, $this->apiElements)) {
                $apiElement = $path;
                break;
            }
        }

        if (is_null($apiElement)) {
            $this->returnError('missing');
        }

        $apiElementKeyValue = null;

        //Make sure that the query param is allowed
        foreach($queries as $query => $kv) {
            if (in_array($query, $this->apiElements[$apiElement])) {
                $apiElementKeyValue[$query] = $kv;
            }
        }

        if (is_null($apiElementKeyValue)) {
            $this->returnError('missing');
        }

        $query = '';
        $values = explode(',', implode(',', array_values($apiElementKeyValue)));

        switch($apiElement) {
            //Get latest applications in area
            //Input: latest?bounds=lat_lo,long_lo,lat_hi,long_hi
            //Output: The top 100 planning applications in the bounding box, ordered by application date descending (latest first)
            case 'latest':
                if (count($values) == 4) {
                    //Basic clean up
                    $bounds = array();
                    foreach ($values as $v) {
                        $v = trim($v);

                        $bounds[] = is_numeric($v) ? $v : null;
                    }

                    if (in_array(null, $bounds)) {
                        $this->returnError('malformed');
                    }

                    $lat_lo = mysql_real_escape_string($bounds[0]);
                    $lng_lo = mysql_real_escape_string($bounds[1]);
                    $lat_hi = mysql_real_escape_string($bounds[2]);
                    $lng_hi = mysql_real_escape_string($bounds[3]);

                    $query = 'SELECT *
                              FROM applications
                              WHERE (lat >= '.$lat_lo.' and lat <= '.$lat_hi.') and
                                    (lng >= '.$lng_lo.' and lng <= '.$lng_hi.')
                              ORDER BY received_date DESC
                              LIMIT 100';
                }
                else {
                    $this->returnError('missing');
                }
                break;

            //Get all applications in area (Note: The area is supposed to be small and just contain a few applications)
            //Input: all?bounds=lat_lo,long_lo,lat_hi,long_hi
            //Output: Applications within the bounding box. Limit to 250, just to be safe.
            case 'all':
                if (count($values) == 4) {
                    //Basic clean up
                    $bounds = array();
                    foreach ($values as $v) {
                        $v = trim($v);

                        $bounds[] = is_numeric($v) ? $v : null;
                    }

                    if (in_array(null, $bounds)) {
                        $this->returnError('malformed');
                    }

                    $lat_lo = mysql_real_escape_string($bounds[0]);
                    $lng_lo = mysql_real_escape_string($bounds[1]);
                    $lat_hi = mysql_real_escape_string($bounds[2]);
                    $lng_hi = mysql_real_escape_string($bounds[3]);

                    $query = 'SELECT *
                              FROM applications
                              WHERE (lat >= '.$lat_lo.' and lat <= '.$lat_hi.') and
                                    (lng >= '.$lng_lo.' and lng <= '.$lng_hi.')
                              LIMIT 250';
                }
                else {
                    $this->returnError('missing');
                }
                break;

            //Get all applications near a point
            //Input: near?center=lat,long
            //Output: The top 50 planning applications near these coordinates, ordered by distance descending (nearest first)
            case 'near':
                if (count($values) == 2) {
                    //Basic clean up
                    $center = array();
                    foreach ($values as $v) {
                        $v = trim($v);

                        $center[] = is_numeric($v) ? $v : null;
                    }

                    if (in_array(null, $center)) {
                        $this->returnError('malformed');
                    }

                    $lat = mysql_real_escape_string($center[0]);
                    $lng = mysql_real_escape_string($center[1]);

                    $x = '('.$lat.'-lat)';
                    $y = '('.$lng.'-lng)';

                    $query = 'SELECT *, sqrt('.$x.'*'.$x.' + '.$y.'*'.$y.') AS distance
                              FROM applications
                              WHERE lat IS NOT NULL or lng IS NOT NULL
                              ORDER BY distance ASC
                              LIMIT 50';
                }
                else {
                    $this->returnError('missing');
                }
                break;

            default:
                $this->returnError('missing');
                break;
        }

        $this->requestedData = mysql_query($query) or die(mysql_error());
    }


    function getHTTPRequest()
    {
        $url = parse_url(substr($_SERVER['REQUEST_URI'], 1));

        $this->requestedPath = explode('/', $url['path']);

        //Make sure that we at least have a query
        if (!isset($url['query'])) {
            $this->returnError('malformed');
        }

        $queries = explode('&', $url['query']);

        $this->requestedQuery = array();

        foreach ($queries as $query) {
            $key = $value = '';
            list($key, $value) = explode("=", $query) + Array(1 => null, null);

            if (!isset($value) || empty($value)) {
                $this->returnError('malformed');
            }
            $this->requestedQuery[$key] = $value;
        }

        //Make sure that we have a proper query
        if (count($this->requestedQuery) < 1) {
            $this->returnError('malformed');
        }
    }


    function returnError($errorType)
    {
        header('HTTP/1.1 400 Bad Request');
        header('Content-type: text/plain; charset=utf-8');

        $s = '';

        switch($errorType) {
            case 'missing': default:
                $s .= 'Missing..';
                break;
            case 'malformed':
                $s .= 'Malformed..';
                break;
        }

        echo $s;

        exit;
    }


    function returnJSON()
    {
        header('Content-type: application/json; charset=utf-8');

        $result = $this->requestedData;
        $s = '';

        if (mysql_num_rows($result) > 0) {
            while($row = mysql_fetch_assoc($result)) {
                $applications[] = $row;
            }
            $s = '{"applications": '.json_encode($applications).'}';
        }
        else {
            $s = '{"applications":[]}';
        }

        echo $s;
    }
}

?>
