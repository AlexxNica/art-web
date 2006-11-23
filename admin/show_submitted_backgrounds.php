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
$reject_array = Array("rejected|not_rel" => "Not relevent", "rejected|bad_url" => "Invalid URL", "rejected|distro" => "Distro Specific", "rejected|low_quality" => "Low Quality","rejected|copyright" => "Copyright", "rejected|duplicate" => "Duplicate", "rejected|other" => "Reject with reason");
$new_status_array = array_merge($status_array,$reject_array);
unset($new_status_array["rejected"]);

$reject_comment = $_POST['other_reason']; /* XXX: could be insecure!!!! */

if(is_array($mark_background))
{
	foreach($mark_background as $markID => $new_status)
	{
		/* update category of item */
		$new_category = validate_input_array_default ($new_category_array[$markID], $background_category_list, '_error');
		if ($new_category != '_error')
			mysql_query("UPDATE incoming_background SET category='$new_category' WHERE backgroundID='$markID' LIMIT 1");
		if (mysql_affected_rows())
			print("\t<p class=\"info\">Marked background $markID category as &quot;$new_category&quot;.</p>\n");

		/* update name of item */
		$new_name = escape_string ($new_background_name_array[$markID]);
		mysql_query("UPDATE incoming_background SET name='$new_name' WHERE backgroundID='$markID' LIMIT 1");
		if (mysql_affected_rows())
			print("\t<p class=\"info\">Updated name of background $markID as &quot;$new_name&quot;.</p>\n");

		/* update description of item */
		$new_description = escape_string ($new_background_description_array[$markID]);
		mysql_query("UPDATE incoming_background SET description='$new_description' WHERE backgroundID='$markID' LIMIT 1");
		if (mysql_affected_rows())
			print("\t<p class=\"info\">Updated description of background $markID.</p>\n");



		/* update status of item */
		$rej_arr = explode("rejected|",$new_status);
		if (!$new_status)
		{
			print("\t<p class=\"error\">Invalid Status for $markID</p>\n");
			print("\t<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.\n");
		}elseif (count($rej_arr) > 1)
		{
 			$reason = $rej_arr[1];
			// Allows you to add your own reject comment
 			if ($reason == "other")
 			{
 				$reason = escape_string ($reject_comment[$markID]);
 				$long = $reason;
 			}
 			else
 			{
 				$long = $reject_array[$new_status];
 			}
 			
 			if ($reason != "")
 			{
 				$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='rejected', comment='$reason' WHERE backgroundID='$markID' LIMIT 1");
 				print("\t<p class=\"info\">Rejected background $markID with \"$long\".</p>\n");
 			}
 			else
 			{
 				print("\t<p class=\"error\">Please enter a reject comment for $markID.</p>\n");
 			}
		}elseif ($new_status != 'new' || $admin_level > 1)
		{
			$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$markID' LIMIT 1");
			// Only print message if status has actually changed
			if (mysql_affected_rows())
				print("\t<p class=\"info\">Marked background $markID as \"{$new_status}\".</p>\n");
		}
	}
	print("\t<p><a href=\"{$_SERVER["PHP_SELF"]}\">Return to incoming backgrounds list</a>.</p>\n");
}
else
{
	if ($admin_level < 2) $sql_exception = " AND incoming_background.userID <> '{$_SESSION['userID']}'";
	$incoming_background_select_result = mysql_query("SELECT incoming_background.*, user.username FROM incoming_background, user WHERE (status='new' OR status='approved') AND user.userID = incoming_background.userID".$sql_exception." ORDER BY date ASC");
	if(mysql_num_rows($incoming_background_select_result)==0)
	{
		print("\tThere are no background submissions.\n");
	}
	else
	{

		if ($admin_level > 1)
		{
			$approved_list_select = mysql_query("SELECT backgroundID, name FROM incoming_background WHERE status='approved'");
			print("\t<form action=\"add_background.php\" method=\"post\">\n");
			print("\t\tApproved items: <select name=\"submitID\">\n");
			while ($row = mysql_fetch_row($approved_list_select))
				print("\t\t\t<option value={$row[0]}>{$row[1]}</option>\n");
			print("\t\t</select><input type=\"submit\" value=\"Add\" />\n");
			print("\t</form>\n\t<hr/>\n");
		}
		print("\t<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>\n");
		print("\t\t<input type=\"submit\" value=\"Update\" />\n");
		print("\t\t<table class=\"submitted_table\" cellspacing=\"0\" cellpadding=\"4px\" >\n");
		print("\t\t\t<tr>\n\t\t\t\t<th>ID</th>\n\t\t\t\t<th>Name, Category</th>\n\t\t\t\t<th>Author, Date</th>\n\t\t\t\t<th>Description</th>\n\t\t\t\t<th>Download</th>\n\t\t\t\t<th>Status, Reason</th>\n\t\t\t</tr>\n");

		$alt = 1;
		while($incoming_background_select_row = mysql_fetch_array($incoming_background_select_result))
		{
			if ($alt == 1) $colour = "style=\"background: #dedede\""; else $colour = "";
			extract($incoming_background_select_row);
			$background_description = htmlspecialchars($incoming_background_select_row["description"]);
			$background_name = htmlspecialchars($name);
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("\t\t\t<tr $colour>\n");
			print("\t\t\t\t<td>$backgroundID</td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t<input name=\"background_name[$backgroundID]\" value=\"".$background_name."\" size=\"15\"/><br />\n\t\t\t\t\t");print_select_box("category[$backgroundID]", array_combine($background_category_list, $background_category_list), $category);print("\n\t\t\t\t\t</td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t<a href=\"/users/$userID\">$username</a><br />\n\t\t\t\t\t$date\n\t\t\t\t\t</td>\n");
			print("\t\t\t\t<td><textarea style=\"width: 100%\" name=\"background_description[".$backgroundID."]\" rows=\"3\">".$background_description."</textarea></td>\n");
			$background_res_select_result = mysql_query("SELECT resolution,filename FROM incoming_background_resolution WHERE backgroundID=$backgroundID");
			print("\t\t\t\t<td>");
			while (list($res, $url) = mysql_fetch_row($background_res_select_result))
			{
				print("<a href=\"$url\">$res</a>&nbsp; ");
			}
			print("</td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t");print_select_box("mark_background[$backgroundID]",$new_status_array,$status);print("<br />\n\t\t\t\t\t<input size=\"16\" name=\"other_reason[$backgroundID]\" id=\"other_reason[$backgroundID]\"/>\n\t\t\t\t</td>\n");
			print("\t\t\t</tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("\t\t</table>\n");
		print("\t\t<input type=\"submit\" value=\"Update\" />\n\t</form>");
	}
}
admin_footer();
?>
