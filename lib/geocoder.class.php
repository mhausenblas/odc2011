<?php

class Geocoder {

    function geocode($address) {
        $result = array(
            'request' => $address,
            'lat' => null,
            'lng' => null,
        );
        $encoded = urlencode($address);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$encoded&region=ie&sensor=false";
        $json = json_decode(file_get_contents($url), true);
        if (!$json) {
            $result['response'] = 'NO JSON';
        } else if ($json['status'] != 'OK') {
            $result['response'] = $json['status'];
        } else {
            $geo = $json['results'][0]['geometry'];
            $result['response'] = $geo['location_type'];
            $result['lat'] = $geo['location']['lat'];
            $result['lng'] = $geo['location']['lng'];
        }
        return $result;
    }
}
