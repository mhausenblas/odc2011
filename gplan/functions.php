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

}

?>
