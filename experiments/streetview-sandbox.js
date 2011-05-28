
var map;
var mapCenter = new google.maps.LatLng(53.27, -9.102);
var currentMarkers = new Array();

// GPlan status based on GPlan_ApplicationStatus.txt
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

// this is dummy data, I made it up based on exitings PA, but the schema should be used:
var data = [
	{appref:'a',lat:53.270,lng:-9.104,appdate:2000,appstatus:8, appdesc:'construct an extension to house'},
	{appref:'b',lat:53.2701,lng:-9.102,appdate:2001,appstatus:8, appdesc:'construct extension to house, again'},
	{appref:'c',lat:53.2703,lng:-9.104,appdate:2002,appstatus:3, appdesc:'another construct'},
	{appref:'d',lat:53.2702,lng:-9.103,appdate:2001,appstatus:3, appdesc:'testing'},
	{appref:'e',lat:53.2702,lng:-9.1041,appdate:2002,appstatus:3, appdesc:'blah blah'},
	{appref:'f',lat:53.2695,lng:-9.1039,appdate:2001,appstatus:3, appdesc:'dunno'},
	{appref:'g',lat:53.2702,lng:-9.1039,appdate:2002,appstatus:3, appdesc:'got it!'},
	{appref:'h',lat:53.2703,lng:-9.104,appdate:2002,appstatus:8, appdesc:'nah, next time'},
	{appref:'i',lat:53.2703,lng:-9.103,appdate:2000,appstatus:8, appdesc:'fail fail fail'},
	{appref:'j',lat:53.270,lng:-9.102,appdate:2001,appstatus:3, appdesc:'yey, I did it'},
	{appref:'k',lat:53.2702,lng:-9.102,appdate:2002,appstatus:8, appdesc:'oh oh'},
	{appref:'l',lat:53.2705,lng:-9.1,appdate:2000,appstatus:8, appdesc:'more here'},
	{appref:'m',lat:53.2701,lng:-9.102,appdate:2001,appstatus:3, appdesc:'sdfsdf'},
	{appref:'n',lat:53.2703,lng:-9.103,appdate:2002,appstatus:8, appdesc:'ffffff'},
	{appref:'o',lat:53.2702,lng:-9.101,appdate:2000,appstatus:8, appdesc:'fas fdsdfsd dfsdfsd sdfsdfsd sdfsdfs '},
	{appref:'p',lat:53.27,lng:-9.1,appdate:2001,appstatus:3, appdesc:'yfff '},
	{appref:'q',lat:53.2703,lng:-9.101,appdate:2002,appstatus:8, appdesc:'fsdfff  ffffff'},
	{appref:'r',lat:53.2698,lng:-9.106,appdate:2001,appstatus:8, appdesc:'fffffff fdsfdffsdnlkndsfl dsfnsdlkfn dskfnsd'},
	{appref:'s',lat:53.2697,lng:-9.102,appdate:2001,appstatus:3, appdesc:'yuu'},
	{appref:'t',lat:53.2699,lng:-9.102,appdate:2002,appstatus:8, appdesc:'vsdv'},
	{appref:'u',lat:53.2698,lng:-9.101,appdate:2000,appstatus:8, appdesc:'abcl'},
	{appref:'v',lat:53.2697,lng:-9.103,appdate:2001,appstatus:3, appdesc:'123 abbbb fddfff///'},
	{appref:'w',lat:53.2698,lng:-9.102,appdate:2002,appstatus:8, appdesc:'? >'},
	{appref:'x',lat:53.2701,lng:-9.103,appdate:2000,appstatus:8, appdesc:'.....'},
	{appref:'y',lat:53.2699,lng:-9.104,appdate:2001,appstatus:3, appdesc:'mmmmm'},
	{appref:'z',lat:53.2703,lng:-9.102,appdate:2002,appstatus:8, appdesc:'.o(bah)'},
	{appref:'1',lat:53.270,lng:-9.103,appdate:2000,appstatus:8, appdesc:'x.x.x.x.x.x.'},
	{appref:'2',lat:53.2701,lng:-9.103,appdate:2001,appstatus:3, appdesc:'. . . . . .    ...     '},
	{appref:'3',lat:53.2698,lng:-9.104,appdate:2002,appstatus:8, appdesc:'this is a very very very long description to test how far we can go if at all, don\'t look here'},
	{appref:'4',lat:53.2704,lng:-9.101,appdate:2000,appstatus:8, appdesc:'fail fail sdfffffffffff'},
	{appref:'5',lat:53.2704,lng:-9.103,appdate:2001,appstatus:3, appdesc:'LAST'}		
];

function fitMap(){
	$("#mainpan").height($(window).height()*0.9);
	$("#map").width($(window).width()-340);
	$("#map").height($(window).height()*0.6);
	$("#palist-content").height($(window).height()*0.85);
}

function makemap() {

	var mapOptions = { 
		zoom: 18,
		center: mapCenter,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		overviewMapControl: true,
		overviewMapControlOptions: {
			position: google.maps.ControlPosition.BOTTOM_RIGHT,
			opened: true
		}
	};

	map = new google.maps.Map(document.getElementById("map"), mapOptions);

	for (i=0; i < data.length; i++) {
		addMarker(data[i]);
	}	
}

function showSV() {
	var svmap = map.getStreetView();
	svmap.setPosition(mapCenter);
	svmap.setPov({
		heading: 170,
		zoom: 1,
		pitch: 0
	});
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
		// marker size is 30x32
		var yMarkerImage = new google.maps.MarkerImage("img/year-marker-"+ r.appstatus +"-"+ r.appyear +".png");

		marker = new google.maps.Marker({
			position: pos,
			map: map,
			icon: yMarkerImage,
			title: APPLICATION_STATUS[r.appstatus]
		});
		
		
		google.maps.event.addListener(marker, "mouseover", function() {
			
			
			if(!$("#pa_"+ r.appref).hasClass("active-pa")){                            
			  $("#pa_"+ r.appref).addClass('active-pa');
			 }

			
		});
		
		google.maps.event.addListener(marker, "mouseout", function() {
			

			$("#pa_"+ r.appref).removeClass('active-pa');	
			
		});
		
		
		currentMarkers.push({ id:r.appref, year:r.appyear, marker:marker });
		$("#palist-content").append("<div class='singlepa' id='pa_" + r.appref  +"'><a href='#" + r.appref + "'>" + record.appdesc + "</a> - " + APPLICATION_STATUS[r.appstatus] + "</div>");
		
	})({'appyear':record.appdate, 'appstatus':record.appstatus, 'appref':record.appref, 'appdesc': record.appdesc});
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


$(function() { 
	$("#palist-content").append("<h2>Planning Applications</h2>");
	makemap(); 
	yearsel();
	fitMap();
	$(window).resize(function() {
		fitMap(); 
	});
	
	$("#show-map").click(function() {
		showMap();
	});
	
	$("#show-sv").click(function() {
		showSV();
	});
	
	$("#palist-vis").toggle(function() {
		$("#palist").slideDown("slow");
		$("#palist-vis").html("&raquo;");
	}, function() {
		$("#palist").slideUp("slow");
		$("#palist-vis").html("&laquo;");
	});
	
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