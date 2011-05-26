<?php

$sites = array(
    'Buncrana' => array(
        'name' => 'Buncrana Town Council',
        'system' => 'ePlan classic plus',
        'url' => "http://www.donegal.ie/buncrana_eplan/internetenquiry/",
        'homepage' => 'rpt_querybysurforrecloc.asp',
    ),
    'Bundoran' => array(
        'name' => 'Bundoran Town Council',
        'system' => 'ePlan classic plus',
        'url' => "http://www.donegal.ie/bundoran_eplan/internetenquiry/",
        'homepage' => 'rpt_querybysurforrecloc.asp',
    ),
    'CorkCity' => array(
        'name' => 'Cork City Council',
        'system' => 'ePlan classic',
        'url' => "http://planning.corkcity.ie/InternetEnquiry/",
        'homepage' => '',
    ),
    'Donegal' => array(
        'name' => 'Donegal County Council',
        'system' => 'ePlan classic plus',
        'url' => "http://www.donegal.ie/DCC/iplaninternet/internetenquiry/",
        'homepage' => 'rpt_querybysurforrecloc.asp',
    ),
    'GalwayCity' => array(
        'name' => 'Galway City Council',
        'system' => 'ePlan classic',
        'url' => "http://gis.galwaycity.ie/ePlan/InternetEnquiry/",
        'homepage' => '',
        'map' => 'http://lab.linkeddata.deri.ie/2010/planning-apps/',
    ),
    'Kerry' => array(
        'name' => 'Kerry County Council',
        'system' => 'ePlan classic plus',
        'url' => "http://atomik.kerrycoco.ie/ePlan/InternetEnquiry/",
        'homepage' => 'rpt_querybysurforrecloc.asp',
        'googlemaps_lowres' => true,
    ),
    'Letterkenny' => array(
        'name' => 'Letterkenny Town Council',
        'system' => 'ePlan classic plus',
        'url' => "http://www.donegal.ie/letterkenny_eplan/internetenquiry/",
        'homepage' => 'rpt_querybysurforrecloc.asp',
    ),
    'LimerickCo' => array(
        'name' => 'Limerick County Council',
        'system' => 'ePlan classic',
        'url' => "http://www.lcc.ie/ePlan/InternetEnquiry/",
        'homepage' => '',
        'googlemaps_lowres' => true,
    ),
    'Longford' => array(
        'name' => 'Longford County Council',
        'system' => 'ePlan classic',
        'url' => "http://www.longfordcoco.ie/ePlan/InternetEnquiry/",
        'homepage' => '',
        'googlemaps_lowres' => true,
    ),
    'NTipperary' => array(
        'name' => 'North Tipperary County Council',
        'system' => 'ePlan classic',
        'url' => "http://www.tipperarynorth.ie/iPlan/InternetEnquiry/",
        'homepage' => '',
        'googlemaps_lowres' => true,
    ),
    'Waterford' => array(
        'name' => 'Waterford County Council',
        'system' => 'ePlan classic',
        'url' => "http://www.waterfordcity.ie/ePlan/InternetEnquiry/",
        'homepage' => '',
        'googlemaps_lowres' => true,
    ),
/*
These are broken as of May 15
    'Leitrim' => "http://193.178.1.87/ePlan/InternetEnquiry/",
    'Cavan' => "http://www.cavancoco.ie/ePlan/InternetEnquiry/",
*/
);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Planning Applications to Local Councils in Ireland</title>
    <style>
th, td { padding: 0.2em 0.4em; }
    </style>
  </head>
  <body>
    <h1>Planning Applications to Local Councils in Ireland</h1>
    <p>This <a href="http://scraperwiki.com/">ScraperWiki</a> view provides Atom feeds
      for the most recent planning applications submitted to various Irish county and
      city councils. It's based on the scraper <a href="http://scraperwiki.com/scrapers/irish_planning_applications/">
      Irish Planning Applications</a>.</p>
    <p>This was made by <a href="http://twitter.com/cygri">@cygri</a> at <a href="http://www.deri.ie/">DERI</a>.</p>
    <p><em>This is work in progress!!!</em></p>
    <table rules="all">
      <tr>
        <th>Authority</th>
        <th>Planning inquiry system</th>
        <th>News Feed</th>
        <th>Map of recent applications</th>
      </tr>
<?php foreach ($sites as $code => $site) { ?>
      <tr>
        <td><?php e($site['name']); ?></td>
        <td><a href="<?php e($site['url'] . $site['homepage']); ?>"><?php e($site['system']); ?></a></td>
        <td><a href="http://scraperwikiviews.com/run/irish_planning_applications_feed/?county=<?php e($code); ?>">Atom</a></td>
        <td><?php if (empty($site['map'])) { echo '–'; } else { ?><a href="<?php e($site['map']); ?>">Map</a><?php } ?></td>
      </tr>
<?php } ?>
      <tr>
        <td><strong>All</strong></td>
        <td>–</td>
        <td><a href="http://scraperwikiviews.com/run/irish_planning_applications_feed/?county=all">Atom</a></td>
        <td><a href="http://scraperwikiviews.com/run/irish_planning_applications_map/">Map</a></td>
      </tr>
    </table>
    <p>Do you want to help adding more councils? Then get in touch on the <a href="https://groups.google.com/group/open-data-ireland">Open Data Ireland</a> Google Group mailing list!</p>
  </body>
</html>
<?php

function e($s) {
    echo htmlspecialchars($s);
}
