<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");


$mark_background = validate_input_regexp_default($_POST["mark_background"], "^[0-9]+$", "");
$new_status = validate_input_array_default($_POST["new_status"], array_keys($status_array), "");
$commented = validate_input_array_default($_POST['commented'], array(true, false), "false");
$comment = mysql_real_escape_string($_POST['comment']);

admin_header("Submitted Backgrounds");
$admin_level = admin_auth(1);

if($mark_background)
{
	if (!$new_status)
	{
		print("<p class=\"error\">Invalid Status</p>");
		print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.");
	}
	elseif ($new_status == "rejected" && $commented != true)
	{
		print("<p>Comments :</p>\n");
		print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n");
		print("<p><textarea name=\"comment\" cols=\"40\" rows=\"10\"></textarea><br />\n");
		print("<input type=\"hidden\" name=\"mark_background\" value=\"$mark_background\" />\n");
		print("<input type=\"hidden\" name=\"new_status\" value=\"rejected\" />\n");
		print("<input type=\"submit\" name=\"commented\" value=\"Add Comment\" />\n");
		print("</p></form>");
	}
	elseif ($commented == false)
	{
		$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$mark_background'");
		print("<p class=\"info\">Successfully marked background $mark_background as $new_status.</p>");
		print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.");
	}
	else
	{
		$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='rejected', comment='$comment' WHERE backgroundID='$mark_background'");
		print("<p class=\"info\">Successfully marked background $mark_background as rejected.</p>");
		print("<p>Your comment was:</p>");
		print("<p>$comment</p>");
		print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.");
	}
}
else
{
	$incoming_background_select_result = mysql_query("SELECT incoming_background.*, user.username FROM incoming_background, user WHERE (status='new' OR status='approved') AND user.userID = incoming_background.userID");
	if(mysql_num_rows($incoming_background_select_result)==0)
	{
		print("There are no background submissions.");
	}
	else
	{
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\" width=\"100%\" >");
		print("<tr><th>Category</th><th>Name</th><th>Author</th><th>Date</th><th>Download</th><th>Action</th></tr>\n");

		$alt = 1;
		while($incoming_background_select_row = mysql_fetch_array($incoming_background_select_result))
		{
			if ($alt == 1) $colour = "style=\"background: #dedede\""; else $colour = "";
			extract($incoming_background_select_row);
			$background_description = html_parse_text($incoming_background_select_row["background_description"]);
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("<tr $colour>");
			print("<td>$category</td>");
			print("<td>".html_parse_text($background_name)."</td>");
			print("<td><a href=\"/users/$userID\">$username</a></td>");
			print("<td>$date</td>");
			$background_res_select_result = mysql_query("SELECT resolution,filename FROM incoming_background_resolution WHERE backgroundID=$backgroundID");
			print("<td>");
			while (list($res, $url) = mysql_fetch_row($background_res_select_result))
			{
				print("<a href=\"$url\">$res</a>");
			}
			print("</td>");
			print("<td>");
			if ($admin_level > 1)
			{
				print("<form action=\"add_background.php\" method=\"post\"><div><input type=\"submit\" value=\"Add\" />");
				print("<input type=\"hidden\" name=\"submitID\" value=\"$backgroundID\" />");
				print("</div></form><hr />");
			}
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>");
			print_select_box("new_status", $status_array, $status);
			print("<input type=\"hidden\" name=\"mark_background\" value=\"$backgroundID\" /><input type=\"submit\" value=\"Update\" /></div></form>");
			print("</td></tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
	}
}
admin_footer();
?>
