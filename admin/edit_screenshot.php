<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a screenshot");
admin_auth(2);

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

// write the updated background text do the database
if($action == "write")
{
	if($name && $category && $userID && $description && $thumbnail_filename && $download_filename)
	{
		$date = $year . "-" . $month . "-" . $day;
		$update_query = "UPDATE screenshot SET name='$name', category='$category', userID='$userID', description='$description', thumbnail_filename='$thumbnail_filename', download_filename='$download_filename'";
		if($update_timestamp_toggle == "on")
		{
			$new_timestamp = time();
			$update_query .= ", add_timestamp='$new_timestamp'";
		}
		$update_query .= " WHERE screenshotID='$ID'";
		$update_result = mysql_query($update_query);

		if (!$update_result)
			print("<p class=\"error\">There was an error updating the screenshot.</p>");
		else
			print("<p class=\"info\">Successfully edited screenshot.</p>");
		print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.</p>");
		}
	else
	{
		print("Error, all of the form fields are not filled in.");
	}
}
// display the background text fields for editing
elseif($action == "edit")
{
	$select_result = mysql_query("SELECT * FROM screenshot WHERE screenshotID='$ID'");
	if(mysql_num_rows($select_result)==0)
	{
		print("Error, Invalid screenshotID.");
	}
	else
	{
		$screenshot_array = mysql_fetch_array($select_result);
		foreach ($screenshot_array as $key => $value) $screenshot_array[$key] = htmlspecialchars($value);
		extract($screenshot_array);

		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><strong><label for=\"name\">Theme Name</label>:</strong></td><td><input type=\"text\" name=\"name\" id=\"name\" size=\"40\" value=\"$name\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"category\">Theme Category/Contest</label>:</strong></td><td><select name=\"category\" id=\"category\">\n");
		foreach ($screenshot_config_array as $loop_category => $value_category)
		{
			if($loop_category == $category)
				$selected = " selected";
			else
				$selected = "";
			print("<option value=\"$loop_category\"$selected>".$value_category['name']."</option>\n");
		}
		print("</select></td></tr>\n");

		print("<tr><td><strong><label for=\"userID\">UserID</label>:</strong></td><td><input type=\"text\" name=\"userID\" id=\"userID\" value=\"$userID\"/></td></tr>\n");
		print("<tr><td><strong><label for=\"description\">Description</label>:</strong></td><td><textarea name=\"description\" id=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
		print("<tr><td><strong><label for=\"thumbnail_filename\">Thumbnail Filename</label>:</strong></td><td><input type=\"text\" name=\"thumbnail_filename\" id=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"download_filename\">Download Filename</label>:</strong></td><td><input type=\"text\" name=\"download_filename\" id=\"download_filename\" size=\"40\" value=\"$download_filename\" /></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"update_timestamp_toggle\" />Update Timestamp");
		print("<input type=\"hidden\" name=\"action\" value=\"write\" />\n");
		print("<input type=\"hidden\" name=\"ID\" value=\"$ID\" />\n");
		print("</td></tr>\n");
		print("<tr><td><input type=\"submit\" value=\"Update Screenshot\" /></td></tr>\n");
		print("</table>\n");
		print("</form>\n");
 	}
}
elseif (isset($category))
{
		$select_result = mysql_query("SELECT screenshotID, name FROM screenshot WHERE category='$category'  ORDER BY screenshotID");
		print("<strong><label for=\"$category\">$category</label></strong><br />");
		if(mysql_num_rows($select_result)==0)
		{
			print("None\n");
		}
		else
		{
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>\n");
			print("<select name=\"ID\" size=\"24\" id=\"$category\">\n");
			while(list($ID,$name) = mysql_fetch_row($select_result))
			{
				print("<option value=\"$ID\">$ID: ".html_parse_text($name)."</option>\n");
			}
			print("</select><br /><input type=\"submit\" value=\"Edit\" />");
			print("<input type=\"hidden\" name=\"action\" value=\"edit\" /></div></form>\n");
		}
		print("\n");
}
else
{
	print("<strong>Category</strong>");
	print("<ul>");
	foreach ($screenshot_config_array as $category => $value)
	{
		print("<li><a href=\"{$_SERVER['PHP_SELF']}?category=$category\">$category</a></li>");
	}
	print("</ul>");
	
	print("<strong>Edit ThemeID</strong>\n");
	print("<form action=\"\" method=\"get\"><p>\n");
	print("<label for=\"ID\">ThemeID</label>: <input type=\"text\" name=\"ID\" id=\"ID\" />\n");
	print("<input type=\"hidden\" name=\"action\" value=\"edit\" />\n");
	print("<input type=\"submit\" value=\"Edit\" />\n");
	print("</p></form>");
}
admin_footer();
?>
