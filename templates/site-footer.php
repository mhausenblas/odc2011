    </div>
    <div id="footer">
      <ul class="site_nav_global">
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
        <li><a href="/stats">Statistics</a></li>
      </ul>
      <p class="note"><em>This is <strong>not</strong> an official council website!</em></p>
      <p id="deri-logo"><a href="http://www.deri.ie/"><img src="/images/deri-logo-100px.png" alt="DERI Galway" /></a></p>
    </div>

    <script type="text/javascript">
        var latlng = <?php echo $councils_la_latlng; ?>

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
            map.setStreetView(panorama);
        }

        $('.map').maphilight();

        initializeStreetView();
    </script>
<?php include 'templates/google-analytics.html'; ?>
</body>
</html>
<?php if (@$page_hidden_message) { ?>
<!-- <?php e($page_hidden_message); ?> -->
<?php } ?>

