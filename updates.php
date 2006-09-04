<?php

require("common.inc.php");

$format = validate_input_array_default($_GET['format'], array('rss', 'atom', 'html'), 'html');
if ($format != 'html')
        $prevent_session = TRUE;

require("art_headers.inc.php");
require("art_listings.inc.php");

$latest = new latest_updates_list;
$latest->format = $format;
$latest->get_view_options();
$latest->select();

if ($format == 'html')
{
	art_header("Updates");
	create_title("Updates", "The $num_updates most recent additions to art.gnome.org");

	$latest->print_search_form();
	print('<hr />');
	$latest->print_page_numbers();
	$latest->print_listing();
	$latest->print_page_numbers();

	art_footer();
}
else
{
	generate_feed($latest, $format);
}
?>
