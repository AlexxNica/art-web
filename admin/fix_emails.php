<?php
require("mysql.inc.php");
print("<html>\n<head></head>\n<body>\n");
print("<b>backgrounds</b>\n<p>\n");

$background_select_result = mysql_query("SELECT backgroundID,author_email FROM background");
print("<table border=\"\">\n");
while(list($backgroundID,$nospam_email)=mysql_fetch_row($background_select_result))
{
	$good_email = ereg_replace("_n0spam","",$nospam_email);
   $background_update_query = "UPDATE background SET author_email='$good_email' WHERE backgroundID='$backgroundID'";
   //$background_update_result = mysql_query($background_update_query);
   print("<tr><td>$backgroundID</td><td>$nospam_email</td><td>$good_email</td><td>$background_update_query</td><td>$background_update_result</td></tr>\n");
}
print("</table>\n");

print("<b>themes</b>\n<p>\n");

$theme_select_result = mysql_query("SELECT themeID,author_email FROM theme");
print("<table border=\"\">\n");
while(list($themeID,$nospam_email)=mysql_fetch_row($theme_select_result))
{
	$good_email = ereg_replace("_n0spam","",$nospam_email);
   $theme_update_query = "UPDATE theme SET author_email='$good_email' WHERE themeID='$themeID'";
   //$theme_update_result = mysql_query($theme_update_query);
   print("<tr><td>$themeID</td><td>$nospam_email</td><td>$good_email</td><td>$theme_update_query</td><td>$theme_update_result</td></tr>\n");
}
print("</table>\n");


print("</body>\n</html>\n");
?>
