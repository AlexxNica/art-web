<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobals stuff
$num_updates = validate_input_regexp_default($_GET["num_updates"], "^[0-9]+$", 12);

if ($num_updates < 1 || $num_updates > 100)
{
	$num_updates = 12;
}

ago_header("UPDATES");
create_title("Updates", "The $num_updates most recent additions to art.gnome.org");

$big_array = get_updates_array($num_updates);
print("<p>\n<table>\n");
for($count=0;$count<count($big_array);$count++)
{
	list($add_timestamp,$type,$ID) = explode("|",$big_array[$count]);
	if($type == "background")
	{
		print_background_row($ID, "list");
	}
	else
	{
		print_theme_row($ID, "list");
	}
}
print("</table>\n");

print("<p><div align=\"center\">Number of updates to display: <form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"get\">");
print("<input type=\"text\" name=\"num_updates\" value=\"$num_updates\" size=\"3\"> ");
print("<input type=\"submit\" value=\"Show\"></form></div>\n");

ago_footer();
?>
