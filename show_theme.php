<?php
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
if($themeID && ($category == "gdm_greeter" || $category == "gtk" || $category == "gtk2" || $category == "metacity" || $category == "metatheme" || $category == "nautilus" || $category == "sawfish" || $category == "sounds" || $category == "splash_screens" ))
{
	include("header.inc.php");
	$temp = "themes_" . $category;
   create_middle_box_top($temp);
   
   $theme_select_result = mysql_query("SELECT * FROM theme WHERE themeID='$themeID'");
	if(mysql_num_rows($theme_select_result)==0)
   {
   	print("Invalid Theme.");
   }
   else
   {
   	$theme_select_row = mysql_fetch_array($theme_select_result);
   	$small_thumbnail_filename = $theme_select_row["small_thumbnail_filename"];
   	$thumbnail_filename = $theme_select_row["thumbnail_filename"];
   	$name = $theme_select_row["theme_name"];
   	$author = $theme_select_row["author"];
   	$author_email = $theme_select_row["author_email"];
   	$date = $theme_select_row["release_date"];
   	list($year,$month,$day)=explode("-",$date);
      $date = $month . "/" . $day . "/" . $year;
      $description = $theme_select_row["description"];
   	$theme_download_select_result = mysql_query("SELECT name, download_name, size FROM theme_download WHERE themeID='$themeID' ORDER BY name");
      print("<table border=\"0\">\n");
      print("<tr><td><a href=\"images/thumbnails/$category/$thumbnail_filename\"><img src=\"images/thumbnails/$category/$small_thumbnail_filename\" border=\"0\" class=\"shot2\"></a></td>\n");
   	print("<td><span class=\"yellow-text\">Name:</span> $name<br>\n<span class=\"yellow-text\">Author:</span> <a href=\"mailto:$author_email\">$author</a><br>\n<span class=\"yellow-text\">Release Date:</span> $date<br><span class=\"yellow-text\">Download:</span><br>");
   	while(list($name,$download_name,$size)=mysql_fetch_row($theme_download_select_result))
   	{
   		print("&nbsp;&nbsp;&nbsp;<a href=\"$mirror_url/themes/$category/$download_name\">$name ($size)</a>\n");
   	}
   	print("</td></tr></table>\n<p>\n");
   	print("<span class=\"yellow-text\">Info:</span> $description");
	}

	create_middle_box_bottom();
	include("footer.inc.php");
}
else
{
	header("Location: index.html");
}
?>
