<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);
escape_gpc_array ($_GET);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

admin_header("Submitted Themes");

if($mark_theme)
{
	$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='$new_status' WHERE themeID='$mark_theme'");
	print("Successfully marked theme as $new_status.<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Return</a> to incoming themes list.");
}
else
{
	print("<form method=\"GET\" action=\"" . $_SERVER["PHP_SELF"] . "\">Show only ");
	create_select_box("theme_type", Array("" => "All", "metacity" => "Metacity", "Icon" => "Icon", "gtk2" => "GTK 2", "gdm_greeter" => "GDM Greeter", "splash_screens" => "Splash Screens", "desktop" => "Desktop"), $theme_type);
	print("themes <input type=\"submit\" value=\"Go\"></form>");
//	print("<a href=\"" . $_SERVER["PHP_SELF"] . "?theme_type=$theme_type\">Table</a>|<a href=\"$PHP_SELF?theme_type=$theme_type&list=urls\">URL List</a><br>");


	if($theme_type)
	{
		$query = "SELECT * FROM incoming_theme WHERE status='new' AND category='$theme_type'";
	}
	else
	{
		$query = "SELECT * FROM incoming_theme WHERE status='new'";
	}
	$incoming_theme_select_result = mysql_query($query);
	print("<hr>");
	if(mysql_num_rows($incoming_theme_select_result)==0)
	{
		print("There are no theme submissions.");
	}
	else
	{
		if ($list=="urls")
		{
			while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
			{
				$theme_url = $incoming_theme_select_row["theme_url"];
				print("$theme_url<br>\n");
			}
			die();
		}
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\">");
		print("<tr><th>ID");
		if ($theme_type == "") print("<th>Category");
		print("<th>Theme Name<th>Author<th>Download<th>Description<th>Action</tr>\n");

		$alt = 1;
		while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
		{
			if ($alt == 1) $colour = "bgcolor=\"silver\""; else $colour = "";
			$themeID = $incoming_theme_select_row["themeID"];
			$theme_name = $incoming_theme_select_row["theme_name"];
			$category = $incoming_theme_select_row["category"];
			$author = $incoming_theme_select_row["author"];
			$author_email = $incoming_theme_select_row["author_email"];
			$theme_url = $incoming_theme_select_row["theme_url"];
			$theme_description = $incoming_theme_select_row["theme_description"];
			print("<tr $colour><td>$themeID</td>");
			if ($theme_type == "") print("<td>$category</td>");
			print("<td>$theme_name</td>");
			print("<td><a href=\"mailto:$author_email\">$author</a></td>");
			print("<td><a href=\"$theme_url\">Download</td>");
			print("<td>$theme_description</td>");
			print("<td><form action=\"add_theme.php\" method=\"post\"><input type=\"hidden\" name=\"theme_submitID\" value=\"$themeID\"><input type=\"hidden\" name=\"theme_name\" value=\"$theme_name\"><input type=\"hidden\" name=\"theme_category\" value=\"$category\"><input type=\"hidden\" name=\"theme_author\" value=\"$author\"><input type=\"hidden\" name=\"author_email\" value=\"$author_email\"><input type=\"hidden\" name=\"description\" value=\"$theme_description\"><input type=\"submit\" value=\"Add\"></form><hr>");
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><input type=\"submit\" value=\"Rejected\"><input type=\"hidden\" name=\"new_status\" value=\"rejected\"><input type=\"hidden\" name=\"mark_theme\" value=\"$themeID\"></form>\n");
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><input type=\"submit\" value=\"Added\"><input type=\"hidden\" name=\"new_status\" value=\"added\"><input type=\"hidden\" name=\"mark_theme\" value=\"$themeID\"></form>\n");
			print("</td></tr>");

			$alt = 2 - $alt + 1;
		}
		print("</table>\n");
	}
}
admin_footer();
?>
