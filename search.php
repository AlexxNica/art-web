<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("SEARCH");
create_middle_box_top("search");

$search_text = urldecode($search_text);

display_search_box($search_text, $search_type, $thumbnails_per_page, $sort_by);
if($search_text && $search_type)
{
	if(!$thumbnails_per_page || $thumbnails_per_page == 0 || $thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if(!$page)
	{
		$page = 1;
	}
	
	/* background name search */
	if($search_type == "background")
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
	elseif($search_type == "theme")
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
	
	/* author search ICKK */
	elseif($search_type == "author")
	{
		unset($big_array);
		if($sort_by == "name")
		{
			$background_select_result = mysql_query("SELECT background_name,backgroundID FROM background WHERE author LIKE '%$search_text%' AND parent='0'");
			while(list($background_name,$backgroundID)=mysql_fetch_row($background_select_result))
			{
				$big_array[] = "$background_name|background|$backgroundID";
			}
			$theme_select_result = mysql_query("SELECT theme_name, themeID FROM theme WHERE author LIKE '%$search_text%'");
			while(list($theme_name,$themeID)=mysql_fetch_row($theme_select_result))
			{
				$big_array[] = "$theme_name|theme|$themeID";
			}
		}
		else
		{
			$background_select_result = mysql_query("SELECT add_timestamp,backgroundID FROM background WHERE author LIKE '%$search_text%' AND parent='0'");
			while(list($add_timestamp,$backgroundID)=mysql_fetch_row($background_select_result))
			{
				$big_array[] = "$add_timestamp|background|$backgroundID";
			}
			$theme_select_result = mysql_query("SELECT add_timestamp, themeID FROM theme WHERE author LIKE '%$search_text%'");
			while(list($add_timestamp,$themeID)=mysql_fetch_row($theme_select_result))
			{
				$big_array[] = "$add_timestamp|theme|$themeID";
			}
		}
			
		if(count($big_array) > 0)
		{
			if($sort_by == "name")
			{
				sort($big_array);
			}
			else
			{
				rsort($big_array);
			}
			reset($big_array);
			
			$num_results = count($big_array);
			$num_pages = ceil($num_results/$thumbnails_per_page);

			if($page > $num_pages)
   		{
   	   	$page = $num_pages;
   		}
			$start = (($page - 1) * $thumbnails_per_page);
			$end = $start + $thumbnails_per_page;
			if($end > $num_results)
			{
				$end = $num_results;
			}
			
			print("<b>Showing " . ($start+1) . " through " . $end . " of $num_results results.</b>\n");
			print("<table border=\"0\">\n");
			for($loop=$start;$loop<$end;$loop++)
			{
				list($name,$type,$ID) = explode("|",$big_array[$loop]);
				if($type == "background")
   			{
   				print_background_row($ID);
				}
   			else
   			{
   				print_theme_row($ID);
				}
			}
			print("</table>\n");
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
}


create_middle_box_bottom();
ago_footer();

?>
