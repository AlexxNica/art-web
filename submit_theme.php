<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("themes");

print("<form action=\"$PHP_SELF\" method=\"post\">\n");
print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\"></td></tr>\n");
print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"gdm_greeter\">gdm_greeter<option value=\"gtk\">gtk<option value=\"gtk2\">gtk2<option value=\"metacity\">metacity<option value=\"metatheme\">metatheme<option value=\"nautilus\">nautilus<option value=\"sawfish\">sawfish<option value=\"sounds\">sounds<option value=\"splash_screens\">splash_screens</select></td></tr>\n");
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
