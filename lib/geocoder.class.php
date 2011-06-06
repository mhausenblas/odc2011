<?php

class Geocoder {

    function geocode($address) {
        $encoded = urlencode($address);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$encoded&region=ie&sensor=false";
        $response = json_decode(file_get_contents($url), true);
        if (!$response || $response['status'] != 'OK') return false;
        $geo = $response['results'][0]['geometry'];
        if ($geo['location_type'] != "RANGE_INTERPOLATED"
            && $geo['location_type'] != "ROOFTOP"
        ) return false;
        return $geo['location'];
    }
}
