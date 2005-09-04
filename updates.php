<?php

require("common.inc.php");
require("art_headers.inc.php");
require("art_listings.inc.php");

// superglobals stuff
$num_updates = validate_input_regexp_default($_GET["num_updates"], "^[0-9]+$", 12);

if ($num_updates < 1 || $num_updates > 100)
{
	$num_updates = 12;
}

$latest = new latest_updates_list;
$latest->per_page = $num_updates;
$latest->select();


art_header("Updates");
create_title("Updates", "The $num_updates most recent additions to art.gnome.org");

$latest->print_listing();

print("<div style=\"text-align: center\"><form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\"><p>");
print("Number of updates to display: <input type=\"text\" name=\"num_updates\" value=\"$num_updates\" size=\"3\" /> ");
print("<input type=\"submit\" value=\"Show\" /></p></form></div>\n");

art_footer();
?>
