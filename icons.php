<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
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

print("<p>\nMost of the icons of this section were done by Tuomas \"tigert\" Kuosmanen and Jakub \"jimmac\" Steiner. Some icons were done by Garrett LeSage, Ed Halley, Ville \"drc\" P&#228;tsi, and Roman \"star\" Beigelbeck.");

create_middle_box_bottom();
include("footer.inc.php");
?>
