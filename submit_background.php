<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("backgrounds");

print("If you would like to submit your background to art.gnome.org, please fill out the form below and provide a web address where we can download your background.\n<p>\n");
print("<form action=\"$PHP_SELF\" method=\"post\">\n");
print("<table border=\"0\">\n");
print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"gnome\">GNOME<option value=\"other\">Other</select></td></tr>\n");
print("<tr><td><b>Background Author:</b></td><td><input type=\"text\" name=\"background_author\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
print("<tr><td><b>URL of Background:</b></td><td><input type=\"text\" name=\"background_url\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
print("</table>\n<p>\n");
print("<input type=\"submit\" value=\"Submit Background\">\n");
print("</form>");

create_middle_box_bottom();
include("footer.inc.php");

?>
