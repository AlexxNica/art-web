<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("LINKS");
create_middle_box_top("links");

print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/gnome-icon.png\"></td><td><font size=\"+1\" class=\"yellow-text\">GNOME Links</a></td></tr>\n");
print("<tr><td>&nbsp;</td><td>");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gnome.org\">GNOME Project Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gnomedesktop.org\">GNOME Desktop News</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gnomesupport.org\">GNOME Support</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.ximian.com\">Ximian Inc. Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.codefactory.se\">Codefactory Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.redhat.com\">RedHat Homepage</a></td></tr>\n");

print("</table>\n");
print("</td></tr>\n");
print("</table>\n<p>\n");

print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/dude-icon.png\"></td><td><font size=\"+1\" class=\"yellow-text\">GNOME Dudes</a></td></tr>\n");
print("<tr><td>&nbsp;</td><td>");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.astrolinux.com\">Aldug's Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.whitecape.org\">Dekars's Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.hadess.net\">Hadess's Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://jimmac.musichall.cz\">Jimmac's Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.linuxart.com\">Garrett's Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://tigert.gimp.org\">Tigert's Homepage</a></td></tr>\n");
print("</table>\n");
print("</td></tr>\n");
print("</table>\n<p>\n");

print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/art-icon.png\"></td><td><font size=\"+1\" class=\"yellow-text\">Theme Links</a></td></tr>\n");
print("<tr><td>&nbsp;</td><td>");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.themedepot.org\">Theme Depot</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://themes.freshmeat.net\">Themes.org</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://galeon.sourceforge.net/themes/themes.php\">Galeon Themes</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://gnomeicu.sourceforge.net/iconthemes.php\">Gnomeicu Themes</a></td></tr>\n");


print("</table>\n");
print("</td></tr>\n");
print("</table>\n<p>\n");

print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/www-icon.png\"></td><td><font size=\"+1\" class=\"yellow-text\">Other Links</a></td></tr>\n");
print("<tr><td>&nbsp;</td><td>");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gimp.org/\">The Gimp</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gnome.org/projects/nautilus/\">Nautilus Homepage</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://g-scripts.sourceforge.net\">Nautilus Script Collection</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.gkrellm.net\">GKrellM</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"http://www.xmms.org\">X Multimedia System (XMMS)</a></td></tr>\n");
print("</table>\n");
print("</td></tr>\n");
print("</table>\n<p>\n");


create_middle_box_bottom();
ago_footer();
?>
