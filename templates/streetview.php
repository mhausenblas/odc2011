<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Local Planning Explorer Ireland</title>
	<link rel="stylesheet" href="/css/overcast/jquery-ui-1.8.13.custom.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/css/streetview.css" type="text/css" media="screen" />
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script src="/js/jquery-ui-1.8.13.custom.min.js"></script>
	<script src="/js/jimpl_cloud.js"></script>
	<script src="/js/streetview.js"></script>
</head>

<body>
	
	<div id="addresspan">
		<input type="text" size="50" value="" id="target-address" />
	</div>
	
	<div id="archive-widget">
		<!-- the tabs and status information -->
		<div id="viewselpan">
			<span id="show-sv" class="viewsel-tab viewsel-tab-selected">street view</span>  
			<span id="show-map" class="viewsel-tab ">map</span>
			<span id="sv-current-pos"></span>
			<span id="sv-pov"></span>
		</div>

		<!-- the map itself -->
		<div id="map"></div>
	
		<!-- the control panel -->
		<div id="controlpan">
			<h2>Filters</h2>
			<div id="filterpan" class="controlfield"> 
				<div id="yearpan">
					<div class="filterlabel">by year ...</div> 
					<div id="yearrange"></div>
					<input id="yearsel" />
				</div>
				<div class="hseparator"></div>
				<div id="legendpan">
					<div class="filterlabel">by status ...</div> 
					<div id="decision-legend"></div>
					<div id="appstatus-legend"></div>
				</div>
				<div class="cmdbtn"><button id="reset-all-filters">reset all filters ...</button></div>
			</div>
			<div class="vseparator"></div>
			<h2>Info</h2>
			<div id="palist" class="controlfield">
				<div id="palist-content">Select a marker to learn more about planning applications in this area ...</div>
				<div id="palist-cloud"></div>
			</div>
		</div>
	</div>
	<canvas id="dynamarker" width="50" height="65"></canvas>
</body>
</html>
