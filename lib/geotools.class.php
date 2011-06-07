<?php

class GeoTools {

    function grid_to_lat_lng($easting, $northing) {
        if (is_null($easting) || is_null($northing)) return null;
        if ($easting == 0 && $northing == 0) return null;
        require_once dirname(__FILE__) . '/gridrefutils/gridrefutils.php';
        static $grutoolbox;
        if (empty($grutoolbox)) {
            $grutoolbox = Grid_Ref_Utils::toolbox();
        }
        return $grutoolbox->grid_to_lat_long($easting, $northing, $grutoolbox->COORDS_GPS_IRISH);
    }
}
