<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Submitted Themes");
$admin_auth = admin_auth(1);

$theme_type_select_array = Array("" => "All", "metacity" => "Metacity", "Icon" => "Icon", "gtk2" => "GTK 2", "gdm_greeter" => "GDM Greeter", "splash_screens" => "Splash Screens", "desktop" => "Desktop");

$mark_theme = validate_input_regexp_default($_POST["mark_theme"], "^[0-9]+$", "");
$new_status = validate_input_array_default($_POST["new_status"], array_keys($status_array), "");
$theme_type = validate_input_array_default($_GET["theme_type"], array_keys($theme_type_select_array), "");

if($mark_theme)
{
	if (!$new_status)
	{
		print("<p class=\"error\">Invalid Status</p>");
	}
	else
	{
		$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='$new_status' WHERE themeID='$mark_theme'");
		print("<p class=\"info\">Successfully marked theme $mark_theme as $new_status.</p>");
	}
	print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Click here</a> to return to incoming themes list.");
}
else
{
	print("<form method=\"GET\" action=\"" . $_SERVER["PHP_SELF"] . "\">Show only ");
	create_select_box("theme_type", $theme_type_select_array, $theme_type);
	print("themes <input type=\"submit\" value=\"Go\"></form>");

	if($theme_type)
	{
		$query_extra = "AND category='$theme_type'";
	}
	$query = "SELECT incoming_theme.*, user.username FROM incoming_theme,user WHERE (status='new' OR status='approved') $query_extra AND user.userID = incoming_theme.userID";
	$incoming_theme_select_result = mysql_query($query);
	print("<hr>");
	if(mysql_num_rows($incoming_theme_select_result)==0)
	{
		print("There are no theme submissions.");
	}
	else
	{
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\">");
		print("<tr><th>ID</th>");
		if ($theme_type == "") print("<th>Category</th>");
		print("<th>Theme Name</th><th>Author</th><th>Date</th><th>Download</th><th>Action</th></tr>\n");

		$alt = 1;
		while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
		{
			if ($alt == 1) $colour = "bgcolor=\"#dedede\""; else $colour = "";
			extract($incoming_theme_select_row);
			print("<tr $colour><td>$themeID</td>");
			if ($theme_type == "") print("<td>$category</td>");
			print("<td>$theme_name</td>");
			print("<td><a href=\"/users/$userID\">$username</a></td>");
			print("<td>$date</td>");
			print("<td><a href=\"$theme_url\">Download</td>");
			print("<td>");
			if ($admin_auth > 1)
			{
				print("<form action=\"add_theme.php\" method=\"post\">");
				print("<input type=\"hidden\" name=\"submitID\" value=\"$themeID\"><input type=\"submit\" value=\"Add\">");
				print("</form>");
			}
			print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">");
			print_select_box("new_status", $status_array, $status);
			print("<input type=\"hidden\" name=\"mark_theme\" value=\"$themeID\"/><input type=\"submit\" value=\"Update\" /></form>");
			print("</td></tr>");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
	}
}
admin_footer();
?>
