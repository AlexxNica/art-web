<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("themes");
print("If you would like to submit your theme to art.gnome.org, please fill out the form below and provide a web address where we can download your theme.\n<p>\n");

print("<form action=\"$PHP_SELF\" method=\"post\">\n");
print("<table border=\"0\">");
print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"gdm_greeter\">GDM Greeter<option value=\"gtk\">GTK+ 1.2<option value=\"gtk2\">GTK+ 2.0<option value=\"metacity\">Metacity<option value=\"metatheme\">Metatheme<option value=\"nautilus\">Nautilus<option value=\"sawfish\">Sawfish<option value=\"sounds\">Sounds<option value=\"splash_screens\">Splash Screens</select></td></tr>\n");
print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
print("<tr><td><b>URL of Theme:</b></td><td><input type=\"text\" name=\"theme_url\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
print("</table>\n<p>\n");
print("<input type=\"submit\" value=\"Submit Theme\">\n"); 
print("</form>\n");

create_middle_box_bottom();
include("footer.inc.php");

?>
