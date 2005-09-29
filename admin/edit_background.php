<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");


// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

$background_category_list = array_keys($background_config_array);
array_shift ($resolution_array); /* Remove 'All' from resolution list */

if ($_GET)
	$category = validate_input_array_default($_GET["category"], $background_category_list, "");

admin_header("Edit a Background");
admin_auth(2);

if (array_key_exists('add_resolution', $_POST))
{
	$backgroundID = validate_input_regexp_default ($_POST['backgroundID'], '^[0-9]+$', -1);
	$type = validate_input_array_default ($_POST['new_res_type'], Array('jpg', 'png', 'svg'), '');
	$resolution = validate_input_array_default ($_POST['new_res_resolution'], $resolution_array, '');
	$filename = escape_string ($_POST['new_res_file']);
	if (mysql_num_rows(mysql_query("SELECT * FROM background_resolution WHERE backgroundID = $backgroundID AND type = '$type' AND resolution = '$resolution'")) > 0)
	{
		print('<p class="error">A '.$resolution.', '.$type.' resolution for '.$background_name.' already exists</p>');
	}
	else
	if ($backgroundID != '' and $type != '' and $resolution != '' and $filename != '')
		$result = mysql_query ("INSERT INTO background_resolution(backgroundID, type, resolution, filename) VALUES ('$backgroundID', '$type', '$resolution', '$filename')");
	
	if ($result)
		print('<p class="info">Added new resolution ('.$resolution.', '.$type.') to background '.$background_name.'</p>');
	else
		print('<p class="error">There was an error adding the new resolution</p>');
	
	print('<p><a href="/admin/edit_background.php?action=edit&backgroundID='.$backgroundID.'">Continue editing </a>"'.$background_name.'"</p>');
	print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.</p>");
}
elseif($action == "write")
{
	/* write the updated background text do the database */
	if($background_name && $userID && $month && $day && $year && $background_description && $thumbnail_filename && $license && $resolution)
	{
		$date = $year . "-" . $month . "-" . $day;
		$background_update_query  = "UPDATE background SET background.background_name='$background_name', background.license='$license', background.version='$version', background.category='$category', background.userID='$userID', background.parent='$parentID', background.release_date='$date', background.background_description='$background_description', background.thumbnail_filename='$thumbnail_filename'";
		if ($update_timestamp_toggle == "on")
		{
			$background_update_query .= ", add_timestamp='".time()."'";
		}
		$background_update_query .= " WHERE background.backgroundID='$backgroundID'";
		if(!$background_update_result = mysql_query($background_update_query)) {
			$error = 1;
		}
		
		$i=0;
		while($resID = $background_resolutionID[$i]) {
			$background_res_update_query[$i] = "UPDATE background_resolution SET resolution='{$resolution[$i]}',filename='{$filename[$i]}' WHERE background_resolutionID=$resID";
			if(!$background_res_update_result = mysql_query($background_res_update_query[$i])) {
			$error++;
			}
			
			$i++;
		}
		if(!$error)
		{
			print("Successfully edited background text in database.");
			print("<table>\n");
			print("<tr><td>background_name</td><td>'".html_parse_text($background_name)."'</td></tr>\n");
			print("<tr><td>license</td><td>'$license'</td></tr>\n");
			print("<tr><td>version</td><td>'$version'</td></tr>\n");
			print("<tr><td>parent</td><td>$parentID</td></tr>\n");
			print("<tr><td>category</td><td>'$category'</td></tr>\n");
			print("<tr><td>userID</td><td>'$userID'</td></tr>\n");
			print("<tr><td>release_date</td><td>'$date'</td></tr>\n");
			print("<tr><td>background_description</td><td>'$background_description'</td></tr>\n");
			print("<tr><td>thumbnail_filename</td><td>'$thumbnail_filename'</td></tr>\n");
			$i=0;
			while($resID = $background_resolution[$i]) {
				print("<tr><td>resolution ($resID) </td><td>$resolution[$i]</td></tr>");
				$i++;
			}
			print("</table>");
			print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.</p>");
			print('<p><a href="/admin/edit_background.php?action=edit&backgroundID='.$backgroundID.'">Continue editing</a> "'.$background_name.'"</p>');
		}
		else
		{
			print("<p class=\"warning\">There were $error error(s).</p>");
			print("<p class=\"info\">Query was:<br/>$background_update_query</p>");
			print("<tt>".mysql_error()."</tt>");
			foreach($background_res_update_query as $query) {
				print("<p class=\"info\">Query was:<br/>$query</p>");
			}
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
	$background_select_query = "SELECT * FROM background WHERE backgroundID='$backgroundID'";
	$background_select_result = mysql_query($background_select_query);
	if (mysql_num_rows($background_select_result)==0)
	{
		print("<p>Could not select background to be updated</p>");
		print("<tt>".mysql_error()."</tt>");
	}
	else
	{
		$background_array = mysql_fetch_array($background_select_result);
		foreach ($background_array as $key => $value) $background_array[$key] = htmlspecialchars($value);
		extract($background_array);

		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><strong><label for=\"background_name\">Background Name</label>:</strong></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\" id=\"background_name\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"category\">Category</label></strong></td><td>");print_select_box("category", array_combine($background_category_list, $background_category_list), $category);print("</td></tr>\n");
		$user_select = mysql_query("SELECT userID,username FROM user");
		while (list($uid, $uname) = mysql_fetch_row($user_select)) $user_array[$uid] = $uname;
		print("<tr><td><strong><label for=\"userID\">UserID:</label></strong></td><td>");print_select_box("userID", $user_array, $userID);print("</td></tr>\n");
		print("<tr><td><strong><label for=\"license\">License</label></strong></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
		print("<tr><td><strong><label for=\"version\">Version</label></strong></td><td><input type=\"text\" name=\"version\" id=\"version\" value=\"$version\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"variation\">Variation of </label></strong></td><td><select name=\"parentID\" id=\"variation\"><option value=\"0\">N/A</option>");

		$background_var_select_result = mysql_query("SELECT backgroundID,background_name,category FROM background WHERE userID=$userID AND parent=0 ORDER BY category");
		while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($background_var_select_result))
		{
			if ($var_themeID == $parent)
				$selected = "selected=\"true\"";
			else
				$selected = "";
			print("<option $selected value=\"$var_themeID\">".html_parse_text($var_theme_name)." ($var_category)</option>");
		}
		print("</select></td></tr>");

		print("<tr><td><strong><label for=\"month\">Release Date</label>:</strong></td><td><input type=\"text\" name=\"month\" id=\"month\" value=\"$month\" size=\"2\" maxlength=\"2\" />/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlength=\"2\" />/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlength=\"4\" /></td></tr>\n");
		print("<tr><td><strong><label for=\"background_description\">Background Description</label>:</strong></td><td><textarea name=\"background_description\" id=\"background_description\" cols=\"40\" rows=\"5\" wrap>$background_description</textarea></td></tr>\n");
		print("<tr><td><strong><label for=\"thumbnail_filename\">Thumbnail Filename</label>:</strong></td><td><input type=\"text\" name=\"thumbnail_filename\" id=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\" /></td></tr>\n");

		$background_resolution_result = mysql_query("SELECT background_resolutionID,resolution,filename FROM background_resolution WHERE backgroundID=$backgroundID");
		$i = 0;
		while($resolution_row = mysql_fetch_array($background_resolution_result)) {
			extract($resolution_row);
			print("<tr><td><strong><label for=\"resolution[$i]\">Resolution #$i</label>:</strong></td>");
			print("<td>");print_select_box("resolution[$i]", $resolution_array, $resolution);
			print("&nbsp;<input type=\"text\" name=\"filename[$i]\" size=\"40\" value=\"$filename\" />");
			print("<input type=\"hidden\" name=\"background_resolutionID[$i]\" value=\"$background_resolutionID\" /></td></tr>\n");
			$i++;
		}
		print('<tr><td><strong>New resolution</strong></td><td>');
		print_select_box("new_res_resolution", $resolution_array, '');
		print_select_box("new_res_type", Array("jpg" => "jpg", "png" => "png", "svg" => "svg"), '');
		print('<input type="text" name="new_res_file"/><input type="submit" name="add_resolution" value="Add Resolution" />');
		print("<tr><td><input type=\"checkbox\" name=\"update_timestamp_toggle\" />Update Timestamp");
		print("<tr><td><input type=\"submit\" value=\"Update Background\" />");
		print("<input type=\"hidden\" name=\"action\" value=\"write\" />\n");
		print("<input type=\"hidden\" name=\"backgroundID\" value=\"$backgroundID\" /></td></tr>\n");
		print("</td></tr></table>\n");
		print("</form>");
 	}
}
elseif($category != "")
{
	$background_select_result = mysql_query("SELECT backgroundID, background_name FROM background WHERE category='$category' $user_sql ORDER BY backgroundID");
	print(mysql_error());
	if(mysql_num_rows($background_select_result)==0)
	{
		print("$category: None\n");
	}
	else
	{
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<div><strong><label for=\"$category\">$category</label></strong><br /><select name=\"backgroundID\" id=\"$category\" size=\"24\">\n");
		while(list($backgroundID,$background_name) = mysql_fetch_row($background_select_result))
		{
			print("<option value=\"$backgroundID\">$backgroundID: ".html_parse_text($background_name)."</option>\n");
		}
		print("</select><br /><input type=\"submit\" value=\"Edit\" />");
		print("<input type=\"hidden\" name=\"action\" value=\"edit\" />\n</div></form>\n");
	}
}
else
{
	print("<strong>Category</strong>");
	print("<ul>");
	for($count=0;$count<count($background_category_list);$count++)
	{
		$category = $background_category_list[$count];
		print("<li><a href=\"{$_SERVER['PHP_SELF']}?category=$category\">$category</a></li>");
	}
	print("</ul>");
	print("<strong>Edit Background</strong>\n");
	print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"get\"><p>\n");
	print("<label for=\"backgroundID\">BackgroundID</label>: <input type=\"text\" name=\"backgroundID\" id=\"backgroundID\" />\n");
	print("<input type=\"hidden\" name=\"action\" value=\"edit\" />\n");
	print("<input type=\"submit\" value=\"edit\" />\n");
	print("</p></form>\n");
}

admin_footer();
?>
