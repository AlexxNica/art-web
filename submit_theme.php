<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobal stuff

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

$theme_name = $_POST["theme_name"];
$category = $_POST["category"];
$theme_author = $_POST["theme_author"];
$author_email = $_POST["author_email"];
$theme_url = $_POST["theme_url"];
$theme_description = $_POST["theme_description"];

ago_header("Theme Submission");
create_middle_box_top("themes");

if($_POST)
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
	print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"\">Choose<option value=\"gtk2\">Applications (gtk+)<option value=\"icon\">Icon<option value=\"gdm_greeter\">Login Manager (gdm)<option value=\"splash_screens\">Splash Screens<option value=\"metacity\">Window Borders (metacity)</select></td></tr>\n");
	print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>URL of Theme:</b></td><td><input type=\"text\" name=\"theme_url\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"theme_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<input type=\"submit\" value=\"Submit Theme\">\n"); 
	print("</form>\n");
}

create_middle_box_bottom();
ago_footer();

?>
