<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Edit a Background");

// write the updated background text do the database
if($action == "write")
{
	if($background_name && $author && $month && $day && $year && $background_description && $thumbnail_filename )
   {
   	$date = $year . "-" . $month . "-" . $day;
      $background_update_query  = "UPDATE background SET background_name='$background_name', author='$author', author_email='$author_email', release_date='$date', background_description='$background_description', thumbnail_filename='$thumbnail_filename', screenshot_filename='$screenshot_filename', screenshot_description='$screenshot_description' WHERE backgroundID='$backgroundID'";
   	//print($background_update_query);
      
      $background_update_result = mysql_query($background_update_query);
      if(mysql_affected_rows() == 1)
      {
      	print("Successfully edited background text in database.");
         print("<p>\n<a href=\"$PHP_SELF\">Click Here</a> to edit another.");
      }
      else
      {
      	print("Database Error, unable to update database.  Contact Alex.");
      }
      
   }
   else
   {
   	print("Error, all of the form fields are not filled in.");
   }
}
// display the background text fields for editing
elseif($action == "edit")
{
	$background_select_result = mysql_query("SELECT background_name,author,author_email,release_date,background_description,thumbnail_filename,screenshot_filename,screenshot_description FROM background WHERE backgroundID='$backgroundID'");
   if(mysql_num_rows($background_select_result)==0)
   {
   	print("Error, Invalid backgroundID.");
   }
   else
   {
   	list($background_name,$author,$author_email,$release_date,$background_description,$thumbnail_filename,$screenshot_filename,$screenshot_description) = mysql_fetch_row($background_select_result);
		$background_name = htmlspecialchars($background_name);
      $author = htmlspecialchars($author);
      $author_email = htmlspecialchars($author_email);
      $background_description = htmlspecialchars($background_description);
      $thumbnail_filename = htmlspecialchars($thumbnail_filename);
      $screenshot_filename = htmlspecialchars($screenshot_filename);
      $screenshot_description = htmlspecialchars($screenshot_description);
      
      list($year,$month,$day) = explode("-",$release_date);
      print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   	print("<table border=\"0\">\n");
   	print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\"></td></tr>\n");
   	print("<tr><td><b>Background Author:</b></td><td><input type=\"text\" name=\"author\" size=\"40\" value=\"$author\"></td></tr>\n");
   	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\" value=\"$author_email\"></td></tr>\n");
   	print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
   	print("<tr><td><b>Background Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap>$background_description</textarea></td></tr>\n");
		print("<tr><td><b>Thumbnail Filename:</b></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\"></td></tr>\n");
		print("<tr><td><b>Screenshot Filename:</b></td><td><input type=\"text\" name=\"screenshot_filename\" size=\"40\" value=\"$screenshot_filename\"></td></tr>\n");
		print("<tr><td><b>Screenshot Description:</b></td><td><textarea name=\"screenshot_description\" cols=\"40\" rows=\"5\" wrap>$screenshot_description</textarea></td></tr>\n");
		print("</table>\n<p>\n");
      print("<input type=\"submit\" value=\"Update Background\">");
      print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
      print("<input type=\"hidden\" name=\"backgroundID\" value=\"$backgroundID\">\n");
      print("</form>");
 	}
}
else
{
	$background_categories = array("gnome","other");
   for($count=0;$count<count($background_categories);$count++)
   {
   	$category = $background_categories[$count];
      $background_select_result = mysql_query("SELECT backgroundID, background_name FROM background WHERE category='$category' ORDER by add_timestamp");
		if(mysql_num_rows($background_select_result)==0)
      {
      	print("<tr><td>$category</td><td colspan=\"2\">None</td></tr>\n");
      }
      else
      {
      	print("<form action=\"$PHP_SELF\" method=\"post\">\n");
         print("<table border=\"0\">\n");
         print("<tr><td>$category</td><td><select name=\"backgroundID\" size=\"10\">\n");
         while(list($backgroundID,$background_name) = mysql_fetch_row($background_select_result))
         {
         	print("<option value=\"$backgroundID\">$background_name\n");
         }
         print("</select></td><td><input type=\"submit\" value=\"Edit\"></td></tr>");
         print("</table>\n");
         print("<input type=\"hidden\" name=\"action\" value=\"edit\">\n</form>\n");
         print("<p>\n");
      }
	}
}

admin_footer();
?>
