<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");

create_middle_box_top("submit");
print("If you would like to submit your background or theme to art.gnome.org, please use the links below.  If you are submitting a background, please make sure that it is a GNOME related background.");
print("<p><ul>\n<li><a class=\"screenshot\" href=\"submit_background.php\">Background submission form</a>\n<li><a class=\"screenshot\" href=\"submit_theme.php\">Theme submission form</a>\n</ul>\n.");
create_middle_box_bottom();
include("footer.inc.php");
?>
