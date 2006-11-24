<?php
require("mysql.inc.php");
require("common.inc.php");
print("<?xml version=\"1.0\"?>\n");
print("<updates>\n");
$cutoff_timestamp = time() - (60*60*24*7);

/* backgrounds */
print("\t<backgrounds>\n");
$background_select_result = mysql_query("SELECT backgroundID, name, category, description, add_timestamp FROM background WHERE add_timestamp > $cutoff_timestamp");
while(list($backgroundID,$name,$category,$description,$add_timestamp)=mysql_fetch_row($background_select_result))
{
	print("\t\t<background>\n");
   print("\t\t\t<name>$name</name>\n");
   print("\t\t\t<date>".date("m/d/Y",$add_timestamp)."</date>\n");
   print("\t\t\t<description>$description</description>\n");
   print("\t\t\t<url>http://art.gnome.org/show_background.php?backgroundID=$backgroundID&category=$category</url>\n");
	print("\t\t</background>\n");
}
print("\t</backgrounds>\n");


/* themes */
print("\t<themes>\n");
$theme_select_result = mysql_query("SELECT themeID, name, category, description, add_timestamp FROM theme WHERE add_timestamp > $cutoff_timestamp");
while(list($themesID,$name,$category,$description,$add_timestamp)=mysql_fetch_row($theme_select_result))
{
	print("\t\t<theme>\n");
   print("\t\t\t<name>$name</name>\n");
   print("\t\t\t<date>".date("m/d/Y",$add_timestamp)."</date>\n");
   print("\t\t\t<description>$escription</description>\n");
   print("\t\t\t<url>http://art.gnome.org/show_theme.php?themeID=$themeID&category=$category</url>\n");
	print("\t\t</theme>\n");
}
print("\t</themes>\n");

print("</updates>\n");
?>
