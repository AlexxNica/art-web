<?php

/* $Id$ */

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");

$request = $PATH_INFO;
$request = ereg_replace("/+", "/", $request);
$sections = explode("/", $request);
$category = $sections[1];
$filename = $sections[2];
if(!$filename || $filename == "")
{
	$filename = "index.php";
}

if($category == "index.php" || $category == "" || !$category)
{

   include("header.inc.php");
   create_middle_box_top("themes");

   print("<table border=\"0\" cellpadding=\"4\">\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gdm_greeter\"><b>GDM Greeter</b></a> - GDM Greeter themes change the appearance of the GNOME 2.0 login screen.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gtk\"><b>GTK+ 1.2</b></a> - GTK+ 1.2 themes alter the appearance of the GTK+ 1.2 widgets. In the GNOME desktop, this means the appearance of all your GNOME applications.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=gtk2\"><b>GTK+ 2.0</b></a> - GTK+ 2.0 themes control the appearance of your GNOME 2.0 programs. </td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=metacity\"><b>Metacity</b></a> - Metacity is a new window manager for GNOME 2.0.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=metatheme\"><b>Metatheme</b></a> - These are themes that control all of the other themes! An all in one solution to themeing your desktop.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=nautilus\"><b>Nautilus</b></a> - Nautilus is the default file manager for GNOME 1.4 &amp; 2.0. These themes control its appearance.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=sawfish\"><b>Sawfish</b></a> - Sawfish is the default window manager for GNOME. The themes for sawfish change the look of the decoration round your windows (titlebars etc).</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=sounds\"><b>Sounds</b></a> - Collection of sounds to compliment the GNOME desktop.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=splash_screens\"><b>Splash Screens</b></a> - Splash Screens are what you first see when you log into GNOME.</td></tr>\n");
   print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"theme_list.php?category=other\"><b>Other ...</b></a> - Other themes.<td></tr>\n");
   print("</table>\n");

   create_middle_box_bottom();
   include("footer.inc.php");
}
else if(($category == "gnome" || $category == "other") && ($filename == "index.php" || $filename=="" || !$filename) )
{
	include("header.inc.php");
	$temp = "themes_" . $category;
   create_middle_box_top($temp);
	print("<div align=\"center\">\n");
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
	   $num_pages = ceil($num_themes/$thumbnails_per_page);
      if($page > $num_pages)
      {
      	$page = $num_pages;
      }
		$start = (($page - 1) * $thumbnails_per_page);
		$theme_select_result = mysql_query("SELECT * FROM theme WHERE category='$category' ORDER BY add_timestamp DESC LIMIT $start, $thumbnails_per_page");
		print_thumbnails_per_page_form();
      print("<p>\n");
      while($theme_select_row = mysql_fetch_array($theme_select_result))
		{
			$themeID = $theme_select_row["themeID"];
		   $thumbnail_filename = $theme_select_row["small_thumbnail_filename"];
		   $theme_name = $theme_select_row["theme_name"];
		   print("<a href=\"show_theme.php?themeID=$themeID&category=$category\" title=\"$theme_name\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" class=\"shot\" border=\"0\" alt=\"$theme_name\"></a>\n");
		   //print("<div class=\"list-thumb\"><a href=\"show_theme.php?themeID=$themeID&category=$category\" title=\"$theme_name\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" border=\"0\" alt=\"$theme_name\"></a><br>$theme_name</div>\n");
		
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
else if(($category == "gnome" || $category == "other") && ereg("^([0-9]{1,5})\.html",$filename))
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
   	$author_email = spam_proof_email($author_email);
      $date = $theme_select_row["release_date"];
   	list($year,$month,$day)=explode("-",$date);
      $date = $month . "/" . $day . "/" . $year;
      $description = $theme_select_row["description"];
   	$theme_download_select_result = mysql_query("SELECT name, download_name, size FROM theme_download WHERE themeID='$themeID' ORDER BY name");
      print("<table border=\"0\">\n");
      print("<tr><td>");
      if($category == "metacity" || $category == "sawfish" || $category == "sounds")
      {
      	print("<img src=\"images/thumbnails/$category/$small_thumbnail_filename\" border=\"0\" class=\"shot2\">");
      }
      else
      {
      	print("<a href=\"images/thumbnails/$category/$thumbnail_filename\"><img src=\"images/thumbnails/$category/$small_thumbnail_filename\" border=\"0\" class=\"shot2\"></a>");
      }
      print("</td>\n");
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
?>
