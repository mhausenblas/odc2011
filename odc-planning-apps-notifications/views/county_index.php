<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="utf-8">
<title>Recent planning applications to <?php echo $name; ?> Council</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="./views/css/homepage.css" />
<style type="text/css" media="screen">
.application {  margin-left: 50px;
 margin-top: 50px;
 clear: left; -moz-border-radius: 10px;
-webkit-border-radius: 10px; 
 background-color:#d8deea;
 height: 250px;
}
.summary {  
 margin-top: 200px;
 clear: left; -moz-border-radius: 10px;
-webkit-border-radius: 10px; 
 background-color:#d8deea;
 height: 50px;
color:#3b5999;
padding:10px;
font-size:16px;
text-decoration:none;
}
.application h2 a{
color:#3b5999;
padding:10px;
font-size:16px;
text-decoration:none;
}
.minimap { float: left; margin-right: 0.7em; width: 200px; height: 200px; border: 1px solid #330; }
.minimap.na { color: #885; font-size: 70%; text-align: center; padding: 2.9em 0; height: auto; }
.application p { margin-left: 212px; }
.details a, #content a { text-decoration: underline; }
img.inline { position: relative; top: 2px; }
#map { margin: 10px auto 50px; height: 500px; width:900px}
#feedicon { display:inline; }
#galway-logo { float: left; margin: 0 1em 1em 0; }
#footer { clear: left; border-top: 1px solid #bbb; padding-top: 0.3em; margin-bottom: 1.4em; }
#deri-logo { float: right; }
</style>
<link rel="alternate" type="application/atom+xml" href="http://lab.linkeddata.deri.ie/2010/planning-apps/feed" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="./views/css/ie.css" />
<![endif]-->	
<script type="text/javascript">
var map;
var data = [];

function makemap()
{
var mapOptions = { "zoom": 9,
		   "center": new google.maps.LatLng(<?php echo $lat;?>,<?php echo $long;?>),
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

<body>


<div id="page">  
<div class="title">
<a href="./"><img src="./views/images/under-construction-icon.jpg" /></a><h1>Planning Applications </h1>
<span>To Local Councils In Ireland</span>

</div>


<div class="summary">
  Recent planning applications to <?php echo $name; ?> Council ..Get Notified on <div id="feedicon"><a href="index.php?rt=feed&name=<?php echo $name;?>" ><img src="./views/images/feed-icon-28x28.png" alt="RSS feed" title="Subscribe to RSS feed" /></a></div> or <div id="feedicon"><a href="http://lab.linkeddata.deri.ie/2010/planning-apps/feed"><img src="./views/images/twitter_icon.png" alt="RSS feed" title="Subscribe to RSS feed" /></a></div>
  
</div>
    
    
    <div id="map"></div>
<?php

date_default_timezone_set('Eire');
$max_age_days = 28;
$max_entries = 50;
$start = date('Y-m-d', time() - $max_age_days * 24 * 60 * 60);
$query = "SELECT appref, date, url, address, applicant, details, lat, lng FROM swdata WHERE county='$name' and date >= '$start' ORDER BY date DESC LIMIT $max_entries";
$data_url = 'http://api.scraperwiki.com/api/1.0/datastore/sqlite?format=jsondict&name=irish_planning_applications&query=' . urlencode($query);
$data = json_decode(file_get_contents($data_url));

//print_r($data);

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
   
    <div id="footer">
      <div id="deri-logo"><a href="http://www.deri.ie/"><img src="http://lab.linkeddata.deri.ie/2010/planning-apps/deri-logo-100px.png" alt="DERI Galway" /></a></div>
      <em>This is <strong>not</strong> an official website</em>
      Contact: <a href="http://richard.cyganiak.de/">Richard Cyganiak</a> (<a href="http://twitter.com/cygri">@cygri</a>)
    </div>
</div>
</body>
</html>
