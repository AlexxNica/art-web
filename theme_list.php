<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
if($category == "gdm_greeter" || $category == "gtk" || $category == "gtk2" || $category == "metacity" || $category == "metatheme" || $category == "nautilus" || $category == "sawfish" || $category == "sounds" || $category == "splash_screens" || $category == "other")
{	
   include("header.inc.php");
	$temp = "themes_" . $category;
   create_middle_box_top($temp);
	print("<div align=\"center\">\n");
	$num_per_page = 12;
	if(!$page)
	{
		$page = 1;
	}
	$theme_select_all_result = mysql_query("SELECT * FROM theme WHERE category='$category' ORDER BY add_timestamp DESC");
	$num_themes = mysql_num_rows($theme_select_all_result);
	if($num_themes == 0)
   {
   	print("No themes have been added to this section yet.");
   }
   else
   {
	   $num_pages = ceil($num_themes/$num_per_page);
		$start = (($page - 1) * $num_per_page);
		$theme_select_result = mysql_query("SELECT * FROM theme WHERE category='$category' ORDER BY add_timestamp DESC LIMIT $start, $num_per_page");
		print("<table width=\"100%\">\n<tr><td>");
      while($theme_select_row = mysql_fetch_array($theme_select_result))
		{
			$themeID = $theme_select_row["themeID"];
		   $thumbnail_filename = $theme_select_row["small_thumbnail_filename"];
		   $theme_name = $theme_select_row["theme_name"];
		   //print("<a href=\"show_theme.php?themeID=$themeID&category=$category\" title=\"$theme_name\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" class=\"shot\" border=\"0\" alt=\"$theme_name\"></a>\n");
		   print("<div class=\"list-thumb\"><a href=\"show_theme.php?themeID=$themeID&category=$category\" title=\"$theme_name\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" border=\"0\" alt=\"$theme_name\"></a><br>$theme_name</div>\n");
		
      }
   	print("</td></tr></table>\n");
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
      print("<br>\n");
      print("<form action=\"show_theme.php\" method=\"get\">\n");
      print("<select name=\"themeID\">\n");
      $theme_select_result = mysql_query("SELECT themeID, theme_name FROM theme WHERE category='$category' ORDER BY theme_name");
		while(list($themeID,$theme_name) = mysql_fetch_row($theme_select_result))
      {
      	print("<option value=\"$themeID\">$theme_name</option>\n");
      }
      print("</select>\n");
      print("<input type=\"hidden\" name=\"category\" value=\"$category\">\n");
      print("<input type=\"submit\" value=\"Go\">\n");
      print("</form>");
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
