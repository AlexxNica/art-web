<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("SUBMIT");
create_middle_box_top("submit");

print("If you would like to submit your background or theme to art.gnome.org, please use the links below.  If you are submitting a background, please make sure that it is a GNOME related background.");
print("<p><ul>\n<li><a class=\"screenshot\" href=\"submit_background.php\">Background submission form</a>\n<li><a class=\"screenshot\" href=\"submit_theme.php\">Theme submission form</a>\n</ul>\n");

create_middle_box_bottom();
ago_footer();
?>
