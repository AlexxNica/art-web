<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");
create_middle_box_top("themes");

if($HTTP_POST_VARS)
{
	if($theme_name && $category && $theme_author && $author_email && $theme_url && $theme_description)
   {
   	$incoming_theme_insert_query  = "INSERT INTO incoming_theme(themeID,status,theme_name,category,author,author_email,theme_url,theme_description) ";
   	$incoming_theme_insert_query .= "VALUES('','new','$theme_name','$category','$theme_author','$author_email','$theme_url','$theme_description')";
   	$incoming_theme_insert_result = mysql_query("$incoming_theme_insert_query");
      if(mysql_affected_rows()==1)
      {
      	print("Thank you, your theme will be considered for inclusion in art.gnome.org");
      }
      else
      {
      	print("There were form submission errors, please try again.");
      }
   }
   else
   {
   	print("Error, you must fill out all of the previous form fields, please go back and try again.");
   }
	print("<p>\n");
}
else
{
	print("If you would like to submit your theme to art.gnome.org, please fill out the form below and provide a web address where we can download your theme.\n<p>\n");
	print("<form action=\"$PHP_SELF\" method=\"post\">\n");
	print("<table border=\"0\">");
	print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"gdm_greeter\">GDM Greeter<option value=\"gtk\">GTK+ 1.2<option value=\"gtk2\">GTK+ 2.0<option value=\"metacity\">Metacity<option value=\"metatheme\">Metatheme<option value=\"nautilus\">Nautilus<option value=\"sawfish\">Sawfish<option value=\"sounds\">Sounds<option value=\"splash_screens\">Splash Screens</select></td></tr>\n");
	print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>URL of Theme:</b></td><td><input type=\"text\" name=\"theme_url\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"theme_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<input type=\"submit\" value=\"Submit Theme\">\n"); 
	print("</form>\n");
}
create_middle_box_bottom();
include("footer.inc.php");

?>
