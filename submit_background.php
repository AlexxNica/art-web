<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");
create_middle_box_top("backgrounds");

if($HTTP_POST_VARS)
{
	if($background_name && $category && $background_author && $author_email && $background_url && $background_description)
   {
   	$incoming_background_insert_query  = "INSERT INTO incoming_background(backgroundID,background_name,category,author,author_email,background_url,background_description) ";
   	$incoming_background_insert_query .= "VALUES('','$background_name','$category','$background_author','$author_email','$background_url','$background_description')";
   	$incoming_background_insert_result = mysql_query("$incoming_background_insert_query");
      if(mysql_affected_rows()==1)
      {
      	print("Thank you, your background will be considered for inclusion in art.gnome.org");
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
}
create_middle_box_bottom();
include("footer.inc.php");

?>
