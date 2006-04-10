<?php
require("mysql.inc.php");
require("common.inc.php");
require("art_listings.inc.php");
require("templates.inc.php");


$style = validate_input_array_default ($_GET['style'], Array('icons','list'), 'list');

$list = new latest_updates_list;
$list->per_page  = 12;
$list->view      = $style;
$list->format    = 'rss';
$list->date_type = 'absolute';
$list->select();

$update_time = $list->last_updated();
$etag = "\"rss-$style-00-$update_time\""; /* time-style-some string which can be changed*/
conditional_get($etag, $update_time);


// using text/xml until firefox bug is fixed.
//header("Content-type: application/rss+xml");
header("Content-type: text/xml");

$header = new template('rss/header.xml');
$header->add_var('site_url', $site_url);
$header->add_var('site_name', $site_name);
$header->add_var('update_time', gmdate('Y-m-d\TH:i:s\Z', $update_time));

$footer = new template('rss/footer.xml');

$header->write();
$list->print_listing();
$footer->write();

?>
