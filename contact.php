<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");

create_middle_box_top("contact");
print("The following people (in alphabetical order) are responsible for the layout and the contents:<p>\n");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:roman_n0spam@gnome.org\">Roman Beigelbeck</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:aldug_n0spam@gnome.org\">Alex Duggan</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:drfickle_n0spam@k-lug.org\">Steve Fox</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:thoma_n0spams@sunshineinabag.co.uk\">Thomas Wood</a></td></tr>\n");
print("</table>\n");

print("<p>\nYou can also catch us on the channel <span class=\"yellow-text\">#gnome-art</span> on <span class=\"yellow-text\">irc.gimp.org</span>.\n");
print("There is also a discussion board for art.gnome.org relevant stuff at <a class=\"screenshot\" href=\"http://gnomesupport.org/forums/index.php?c=6\"><b>http://gnomesupport.org/forums/index.php?c=6</b></a>.");

create_middle_box_bottom();
include("footer.inc.php");
?>
