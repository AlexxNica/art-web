<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");

create_middle_box_top("icons");
print("<table>\n");
while(list($key,$val)=each($GLOBALS["sys_icon_type_array"]))
{
	$realname = $val["realname"];
   $icon_filename = $val["image"];
   $tarball_name = $val["tarball_name"];
   $tarball_filename =$val["tarball_filename"];
   print("<tr><td><img src=\"images/icons/$key/$icon_filename\"></td><td><a href=\"show_icons.php?type=$key\">$realname</a> [<a href=\"/images/icons/$tarball_filename\">$tarball_name</a>]</td></tr>\n");
}
print("</table>\n");

//print("<p>\nMost of the icons of this section were done by Tuomas \"tigert\" Kuosmanen, Jakub \"jimmac\" Steiner and Justin \"largo\" Stressman. Some icons were done by Garrett LeSage, Ed Halley, Ville \"drc\" P&#228;tsi, and Roman \"star\" Beigelbeck.");
print("<p>\nThanks to the following artists for their great icons:");
print("<ul>\n");
print("<li><a href=\"mailto:tigert _AT_ ximian.com\">Tuomas \"tigert\" Kuosmanen</a>\n");
print("<li><a href=\"mailto:jimmac _AT_ ximian.com\">Jakub \"jimmac\" Steiner</a>\n");
print("<li><a href=\"mailto:largo _AT_ windowmaker.org\">Justin \"largo\" Stressman</a>\n");
print("<li><a href=\"mailto:garrett _AT_ linuxart.com\">Garrett LeSage</a>\n");
print("<li>Ed Halley\n");
print("<li>Ville \"drc\" P&#228;tsi\n");
print("<li><a href=\"mailto:roman _AT_ gnome.org\">Roman \"star\" Beigelbeck</a>\n");
print("<li>and many more...\n");
print("</ul>\n");

create_middle_box_bottom();
include("footer.inc.php");
?>
