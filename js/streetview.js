/////////////////////////////////////////////////////////////////////////////// 
//  PA map widget view globals
//

// the following are the PA map widget's supported modes:
var OVERVIEW_MODE = 0; // showing the latest PAs in a certain area, no control panel visible
var ARCHIVE_MODE = 1; // showing all PAs in a certain area, control panel visible
var SV_DETAIL_MODE = 2; // showing nearest PAs, control panel visible


// globally configure the PA map widget here:
var pam = ARCHIVE_MODE; // the selected mode
var mapLatLow = 53.1; // for OVERVIEW_MODE and ARCHIVE_MODE
var mapLngLow = -8.2; // for OVERVIEW_MODE and ARCHIVE_MODE
var mapLatHi = 53.2; // for OVERVIEW_MODE and ARCHIVE_MODE
var mapLngHi = -8; // for OVERVIEW_MODE and ARCHIVE_MODE
var mapCenterLat = 53.15; // for SV_DETAIL_MODE
var mapCenterLng = -8.0392; // for SV_DETAIL_MODE
var mapInitialZoomFactor = 12; // the default zoom factor ( 7 ~ all Ireland, 10 - 12 ~ county-level, > 12 ~ village-level)
var mapWidth = 0.6; // the preferred width of the map
var mapHeight = 0.8; // the preferred height of the map
var mapDefaultIsStreetView = false; // start in street view mode or not
var filterMinYear = 1970; // min. value for the filter-by-year
var filterMaxYear = 2010; // max. value for the filter-by-year

// configure the PA API here:
var PA_API_BASE_URI = "http://planning-apps.opendata.ie/";

//////////////////////////////////
// internal globals - don't touch:
var PA_STATE_SELECTION_INACTIVE = "inactive";
var PA_YEAR_SELECTION_INACTIVE = -1;

var mapCenter = new google.maps.LatLng(mapCenterLat, mapCenterLng);
var map; // the Google map (both for overview and street view)
var councils; // the council look-up table
var currentMarkers = new Array(); // list of active markers in the viewport
var pastateSelection = PA_STATE_SELECTION_INACTIVE; // the filter-by-status 
var currentMinYear = PA_YEAR_SELECTION_INACTIVE; // filter-by-year
var currentMaxYear = PA_YEAR_SELECTION_INACTIVE; // filter-by-year

// the planning application (PA) data a list of PA objects, 
// filled dynamically via the JSON API
var paData = new Array();

// GPlan application status - based on GPlan_ApplicationStatus.txt
var APPLICATION_STATUS = {
 "0":"INCOMPLETED APPLICATION",
 "1":"NEW APPLICATION",
 "2":"FURTHER INFORMATION",
 "3":"DECISION MADE",
 "4":"LEAVE TO APPEAL",
 "5":"APPEALED",
 "8":"WITHDRAWN",
 "9":"APPLICATION FINALISED",
 "10":"PRE-VALIDATION",
 "11":"DEEMED WITHDRAWN",
 "12":"APPEALED FINANCIAL"
};

// GPlan decision code - based on GPlan_DecisionCodes.txt
var DECISION_CODE = {
	"N":"UNKNOWN",
	"C":"CONDITIONAL",
	"U":"UNCONDITIONAL",
	"R":"REFUSED"
};

// color-coded marker corresponding to the GPlan status above
var MARKER_COLOR = {
	 "0":"#303030",
	 "1":"#006600",
	 "2":"#336633",
	 "3":"#000066",
	 "4":"#333060",
	 "5":"#363666",
	 "8":"#600",
	 "9":"#661122",
	 "10":"#603",
	 "11":"#663300",
	 "12":"#633"
};

// mapping of PA decision+status to color
var PA_STATE = {
	"incomplete or withdrawn" : "#9f9f9f",
	"refused": "#660000",
	"conditional" : "#333060",
	"unconditional" : "#060",
	"no data or decision" : "#000"
};

/////////////////////////////////////////////////////////////////////////////// 
//  PA map widget view main event loop
//

$(function() { 
		
	if(pam == OVERVIEW_MODE) {
		getLatestPAsIn(mapLatLow, mapLngLow, mapLatHi, mapLngHi, function(data, textStatus){
			loadCouncils(function() {
				fillPAData(data);
				initUI(false);
			});
		});
	}
	
	if(pam == ARCHIVE_MODE) {
		getAllPAsIn(mapLatLow, mapLngLow, mapLatHi, mapLngHi, function(data, textStatus){
			loadCouncils(function() {
				fillPAData(data);
				initUI(true);
			});
		});
	}
	
	if(pam == SV_DETAIL_MODE) {
		getNearestPAs(mapCenterLat, mapCenterLng, function(data, textStatus){
			loadCouncils(function() {
				fillPAData(data);
				mapDefaultIsStreetView = true;
				initUI(true);
			});
		});
	}

	// whenever window is resized, adapt size of widgets
	$(window).resize(function() { 
		fitPAAWidgets(); 
	});
	
	// tab switches (map view vs. street view)
	$("#show-map").click(function() {
		showMap();
	});	
	$("#show-sv").click(function() {
		showSV();
	});
	
	// geo-code address - look up address as soon as ENTER is hit
	$("#target-address").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		var address = "";
		if (code == 13) {
			address = $("#target-address").val();
			gotoAddress(address, {
				showsv: mapDefaultIsStreetView,
				reportresult: true 
			});
		}
	});
	
	$("#reset-all-filters").click(function() {
		resetAllFilters();
	});
	
	// filter PAs by status
	$("#appstatus-legend div").live('click', function() {
		var targetState = $(this).text();

		if(pastateSelection == targetState) { // deactivation request for filter-by-state
			pastateSelection = PA_STATE_SELECTION_INACTIVE;
			$(this).css("border", "0");
			if((currentMinYear != PA_YEAR_SELECTION_INACTIVE) || (currentMaxYear != PA_YEAR_SELECTION_INACTIVE)) { // filter-by-year is active
				filterPAByYear(currentMinYear, currentMaxYear);
			}
			else {
				showAllMarkers();
			}
		}
		else { // activation or change request for filter-by-state
			pastateSelection = targetState;
			filterPAByState(targetState);
			$("#appstatus-legend div").each(function(index) {
				$(this).css("border", "0");
			});
			$(this).css("border", "3px solid #fff");	
		}
	});
	
	// highlighting marker of selected PA
	$(".singlepa").live('mouseenter', function() {
		var thispa = $(this).attr('id');
		for (var i = 0; i < currentMarkers.length; i++){
			var paid = 'pa_' + currentMarkers[i].id;
			if(thispa == paid){
				currentMarkers[i].marker.setAnimation(google.maps.Animation.BOUNCE);
			}
			else {
				currentMarkers[i].marker.setAnimation(null);
			}
		}
	});
	
	// reset marker bounce
	$("#palist").mouseout(function() {
		for (var i = 0; i < currentMarkers.length; i++){
			currentMarkers[i].marker.setAnimation(null);
		}
	});
	
});


/////////////////////////////////////////////////////////////////////////////// 
//  PA map widget view library
//

function initUI(showControlPanel){
	makemap(); // create the Google Map
	if(showControlPanel) {
		makelegend(); // create the legend
		yearsel(filterMinYear, filterMaxYear); // create the year selection slider
	}
	else {
		$("#controlpan").html("");
		$("#viewselpan").html("");
		mapWidth = 0.95;
	}
	fitPAAWidgets(); // initial sizing of the widgets (map, year selection slider, etc.)
	if(mapDefaultIsStreetView) showSV(); // show SV initially
}

function fitPAAWidgets(){
	$("#map").width($(window).width()*mapWidth);
	$("#controlpan").width($(window).width()*(1 - mapWidth - 0.05));
	$("#map").height($(window).height()*mapHeight);
}

function makemap() {
	var mapOptions = { 
		zoom: mapInitialZoomFactor,
		center: mapCenter,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		overviewMapControl: true,
		overviewMapControlOptions: {
			position: google.maps.ControlPosition.BOTTOM_RIGHT,
			opened: true
		}
	};

	// create the map with options from above
	map = new google.maps.Map(document.getElementById("map"), mapOptions);
	
	// handle SV visibility
	google.maps.event.addListener(map.getStreetView(), 'visible_changed', function() {	
		if(map.getStreetView().getVisible()){ // the SV is currently visible make sure the tabs are respectively selected
			$("#show-sv").addClass('viewsel-tab-selected');
			$("#show-map").removeClass('viewsel-tab-selected');
		}
		else {
			$("#show-sv").removeClass('viewsel-tab-selected');
			$("#show-map").addClass('viewsel-tab-selected');
		}
	});
	
	// handle current position in SV
	google.maps.event.addListener(map.getStreetView(), 'position_changed', function() {
		var lat = Math.floor(map.getStreetView().getPosition().lat()*10000+1)/10000;
		var lng = Math.floor(map.getStreetView().getPosition().lng()*10000+1)/10000;
		$("#sv-current-pos").html("latitude: " + lat + " <span style ='color: #cfcfcf'>|</span> longitude: " + lng);
	});


	// handle point of view in SV
	google.maps.event.addListener(map.getStreetView(), 'pov_changed', function() {
		var heading = Math.floor(map.getStreetView().getPov().heading);
		var pitch = Math.floor(map.getStreetView().getPov().pitch*100+1)/100;
		$("#sv-pov").html("heading: " +  heading + " <span style ='color: #cfcfcf'>|</span> pitch: " + pitch);
	});


	// add the markers based on the data we get from the PA service
	for (i=0; i < paData.length; i++) {
		addMarker(paData[i]);
	}	
}

function makelegend(){
	$.each(PA_STATE, function(index, val){
		$('#appstatus-legend').append("<div style='padding: 2px; color: #f0f0f0; background:" + PA_STATE[index] +";'>" + index + "</div>");	
	});	
}

function showSV() {
	var svmap = map.getStreetView();
	var panoOptions = {
		position: map.getCenter(),
			pov: {
			heading: -10,
			pitch: 0,
			zoom: 1
		},
		addressControlOptions: {
			position: google.maps.ControlPosition.BOTTOM
		},
		linksControl: true,
		panControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
		},
		enableCloseButton: false
	};

	svmap.setOptions(panoOptions);
	svmap.setVisible(true);
	
	$("#palist-content").html("");
	$("#palist-cloud").html("");
	$("#show-map").removeClass('viewsel-tab-selected');
	$("#show-sv").addClass('viewsel-tab-selected');
	
	// TODO: implement TOP-k based on distance
	// for (var i = 0; i < currentMarkers.length; i++){
	// 	$("#palist-content").append("<div class='singlepa' id='pa_" + currentMarkers[i].id  +"'>" + currentMarkers[i].year +": <a href='#" + currentMarkers[i].id + "'>" + currentMarkers[i].desc + "</a></div>");
	// }	
}

function showMap() {
	map.getStreetView().setVisible(false);
	$("#show-sv").removeClass('viewsel-tab-selected');
	$("#show-map").addClass('viewsel-tab-selected');
	$("#palist-content").html("");
	$("#sv-current-pos").html("");
	$("#sv-pov").html("");
	
	// tag cloud based on visible PAs:
	$("#palist-cloud").html("");
	$.each(currentMarkers, function(index, val){ // add the words from the visible PA descriptions to the cloud div
		$.each(currentMarkers[index].desc.split(' '), function(windex, word){
			word = word.replace(",", "");
			// TODO: find a better filtering method for blacklisted words ...
			if(word.trim() != "" || word.trim() != "an" || word.trim() != "to"  || word.trim() != "and"  || word.trim() != "of" ) {
				if (index < currentMarkers.length - 1) $("#palist-cloud").append(word + " , ");
				else $("#palist-cloud").append(word.toLowerCase());
			}
		});
	});
	$("#palist-cloud").tagCloud({
		separator: ',',
		randomize: true,
		sizefactor: 3
	});
	
}

function addMarker(record) {
	(function(r) {
		var pos = getDisplayPosition(r.lat, r.lng);
		var year = (new Date(Date.parse(r.appdate))).getFullYear();
		var yMarkerImage = new google.maps.MarkerImage(drawMarker(year, r.decision, r.appstatus));
		var marker = new google.maps.Marker({
			position: pos,
			map: map,
			icon: yMarkerImage,
			title: APPLICATION_STATUS[r.appstatus]
		});
		
		// remember the marker
		currentMarkers.push({ id:r.appref, d:r.decision, s:r.appstatus, year:year, desc:r.appdesc, marker:marker });
		
		// for non-SV mode enable detail view
		google.maps.event.addListener(marker, "click", function() {
			if(!map.getStreetView().getVisible()){ // the SV is not visible
				$("#palist-content").html(renderPADetail(r));
			}
		});
	})({
		'council': record.council, // council ID
		'appref': record.appref, // the PA reference
		'lat': record.lat, // the PA's latitude
		'lng': record.lng, // the PA's longitude
		'appdate': record.appdate, // when the PA was submitted
		'address' : record.address, // for which address the PA was submitted
		'decision': record.decision, // the decision on the PA
		'appstatus': record.appstatus, // the current status of the PA
		'appdesc': record.appdesc, // the short description of the PA
		'url': record.url // the URL of the original PA
	});	
}

function renderPADetail(r){
	var year = (new Date(Date.parse(r.appdate))).getFullYear();
	var mimg = "<img src='" + drawMarker(year, r.decision, r.appstatus) + "' alt='application status' style='vertical-align:text-top; float: right;' />";
	var b = "<div class='singlepa' id='pa_" + r.appref  +"'>";
    b += "<h3><a href='" + PA_API_BASE_URI + r.council + "#" + r.appref + "'>" + r.address + "</a></h3>";
    b += mimg;
 	b +=  "<p>Date of application: " + r.appdate + "</p>";
 	b +=  "<p>Details: " + r.appdesc.toLowerCase() + "</p>";
	if(r.url) b +=  "<p>File: <a href='" + r.url + "'>"+ r.appref + "</a></p>";
 	b +=  "</div>";
	return b;

}


// render marker dynamically, based on year and combination of decision and app status
function drawMarker(year, decision, status){
	// see http://www.html5canvastutorials.com and http://diveintohtml5.org/canvas.html for more tricks ...
	year = year.toString().substring(2); // only take last two digits into account
	var canvas = document.getElementById("dynamarker");
	var context = canvas.getContext('2d');
	context.font = "9pt Arial";
	context.lineWidth = 1;
	context.strokeStyle = "#fff";

	// create the semi-circle
	context.beginPath();
	context.arc(25, 25, 18, 0, Math.PI, true);
	context.closePath();
	context.fillStyle = colorCodePA(decision, status);
	context.fill();
    context.stroke();

	// create the pin
	context.beginPath();
	context.moveTo(25, 64);
	context.lineTo(25, 26);
	context.fillStyle = '#fff';
	context.fill();
    context.stroke();

	// display year on the marker
	context.fillStyle = "WHITE";
	context.fillText(year, 18, 21);

	return canvas.toDataURL("image/png");
}

function colorCodePA(decision, status){
	if(decision == 'N' && inArray(status,['0', '2', '8', '11'])) { // incomplete or withdrawn
		return '#9f9f9f';
	}
	else {
		if(decision == 'R') { // refused
			return '#660000';
		}
		else {
			if(decision == 'C') { // conditional
				return '#333060';
			}
			else {
				if(decision == 'U') { // unconditional
					return '#060';
				}
				else { // no data or decision
					return '#000';
				}
			}
		}
	}
}

function inArray(element, alist) {
	var aa = {};
	for(var i=0;i<alist.length;i++) aa[alist[i]]='';
	return element in aa;
}

// makes sure that no two markers are on the exact same location (by scanning marker list and randomly shuffling around)
function getDisplayPosition(lat, lng){
	var pos = new google.maps.LatLng(lat, lng);
	//console.log("CURRENT position:" + pos);
	if(currentMarkers.length > 0) {
		for (var i = 0; i < currentMarkers.length; i++){
			var takenpos =  currentMarkers[i].marker.getPosition();
			if(pos.equals(takenpos)){//(lat == takenlat) && (lng == takenlng)) { // compare each component manually - I don't trust the rounding stuff from LatLng
				lat += 0.00001 * Math.random();
				lng += 0.00001 * Math.random();
				//console.log("MATCH! using new position with lat=" + lat + " lng=" + lng);
				return new google.maps.LatLng(lat, lng);
			}
		}
	}
	return new google.maps.LatLng(lat, lng);
}

function yearsel(starty, endy){
	$("#yearrange").slider({
		range: true,
		min: starty,
		max: endy,
		values: [ starty, endy ],
		slide: function(event, ui) {
			$("#yearsel").val(ui.values[0] + " - " + ui.values[1]);
			filterPAByYear(ui.values[0], ui.values[1]);
		}
	});
	$("#yearsel").val($("#yearrange").slider("values", 0) + " - " + $("#yearrange").slider("values", 1));
	$("#yearsel").attr('readonly', true);
	
}

function showAllMarkers(){
	for(i in currentMarkers){
		currentMarkers[i].marker.setVisible(true);
	}
}

function hideAllMarkers(){
	for(i in currentMarkers){
		currentMarkers[i].marker.setVisible(false);
	}
}

function isMarkerInSelectedState(marker, pastate){
	if(pastate == 'incomplete or withdrawn') {
		if((marker.d == 'N') && inArray(marker.s,['0', '2', '8', '11'])) {
			return true;
		}
		else return false;
	}
	else {
		if(pastate == 'refused'){
			if(marker.d == 'R') {
				return true;
			}
			else return false;
		}
		else {
			if(pastate == 'conditional'){
				if(marker.d == 'C') {
					return true;
				}
				else return false;
			}
			else{
				if(pastate == 'unconditional'){
					if(marker.d == 'U') {
						return true;
					}
					else return false;
				}
			}
		}
	}
	
}

function filterPAByYear(miny, maxy){
	currentMinYear = miny;
	currentMaxYear = maxy;
	
	if(pastateSelection == PA_STATE_SELECTION_INACTIVE) { 
		showAllMarkers();
		for(i in currentMarkers){
			if((currentMarkers[i].year <= miny) || (currentMarkers[i].year >= maxy)) {
				currentMarkers[i].marker.setVisible(false);
			}
			else {
				currentMarkers[i].marker.setVisible(true);
			}
		}
	}
	else { // filter-by-state is active
 		for(i in currentMarkers){
			if((currentMarkers[i].year <= miny) || (currentMarkers[i].year >= maxy)) {
				currentMarkers[i].marker.setVisible(false);
			}
			else {
				if(isMarkerInSelectedState(currentMarkers[i], pastateSelection)) currentMarkers[i].marker.setVisible(true);
			}
		}
	}
}

function filterPAByState(pastate){
	hideAllMarkers();
	pastateSelection = pastate;

	for(i in currentMarkers){
		if(isMarkerInSelectedState(currentMarkers[i], pastateSelection)) {
			currentMarkers[i].marker.setVisible(true);
		}
	}
	
	if((currentMinYear != PA_YEAR_SELECTION_INACTIVE) || (currentMaxYear != PA_YEAR_SELECTION_INACTIVE)) { // filter-by-year is active
		filterPAByYear(currentMinYear, currentMaxYear);
	}
}

function resetAllFilters(){
	currentMinYear = PA_YEAR_SELECTION_INACTIVE;
	currentMaxYear = PA_YEAR_SELECTION_INACTIVE;
	pastateSelection = PA_STATE_SELECTION_INACTIVE;
	showAllMarkers();
	$("#appstatus-legend div").each(function(index) {
		$(this).css("border", "0");
	});
	$("#yearrange").slider("values", 0, filterMinYear);
	$("#yearrange").slider("values", 1, filterMaxYear);
	$("#yearsel").val($("#yearrange").slider("values", 0) + " - " + $("#yearrange").slider("values", 1));
}


/////////////////////////////////////////////////////////////////////////////// 
//  PA map widget data API calls
//

function fillPAData(data){
	if(data.applications) {  
		paData = []; // make sure the PA list is empty
		for(pa in data.applications){
			var council = lookupCouncil(data.applications[pa].council_id); // council short name from ID
			var appref = data.applications[pa].app_ref; // the PA reference
			var lat = data.applications[pa].lat; // the PA's latitude
			var lng = data.applications[pa].lng; // the PA's longitude
			var appdate = data.applications[pa].received_date; // when the PA was submitted
			var address = data.applications[pa].address1; // for which address the PA was submitted
			var decision = data.applications[pa].decision;  // the decision on the PA
			var status = data.applications[pa].status; // the current status of the PA
	 		var details = data.applications[pa].details; // the short description of the PA
			var url = data.applications[pa].url; // the URL of the original PA
			//console.log("from council " + council + " got a PA at (" + lat + "," + lng + ") with details: " + details);
			paData.push({council:council,appref:appref,lat:lat,lng:lng,appdate:appdate,address:address,decision:decision,appstatus:status,appdesc:details,url:url});
		}
	}
}

function loadCouncils(callback) {
	$.getJSON(PA_API_BASE_URI + "councils", function(data) {
		councils = data;
		callback();
	});
}

function lookupCouncil(councilID) {
	return councils[councilID].short;
}

function getNearestPAs(lat, lng, callback) {
	$.getJSON(PA_API_BASE_URI + "near?center=" + lat + "," + lng, callback);
}

function getAllPAsIn(latLow, lngLow, latHi, lngHi, callback) {
	$.getJSON(PA_API_BASE_URI + "all?bounds=" + latLow + "," + lngLow + "," + latHi + "," + lngHi, callback);
}

function getLatestPAsIn(latLow, lngLow, latHi, lngHi, callback) {
	$.getJSON(PA_API_BASE_URI + "latest?bounds=" + latLow + "," + lngLow + "," + latHi + "," + lngHi, callback);
}


/////////////////////////////////////////////////////////////////////////////// 
//  Google Maps Javascript API V3 calls
//  http://code.google.com/apis/maps/documentation/javascript/reference.html

// the address can either be a postal address such as 'Shannonbridge, Galway' 
// or a lat/lng position, for example, '53.2791,-8.0482' - the effect of the call
// is that the map will be centered around this address.
function gotoAddress(address, options) {
	var defaults = {
		showsv: true, // by default show the map in street view mode
		reportresult: true // by default show a dialg that informs the user of invalid address
	};  
	var options = $.extend(defaults, options);
	
	$.getJSON("http://maps.google.com/maps/geo?q="+ address+"&sensor=false&output=json&callback=?", function(data, textStatus){
		if(data.Status.code==200) {  
			var latitude = data.Placemark[0].Point.coordinates[1];  
			var longitude = data.Placemark[0].Point.coordinates[0];  
			//console.log("got lat:" + latitude + " long: " + longitude + " for address: " + address);
			if(latitude && longitude) { // we have both values
				map.setCenter(new google.maps.LatLng(latitude, longitude));
				if(options.showsv && map.getStreetView().getVisible()) { // the SV is currently visible
					showSV();
				}
				else {
					showMap();
				}
			}
		}
		else {
			if(options.reportresult) alert("Sorry, I didn't find the address you've provided. Try again, please ...");
		}
	});
}