<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");

include("header.inc.php");
create_middle_box_top("screenshots");
print("<div align=\"center\">\n");
$num_per_page = 12;
if(!$page)
{
	$page = 1;
}
$screenshot_select_all_result = mysql_query("SELECT * FROM screenshot ORDER BY add_timestamp DESC");
$num_screenshots = mysql_num_rows($screenshot_select_all_result);
if($num_screenshots == 0)
{
   print("No screenshots have been added to the site.");
}
else
{
	$num_pages = ceil($num_screenshots/$num_per_page);
	$start = (($page - 1) * $num_per_page);
	$screenshot_select_result = mysql_query("SELECT * FROM screenshot ORDER BY add_timestamp DESC LIMIT $start, $num_per_page");
	while($screenshot_select_row = mysql_fetch_array($screenshot_select_result))
	{
		$screenshotID = $screenshot_select_row["screenshotID"];
		$thumbnail_filename = $screenshot_select_row["thumbnail_filename"];
		print("<a href=\"show_screenshot.php?screenshotID=$screenshotID\"><img src=\"images/thumbnails/screenshots/$thumbnail_filename\" class=\"shot\" border=\"0\"></a>\n");
	}

   print("<p>\n");
   if($page > 1)
   {
      $prev_page = $page -1;
      print(" <a href=\"$PHP_SELF?category=$category&page=$prev_page\">[&lt;]</a>");
   }
	for($count=1;$count<=$num_pages;$count++)
	{
		if($count == $page)
   	{
   	   print("<span class=\"yellow-text\">[$count]</span> ");
		}
   	else
   	{
   	   print("<a href=\"$PHP_SELF?category=$category&page=$count\">[$count]</a> ");
   	}
   }
   if($page < $num_pages)
   {
      $next_page = $page +1;
      print(" <a href=\"$PHP_SELF?category=$category&page=$next_page\">[&gt;]</a>");
   }
}
print("</div>\n");
create_middle_box_bottom();
include("footer.inc.php");
?>
