/////////////////////////////////////////////////////////////////////////////// 
//  PA archive view globals
//
var map;
var mapCenter = new google.maps.LatLng(53.270, -9.104);
var mapInitialZoomFactor = 18;

var currentMarkers = new Array();

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


// this is dummy data, I made it up based on exitings PA, but the schema should be used:
var data = [
	{appref:'a',lat:53.270,lng:-9.104,appdate:2000, decision:"R", appstatus:9, appdesc:'construct an extension to house'},
	{appref:'b',lat:53.270,lng:-9.104,appdate:2001, decision:"R", appstatus:8, appdesc:'construct extension to house, again'},
	{appref:'c',lat:53.270,lng:-9.104,appdate:2003, decision:"N", appstatus:1, appdesc:'another construct'},
	{appref:'d',lat:53.270,lng:-9.104,appdate:2004, decision:"U", appstatus:3, appdesc:'testing'},
	{appref:'e',lat:53.270,lng:-9.104,appdate:2005, decision:"C", appstatus:8, appdesc:'construct extension to house, again'},
	{appref:'f',lat:53.270,lng:-9.104,appdate:2006, decision:"N", appstatus:11, appdesc:'construct extension to house, again'},
	{appref:'g',lat:53.270,lng:-9.104,appdate:2007, decision:"R", appstatus:2, appdesc:'demolish house'},
	{appref:'h',lat:53.270,lng:-9.104,appdate:2008, decision:"U", appstatus:0, appdesc:'construct extension to shed'}
];

/////////////////////////////////////////////////////////////////////////////// 
//  PA archive view main event loop
//

$(function() { 
	
	makemap(); // create the Google Map
	makelegend(); // create the legend
	yearsel(); // create the year selection slider
	fitPAAWidgets(); // initial sizing of the widgets (map, year selection slider, etc.)
	showSV(); // show SV initially
	
	$(window).resize(function() { // whenever window is resized, adapt size of widgets
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
			$.getJSON("http://maps.google.com/maps/geo?q="+ address+"&sensor=false&output=json&callback=?", function(data, textStatus){
				if(data.Status.code==200) {  
					var latitude = data.Placemark[0].Point.coordinates[1];  
					var longitude = data.Placemark[0].Point.coordinates[0];  
					console.log("got lat:" + latitude + " long: " + longitude + " for address: " + address);
					if(latitude && longitude) {
						map.setCenter(new google.maps.LatLng(latitude, longitude));
						if(map.getStreetView().getVisible()){ // the SV is currently visible
							showSV();
						}
					}
				}
				else {
					alert("Sorry, I didn't find the address you've provided. Try again, please ...");
				}
			});
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
	
	$("#palist").mouseout(function() {
		for (var i = 0; i < currentMarkers.length; i++){
			currentMarkers[i].marker.setAnimation(null);
		}
	});
	
});


/////////////////////////////////////////////////////////////////////////////// 
//  PA archive view library
//

function fitPAAWidgets(){
//	$("#mainpan").height($(window).height()*0.7);
	$("#map").width($(window).width()*0.98);
	$("#map").height($(window).height()*0.6);
//	$("#palist-content").height($(window).height()*0.85);
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
	for (i=0; i < data.length; i++) {
		addMarker(data[i]);
	}	
}

function makelegend(){
	/*
	$.each(DECISION_CODE, function(index, val){
		$("#decision-legend").append("<div style='padding: 2px;'>" + val.toLowerCase() + "</div>");	
	});
	$.each(MARKER_COLOR, function(index, val){
		$("#appstatus-legend").append("<div style='padding: 2px; color: #f0f0f0; background:" + MARKER_COLOR[index] +";'>" + APPLICATION_STATUS[index].toLowerCase() + "</div>");	
	});
	*/
	$.each(PA_STATE, function(index, val){
		$("#appstatus-legend").append("<div style='padding: 2px; color: #f0f0f0; background:" + PA_STATE[index] +";'>" + index + "</div>");	
	});
	
	
	
}

function showSV() {
	var svmap = map.getStreetView();
	var panoOptions = {
		position: map.getCenter(),
			pov: {
			heading: 170,
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
	for (var i = 0; i < currentMarkers.length; i++){
		$("#palist-content").append("<div class='singlepa' id='pa_" + currentMarkers[i].id  +"'>" + currentMarkers[i].year +": <a href='#" + currentMarkers[i].id + "'>" + currentMarkers[i].desc + "</a></div>");
	}	
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
			if(word.trim() != "" && word.trim() != "an" && word.trim() != "to" ) {
				if (index < currentMarkers.length - 1) $("#palist-cloud").append(word + " , ");
				else $("#palist-cloud").append(word);
			}
		});
	});
	$("#palist-cloud").tagCloud({
		separator: ',',
		randomize: true,
		sizefactor: 8
	});
	
}

function addMarker(record) {
	(function(r) {
		var pos = getDisplayPosition(record.lat, record.lng);
		var yMarkerImage = new google.maps.MarkerImage(drawMarker(r.appyear, r.decision, r.appstatus));
		var marker = new google.maps.Marker({
			position: pos,
			map: map,
			icon: yMarkerImage,
			title: APPLICATION_STATUS[r.appstatus]
		});
		// remember the marker
		currentMarkers.push({ id:r.appref, year:r.appyear, desc:r.appdesc, marker:marker});
		
		google.maps.event.addListener(marker, "click", function() {
			if(!map.getStreetView().getVisible()){ // the SV is not visible
				$("#palist-content").html("<div class='singlepa' id='pa_" + r.appref  +"'>" + r.appyear + ": <a href='#" + r.appref + "'>" + r.appdesc + "</a> - " + DECISION_CODE[r.decision] + " - "  + APPLICATION_STATUS[r.appstatus] + "</div>");
			}
		});
	})({'appyear':record.appdate, 'decision':record.decision, 'appstatus':record.appstatus, 'appref':record.appref, 'appdesc': record.appdesc});
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
	if(decision == 'N' && inArray(status,['0', '2', '8', '11'])) { // incomplete or withdrawn PA, if decision is unknown
		return '#9f9f9f';
	}
	else {
		if(decision == 'R') { // refused PA
			return '#660000';
		}
		else {
			if(decision == 'C') { // conditional PA
				return '#333060';
			}
			else {
				if(decision == 'U') { // unconditional PA
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

function yearsel(){
	$("#yearrange").slider({
		range: true,
		min: 2000,
		max: 2010,
		values: [ 2000, 2010 ],
		slide: function(event, ui) {
			$("#yearsel").val(ui.values[0] + " - " + ui.values[1]);
			filterPA(ui.values[0], ui.values[1]);
		}
	});
	$("#yearsel").val($("#yearrange").slider("values", 0) + " - " + $("#yearrange").slider("values", 1 ));
	$("#yearsel").attr('readonly', true);
	
}

function filterPA(miny, maxy){
	for(i in currentMarkers){
		if((currentMarkers[i].year >= miny) && ( currentMarkers[i].year <= maxy)) {
			currentMarkers[i].marker.setVisible(true);
		}
		else {
			currentMarkers[i].marker.setVisible(false);
		}
	}
}