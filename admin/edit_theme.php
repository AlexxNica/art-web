<?php
require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

print("<html>\n<head><title>Edit a Theme</title></head>\n<body>\n");
print("<div align=\"center\">");
print("<font size=\"+2\">Edit a Theme</font>\n<p>\n");
print("</div>\n");

// write the updated background text do the database
if($action == "write")
{
	if($theme_category && $theme_name && $theme_author && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename )
   {
   	$date = $year . "-" . $month . "-" . $day;
      $theme_update_query  = "UPDATE theme SET category='$theme_category', theme_name='$theme_name', author='$theme_author', author_email='$author_email', release_date='$date', description='$description', thumbnail_filename='$thumbnail_filename', small_thumbnail_filename='$small_thumbnail_filename'";
      if($update_timestamp_toggle == "on")
      {
      	$new_timestamp = time();
         $theme_update_query .= ", add_timestamp='$new_timestamp'";
      }
      $theme_update_query .= " WHERE themeID='$themeID'";
   	//print($theme_update_query);
      $theme_update_result = mysql_query($theme_update_query);
     	$theme_downloadIDs = array_keys($theme_download_name);
     	for($count=0;$count<count($theme_downloadIDs);$count++)
      {
      	$theme_downloadID = $theme_downloadIDs[$count];
         $theme_download_update_query = "UPDATE theme_download SET name='".$theme_download_name[$theme_downloadID]."', download_name='".$theme_download_download_name[$theme_downloadID]."WHERE theme_downloadID='$theme_downloadID'";
         //print("$theme_download_update_query\n<p>\n");
         $theme_download_update_result = mysql_query($theme_download_update_query);
      }
      print("Successfully edited theme text in database.");
      print("<p>\n<a href=\"$PHP_SELF\">Click Here</a> to edit another.");
     	}
   else
   {
   	print("Error, all of the form fields are not filled in.");
   }
}
// display the background text fields for editing
elseif($action == "edit")
{
	$theme_categories = array("gdm_greeter","gtk","gtk2","icon","metacity","metatheme","nautilus","sawfish","sounds","splash_screens","other");
   $theme_select_result = mysql_query("SELECT category,theme_name,author,author_email,release_date,description,thumbnail_filename,small_thumbnail_filename FROM theme WHERE themeID='$themeID'");
   if(mysql_num_rows($theme_select_result)==0)
   {
   	print("Error, Invalid themeID.");
   }
   else
   {
   	list($theme_category,$theme_name,$author,$author_email,$release_date,$description,$thumbnail_filename,$small_thumbnail_filename) = mysql_fetch_row($theme_select_result);
		
      $theme_category = htmlspecialchars($theme_category);
      $theme_name = htmlspecialchars($theme_name);
      $author = htmlspecialchars($author);
      $author_email = htmlspecialchars($author_email);
      $description = htmlspecialchars($description);
      $thumbnail_filename = htmlspecialchars($thumbnail_filename);
      $small_thumbnail_filename = htmlspecialchars($small_thumbnail_filename);
  
      
      list($year,$month,$day) = explode("-",$release_date);
      print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   	print("<table border=\"0\">\n");
   	print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\" value=\"$theme_name\"></td></tr>\n");
   	print("<tr><td><b>Theme Category:</b></td><td><select name=\"theme_category\">\n");
      for($count=0;$count<count($theme_categories);$count++)
      {
      	$loop_theme_category = $theme_categories[$count];
         if($loop_theme_category == $theme_category)
         {
         	$selected = " selected";
         }
         else
         {
         	$selected = "";
         }
         print("<option value=\"$loop_theme_category\"$selected>$loop_theme_category\n");
      }
      print("</select></td></tr>\n");
      print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\" value=\"$author\"></td></tr>\n");
   	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\" value=\"$author_email\"></td></tr>\n");
   	print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
   	print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
		print("<tr><td><b>Thumbnail Filename:</b></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\"></td></tr>\n");
		print("<tr><td><b>Small Thumbnail Filename:</b></td><td><input type=\"text\" name=\"small_thumbnail_filename\" size=\"40\" value=\"$small_thumbnail_filename\"></td></tr>\n");
		print("</table>\n<p>\n");
      print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
      print("<input type=\"hidden\" name=\"themeID\" value=\"$themeID\">\n");
      print("<p>\n");
      
      $theme_download_select_result = mysql_query("SELECT theme_downloadID,name,download_name FROM theme_download WHERE themeID='$themeID'");
      if(mysql_num_rows($theme_download_select_result)==0)
      {
      	print("No downloads for this theme.");
      }
      else
      {
      	print("<table border=\"0\">\n");
         print("<tr><td><b>Name</b></td><td><b>Filename</b></td><td><b>Size</b></td></tr>\n");
         while(list($theme_downloadID,$theme_download_name,$theme_download_download_name)=mysql_fetch_row($theme_download_select_result))
         {
         	$theme_download_name = htmlspecialchars($theme_download_name);
            $theme_download_download_name = htmlspecialchars($theme_download_download_name);
            
            print("<tr><td><input type=\"text\" name=\"theme_download_name[$theme_downloadID]\" value=\"$theme_download_name\" size=\"40\"></td><td><input type=\"text\" name=\"theme_download_download_name[$theme_downloadID]\" value=\"$theme_download_download_name\" size=\"40\"></td><td></td></tr>\n");
         }
         print("</table>");
      }
      print("<p>\n<input type=\"checkbox\" name=\"update_timestamp_toggle\">Update Timestamp");
      print("<p>\n<input type=\"submit\" value=\"Update Theme\">");
      print("</form>");
 	}
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
         print("</select></td><td><input type=\"submit\" value=\"Edit\"></td></tr>");
         print("<input type=\"hidden\" name=\"action\" value=\"edit\">\n</form>\n");
      }
      print("</table>\n<p>\n");
   }
}

print("</body>\n</html>\n");
?>
