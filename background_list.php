<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
if($category == "gnome" || $category == "other")
{
	include("header.inc.php");
	$temp = "backgrounds_" . $category;
   create_middle_box_top($temp);
	print("<div align=\"center\">\n");
	$num_per_page = 12;
	if(!$page)
	{
		$page = 1;
	}
	$background_select_all_result = mysql_query("SELECT * FROM background WHERE category='$category' AND parent='0' ORDER BY add_timestamp DESC");
	$num_backgrounds = mysql_num_rows($background_select_all_result);
	if($num_backgrounds == 0)
   {
   	print("No backgrounds have been added to this section yet.");
   }
   else
   {
	   $num_pages = ceil($num_backgrounds/$num_per_page);
		$start = (($page - 1) * $num_per_page);
		$background_select_result = mysql_query("SELECT * FROM background WHERE category='$category' AND parent='0' ORDER BY add_timestamp DESC LIMIT $start, $num_per_page");
		while($background_select_row = mysql_fetch_array($background_select_result))
		{
			$backgroundID = $background_select_row["backgroundID"];
		   $thumbnail_filename = $background_select_row["thumbnail_filename"];
		   $background_name = $background_select_row["background_name"];
		   print("<a href=\"show_background.php?backgroundID=$backgroundID&category=$category\" title=\"$background_name\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" class=\"shot\" border=\"0\" alt=\"$background_name\"></a>\n");
		}
		print("<p>\n<span class=\"yellow-text\">Page</span> ");
		if($page > 1)
      {
      	$prev_page = $page -1;
         print(" <a href=\"$PHP_SELF?category=$category&page=$prev_page\">&lt;</a>");
      }
   
      for($count=1;$count<=$num_pages;$count++)
		{
			if($count == $page)
   	   {
   	   	print("<span class=\"yellow-text\">$count</span> ");
			}
   	   else
   	   {
   	   	print("<a href=\"$PHP_SELF?category=$category&page=$count\">$count</a> ");
   	   }
   	}
      if($page < $num_pages)
      {
      	$next_page = $page +1;
         print(" <a href=\"$PHP_SELF?category=$category&page=$next_page\">&gt;</a>");
      }
   }
   print("</div>\n");
   create_middle_box_bottom();
	include("footer.inc.php");
}
else
{
	header("Location: index.html");
}
?>
