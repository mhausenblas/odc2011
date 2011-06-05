<?php

class Bitly {
    var $username;
    var $api_key;

    function __construct($config) {
        $this->username = $config['bitly_username'];
        $this->api_key = $config['bitly_api_key'];
    }

    function shorten($url) {
        $encoded = urlencode($url);
        $version = '2.0.1';
        $bitly = "http://api.bit.ly/shorten?version=$version&longUrl=$encoded&login=$this->username&apiKey=$this->api_key&format=json&history=1";
        $response = file_get_contents($bitly);
        $json = @json_decode($response,true);
        return $json['results'][$url]['shortUrl'];
    }
}
