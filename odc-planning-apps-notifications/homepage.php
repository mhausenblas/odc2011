<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
      <title>Recent planning applications</title>
      <script type="text/javascript"
      src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js">
      </script>
      <script type="text/javascript"
      src="js/jquery.maphilight.min.js"></script>
      <link rel="stylesheet" type="text/css"
      href="css/homepage.css" />
      <script type="text/javascript">$(function() {
      $('.map').maphilight(); $('.cocotitle').mouseover(
      function(e) { var id=$(this).attr('id');
      $('#'+id+'area').mouseover(); $('#'+id+'rss').show();
      $('#'+id+'twit').show(); } ); $('.cocotitle').click(function
      (e){ var id=$(this).attr('id'); $('#'+id+'lpa').toggle(); });
      $('.cocotitle').mouseout( function(e) { var
      id=$(this).attr('id'); $('#'+id+'area').mouseout()
      $('#'+id+'rss').hide(); $('#'+id+'twit').hide(); } );
      });</script>
      <!--[if IE]>
                <link rel="stylesheet" type="text/css" href="css/ie.css" />
        <![endif]-->
      <body>
        <div id="page">
          <?php include("header.php"); ?>
          <div class="window">
           
              <?php include("cocolist.php"); ?>
           
          </div>
		  <div class="recenttwits">     
              <?php include("twits.php"); ?>
          </div>
          <table id="contianer">
            <tr>
              <td width="55%">
                <div>
                  <img class="map" src="images/demo_ireland.png"
                  usemap="#ireland">
                    <tr>
                      <td>
                        <?php include("footer.php"); ?>
						<?php include("map.php"); ?>
                      </td>
                    </tr>
                  </img>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </body>
    </meta>
  </head>
</html>
