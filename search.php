<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("Search");
create_title("Search", "Search for themes and backgrounds");

// superglobals stuff

$page = validate_input_regexp_default ($_GET["page"], "^[0-9]+$", 1);
$search_type = validate_input_array_default($_GET["search_type"], array("background_name", "theme_name", "author"), "");
$search_text = mysql_real_escape_string(urldecode($_GET["search_text"]));
$sort_by = validate_input_array_default ($_GET["sort_by"], array ("name","date","popularity"),$valid_sort_by_array ,"date");
$thumbnails_per_page = validate_input_regexp_default ($_GET["thumbnails_per_page"], "^[0-9]+$", 12);

display_search_box(htmlspecialchars(stripslashes($search_text)), $search_type, $thumbnails_per_page, $sort_by);
if($search_text && $search_type)
{
	/* background name search */
	if ($search_type == "background_name")
	{
		$background_all_select_result = mysql_query("SELECT backgroundID FROM background WHERE background_name LIKE '%$search_text%' AND parent='0' ORDER BY background_name");
		$num_backgrounds = mysql_num_rows($background_all_select_result);
		if($num_backgrounds > 0)
		{
			list($page, $num_pages) = background_search_result($search_text, $search_type, "", $thumbnails_per_page, $sort_by, $page, $num_backgrounds, "list");
		}
		else
		{
			print("<p class=\"info\">No results matched your search, please try again.</p>");
		}
	}
	/* theme name search */
	elseif ($search_type == "theme_name")
	{
		$theme_all_select_result = mysql_query("SELECT themeID FROM theme WHERE theme_name LIKE '%$search_text%' ORDER BY theme_name");
		$num_themes = mysql_num_rows($theme_all_select_result);
		if($num_themes > 0)
		{
			list($page, $num_pages) = theme_search_result($search_text, $search_type, "", $thumbnails_per_page, $sort_by, $page, $num_themes, "list");
		}
		else
		{
			print("<p class=\"info\">No results matched your search, please try again.</p>");
		}
	}
	/* Author name search */
	elseif ($search_type == "author")
	{
		$user_select_result = mysql_query("SELECT userID, realname FROM user WHERE realname LIKE '%$search_text%' ORDER BY realname");
		if(mysql_num_rows($user_select_result) > 0)
		{
			print("<div class=\"h2\">Search Results</div><ul>");
			while (list($userID, $realname) = mysql_fetch_row($user_select_result))
			{
				print("<li><a href=\"/users/$userID\">$realname</a></li>");
			}
			print("</ul>");
		}
		else
		{
			print("<p class=\"info\">No results matched your search, please try again.</p>");
		}
	}
	/* Page Navigation System */
	print("<p>\n<div align=\"center\">");
	$search_text = urlencode($search_text);
	print("<p>\n");
	if($page > 1)
	{
		$prev_page = $page -1;
		print(" <a href=\"" . $_SERVER["PHP_SELF"] . "?search_type=$search_type&search_text=$search_text&page=$prev_page&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[&lt;]</a>");
	}
	for($count=1;$count<=$num_pages;$count++)
	{
		if($count == $page)
		{
			print("<span class=\"bold-text\">[$count]</span> ");
		}
		else
		{
			print("<a href=\"" . $_SERVER["PHP_SELF"] . "?search_type=$search_type&search_text=$search_text&page=$count&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[$count]</a> ");
		}
	}
	if($page < $num_pages)
	{
		$next_page = $page +1;
		print(" <a href=\"" . $_SERVER["PHP_SELF"] . "?search_type=$search_type&search_text=$search_text&page=$next_page&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[&gt;]</a>");
	}
	print("</div>\n");
}
else
{
	print("<p class=\"info\">Please enter some search terms in the box above.</p>");
}


ago_footer();

?>
