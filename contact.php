<?php

require("mysql.inc.php");
require("includes/common.inc.php");
require("includes/ago_headers.inc.php");

ago_header("Contact");

create_title("Contact Information", "");

print("<p>If you have queries regarding the site content or layout, please contact us by e-mail through <a href=\"mailto:thos _AT_ gnome.org\">thos _AT_ gnome.org</a>.</p>");

print("<p>\nYou can also catch us on the channel <a href=\"irc://irc.gnome.org/#gnome-art\">#gnome-art</a> on <span class=\"bold-text\">irc.gimp.org</span>.\n");
print("<p>To report bugs or request enhancements for art.gnome.org, please put them in the <a class=\"bold-link\" href=\"http://bugzilla.gnome.org/enter_bug.cgi?product=website&component=art\">GNOME bugzilla</a> (art component of website)");
print("<p>There is also a discussion board for art.gnome.org relevant stuff at <a class=\"bold-link\" href=\"http://gnomesupport.org/forums/index.php?c=6\"><b>http://gnomesupport.org/forums/index.php?c=6</b></a>.");
print("<p>A mailing list for GNOME themes and related topics is available at <a class=\"bold-link\" href=\"http://mail.gnome.org/mailman/listinfo/gnome-themes-list\"><b>http://mail.gnome.org/mailman/listinfo/gnome-themes-list</b></a>.");

print("<p>\nIf you would like to submit a background or theme to art.gnome.org, please use the <a class=\"bold-link\" href=\"submit_background.php\">background submission</a> or <a class=\"bold-link\" href=\"submit_theme.php\">theme submission</a> forms.");

ago_footer();

?>
