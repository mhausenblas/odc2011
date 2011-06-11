<?php

include dirname(__FILE__) . '/../init.php';

if ($argc != 2 || !is_numeric($argv[1])) {
    echo "Usage: php geocode.php count\n";
    echo "  where count is the number of geocoding requests to make\n";
    die();
}

$requests = $argv[1];

$apps = $planning->get_applications("SELECT * FROM applications
WHERE (app_ref, council_id) NOT IN (SELECT app_ref, council_id FROM geocoding)
AND lat IS NULL
ORDER BY received_date DESC
LIMIT $requests");
foreach ($apps as $app) {
    $address = str_replace("\n", ', ', $app['address']);
    if ($planning->geocode_application($app)) {
        $planning->update_application($app);
        echo "# Success: $app[council_id]:$app[app_ref] ($address) $app[lat],$app[lng]\n";
    } else {
        echo "# Failed or approximate: $app[council_id]:$app[app_ref] ($address)\n";
    }
}
die();

