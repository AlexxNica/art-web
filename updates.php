<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");

create_middle_box_top("updates");
if (!$num_updates || $num_updates=="" || $num_updates==0)
{
	$num_updates = 12;
}
print("The $num_updates most recent additions are:");

$big_array = get_updates_array($num_updates);
print("<p>\n<table>\n");
for($count=0;$count<count($big_array);$count++)
{
	list($add_timestamp,$type,$ID) = explode("|",$big_array[$count]);
	if($type == "background")
   {
   	$background_select_result = mysql_query("SELECT background_name, category, author,release_date,thumbnail_filename FROM background WHERE backgroundID='$ID'");
   	list($background_name,$category,$author,$release_date,$thumbnail_filename) = mysql_fetch_row($background_select_result);
      $release_date = fix_sql_date($release_date,"/");
      print("<tr><td><a href=\"show_background.php?backgroundID=$ID&category=$category\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\"></td><td><a class=\"screenshot\" href=\"show_background.php?backgroundID=$ID&category=$category\">$background_name</a><br>$release_date<br>BACKGROUNDS - GNOME<br>$author</td></tr>\n");
   }
   else
   {
   	$theme_select_result = mysql_query("SELECT theme_name, category, author, release_date,small_thumbnail_filename FROM theme WHERE themeID='$ID'");
   	list($theme_name,$category,$author,$release_date,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
      $release_date = fix_sql_date($release_date,"/");
      $category_good = $linkbar["themes_" . $category]["alt"];
      print("<tr><td><a href=\"show_theme.php?themeID=$ID&category=$category\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" border=\"0\"></a></td><td><a class=\"screenshot\" href=\"show_theme.php?themeID=$ID&category=$category\">$theme_name</a><br>$release_date<br>THEMES - $category_good<br>$author</td></tr>\n");
	}
}
print("</table>\n");
print("<p><div align=\"center\">Number of updates to display: <form action=\"$PHP_SELF\" method=\"get\">");
print("<input type=\"text\" name=\"num_updates\" value=\"$num_updates\" size=\"3\"> ");
print("<input type=\"submit\" value=\"Show\"></form></div>\n");
create_middle_box_bottom();
include("footer.inc.php");
?>
