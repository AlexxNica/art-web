<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("SEARCH");
create_middle_box_top("search");

// superglobals stuff
$page = validate_input_regexp_default ($_GET["page"], "^[0-9]+$", 1);
$search_type = $_GET["search_type"];
$search_text = $_GET["search_text"];
$sort_by = validate_input_array_default ($_GET["sort_by"], array ("name","date","popularity"),$valid_sort_by_array ,"date");
$thumbnails_per_page = validate_input_regexp_default ($_GET["thumbnails_per_page"], "^[0-9]+$", 12);

$search_text = urldecode($search_text);

display_search_box($search_text, $search_type, $thumbnails_per_page, $sort_by);
if($search_text && $search_type)
{
	/* background name search */
	if ($search_type == "background")
	{
		$background_all_select_result = mysql_query("SELECT backgroundID FROM background WHERE background_name LIKE '%$search_text%' AND parent='0' ORDER BY background_name");
		$num_backgrounds = mysql_num_rows($background_all_select_result);
		if($num_backgrounds > 0)
		{
			list($page, $num_pages) = background_search_result($search_text, $search_type, "", $thumbnails_per_page, $sort_by, $page, $num_backgrounds);
		}
		else
		{
			print("<p>No results matched your search, please try again.");
		}
	}
	/* theme name search */
	elseif ($search_type == "theme")
	{
		$theme_all_select_result = mysql_query("SELECT themeID FROM theme WHERE theme_name LIKE '%$search_text%' ORDER BY theme_name");
		$num_themes = mysql_num_rows($theme_all_select_result);
		if($num_themes > 0)
		{
			list($page, $num_pages) = theme_search_result($search_text, $search_type, "", $thumbnails_per_page, $sort_by, $page, $num_themes);
		}
		else
		{
			print("<p>No results matched your search, please try again.");
		}
	}
	
	/* Page Navigation System */
	print("<p>\n<div align=\"center\">");
	$search_text = urlencode($search_text);
	print("<p>\n");
	if($page > 1)
	{
   	$prev_page = $page -1;
   	print(" <a href=\"$PHP_SELF?search_type=$search_type&search_text=$search_text&page=$prev_page&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[&lt;]</a>");
	}
	for($count=1;$count<=$num_pages;$count++)
	{
		if($count == $page)
   	{
   		print("<span class=\"bold-text\">[$count]</span> ");
		}
   	else
   	{
   		print("<a href=\"$PHP_SELF?search_type=$search_type&search_text=$search_text&page=$count&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[$count]</a> ");
   	}
	}
	if($page < $num_pages)
	{
   	$next_page = $page +1;
   	print(" <a href=\"$PHP_SELF?search_type=$search_type&search_text=$search_text&page=$next_page&sort_by=$sort_by&thumbnails_per_page=$thumbnails_per_page\">[&gt;]</a>");
	}
	print("</div>\n");
	print ($sort_by);
}
else
{
	print("<b>Please enter some search terms in the box above.</b>\n<p>\n");
}


create_middle_box_bottom();
ago_footer();

?>
