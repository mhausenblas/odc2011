<?php

class Site {
    var $page_uri;
    var $response;
    var $planning;

    function __construct($page_uri, $response, $planning) {
        $this->page_uri = $page_uri;
        $this->response = $response;
        $this->planning = $planning;
    }

    function exception_handler($ex) {
        if (is_a($ex, 'HTTPException')) {
            $ex->respond($this->response);
            return;
        }
        if (is_a($ex, 'DatabaseException')) {
            $this->response->error(500, array("message" => array($ex->getMessage(), 'Full query: ' . $ex->getQuery())));
        }
        $this->response->error(500, array("page_hidden_message" => $ex->getMessage()));
    }

    function action_home() {
        $options = array(
                "title" => "Planning Applications to Local Councils in Ireland",
                "scripts" => array(
                    "http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js",
                    "/js/jquery.maphilight.min.js",
                    "/js/home-map.js",
                    "http://maps.google.com/maps/api/js?sensor=false",
                    "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"
                ),
                "css" => array(
                    "/css/homepage.css",
                ),
                "councils" => $this->planning->get_council_list(),
                "apps" => $this->planning->get_latest_application_per_council(),
        );
        $this->response->render("page-home", $options);
    }

    function action_stats() {
        $data = $this->planning->get_council_stats();
        foreach ($data as $k1 => $value1) {
            foreach ($data[$k1] as $k2 => $value2) {
                for ($i = 0; $i < 2; $i++) {
                    if ($data[$k1][$k2][$i] == 0) $data[$k1][$k2][$i] = '';
                }
            }
        }
        $options = array(
                "title" => "Statistics on data coverage",
                "data" => $data,
                "first_year" => $this->planning->get_first_year(),
        );
        $this->response->render("stats", $options);
    }

    //Input: latest?bounds=lat_lo,long_lo,lat_hi,long_hi
    //Output: The top 100 planning applications in the bounding box, ordered by application date descending (latest first)
    //Input: all?bounds=lat_lo,long_lo,lat_hi,long_hi
    //Output: Applications within the bounding box. Limit to 250, just to be safe.
    function action_api_area($op, $bounds) {
        $bounds = explode(',', $bounds);
        if (count($bounds) != 4) {
            $this->response->error(400, array('plaintext' => 'Malformed bounds; expected numeric "lat_lo,lng_lo,lat_hi,lng_hi" coordinates'));
        }
        foreach ($bounds as $i => $v) {
            $bounds[$i] = trim($v);
            if (is_numeric($bounds[$i])) continue;
            $this->response->error(400, array('plaintext' => 'Malformed bounds; expected numeric "lat_lo,lng_lo,lat_hi,lng_hi" coordinates'));
        }
        if ($op == 'latest') {
            $applications = $this->planning->get_latest_applications($bounds);
        } else if ($op == 'all') {
            $applications = $this->planning->get_all_applications($bounds);
        } else {
            $this->response->error(400, array('plaintext' => 'Unknown API operation'));
        }
        $this->response->json(array('applications' => $applications));
    }

    //Input: near?center=lat,long
    //Output: The top 50 planning applications near these coordinates, ordered by distance descending (nearest first)
    function action_api_near($centre) {
        $centre = explode(',', $centre);
        if (count($centre) != 2) {
            $this->response->error(400, array('plaintext' => 'Malformed center; expected numeric "lat,long" coordinates'));
        }
        foreach ($centre as $i => $v) {
            $centre[$i] = trim($v);
            if (is_numeric($centre[$i])) continue;
            $this->response->error(400, array('plaintext' => 'Malformed center; expected numeric "lat,long" coordinates'));
        }
        $applications = $this->planning->get_applications_near($centre);
        $this->response->json(array('applications' => $applications));
    }

    function action_council_list() {
        $this->response->json($this->planning->get_council_list());
    }

    function action_council_details($shortname) {
        $this->response->render('streetview', null, false);
    }

    function action_feed($shortname = null) {
        $all = !$shortname;
        $apps = $this->planning->get_recent_applications($shortname);
        $max_date = date('Y-m-d', time() - 7 * 24 * 60 * 60);
        foreach ($apps as $app) {
          if ($app['received_date'] > $max_date) $max_date = $app['received_date'];
        }
        $options = array(
            'title' => 'Planning Applications',
            'url' => $this->response->absolute('feed' . ($all ? '' : "/$shortname")),
            'apps' => $apps,
            'all' => $all,
            'max_date' => $max_date,
            'councils' => $this->planning->get_council_list(),
        );
        $this->response->render('feed', $options, false);
    }
}
