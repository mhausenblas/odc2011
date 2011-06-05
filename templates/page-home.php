<form action="">
<input type="submit" name="search_btn" class="sreachbtn" id="search_btn" value="Search" />
<input type="text" name="search_address" class="sreach" id="search_address" value="Search Address" />
</form>

<ul id="councils_list">
<?php foreach ($councils as $council_id => $council_d) { ?>
    <li id="<?php e($council_d['short']); ?>">
        <h3><a href="/<?php e($council_d['short']); ?>"><?php e($council_d['name']); ?></a></h3>
<?php
if (isset($apps[$council_id])) {
  $app = $apps[$council_id];
?>
        <a href="/feed/<?php e($council_d['short']); ?>">Feed</a>
        <a href="http://twitter.com/<?php e($council_d['short']); ?>Pln">Twitter</a>
        <div class="street_view">StreetView goes here</div>
        <p><?php e(str_replace("\n", ", ", $app['address'])); ?></p>
        <p><?php e(str_replace("\n", "<br/>", $app['details'])); ?></p>
        <span class="more"><a href="/<?php e($council_d['short']); ?>#<?php e($app['app_ref']); ?>" title="Find out more about this planning application.">#</a></span><?php } ?>
    </li>
<?php } ?>
</ul>

<img class="map" src="images/demo_ireland.png" usemap="#ireland">
<?php include("templates/map.php"); ?>
