<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

$mark_background = validate_input_regexp_default($_POST["mark_background"], "^[0-9]+$", "");
$new_status = validate_input_array_default($_POST["new_status"], Array("new", "rejected", "added"), "");

admin_header("Submitted Backgrounds");

if($mark_background)
{
	$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='$new_status' WHERE backgroundID='$mark_background'");
	print("Successfully marked background as $new_status.");
	print("<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Click here</a> to return to incoming backgrounds list.");
}
else
{
	$incoming_background_select_result = mysql_query("SELECT * FROM incoming_background WHERE status='new'");
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
			$backgroundID = $incoming_background_select_row["backgroundID"];
			$background_name = $incoming_background_select_row["background_name"];
			$category = $incoming_background_select_row["category"];
			$userID = $incoming_background_select_row["userID"];
			$date = $incoming_background_select_row["date"];
			$background_description = htmlspecialchars($incoming_background_select_row["background_description"]);
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("<tr $colour>");
			print("<td>$category</td>");
			print("<td>$background_name</td>");
			print("<td><a href=\"/users/$userID\">$userID</a></td>");
			print("<td>$date</td>");
			$background_res_select_result = mysql_query("SELECT resolution,filename FROM incoming_background_resolution WHERE background_resolutionID=$backgroundID");
			print("<td>");
			while (list($res, $url) = mysql_fetch_row($background_res_select_result))
			{
				print("<a href=\"$url\">$res</a>");
			}
			print("</td>");
			print("<td><form action=\"add_background.php\" method=\"post\"><input type=\"submit\" value=\"Add\">");
			print("<input type=\"hidden\" name=\"submitID\" value=\"$backgroundID\">");
			print("</form><hr />");
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><input type=\"hidden\" name=\"new_status\" value=\"rejected\"><input type=\"submit\" value=\"Rejected\"><input type=\"hidden\" name=\"mark_background\" value=\"$backgroundID\"></form>");
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><input type=\"hidden\" name=\"new_status\" value=\"added\"><input type=\"submit\" value=\"Added\"><input type=\"hidden\" name=\"mark_background\" value=\"$backgroundID\"></form>");
			print("</td></tr>\n");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
	}
}
admin_footer();
?>
