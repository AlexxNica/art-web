<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a contest item");
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
	if($contest_name && $contest_category && $userID && $license && $month && $day && $year && $description && $small_thumbnail_filename && $download_filename)
	{
		$date = $year . "-" . $month . "-" . $day;
		$contest_update_query = "UPDATE contest SET name='$contest_name', contest='$contest_category', userID='$userID', license='$license', version='$version', parent='$parentID', release_date='$date', description='$description', thumbnail_filename='$thumbnail_filename', small_thumbnail_filename='$small_thumbnail_filename', download_filename='$download_filename'";
		if($update_timestamp_toggle == "on")
		{
			$new_timestamp = time();
			$contest_update_query .= ", add_timestamp='$new_timestamp'";
		}
		$contest_update_query .= " WHERE contestID='$contestID'";
		$contest_update_result = mysql_query($contest_update_query);

		print("<p class=\"info\">Successfully edited contest item text in database.</p>");
		print("<table><tr><td>contest_name</td><td>'".html_parse_text($contest_name)."'</td></tr><tr><td>category/contest</td><td>'$contest_category'</td></tr><tr><td>userID</td><td>'$userID'</td></tr><tr><td>license</td><td>'$license'</td></tr><tr><td>parent</td><td>'$parentID'</td></tr><tr><td>release_date</td><td>'$date'</td></tr><tr><td>description</td><td>'".html_parse_text($description)."'</td></tr><tr><td>thumbnail_filename</td><td>'$thumbnail_filename'</td></tr><tr><td>small_thumbnail_filename</td><td>'$small_thumbnail_filename'</td></tr><tr><td>download_filename</td><td>'$download_filename'</td></tr></table>");
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
	$contest_select_result = mysql_query("SELECT * FROM contest WHERE contestID='$contestID'");
	if(mysql_num_rows($contest_select_result)==0)
	{
		print("Error, Invalid contestID.");
	}
	else
	{
		$contest_array = mysql_fetch_array($contest_select_result);
		foreach ($contest_array as $key => $value) $contest_array[$key] = htmlspecialchars($value);
		extract($contest_array);

		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><strong><label for=\"contest_name\">Theme Name</label>:</strong></td><td><input type=\"text\" name=\"contest_name\" id=\"contest_name\" size=\"40\" value=\"$name\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"contest_category\">Theme Category/Contest</label>:</strong></td><td><select name=\"contest_category\" id=\"contest_category\">\n");
		foreach ($contest_config_array as $loop_contest_category => $value_categories)
		{
			if($loop_contest_category == $contest)
				$selected = " selected";
			else
				$selected = "";
			print("<option value=\"$loop_contest_category\"$selected>$loop_contest_category</option>\n");
		}
		print("</select></td></tr>\n");
		$user_select = mysql_query("SELECT userID,username FROM user");
		while (list($uid, $uname) = mysql_fetch_row($user_select)) $user_array[$uid] = $uname;
		print("<tr><td><strong><label for=\"userID\">UserID</label>:</strong></td><td>");print_select_box("userID", $user_array, $userID);print("</td></tr>\n");
		print("<tr><td><strong><label for=\"license\">License</label></strong></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
		print("<tr><td><strong><label for=\"version\">Version</label></strong></td><td><input type=\"text\" name=\"version\" id=\"version\" value=\"$version\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"variation\">Variation of</label> </strong></td><td><select name=\"parentID\" id=\"variation\"><option value=\"0\">N/A</option>");

		$contest_var_select_result = mysql_query("SELECT contestID,name,category FROM contest WHERE userID=$userID AND parent=0 ORDER BY category");
		while(list($var_contestID,$var_contest_name, $var_category)=mysql_fetch_row($contest_var_select_result))
		{
			if ($var_contestID == $parent)
				$selected = "selected=\"true\"";
			else
				$selected = "";
			print("<option $selected value=\"$var_contestID\">".html_parse_text($var_contest_name)." ($var_category)</option>");
		}
		print("</select></td></tr>");

		print("<tr><td><strong><label for=\"month\">Release Date</label>:</strong></td><td><input type=\"text\" id=\"month\" name=\"month\" value=\"$month\" size=\"2\" maxlength=\"2\" />/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlength=\"2\" />/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlength=\"4\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"description\">Description</label>:</strong></td><td><textarea name=\"description\" id=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
		print("<tr><td><strong><label for=\"thumbnail_filename\">Thumbnail Filename</label>:</strong></td><td><input type=\"text\" name=\"thumbnail_filename\" id=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"small_thumbnail_filename\">Small Thumbnail Filename</label>:</strong></td><td><input type=\"text\" name=\"small_thumbnail_filename\" id=\"small_thumbnail_filename\" size=\"40\" value=\"$small_thumbnail_filename\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"download_filename\">Download Filename</label>:</strong></td><td><input type=\"text\" name=\"download_filename\" id=\"download_filename\" size=\"40\" value=\"$download_filename\" /></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"update_timestamp_toggle\" />Update Timestamp");
		print("<input type=\"hidden\" name=\"action\" value=\"write\" />\n");
		print("<input type=\"hidden\" name=\"contestID\" value=\"$contestID\" />\n");
		print("</td></tr>\n");
		print("<tr><td><input type=\"submit\" value=\"Update Theme\" /></td></tr>\n");
		print("</table>\n");
		print("</form>\n");
 	}
}
elseif (isset($category))
{
		$contest_select_result = mysql_query("SELECT contestID, name FROM contest WHERE contest='$category'  ORDER BY contestID");
		print("<strong><label for=\"$category\">$category</label></strong><br />");
		if(mysql_num_rows($contest_select_result)==0)
		{
			print("None\n");
		}
		else
		{
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>\n");
			print("<select name=\"contestID\" size=\"24\" id=\"$category\">\n");
			while(list($contestID,$contest_name) = mysql_fetch_row($contest_select_result))
			{
				print("<option value=\"$contestID\">$contestID: ".html_parse_text($contest_name)."</option>\n");
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
	foreach ($contest_config_array as $category => $value)
	{
		print("<li><a href=\"{$_SERVER['PHP_SELF']}?category=$category\">$category</a></li>");
	}
	print("</ul>");
	
	print("<strong>Edit ThemeID</strong>\n");
	print("<form action=\"\" method=\"get\"><p>\n");
	print("<label for=\"ThemeID\">ThemeID</label>: <input type=\"text\" name=\"contestID\" id=\"contestID\" />\n");
	print("<input type=\"hidden\" name=\"action\" value=\"edit\" />\n");
	print("<input type=\"submit\" value=\"Edit\" />\n");
	print("</p></form>");
}
admin_footer();
?>
