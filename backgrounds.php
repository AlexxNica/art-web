<?php
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("backgrounds");

print("Backgrounds are images for use on your desktop as the desktop background, sometimes known as wallpapers.");
print("<p>\n<table border=\"0\" cellpadding=\"4\">");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"background_list.php?category=gnome\">GNOME</a> - The GNOME project has built a complete, free and easy-to-use desktop environment for the user, as well as a powerful application framework for the software developer.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"background_list.php?category=other\">Other</a> - Backgrounds featuring other GNOME based companies such as Ximian, Codefactory, RedHat, etc.</td></tr>\n");

print("</table>\n");


create_middle_box_bottom();
include("footer.inc.php");
?>
