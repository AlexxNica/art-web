<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");


$mark_background = validate_input_regexp_default($_POST["mark_background"], "^[0-9]+$", "");
$new_status = validate_input_array_default($_POST["new_status"], array_keys($status_array), "");

admin_header("Submitted Backgrounds");

if($mark_background)
{
	if (!$new_status)
	{
		print("<p class=\"error\">Invalid Status</p>");
	}
	else
	{
		$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$mark_background'");
		print("<p class=\"info\">Successfully marked background $mark_theme as $new_status.</p>");
	}
	print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming backgrounds list.");
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
			if ($alt == 1) $colour = "bgcolor=\"#dedede\""; else $colour = "";
			extract($incoming_background_select_row);
			$background_description = htmlspecialchars($incoming_background_select_row["background_description"]);
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("<tr $colour>");
			print("<td>$category</td>");
			print("<td>$background_name</td>");
			print("<td><a href=\"/users/$userID\">$username</a></td>");
			print("<td>$date</td>");
			$background_res_select_result = mysql_query("SELECT resolution,filename FROM incoming_background_resolution WHERE backgroundID=$backgroundID");
			print("<td>");
			while (list($res, $url) = mysql_fetch_row($background_res_select_result))
			{
				print("<a href=\"$url\">$res</a>");
			}
			print("</td>");
			print("<td><form action=\"add_background.php\" method=\"post\"><input type=\"submit\" value=\"Add\">");
			print("<input type=\"hidden\" name=\"submitID\" value=\"$backgroundID\">");
			print("</form><hr />");
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">");
			print_select_box("new_status", $status_array, $status);
			print("<input type=\"hidden\" name=\"mark_background\" value=\"$backgroundID\"/><input type=\"submit\" value=\"Update\" /></form>");
			print("</td></tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
	}
}
admin_footer();
?>
