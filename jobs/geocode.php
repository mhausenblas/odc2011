<?php

include dirname(__FILE__) . '/../config.inc.php';

require_once dirname(__FILE__) . '/../lib/db.class.php';
require_once dirname(__FILE__) . '/../lib/planning.class.php';

$db = new DB($config);
$planning = new Planning($db);

$apps = $planning->get_applications('SELECT * FROM applications WHERE lat IS NULL ORDER BY received_date DESC LIMIT 2000');
foreach ($apps as $app) {
    if ($planning->geocode_application($app)) {
        $planning->update_application($app);
        echo "# Geocoded: $app[council_id]:$app[app_ref] ($app[address]) $app[lat],$app[lng]\n";
    } else {
        echo "# Geocoding failed: $app[council_id]:$app[app_ref] ($app[address])\n";
    }
}
die();

