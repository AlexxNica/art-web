<?php
require("templates.inc.php");
require("common.inc.php");
require("art_listings.inc.php");


$style = validate_input_array_default ($_GET['style'], Array('icons','list'), 'list');

$list = new latest_updates_list;
$list->per_page  = 12;
$list->view      = $style;
$list->format    = 'rss';
$list->select();

generate_feed($list, 'rss');

?>
