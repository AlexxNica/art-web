<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("ICONS");
create_middle_box_top("icons");
print("<table>\n");
$icon_select_result = mysql_query("SELECT * FROM icon");
while($icon_select_row=mysql_fetch_array($icon_select_result))
{
	$name = $icon_select_row["name"];
   $realname = $icon_select_row["realname"];
   $icon_filename = $icon_select_row["image"];
   $tarball_name = $icon_select_row["tarball_name"];
   $tarball_filename =$icon_select_row["tarball_filename"];
   print("<tr><td><img src=\"images/icons/$name/$icon_filename\"></td><td><a href=\"show_icons.php?type=$name\">$realname</a> [<a href=\"/images/icons/$tarball_filename\">$tarball_name</a>]</td></tr>\n");
}
print("</table>\n");

print("<p>\nThanks to the following artists for their great icons:");
print("<p>\n<table border=\"0\" cellpadding=\"2\">\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a href=\"mailto:tigert _AT_ ximian.com\">Tuomas \"tigert\" Kuosmanen</a></td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a href=\"mailto:jimmac _AT_ ximian.com\">Jakub \"jimmac\" Steiner</a></td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a href=\"mailto:largo _AT_ windowmaker.org\">Justin \"largo\" Stressman</a></td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a href=\"mailto:garrett _AT_ linuxart.com\">Garrett LeSage</a></td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td>Ed Halley</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td>Ville \"drc\" P&#228;tsi</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a href=\"mailto:roman _AT_ gnome.org\">Roman \"star\" Beigelbeck</a></td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td>and many more...</td></tr>\n");
print("</table>\n");

create_middle_box_bottom();
ago_footer();

?>
