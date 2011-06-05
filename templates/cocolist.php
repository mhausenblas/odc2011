<?php
sort($councils);
$apps = array();
//We reverse for now so that the most recent app_ref code will be treated as latest for a council.
$councils_la = array_reverse($councils_la);

foreach($councils_la as $app_ref => $application) {
    $apps[$application['council_id']] = $application;
}
//print_r($apps);
?>

<ul id="councils_list">
<?php foreach ($councils as $council_id => $council_d) { ?>
    <li id="<?php echo strtolower($council_d['short']); ?>">
<?php $council_url = 'http://planning-apps.opendata.ie/'.$council_d['short']; ?>
        <h3><a href="<?php echo $council_url; ?>"><?php echo $council_d['name']; ?></a></h3>
<?php if (isset($apps[$council_id])) { ?>
        <a href="/feed/<?php echo $council_d['short']; ?>">Feed</a>
        <a href="http://twitter.com/<?php echo $council_d['short']; ?>Pln">Twitter</a>
        <div class="street_view">StreetView goes here</div>
        <p><?php
            //TODO: Replacing "&nbsp;" occurrences here but it should be done before importing the data
            $addr = array($apps[$council_id]['address1'], $apps[$council_id]['address2'], $apps[$council_id]['address3'], $apps[$council_id]['address4']);
            $address = array();
            foreach($addr as $a) {
                $address[] = $a;
            }
            $address = implode(', ', $address);
            echo string_cleanup($address);
        ?></p>
        <p><?php echo string_cleanup($apps[$council_id]['details']); ?></p>
        <span class="more"><a href="<?php echo $council_url.'#'.$apps[$council_id]['app_ref']; ?>" title="Find out more about this planning application.">#</a></span><?php } ?>

    </li>
<?php } ?>
</ul>

<?php
function string_cleanup($s) {
    $s = str_replace("\n", ', ', $s);
    $s = preg_replace("#(\s|&nbsp;)\s?(&nbsp;)?#", ' ', $s);
    $s = preg_replace('#,(\s)*,#', ',', $s);
    $s = preg_replace('#,\s+,#', '', $s);

    return trim($s);
}
?>
