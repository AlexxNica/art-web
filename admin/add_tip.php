<?php
require("mysql.inc.php");
require("includes/headers.inc.php");
require("common.inc.php");

admin_header("Add a new Tip &amp; Tricks");
admin_auth(2);

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

print("<div align=\"center\">");
print("<font size=\"+2\">Add a Tip &amp Trick</font>\n<p>\n");
if($HTTP_POST_VARS)
{
	if( $title && $body )
   {
   	$tip_insert_result = mysql_query("INSERT INTO tip(tipID,type,title,body) VALUES('','$normal','$title','$body')");
      if($tip_insert_result)
      {	
      	print("Successfully added tip to database.");
         print("<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to add another.");
      }
      else
      {
      	print("Error updating db, make sure magic quotes are turned on.");
      }
   }
   else
  	{
   	print("Error, all of the form fields are not filled in.");
   }
}
else
{
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
   print("<p>Title:<br>\n");
   print("<input type=\"text\" name=\"title\" size=\"60\">\n");
   print("<p>Text:<br>\n");
   print("<textarea name=\"body\" cols=\"60\" rows=\"16\"></textarea>\n");
   print("<p><input type=\"submit\" value=\"Add Tip\">\n");
   print("</form>\n");
}
print("</div>");
?>
