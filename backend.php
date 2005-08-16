<?php
require("mysql.inc.php");
require("common.inc.php");
require("art_listings.inc.php");

$style = validate_input_array_default ($_GET['style'], Array('icons','list'), 'list');

$list = new latest_updates_list;
$list->per_page  = 12;
$list->view      = $style;
$list->format    = 'rss';
$list->date_type = 'absolute';
$list->select();


// using text/xml until firefox bug is fixed.
//header("Content-type: application/rss+xml");
header("Content-type: text/xml");

print('<?xml version="1.0" encoding="ISO-8859-1" ?>');
?>
<rss version="2.0">
	<channel>
	<title>art.gnome.org releases</title>
	<image><link><?php print $site_url?></link><url><?php print $site_url?>/images/site/art-icon.png</url><title>art.gnome.org</title></image>
	<link><?php print $site_url?></link>
	<description>A list of recent backgrounds and themes released on art.gnome.org</description>
	<webMaster>thos@nospam.gnome.org</webMaster>
<?php
$list->print_listing();
?>
	</channel>
</rss>