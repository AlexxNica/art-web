<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");

create_middle_box_top("updates");
$num_updates = 12;
print("The $num_updates most recent additions are:");
/*
unset($big_array);
$background_select_result = mysql_query("SELECT backgroundID,add_timestamp FROM background ORDER BY add_timestamp DESC LIMIT 12");
while( list($backgroundID,$add_timestamp) = mysql_fetch_row($background_select_result) )
{
	$big_array[] = $add_timestamp . "|background|". $backgroundID;
}
$theme_select_result = mysql_query("SELECT themeID,add_timestamp FROM theme ORDER BY add_timestamp DESC LIMIT 12");
while( list($backgroundID,$add_timestamp) = mysql_fetch_row($theme_select_result) )
{
	$big_array[] = $add_timestamp . "|theme|". $backgroundID;
}
rsort($big_array);
//print_r($big_array);
*/
$big_array = get_updates_array($num_updates);
print("<p>\n<table>\n");
for($count=0;$count<$num_updates;$count++)
{
	list($add_timestamp,$type,$ID) = explode("|",$big_array[$count]);
	if($type == "background")
   {
   	$background_select_result = mysql_query("SELECT background_name, category, author,thumbnail_filename FROM background WHERE backgroundID='$ID'");
   	list($background_name,$category,$author,$thumbnail_filename) = mysql_fetch_row($background_select_result);
      print("<tr><td><a href=\"show_background.php?backgroundID=$ID&category=$category\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\"></td><td><a class=\"screenshot\" href=\"show_background.php?backgroundID=$ID&category=$category\">$background_name</a><br>BACKGROUNDS - GNOME<br>$author</td></tr>\n");
   }
   else
   {
   	$theme_select_result = mysql_query("SELECT theme_name, category, author, small_thumbnail_filename FROM theme WHERE themeID='$ID'");
   	list($theme_name,$category,$author,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
      $category_good = $linkbar["themes_" . $category]["alt"];
      print("<tr><td><a href=\"show_theme.php?themeID=$ID&category=$category\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" border=\"0\"></a></td><td><a class=\"screenshot\" href=\"show_theme.php?themeID=$ID&category=$category\">$theme_name</a><br>THEMES - $category_good<br>$author</td></tr>\n");
	}
}
print("</table>\n");
create_middle_box_bottom();
include("footer.inc.php");
?>
