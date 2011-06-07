$(document).ready(function(){
    var map;
    var panorama;
    var str;

    function initializeStreetView(){
        $.each(latlng, function(index){
            $('#councils_list li#'+index+' .recent_application h4').after('<div class="street_view" id="map_canvas_' + index + '">' + '</div>');
            l = latlng[index].split(',');
            loadMap(document.getElementById('map_canvas_'+index),new google.maps.LatLng(l[0],l[1]));
            $('#map_canvas_'+index).click(function() {
                app_ref = $('#councils_list li#'+index+' .more a').attr('href');
                if (app_ref[0] == '/') {
                    app_ref = app_ref.substring(1);
                }
                window.location.href = window.location.href + app_ref;
            });
        });
    }

    function loadMap(id, place) {
        var mapCenterLat = place.lat();
        var mapCenterLng = place.lng();

        var mapOptions = {
            center: place,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(id, mapOptions);
        var panoramaOptions = {
            position: place,
            pov: {
                heading: 170,
                pitch: 0,
                zoom: 1
            },
            scrollwheel: false,
            addressControl: false,
            zoomControl: false,
            linksControl: false,
            panControl: false
        };

        var panorama = new  google.maps.StreetViewPanorama(id,panoramaOptions);

        //FIXME: event listeners below, getBearing and get getPitch are copied from streetview.js.
        // Only streetview.js should be used when it is refactored.

        // handle current position in SV
        google.maps.event.addListener(panorama, 'position_changed', function() {
            var pos = panorama.getPosition();
            var bearing = getBearing(pos, new google.maps.LatLng(mapCenterLat, mapCenterLng));
            panorama.setPov({'heading': bearing, 'pitch': 0, 'zoom': 1});
        });

        map.setStreetView(panorama);
    }

    function getBearing(pos1, pos2) {
        var dlat = pos2.lat() - pos1.lat();
        var dlng = pos2.lng() - pos1.lng();
        if (dlat < 0) {
            if (dlng > 0) return Math.atan(dlng/dlat) * 180.0 / Math.PI + 180.0;
            if (dlng < 0) return Math.atan(dlng/dlat) * 180.0 / Math.PI + 180.0;
            return 180.0;
        } else if (dlat > 0) {
            if (dlng > 0) return Math.atan(dlng/dlat) * 180.0 / Math.PI;
            if (dlng < 0) return Math.atan(dlng/dlat) * 180.0 / Math.PI;
            return 0.0;
        } else {
            if (dlng < 0) return -90.0;
            if (dlng > 0) return 90.0;
            return Number.NaN;
        }
    }

    $('.map').maphilight();

    initializeStreetView();
});
