<?php

/* $Id$ */

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("THEMES");
create_middle_box_top("themes");

print("<table border=\"0\" cellpadding=\"4\">\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gdm_greeter\"><b>GDM Greeter</b></a> - GDM Greeter themes change the appearance of the GNOME 2.0 login screen.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gtk\"><b>GTK+ 1.2</b></a> - GTK+ 1.2 themes alter the appearance of the GTK+ 1.2 widgets. In the GNOME desktop, this means the appearance of all your GNOME applications.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gtk2\"><b>GTK+ 2.0</b></a> - GTK+ 2.0 themes control the appearance of your GNOME 2.0 programs. </td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=icon\"><b>Icon</b></a> - GNOME 2.2.x system icon themes change the themes in nautilus, file-roller, etc..</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=metacity\"><b>Metacity</b></a> - Metacity is a new window manager for GNOME 2.0.</td></tr>\n");
//print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=nautilus\"><b>Nautilus</b></a> - Nautilus is the default file manager for GNOME 1.4 &amp; 2.0. These themes control its appearance.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=sawfish\"><b>Sawfish</b></a> - Sawfish is the default window manager for GNOME. The themes for sawfish change the look of the decoration round your windows (titlebars etc).</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=sounds\"><b>Sounds</b></a> - Collection of sounds to compliment the GNOME desktop.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=splash_screens\"><b>Splash Screens</b></a> - Splash Screens are what you first see when you log into GNOME.</td></tr>\n");
print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=other\"><b>Other ...</b></a> - Other themes.<td></tr>\n");
print("</table>\n");

create_middle_box_bottom();
ago_footer();
?>
