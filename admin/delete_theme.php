<?php
require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

print("<html>\n<head><title>Delete a Theme</title></head>\n<body>\n");
print("<div align=\"center\">");
print("<font size=\"+2\">Delete a Theme</font>\n<p>\n");
print("</div>\n");

// write the updated background text do the database
if($action == "delete")
{
	/* remove from theme database */
   $theme_delete_query = "DELETE FROM theme WHERE themeID='$themeID'";
   //print("$theme_delete_query\n");
   $theme_delete_result = mysql_query($theme_delete_query);
   
   /* remove from theme_download database */
   $theme_download_delete_query = "DELETE FROM theme_download WHERE themeID='$themeID'";
   //print("$theme_download_delete_query\n");
   $theme_download_delete_result = mysql_query($theme_download_delete_query);
   
   if($theme_delete_result && $theme_download_delete_result)
   {
   	print("Successfully deleted theme.");
   }
   else
   {
   	print("Error deleting theme.");
   }
   print("Click <a href=\"$PHP_SELF\">here</a> to return");
   

}
// display the confirmation window
elseif($action == "confirm")
{
	$theme_select_result = mysql_query("SELECT theme_name FROM theme WHERE themeID='$themeID'");
   list($theme_name) = mysql_fetch_row($theme_select_result);
   print("Are you sure you want to delete $theme_name (themeID: $themeID) from the database?");
   print("<p>\n");
   print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   print("<input type=\"submit\" value=\"Continue\">\n");
   print("<input type=\"hidden\" name=\"themeID\" value=\"$themeID\">\n");
   print("<input type=\"hidden\" name=\"action\" value=\"delete\">\n");
   print("</form>\n");
}
else
{
	$theme_categories = array("gdm_greeter","gtk","gtk2","icon","metacity","metatheme","nautilus","sawfish","sounds","splash_screens","other");
   for($count=0;$count<count($theme_categories);$count++)
   {
   	$category = $theme_categories[$count];
      $theme_select_result = mysql_query("SELECT themeID, theme_name FROM theme WHERE category='$category' ORDER by theme_name");
		print("<table border=\"0\">\n");
      print("<tr><td>$category</td>");
      if(mysql_num_rows($theme_select_result)==0)
      {
      	print("<td colspan=\"2\">None</td></tr>\n");
      }
      else
      {
      	print("<form action=\"$PHP_SELF\" method=\"post\">\n");
         print("<td><select name=\"themeID\" size=\"5\">\n");
         while(list($themeID,$theme_name) = mysql_fetch_row($theme_select_result))
         {
         	print("<option value=\"$themeID\">$theme_name\n");
         }
         print("</select></td><td><input type=\"submit\" value=\"Delete\"></td></tr>");
         print("<input type=\"hidden\" name=\"action\" value=\"confirm\">\n</form>\n");
      }
      print("</table>\n<p>\n");
   }
}

print("</body>\n</html>\n");
?>
