<?php

require_once(dirname(__FILE__) . '/twitter-api/tmhOAuth.php');

class Twitter {
    var $accounts;

    function __construct($accounts) {
        $this->accounts = $accounts;
    }

    function tweet($tweet, $account) {
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key'    => $this->accounts[$account]['consumer_key'],
            'consumer_secret' => $this->accounts[$account]['consumer_secret'],
            'user_token'      => $this->accounts[$account]['user_token'],
            'user_secret'     => $this->accounts[$account]['user_secret'],
        ));
        $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
            'status' => $tweet
        ));
        if ($code != 200) {
            throw new Exception("Twitter API exception: $code");
        }
        $tweeted = $tmhOAuth->response['response'];
        if (is_object($tweeted)) {
            $tweeted = get_object_vars($tweeted);
        }
        if (!is_array($tweeted)) {
            $tweeted = json_decode($tweeted, true);
        }
        if (is_array($tweeted)) {
            return $tweeted["id_str"];
        }
        throw new Exception("Twitter API exception: no array or object");
    }
}
