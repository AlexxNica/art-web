<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

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
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\">");
		print("<tr><td><b>ID</b></td><td><b>Category</b></td><td><b>Background Name</b></td><td><b>Author</b></td><td><b>Download</b></td><td><b>Description</b></td><td><b>Action</b></td></tr>\n");

		$alt = 1;
		while($incoming_background_select_row = mysql_fetch_array($incoming_background_select_result))
		{
			if ($alt == 1) $colour = "bgcolor=\"silver\""; else $colour = "";
			$backgroundID = $incoming_background_select_row["backgroundID"];
			$background_name = $incoming_background_select_row["background_name"];
			$category = $incoming_background_select_row["category"];
			$author = $incoming_background_select_row["author"];
			$author_email = $incoming_background_select_row["author_email"];
			$background_url = $incoming_background_select_row["background_url"];
			$background_screenshot_url = $incoming_background_select_row["background_screenshot_url"];
			$background_description = $incoming_background_select_row["background_description"];
			if($background_screenshot_url != "")
			{
				$screenshot_link = "<a href=\"$background_screenshot_url\">Screenshot</a>";
			}
			print("<tr $colour><td>$backgroundID</td>");
			print("<td>$category</td>");
			print("<td>$background_name</td>");
			print("<td><a href=\"mailto:$author_email\">$author</a></td>");
			print("<td><a href=\"$background_url\">Download</td>");
			print("<td>$background_description</td>");
			print("<td><form action=\"add_background.php\" method=\"post\"><input type=\"submit\" value=\"Add\">");
			print("<input type=\"hidden\" name=\"background_name\" value=\"$background_name\">");
			print("<input type=\"hidden\" name=\"author\" value=\"$author\">");
			print("<input type=\"hidden\" name=\"author_email\" value=\"$author_email\">");
			print("<input type=\"hidden\" name=\"background_description\" value=\"$background_description\">");
			print("<input type=\"hidden\" name=\"backgroundID\" value=\"$backgroundID\">");
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
