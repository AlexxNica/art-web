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
   print("<tr><td><img src=\"images/icons/$key/$icon_filename\"></td><td><a href=\"show_icons.php?type=$key\">$realname</a> [<a href=\"$mirror_url/images/icons/$tarball_filename\">$tarball_name</a>]</td></tr>\n");
}
print("</table>\n");


create_middle_box_bottom();
include("footer.inc.php");
?>
