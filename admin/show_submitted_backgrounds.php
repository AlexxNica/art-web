<?php
require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

print("<html>\n<head><title>Show Submitted Backgrounds</title></head>\n<body>\n");

if($mark_background)
{
	$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$mark_background'");
   print("Successfully marked background as $new_status.<p><a href=\"$PHP_SELF\">Click here</a> to return to incoming backgrounds list.");
}
else
{
   $incoming_background_select_result = mysql_query("SELECT * FROM incoming_background WHERE status='new'");
   if(mysql_num_rows($incoming_background_select_result)==0)
   {
	   print("There are no background submissions.");
   }
   else
   {
	   print("<table border=\"1\">");
      print("<tr><td><b>ID</b></td><td><b>Category</b></td><td><b>Background Name</b></td><td><b>Author</b></td><td><b>Download</b></td><td><b>Screenshot</b></td><td><b>Description</b></td><td><b>Status</b></td></tr>\n");
	   while($incoming_background_select_row = mysql_fetch_array($incoming_background_select_result))
      {
   	   $backgroundID = $incoming_background_select_row["backgroundID"];
   	   $background_name = $incoming_background_select_row["background_name"];
   	   $category = $incoming_background_select_row["category"];
   	   $author = $incoming_background_select_row["author"];
   	   $author_email = $incoming_background_select_row["author_email"];
   	   $background_url = $incoming_background_select_row["background_url"];
   	   $background_screenshot_url = $incoming_background_select_row["background_screenshot_url"];
         $background_description = $incoming_background_select_row["background_description"];
   	   if($background_screenshot_url != "")
         {
         	$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
         }
         print("<tr><td>$backgroundID</td><td>$category</td><td>$background_name</td><td><a href=\"mailto:$author_email\">$author</a></td><td><a href=\"$background_url\">Download</td><td>$screenshot_link</td><td>$background_description</td><td><form action=\"$PHP_SELF\" method=\"post\"><select name=\"new_status\"><option value=\"new\">New<option value=\"added\">Added<option value=\"rejected\">Rejected</select><input type=\"submit\" value=\"Change\"><input type=\"hidden\" name=\"mark_background\" value=\"$backgroundID\"></form></td></tr>\n");
	   }
      print("</table>\n");
   }
}
print("</body>\n</html>\n");
?>
