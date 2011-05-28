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

// this is dummy data, I made it up based on exitings PA, but the schema should be used:
var data = [
	{appref:'a',lat:53.270,lng:-9.104,appdate:2000, decision:"R", appstatus:9, appdesc:'construct an extension to house'},
	{appref:'b',lat:53.270,lng:-9.104,appdate:2001, decision:"N", appstatus:8, appdesc:'construct extension to house, again'},
	{appref:'c',lat:53.270,lng:-9.104,appdate:2003, decision:"C", appstatus:1, appdesc:'another construct'},
	{appref:'d',lat:53.270,lng:-9.104,appdate:2004, decision:"U", appstatus:3, appdesc:'testing'}
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
	$("#map").width($(window).width()-355);
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

	// add the markers based on the data we get from the PA service
	for (i=0; i < data.length; i++) {
		addMarker(data[i]);
	}	
}

function makelegend(){
	$.each(DECISION_CODE, function(index, val){
		$("#decision-legend").append("<div style='padding: 2px;'>" + val.toLowerCase() + "</div>");	
	});
	$.each(MARKER_COLOR, function(index, val){
		$("#appstatus-legend").append("<div style='padding: 2px; color: #f0f0f0; background:" + MARKER_COLOR[index] +";'>" + APPLICATION_STATUS[index].toLowerCase() + "</div>");	
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
	$("#show-map").removeClass('viewsel-tab-selected');
	$("#show-sv").addClass('viewsel-tab-selected');
}

function showMap() {
	map.getStreetView().setVisible(false);
	$("#show-sv").removeClass('viewsel-tab-selected');
	$("#show-map").addClass('viewsel-tab-selected');
}

function addMarker(record) {
	var pos =  getDisplayPosition(record.lat, record.lng);

	(function(r) {
		var yMarkerImage = new google.maps.MarkerImage(drawMarker(r.appyear,r.appstatus));

		marker = new google.maps.Marker({
			position: pos,
			map: map,
			icon: yMarkerImage,
			title: APPLICATION_STATUS[r.appstatus]
		});
		
		google.maps.event.addListener(marker, "click", function() {
			if(map.getStreetView().getVisible()){ // the SV is currently visible
				// TODO: implement TOP-k listing by distance
			}
			else {
				$("#palist-content").html("<div class='singlepa' id='pa_" + r.appref  +"'><a href='#" + r.appref + "'>" + r.appdesc + "</a> - " + DECISION_CODE[r.decision] + " - "  + APPLICATION_STATUS[r.appstatus] + "</div>");
			}
		});
		$("#palist-content").append("<div class='singlepa' id='pa_" + r.appref  +"'><a href='#" + r.appref + "'>" + r.appdesc + "</a> - " + DECISION_CODE[r.decision] + " - "  + APPLICATION_STATUS[r.appstatus] + "</div>");
	
		
		currentMarkers.push({ id:r.appref, year:r.appyear, marker:marker });
		
		
	})({'appyear':record.appdate, 'decision':record.decision, 'appstatus':record.appstatus, 'appref':record.appref, 'appdesc': record.appdesc});
}


function drawMarker(year,status){
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
	context.fillStyle = MARKER_COLOR[status];
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

function getDisplayPosition(lat, lng){
	var pos = new google.maps.LatLng(lat, lng);
	var apos;
	/*
	if(currentMarkers.length > 0) {
		for (var i = 0; i < currentMarkers.length; i++){
			apos = currentMarkers[i].marker.getPosition();
			if(pos.equals(apos)) {
				return new google.maps.LatLng(record.lat + 0.00001, record.lng + 0.00001);
			}
		}
	}
	*/
	return pos;
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