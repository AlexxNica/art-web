<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a Theme");
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
	if($theme_name && $theme_category && $userID && $license && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename && $download_filename)
	{
		$date = $year . "-" . $month . "-" . $day;
		$theme_update_query = "UPDATE theme SET theme_name='$theme_name', category='$theme_category', userID='$userID', license='$license', version='$version', parent='$parentID', release_date='$date', description='$description', thumbnail_filename='$thumbnail_filename', small_thumbnail_filename='$small_thumbnail_filename', download_filename='$download_filename'";
		if($update_timestamp_toggle == "on")
		{
			$new_timestamp = time();
			$theme_update_query .= ", add_timestamp='$new_timestamp'";
		}
		$theme_update_query .= " WHERE themeID='$themeID'";
		$theme_update_result = mysql_query($theme_update_query);

		print("<p class=\"info\">Successfully edited theme text in database.</p>");
		print("<table><tr><td>theme_name</td><td>'$theme_name'</td></tr><tr><td>category</td><td>'$theme_category'</td></tr><tr><td>userID</td><td>'$userID'</td></tr><tr><td>license</td><td>'$license'</td></tr><tr><td>parent</td><td>'$parentID'</td></tr><tr><td>release_date</td><td>'$date'</td></tr><tr><td>description</td><td>'$description'</td></tr><tr><td>thumbnail_filename</td><td>'$thumbnail_filename'</td></tr><tr><td>small_thumbnail_filename</td><td>'$small_thumbnail_filename'</td></tr><tr><td>download_filename</td><td>'$download_filename'</table>");
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
	$theme_categories = array("gdm_greeter","gtk2","icon","metacity","splash_screens");
	$theme_select_result = mysql_query("SELECT * FROM theme WHERE themeID='$themeID'");
	if(mysql_num_rows($theme_select_result)==0)
	{
		print("Error, Invalid themeID.");
	}
	else
	{
		$theme_array = mysql_fetch_array($theme_select_result);
		foreach ($theme_array as $key => $value) $theme_array[$key] = htmlspecialchars($value);
		extract($theme_array);

		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\" value=\"$theme_name\"></td></tr>\n");
		print("<tr><td><b>Theme Category:</b></td><td><select name=\"theme_category\">\n");
		for($count=0;$count<count($theme_categories);$count++)
		{
			$loop_theme_category = $theme_categories[$count];
			if($loop_theme_category == $category)
				$selected = " selected";
			else
				$selected = "";
			print("<option value=\"$loop_theme_category\"$selected>$loop_theme_category\n");
		}
		print("</select></td></tr>\n");
		$user_select = mysql_query("SELECT userID,username FROM user");
		while (list($uid, $uname) = mysql_fetch_row($user_select)) $user_array[$uid] = $uname;
		print("<tr><td><b>UserID:</b></td><td>");print_select_box("userID", $user_array, $userID);print("</td></tr>\n");
		print("<tr><td><b>License</b></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
		print("<tr><td><b>Version</b></td><td><input type=\"text\" name=\"version\" value=\"$version\"></td></tr>\n");
		print("<tr><td><b>Variation of </b></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>");

		$theme_var_select_result = mysql_query("SELECT themeID,theme_name,category FROM theme WHERE userID=$userID AND parent=0 ORDER BY category");
		while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($theme_var_select_result))
		{
			if ($var_themeID == $parent)
				$selected = "selected=\"true\"";
			else
				$selected = "";
			print("<option $selected value=\"$var_themeID\">$var_theme_name ($var_category)</option>");
		}
		print("</td></tr>");

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
