<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");


$commented = validate_input_array_default($_POST['commented'], array(true, false), "false");
$comment = mysql_real_escape_string($_POST['comment']);
$mark_background = $_POST['mark_background'];
$new_category_array = $_POST['category'];
$new_background_name_array = $_POST['background_name'];
$new_background_description_array = $_POST['background_description'];

admin_header("Submitted Backgrounds");
$admin_level = admin_auth(1);

$background_category_list = array_keys($background_config_array);
$reject_array = Array("rejected|not_rel" => "Not relevent", "rejected|bad_url" => "Invalid URL", "rejected|distro" => "Distro Specific", "rejected|low_quality" => "Low Quality","rejected|copyright" => "Copyright", "rejected|duplicate" => "Duplicate");
$new_status_array = array_merge($status_array,$reject_array);
unset($new_status_array["rejected"]);

if(is_array($mark_background))
{
	foreach($mark_background as $markID => $new_status)
	{
		/* update category of item */
		$new_category = validate_input_array_default ($new_category_array[$markID], $background_category_list, '_error');
		if ($new_category != '_error')
			mysql_query("UPDATE incoming_background SET category='$new_category' WHERE backgroundID='$markID'");
		if (mysql_affected_rows())
			print("<p class=\"info\">Marked background $markID category as &quot;$new_category&quot;.</p>");

		/* update name of item */
		$new_name = escape_string ($new_background_name_array[$markID]);
		mysql_query("UPDATE incoming_background SET background_name='$new_name' WHERE backgroundID='$markID'");
		if (mysql_affected_rows())
			print("<p class=\"info\">Updated name of background $markID as &quot;$new_name&quot;.</p>");

		/* update description of item */
		$new_description = escape_string ($new_background_description_array[$markID]);
		mysql_query("UPDATE incoming_background SET background_description='$new_description' WHERE backgroundID='$markID'");
		if (mysql_affected_rows())
			print("<p class=\"info\">Updated description of background $markID.</p>");



		/* update status of item */
		$rej_arr = explode("rejected|",$new_status);
		if (!$new_status)
		{
			print("<p class=\"error\">Invalid Status for $markID</p>");
			print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.");
		}elseif (count($rej_arr) > 1)
		{
			$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='rejected', comment='{$rej_arr[1]}' WHERE backgroundID='$markID'");
			print("<p class=\"info\">Rejected background $markID with \"{$reject_array[$new_status]}\".</p>");
		}elseif ($new_status != 'new')
		{
			$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$markID'");
			// Only print message if status has actually changed
			if (mysql_affected_rows())
				print("<p class=\"info\">Marked background $markID as \"{$new_status}\".</p>");
		}
	}
	print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Return to incoming backgrounds list</a>.</p>");
}
else
{
	$incoming_background_select_result = mysql_query("SELECT incoming_background.*, user.username FROM incoming_background, user WHERE (status='new' OR status='approved') AND user.userID = incoming_background.userID ORDER BY date ASC");
	if(mysql_num_rows($incoming_background_select_result)==0)
	{
		print("There are no background submissions.");
	}
	else
	{
		if ($admin_level > 1)
		{
			$approved_list_select = mysql_query("SELECT backgroundID, background_name FROM incoming_background WHERE status='approved'");
			print("<form action=\"add_background.php\" method=\"post\">");
			print("Approved items: <select name=\"submitID\">");
			while ($row = mysql_fetch_row($approved_list_select))
				print("<option value={$row[0]}>{$row[1]}</option>");
			print("</select><input type=\"submit\" value=\"Add\" />");
			print("</form><hr/>");
		}
		print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>");
		print("<input type=\"submit\" value=\"Update\" />");
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\" >");
		print("<tr><th>ID</th><th>Name</th><th>Category</th><th>Author</th><th>Date</th><th>Description</th><th>Download</th><th>Status</th></tr>\n");

		$alt = 1;
		while($incoming_background_select_row = mysql_fetch_array($incoming_background_select_result))
		{
			if ($alt == 1) $colour = "style=\"background: #dedede\""; else $colour = "";
			extract($incoming_background_select_row);
			$background_description = htmlspecialchars($incoming_background_select_row["background_description"]);
			$background_name = htmlspecialchars($background_name);
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("<tr $colour>");
			print("<td>$backgroundID</td>");
			print("<td><input name=\"background_name[$backgroundID]\" value=\"".$background_name."\"/></td>");
			print('<td>');print_select_box("category[$backgroundID]", array_combine($background_category_list, $background_category_list), $category);print('</td>');
			print("<td><a href=\"/users/$userID\">$username</a></td>");
			print("<td>$date</td>");
			print('<td><textarea name="background_description['.$backgroundID.']" cols="20" rows="3">'.$background_description.'</textarea></td>');
			$background_res_select_result = mysql_query("SELECT resolution,filename FROM incoming_background_resolution WHERE backgroundID=$backgroundID");
			print("<td>");
			while (list($res, $url) = mysql_fetch_row($background_res_select_result))
			{
				print("<a href=\"$url\">$res</a>&nbsp; ");
			}
			print("</td>");
			print("<td>");print_select_box("mark_background[$backgroundID]",$new_status_array,$status);print("</td>");
			print("</tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("</table>");
		print("<input type=\"submit\" value=\"Update\" /></form>\n");
	}
}
admin_footer();
?>
