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
    var $requestedPaths;
    var $requestedData;

    function __construct() {
        $this->dataDirectory = '/var/www/gplan/data/';

        $this->data = array(
                'GPlan_ApplicationStatus.csv' => 'applicationstatus',
                'GPlan_Authorities.csv' => 'authorities',
                'GPlan_Counties.csv' => 'counties',
                'GPlan_DecisionCodes.csv' => 'decisioncodes',
                'GPlan_LocalAuthorityBounds.csv' => 'localauthoritybounds',
                'GPlan_Metadata.csv' => 'metadata',
                'GPlan_Townlands.csv' => 'townlands'
        );
    }


    function config()
    {

    }


    function init()
    {
        $this->getHTTPRequest();
        $this->getRequestedData();
        $this->showPage();
    }


    function cleanCSV()
    {
        foreach($this->data as $data => $tableName) {
            $badRows = $goodRows = 0;

            if (($handle = fopen($this->dataDirectory.$data, "r")) !== FALSE) {
                $query = 'SHOW COLUMNS FROM '.$tableName;
                $result = mysql_query($query) or die(mysql_error());

                if (mysql_num_rows($result) > 0) {
                    $fieldNames = array();

                    while ($row = mysql_fetch_assoc($result)) {
                        $fieldNames[] = $row['Field'];
                    }

                    $fp = fopen($this->dataDirectory.$data.'.clean.csv', 'w');
                    echo "\nCleaning: ".$this->dataDirectory.$data;

                    while (($dataRow = fgetcsv($handle, 0, ",")) !== FALSE) {

                        if (count($fieldNames) != count($dataRow)) {
                            $badRows++;
                        }

                        else {
                            fwrite($fp, implode(',', $dataRow)."\r\n");
                            $goodRows++;
                        }
                    }
                    fclose($fp);
                }

                fclose($handle);
            }

            echo "\nBad rows:  ".$badRows.' in '.$this->dataDirectory.$data;
            echo "\nGood rows: ".$goodRows.' in '.$this->dataDirectory.$data;
        }

    }

    function loadDataToMySQL($file, $tableName)
    {
        echo "\nLoading $file into table: $tableName";

        $query = "
                  LOAD DATA LOCAL INFILE '$file'
                  INTO TABLE $tableName
                  FIELDS TERMINATED BY ','
                  LINES TERMINATED BY '\r\n'
                 ";
        $result = mysql_query($query) or die(mysql_error());

        if ($result == 1) {
            echo "\n".'LOAD DATA: SUCCESS :)';
        }
        else {
            echo "\n".'LOAD DATA: FAIL :(';
        }
    }


    function getRequestedData()
    {
        $paths = $this->requestedPaths;

//        print_r($paths);

        //TODO:
        // Be more conservative. Don't use * in query.
        // Consider case sensitivity
        // Use ORDER
        // Reconsider use of LIMIT

        $LIMIT = ' LIMIT 1000';

        $item = trim(preg_replace('#[^\w]#i', ' ', $paths[1]));

        switch($paths[0]) {
            // e.g., application-status/incompleted-application
            case 'application-status':
                $query = 'SELECT *
                          FROM metadata
                          WHERE application_status="'.strtoupper($item).'"'
                          .$LIMIT;
                break;

            // e.g., authority/laois-county-council
            case 'authority':
                $query = 'SELECT *
                          FROM authorities LEFT JOIN metadata ON authorities.id = metadata.authority
                          WHERE authorities.name="'.$item.'"'
                          .$LIMIT;
                break;

/*
TODO:
/county/carlow
counties.name


/decision/conditional
decisioncodes.name


/local-authority-bounds/cork-county-council
localauthoritybounds.authority
*/

            default:
                echo '<h1>Home sweet home.</h1>';
                break;
        }

        $result = mysql_query($query) or die(mysql_error());

        if (mysql_num_rows($result) > 0) {
            $this->requestedData = $result;
        }
        else {
            //Do something useful.
        }

    }


    function getHTTPRequest()
    {
        $url = parse_url(substr($_SERVER['REQUEST_URI'], 1));

        $this->requestedPaths = explode('/', $url['path']);
    }


    function showPage()
    {
        $table = $this->getDataTable();

        $page = '';
        $page .= $table;

        echo $page;
    }


    function getDataTable()
    {
        $s = '';
        $result = $this->requestedData;

        //TODO: Move this style out to external CSS file.
        $s .= "\n<style>table { border-collapse:collapse; } td, th { border:1px solid #eee; padding:2px; }</style>";
        $s .= "\n".'<table>';
        $s .= "\n".'<caption>GPlan Data</caption>';
        $s .= "\n".'<tbody>';

        $s .= '<tr>';
        $i = 0;
        while ($i < mysql_num_fields($result)) {
            $meta = mysql_fetch_field($result, $i);
            echo '<th>'.$meta->name.'</th>';
            $i++;
        }
        $s .= '</tr>';

        while($row=mysql_fetch_assoc($result)) {
            $s .= "\n".'<tr>';
            foreach($row as $key=>$value) {
                $s .= "\n".'<td>'.$value.'</td>';
            }
            $s .= "\n".'</tr>';
        }

        $s .= "\n".'</tbody>';
        $s .= "\n".'</table>';

        return $s;
    }

}

?>
