<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a Theme");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

// write the updated background text do the database
if($action == "write")
{
	if($theme_category && $theme_name && $theme_author && $category && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename )
	{
		$date = $year . "-" . $month . "-" . $day;
		$theme_update_query = "UPDATE theme SET category='$theme_category', theme_name='$theme_name', author='$theme_author', author_email='$author_email', release_date='$date', description='$description', thumbnail_filename='$thumbnail_filename', small_thumbnail_filename='$small_thumbnail_filename', download_filename='$download_filename'";
		if($update_timestamp_toggle == "on")
		{
			$new_timestamp = time();
			 $theme_update_query .= ", add_timestamp='$new_timestamp'";
		}
		$theme_update_query .= " WHERE themeID='$themeID'";
		$theme_update_result = mysql_query($theme_update_query);

		print("Successfully edited theme text in database.");
		print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
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
	$theme_select_result = mysql_query("SELECT category,theme_name,userID,release_date,description,thumbnail_filename,small_thumbnail_filename,download_filename FROM theme WHERE themeID='$themeID'");
	if(mysql_num_rows($theme_select_result)==0)
	{
		print("Error, Invalid themeID.");
	}
	else
	{
		list($theme_category,$theme_name,$userID,$release_date,$description,$thumbnail_filename,$small_thumbnail_filename,$download_filename) = mysql_fetch_row($theme_select_result);
		
		$theme_category = htmlspecialchars($theme_category);
		$theme_name = htmlspecialchars($theme_name);
		$userID = htmlspecialchars($userID);
		$description = htmlspecialchars($description);
		$thumbnail_filename = htmlspecialchars($thumbnail_filename);
		$small_thumbnail_filename = htmlspecialchars($small_thumbnail_filename);
		$download_filename = htmlspecialchars($download_filename);
	
		
		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
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
		print("<tr><td><b>UserID:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\" value=\"$userID\"></td></tr>\n");
		print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
		print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
		print("<tr><td><b>Thumbnail Filename:</b></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\"></td></tr>\n");
		print("<tr><td><b>Small Thumbnail Filename:</b></td><td><input type=\"text\" name=\"small_thumbnail_filename\" size=\"40\" value=\"$small_thumbnail_filename\"></td></tr>\n");
		print("<tr><td><b>Download Filename:</b></td><td><input type=\"text\" name=\"download_filename\" size=\"40\" value=\"$download_filename\"></td></tr>\n");
		print("</table>\n<p>\n");
		print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
		print("<input type=\"hidden\" name=\"themeID\" value=\"$themeID\">\n");
		print("<p>\n");
		
		print("<p>\n<input type=\"checkbox\" name=\"update_timestamp_toggle\">Update Timestamp");
		print("<p>\n<input type=\"submit\" value=\"Update Theme\">");
		print("</form>");
 	}
}
elseif (isset($category))
{
		$theme_select_result = mysql_query("SELECT themeID, theme_name FROM theme WHERE category='$category'  ORDER BY themeID");
		print("<b>$category</b><br />");
		if(mysql_num_rows($theme_select_result)==0)
		{
			print("None\n");
		}
		else
		{
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n");
			print("<select name=\"themeID\" size=\"24\">\n");
			while(list($themeID,$theme_name) = mysql_fetch_row($theme_select_result))
			{
				print("<option value=\"$themeID\">$themeID: $theme_name\n");
			}
			print("</select><br /><input type=\"submit\" value=\"Edit\">");
			print("<input type=\"hidden\" name=\"action\" value=\"edit\"></form>\n");
		}
		print("</table>\n<p>\n");
}
else
{
	$theme_categories = array("gdm_greeter","gtk","gtk2","icon","metacity","metatheme","nautilus","sawfish","sounds","splash_screens","other");
	print("<b>Category</b>");
	print("<ul>");
	for($count=0;$count<count($theme_categories);$count++)
	{
		$category = $theme_categories[$count];
		print("<li><a href=\"{$_SERVER['PHP_SELF']}?category=$category\">$category</a></li>");
	}
	print("</ul>");
}
admin_footer();
?>
