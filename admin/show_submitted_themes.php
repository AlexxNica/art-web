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
			print("<p class=\"error\">Invalid Status for $markID</p>");
			print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming themes list.");
		}elseif (count($rej_arr) > 1)
		{
			$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='rejected', comment='{$rej_arr[1]}' WHERE themeID='$markID'");
			print("<p class=\"info\">Rejected theme $markID with \"{$reject_array[$new_status]}\".</p>");
		}elseif ($new_status != 'new' || $admin_level > 1)
		{
			$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='$new_status' WHERE themeID='$markID'");
			// Only print message if status has actually changed
			if (mysql_affected_rows())
				print("<p class=\"info\">Marked theme $markID as \"{$new_status}\".</p>");
		}

		/* update category of item */
		$new_category = validate_input_array_default ($new_category_array[$markID], $theme_category_list, '_error');
		if ($new_category != '_error')
		{
			mysql_query("UPDATE incoming_theme SET category='$new_category' WHERE themeID='$markID'");
			if (mysql_affected_rows())
				print("<p class=\"info\">Marked theme $markID category as &quot;$new_category&quot;.</p>");
		}


	}
	print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Return to incoming themes list</a></p>");
}
else
{

	if($theme_type)
	{
		$query_extra = "AND category='$theme_type'";
	}
	$query = "SELECT incoming_theme.*, user.username FROM incoming_theme,user WHERE (status='new' OR status='approved') $query_extra AND user.userID = incoming_theme.userID ORDER BY date ASC";
	$incoming_theme_select_result = mysql_query($query);
	print("<hr />");
	if(mysql_num_rows($incoming_theme_select_result)==0)
	{
		print("There are no theme submissions.");
	}
	else
	{
		if ($admin_level > 1)
		{
			$approved_list_select = mysql_query("SELECT themeID, theme_name, category FROM incoming_theme WHERE status='approved'");
			print("<form action=\"add_theme.php\" method=\"post\">");
			print("Approved items: <select name=\"submitID\">");
			while ($row = mysql_fetch_row($approved_list_select))
				print("<option value={$row[0]}>{$row[1]} ({$row[2]})</option>");
			print("</select><input type=\"submit\" value=\"Add\" />");
			print("</form><hr/>");
		}


		print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">");
		print("<input type=\"submit\" value=\"Update\" />");
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\">");
		print("<tr><th>ID</th><th>Theme Name</th><th>Category</th><th>Author</th><th>Date</th><th>Download</th><th>Action</th></tr>\n");

		$alt = 1;
		while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
		{
			if ($alt == 1) $colour = "style=\"background: #dedede\""; else $colour = "";
			extract($incoming_theme_select_row);
			print("<tr $colour><td>$themeID</td>");
			print("<td>".html_parse_text($theme_name)."</td>");
			print ('<td>');print_select_box ("category[$themeID]", $theme_type_select_array, $category);print ('</td>');
			print("<td><a href=\"/users/$userID\">$username</a></td>");
			print("<td>$date</td>");
			print("<td><a href=\"".html_parse_text($theme_url)."\">Download</a></td>");
			print("<td>");print_select_box("mark_theme[$themeID]",$new_status_array,$status);print("</td>");
			print("</tr>");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
		print("<input type=\"submit\" value=\"Update\" /></form>\n");
	}
}
admin_footer();
?>
