<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("Contact Information");
create_middle_box_top("contact");

print("The following people (in alphabetical order) are responsible for the layout and the contents:<p>\n");
print("<table border=\"0\">\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:roman _AT_ gnome.org\">Roman Beigelbeck</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:aldug _AT_ gnome.org\">Alex Duggan</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:ajgenius _AT_ ajgenius.us\">Andrew Johnson</a></td></tr>\n");
print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a class=\"screenshot\" href=\"mailto:thos _AT_ thos.me.uk\">Thomas Wood</a></td></tr>\n");
print("</table>\n");

print("<p>\nYou can also catch us on the channel <span class=\"yellow-text\">#gnome-art</span> on <span class=\"yellow-text\">irc.gimp.org</span>.\n");
print("<p>To report bugs or request enhancements for art.gnome.org, please put them in the <a class=\"screenshot\" href=\"http://bugzilla.gnome.org/enter_bug.cgi?product=website&component=art\">GNOME bugzilla</a> (art component of website)");
print("<p>There is also a discussion board for art.gnome.org relevant stuff at <a class=\"screenshot\" href=\"http://gnomesupport.org/forums/index.php?c=6\"><b>http://gnomesupport.org/forums/index.php?c=6</b></a>.");

print("<p>\nIf you would like to submit a background or theme to art.gnome.org, please use the <a class=\"screenshot\" href=\"submit_background.php\">background submission</a> or <a class=\"screenshot\" href=\"submit_theme.php\">theme submission</a> forms.");

create_middle_box_bottom();
ago_footer();

?>
