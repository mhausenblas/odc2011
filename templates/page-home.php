<form action="">
<input type="submit" name="search_btn" class="sreachbtn" id="search_btn" value="Search" />
<input type="text" name="search_address" class="sreach" id="search_address" value="Search Address" />
</form>

<?php $councils_la_latlng = array(); ?>
<ul id="councils_list">
<?php foreach ($councils as $council_id => $council_d) { ?>
    <li id="<?php e($council_d['short']); ?>">
        <h3><a href="/<?php e($council_d['short']); ?>"><?php e($council_d['name']); ?></a></h3>
<?php
if (isset($apps[$council_id])) {
    $app = $apps[$council_id];
    if (isset($app['lat']) && isset($app['lng'])) {
        $councils_la_latlng[$council_d['short']] = $app['lat'].','.$app['lng'];
    }
?>
        <ul class="see_also">
            <li><a href="/feed/<?php e($council_d['short']); ?>" class="icon_feed">Feed</a></li>
            <li><a href="http://twitter.com/<?php e($council_d['short']); ?>Pln" class="icon_twitter">Twitter</a></li>
        </ul>
        <div class="recent_application">
            <h4>Most recent application</h4>
            <p><?php e(str_replace("\n", ", ", $app['address'])); ?></p>
            <p><?php e(str_replace("\n", "<br/>", $app['details'])); ?></p>
            <p class="more"><a href="/<?php e($council_d['short']); ?>#<?php e($app['app_ref']); ?>" title="Find out more about this planning application.">more</a></p>
        </div><?php } ?>
    </li>
<?php } ?>
</ul>
<?php $councils_la_latlng = json_encode($councils_la_latlng); ?>

<img  src="images/ireland-map.png" usemap="#ireland">
<?php include("templates/map.php"); ?>
