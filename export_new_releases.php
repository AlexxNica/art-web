<?php
require("mysql.inc.php");
require("common.inc.php");
print("<?xml version=\"1.0\"?>\n");
print("<upates>\n");
$cutoff_timestamp = time() - (60*60*24*7);
/* backgrounds */
print("\t<backgrounds>\n");
$background_select_result = mysql_query("SELECT backgroundID, background_name, category, background_description, add_timestamp FROM background WHERE add_timestamp > $cutoff_timestamp");
while(list($backgroundID,$background_name,$category,$background_description,$add_timestamp)=mysql_fetch_row($background_select_result))
{
	print("\t\t<background>\n");
   print("\t\t\t<name>$background_name</name>\n");
   print("\t\t\t<date>".date("m/d/Y",$add_timestamp)."</date>\n");
   print("\t\t\t<description>$background_description</description>\n");
   print("\t\t\t<url>http://art.gnome.org/show_background.php?backgroundID=$backgroundID&category=$category</url>\n");
	print("\t\t</background>\n");
}
print("\t</backgrounds>\n");
/* themes */
print("\t<themes>\n");
$theme_select_result = mysql_query("SELECT themeID, theme_name, category, description, add_timestamp FROM theme WHERE add_timestamp > $cutoff_timestamp");
while(list($themesID,$themes_name,$category,$theme_description,$add_timestamp)=mysql_fetch_row($theme_select_result))
{
	print("\t\t<theme>\n");
   print("\t\t\t<name>$theme_name</name>\n");
   print("\t\t\t<date>".date("m/d/Y",$add_timestamp)."</date>\n");
   print("\t\t\t<description>$theme_description</description>\n");
   print("\t\t\t<url>http://art.gnome.org/show_theme.php?themeID=$themeID&category=$category</url>\n");
	print("\t\t</theme>\n");
}
print("\t</themes>\n");

print("</updates>\n");
?>
