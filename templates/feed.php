<?php

header('Content-Type: application/atom+xml');

echo "<?xml version=\"1.0\"?>\n";

?><feed xmlns="http://www.w3.org/2005/Atom">
  <title><?php e($title); ?></title>
  <subtitle>Recent planning applications submitted to local councils</subtitle>
  <link href="<?php e($base); ?>"/>
  <link rel="self" href="<?php e($url); ?>"/>
  <updated><?php e($max_date); ?>T06:00:00Z</updated>
  <id><?php e($url); ?></id>
<?php foreach ($apps as $app) { ?>
  <entry>
    <title><?php e(($all ? '' : ($councils[$app['council_id']]['name'] . ': ')) . str_replace("\n", ", ", $app['address'])); ?> [<?php e($app['app_ref']); ?>]</title>
    <link href="<?php e($app['url']); ?>"/>
    <id>tag:planning-apps.opendata.ie,2011:<?php e($app['council_id']); ?>:<?php e($app['app_ref']); ?></id>
    <updated><?php e($app['received_date']); ?>T06:00:00Z</updated>
    <author><name><?php e((@$app['applicant']) ? $app['applicant'] : $councils[$app['council_id']]['name']); ?></name></author>
    <content type="xhtml" xml:lang="en">
      <div xmlns="http://www.w3.org/1999/xhtml">
        <p><?php e($app['details']); ?></p>
<?php if (!empty($app['lat'])) { ?>
        <p><img src="<?php e('http://maps.google.com/maps/api/staticmap?size=200x200&zoom=' . ($councils[$app['council_id']]['lowres'] ? 14 : 16) . '&maptype=hybrid&markers=size:mid|' . $app['lat'] . ',' . $app['lng']. '&sensor=false'); ?>" /></p>
<?php } ?>
      </div>
    </content>
  </entry>
<?php } ?>
</feed>
