<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Submitted Themes");
$admin_level = admin_auth(1);

$theme_category_list = array_keys ($theme_config_array);
$theme_type_select_array = array_combine ($theme_category_list, $theme_category_list);

$mark_theme = $_POST['mark_theme'];
$new_status = validate_input_array_default($_POST["new_status"], array_keys($status_array), "");
$new_category_array = $_POST['category'];

$reject_array = Array("rejected|not_rel" => "Not relevent", "rejected|bad_url" => "Invalid URL", "rejected|distro" => "Distro Specific", "rejected|low_quality" => "Low Quality","rejected|copyright" => "Copyright","rejected|duplicate" => "Duplicate","rejected|badform" => "Badly Formed");
$new_status_array = array_merge($status_array,$reject_array);
unset($new_status_array["rejected"]);

if(is_array($mark_theme))
{
	foreach($mark_theme as $markID => $new_status)
	{
		$rej_arr = explode("rejected|",$new_status);
		if (!$new_status)
		{
			print("\t<p class=\"error\">Invalid Status for $markID</p>\n");
			print("\t<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming themes list.\n");
		}elseif (count($rej_arr) > 1)
		{
			$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='rejected', comment='{$rej_arr[1]}' WHERE themeID='$markID'");
			print("\t<p class=\"info\">Rejected theme $markID with \"{$reject_array[$new_status]}\".</p>\n");
		}elseif ($new_status != 'new' || $admin_level > 1)
		{
			$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='$new_status' WHERE themeID='$markID'");
			// Only print message if status has actually changed
			if (mysql_affected_rows())
				print("\t<p class=\"info\">Marked theme $markID as \"{$new_status}\".</p>\n");
		}

		/* update category of item */
		$new_category = validate_input_array_default ($new_category_array[$markID], $theme_category_list, '_error');
		if ($new_category != '_error')
		{
			mysql_query("UPDATE incoming_theme SET category='$new_category' WHERE themeID='$markID'");
			if (mysql_affected_rows())
				print("\t<p class=\"info\">Marked theme $markID category as &quot;$new_category&quot;.</p>\n");
		}


	}
	print("\t<p><a href=\"{$_SERVER["PHP_SELF"]}\">Return to incoming themes list</a></p>\n");
}
else
{

	if($theme_type)
	{
		$query_extra = "AND category='$theme_type'";
	}
	$query = "SELECT incoming_theme.*, user.username FROM incoming_theme,user WHERE (status='new' OR status='approved') $query_extra AND user.userID = incoming_theme.userID ORDER BY date ASC";
	$incoming_theme_select_result = mysql_query($query);
	print("\t<hr />\n");
	if(mysql_num_rows($incoming_theme_select_result)==0)
	{
		print("\tThere are no theme submissions.\n");
	}
	else
	{
		if ($admin_level > 1)
		{
			$approved_list_select = mysql_query("SELECT themeID, name, category, description FROM incoming_theme WHERE status='approved'");
			print("\t<form action=\"add_theme.php\" method=\"post\">\n");
			print("\t\tApproved items: <select name=\"submitID\">\n");
			while ($row = mysql_fetch_row($approved_list_select))
				print("\t\t\t<option value={$row[0]}>{$row[1]} ({$row[2]})</option>\n");
			print("\t\t</select><input type=\"submit\" value=\"Add\" />\n");
			print("\t</form>\n\t<hr/>\n");
		}

		print("\t<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n");
		print("\t\t<input type=\"submit\" value=\"Update\" />\n");
		print("\t\t<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\">\n");
		print("\t\t\t<tr>\n\t\t\t\t<th>ID</th>\n\t\t\t\t<th>Theme Name,Category</th>\n\t\t\t\t<th>Author, Date</th>\n\t\t\t\t<th>Description</th>\n\t\t\t\t<th>Download</th>\n\t\t\t\t<th>Action</th>\n\t\t\t</tr>\n");

		$alt = 1;
		while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
		{
			if ($alt == 1) $colour = "style=\"background: #dedede\""; else $colour = "";
			extract($incoming_theme_select_row);
			$theme_description = htmlspecialchars($incoming_theme_select_row["description"]);
			print("\t\t\t<tr $colour><td>$themeID</td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t".html_parse_text($name)."<br />\n\t\t\t\t\t");print_select_box ("category[$themeID]", $theme_type_select_array, $category);print("\n\t\t\t\t</td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t<a href=\"/users/$userID\">$username</a><br />\n\t\t\t\t\t$date\n\t\t\t\t</td>\n");
			print("\t\t\t\t<td>".$theme_description."</td>\n");
			print("\t\t\t\t<td><a href=\"".htmlentities($theme_url)."\">Download</a></td>\n");
			print("\t\t\t\t<td>");print_select_box("mark_theme[$themeID]",$new_status_array,$status);print("</td>\n");
			print("\t\t\t</tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("\t\t</table>\n");
		print("\t\t<input type=\"submit\" value=\"Update\" />\n\t</form>");
	}
}
admin_footer();
?>
