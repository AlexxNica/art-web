<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobals stuff
$num_updates = $_GET["num_updates"];

ago_header("UPDATES");
create_middle_box_top("updates");

/*
if (!$num_updates || $num_updates=="" || $num_updates==0)
{
	$num_updates = 12;
}
*/
print("The $num_updates most recent additions are:");

$big_array = get_updates_array($num_updates);
print("<p>\n<table>\n");
for($count=0;$count<count($big_array);$count++)
{
	list($add_timestamp,$type,$ID) = explode("|",$big_array[$count]);
	if($type == "background")
   {
   	print_background_row($ID);
	}
   else
   {
   	print_theme_row($ID);
	}
}
print("</table>\n");
print("<p><div align=\"center\">Number of updates to display: <form action=\"$PHP_SELF\" method=\"get\">");
print("<input type=\"text\" name=\"num_updates\" value=\"$num_updates\" size=\"3\"> ");
print("<input type=\"submit\" value=\"Show\"></form></div>\n");
create_middle_box_bottom();
ago_footer();
?>
