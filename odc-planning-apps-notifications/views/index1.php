<!DOCTYPE HTML>
<html dir="ltr" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recent planning applications to Galway City Council</title>
    <style type="text/css" media="screen">
        body { color: #330; margin: 1.5em 2em; font-family: Georgia; background-image:url('http://lab.linkeddata.deri.ie/2010/planning-apps/beta.jpeg'); background-repeat: no-repeat; background-position: 53em 58px; }
        body { background-position: -3px -12px; }
        h1 { font-size: 180%; font-weight: normal; margin: 0 0 0.5em; }
        h2 { font-size: 130%; font-weight: normal; margin: 0 0 0.7em 0; padding-top: 1em; }
        a { color: #338; text-decoration: none; }
        a:visited { color: #636; text-decoration: none; }
        a:hover { text-decoration: underline; }
        a:active { color: red; }
        p { margin: 0.7em 0; }
        a img { border: none; }
        h1 small { color: #885; font-size: 50%; }
        #content p { max-width: 50em; }
        .application { clear: left; }
        .minimap { float: left; margin-right: 0.7em; width: 200px; height: 200px; border: 1px solid #330; }
        .minimap.na { color: #885; font-size: 70%; text-align: center; padding: 2.9em 0; height: auto; }
        .application p { margin-left: 212px; }
        .details a, #content a { text-decoration: underline; }
        img.inline { position: relative; top: 2px; }
        #map { height: 500px; }
        #feedicon { float: right; }
        #galway-logo { float: left; margin: 0 1em 1em 0; }
        #footer { clear: left; border-top: 1px solid #bbb; padding-top: 0.3em; margin-bottom: 1.4em; }
        #deri-logo { float: right; }
    </style>
    <link rel="alternate" type="application/atom+xml" href="http://lab.linkeddata.deri.ie/2010/planning-apps/feed" />
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript">
var map;
var data = [];

function makemap()
{
    var mapOptions = { "zoom": 13,
                       "center": new google.maps.LatLng(53.27465, -9.05170),
                       "mapTypeId": google.maps.MapTypeId.HYBRID
                     };
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    for (i=0; i < data.length; i++) {
        addMarker(data[i]);
    }
}

function addMarker(record)
{
    pos = new google.maps.LatLng(record.lat, record.lng);
    marker = new google.maps.Marker({position:pos, map:map});
    (function(j) {
        google.maps.event.addListener(j.marker, "click", function() {
            document.location = '#_' + j.appref;
        });
    })({'marker':marker, 'appref':record.appref});
}

$(function() { makemap(); });

    </script>
</head>

<body id="top">
    <div id="feedicon"><a href="http://lab.linkeddata.deri.ie/2010/planning-apps/feed"><img src="http://lab.linkeddata.deri.ie/2010/planning-apps/feed-icon-28x28.png" alt="RSS feed" title="Subscribe to RSS feed" /></a></div>
    <h1>Recent planning applications to Galway City Council</h1>
    <div id="content">
        <div id="galway-logo"><a href="http://www.galwaycity.ie/"><img src="http://lab.linkeddata.deri.ie/2010/planning-apps/galway-crest-100px.png" alt="Galway" /></a></div>
        <p><strong><em>What is this?</em></strong> This page shows all
            <strong><a href="http://www.galwaycity.ie/AllServices/Planning/">planning applications</a>
            submitted to Galway City Council</strong> within the last four weeks. There is also a
            <strong><a href="http://lab.linkeddata.deri.ie/2010/planning-apps/feed"><img class="inline" src="http://lab.linkeddata.deri.ie/2010/planning-apps/feed-icon-14x14.png" alt=""> live RSS feed</a></strong>
            of the latest submissions. You can subscribe to the feed using any RSS reader,
            such as <a href="http://www.google.com/reader/">Google Reader</a>.</p>
        <p><strong><em>How does it work?</em></strong> These are search results from the City Council's
            <a href="http://gis.galwaycity.ie/ePlan/InternetEnquiry/rpt_QueryBySurForRecLoc.asp">ePlan system</a>,
            extracted with
            <a href="http://scraperwiki.com/scrapers/latest-galway-city-planning-applications/">a scraper</a>
            built using the awesome <a href="http://scraperwiki.com/">ScraperWiki platform</a>.</p>
        <p><strong><em>Who made this?</em></strong> This page was made during the
            <strong><a href="http://www.opendataday.org/">International Open Data Hackathon</a></strong>
            on December 4/5 2010 by a team at <strong>NUI Galway's <a href="http://www.deri.ie/">DERI</a></strong>,
            and further improved at subsequent hack days.
            It is based on a <a href="http://scraperwiki.com/scrapers/ie_planningalerts_corkcity/">scraper
            for Cork City planning applications</a> made earlier by
            <a href="http://handelaar.org/">John Handelaar</a> and adapted for Galway by
            <a href="http://www.gavinsblog.com/">Gavin Sheridan</a>—thanks guys!</p>
        <p><strong><em>Why?</em></strong> We like to know what's going on in our city.
            The Council's planning system doesn't have a news feed and we find it
            too cumbersome for use by the casual citizen. Half of it only works
            for Microsoft Windows users.</p>
    </div>
    <div id="map"></div>
<?php

date_default_timezone_set('Eire');
$max_age_days = 28;
$max_entries = 50;
$start = date('Y-m-d', time() - $max_age_days * 24 * 60 * 60);
$query = "SELECT appref, date, url, address, applicant, details, lat, lng FROM swdata WHERE county='GalwayCity' and date >= '$start' ORDER BY date DESC LIMIT $max_entries";
$data_url = 'http://api.scraperwiki.com/api/1.0/datastore/sqlite?format=jsondict&name=irish_planning_applications&query=' . urlencode($query);
$data = json_decode(file_get_contents($data_url));

function e($s) {
  echo htmlspecialchars($s);
}

function autolink($text) {
  $text = htmlspecialchars($text);
  echo preg_replace('!(FS)/(\d\d?\d?)/?(\d\d)|(\d\d)/(\d\d?\d?)!', '<a href="http://gis.galwaycity.ie/ePlan/InternetEnquiry/rpt_ViewApplicDetails.asp?validFileNum=1&amp;app_num_file=$1$2$3$4$5">$0</a>', $text);
}

foreach ($data as $app) { ?>
    <div class="application" id="_<?php e($app->appref); ?>">
        <h2><a href="<?php e($app->url); ?>"><?php e(str_replace("\n", ', ', $app->address)); ?> (#<?php e($app->appref); ?>)</a></h2>
<?php if (isset($app->lat)) { ?>
        <script>data.push({appref:'<?php e($app->appref); ?>',lat:<?php e($app->lat); ?>,lng:<?php e($app->lng); ?>});</script>
        <div class="minimap">
            <a href="#top">
                <img src="<?php e('http://maps.google.com/maps/api/staticmap?size=200x200&zoom=16&maptype=hybrid&markers=size:mid|' . $app->lat . ',' . $app->lng . '&sensor=false'); ?>" />
            </a>
        </div>
<?php } else { ?>
        <div class="minimap na">
            Not yet geocoded<br />by city council
        </div>
<?php } ?>
        <p>
            <strong>Who is applying:</strong> <?php e($app->applicant); ?><br />
            <strong>Date of application:</strong> <?php e($app->date); ?><br />
            <strong>File number:</strong> <a href="<?php e($app->url); ?>"><?php e($app->appref); ?></a>
        </p>
        <p class="details"><?php echo autolink($app->details); ?></p>
    </div>
<?php } ?>
    <div style="clear:left;">&nbsp;</div>
    <p>Older planning applications are available through the City Council's <a href="http://www.galwaycity.ie/AllServices/Planning/OnlinePlanningEnquirySystem/">Online Planning Enquiry System</a>.</p>
    <div id="footer">
      <div id="deri-logo"><a href="http://www.deri.ie/"><img src="http://lab.linkeddata.deri.ie/2010/planning-apps/deri-logo-100px.png" alt="DERI Galway" /></a></div>
      <em>This is <strong>not</strong> an official website of the <a href="http://www.galwaycity.ie/">Galway City Council</a>!</em>
      Contact: <a href="http://richard.cyganiak.de/">Richard Cyganiak</a> (<a href="http://twitter.com/cygri">@cygri</a>)
    </div>
</body>
</html>
