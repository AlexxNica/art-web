<?php
	require("mysql.inc.php");
	require("session.inc.php");
if($site_theme == "lite")
{
	print("<td><img src=\"images/site/pixel.png\" width=\"10\"></td>\n");
}
?>
<!-- Right Column -->
<?
if($site_theme == "lite")
{
	print("<td width=\"231\">\n");
}
else
{
	print("<td width=\"241\">\n");
}
?>
<!-- Screenshot -->
<?
if($site_theme == "lite")
{
?>
	<div class="mb_lite-title">SCREENSHOT</div>
   <div class="mb_lite-contents">
<?
}
else
{
?>
<table border="0" width="241" cellpadding="0" cellspacing="0">
<tr><td width="1"><img src="images/site/ART-Pill_l.png"></td><td colspan="3" width="240"><img src="images/site/SHOT-Pill.png"></td></tr>
<tr><td width="1" class="black-line"></td><td bgcolor="#a8a7b7" width="182">
<?php

}
print("<div align=\"center\">\n");
$screenshot_select_result = mysql_query("SELECT screenshotID, thumbnail_filename FROM screenshot ORDER BY date DESC LIMIT 1");
list($screenshotID,$thumbnail_filename) = mysql_fetch_row($screenshot_select_result);
print("<a href=\"show_screenshot.php?screenshotID=$screenshotID\"><img src=\"images/thumbnails/screenshots/$thumbnail_filename\" border=\"0\"></a>\n");
print("<br><a href=\"screenshot_list.php\">More ...</a>");
print("</div>\n");
if($site_theme == "lite")
{
?>
	</div>
<?
}
else
{
?>
</td><td width="13" class="vertical-shadow"><img src="images/site/LBOX-shadow_v.png"></td><td><img src="images/site/pixel.png" width="45"></td></tr>
<tr><td colspan="4"><img src="images/site/SHOT_shadow.png" width="196"></td></tr>
</table>
<?
}
?>
<!-- End Screenshot -->
<p>

<!-- Background -->
<?
if($site_theme == "lite")
{
?>
	<div class="mb_lite-title">BACKGROUND</div>
   <div class="mb_lite-contents">
<?
}
else
{
?>
<table border="0" width="241" cellpadding="0" cellspacing="0">
<tr><td width="1"><img src="images/site/ART-Pill_l.png"></td><td colspan="3" width="240"><img src="images/site/BACKGROUND-Pill.png"></td></tr>
<tr><td width="1" class="black-line"></td><td bgcolor="#a8a7b7" width="182">
<?php
}
print("<div align=\"center\">\n");
$background_select_result = mysql_query("SELECT backgroundID,category,thumbnail_filename FROM background ORDER BY add_timestamp DESC LIMIT 1");
list($backgroundID,$background_category,$thumbnail_filename) = mysql_fetch_row($background_select_result);
print("<a href=\"show_background.php?backgroundID=$backgroundID&category=$background_category\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\"></a>\n");
print("</div>\n");
if($site_theme == "lite")
{
?>
	</div>
<?
}
else
{
?>
</td><td width="13" class="vertical-shadow"><img src="images/site/LBOX-shadow_v.png"></td><td><img src="images/site/pixel.png" width="45"></td></tr>
<tr><td colspan="4"><img src="images/site/SHOT_shadow.png" width="196"></td></tr>
</table>
<?
}
?>
<!-- End Background -->
<p>

<!-- Theme -->
<?

if($site_theme == "lite")
{
?>
	<div class="mb_lite-title">THEME</div>
   <div class="mb_lite-contents">
<?
}
else
{
?>
<table border="0" width="196" cellpadding="0" cellspacing="0">
<tr><td width="1"><img src="images/site/ART-Pill_l.png"></td><td width="165"><img src="images/site/THEME-Pill.png"></td><td width="17" class="horizontal-split"></td><td><img src="images/site/LBOX-top_r.png"></td></tr>
<tr><td width="1" class="black-line"></td><td colspan="2" bgcolor="#a8a7b7">
<?php
}
print("<div align=\"center\">\n");
$theme_select_result = mysql_query("SELECT themeID,category,small_thumbnail_filename FROM theme ORDER BY add_timestamp DESC LIMIT 1");
list($themeID,$theme_category,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
print("<a href=\"show_theme.php?themeID=$themeID&category=$theme_category\"><img src=\"images/thumbnails/$theme_category/$thumbnail_filename\" border=\"0\"></a>\n");
print("</div>\n");
if($site_theme == "lite")
{
?>
	</div>
<?
}
else
{

?>
</td><td width="13" class="vertical-shadow"></td></tr>
<tr><td colspan="4"><img src="images/site/SHOT_shadow.png" width="196"></td></tr>
</table>
<?
}
?>
<!-- End Theme -->
<?
if($site_theme == "lite")
{
	?>
   <img src="images/site/pixel.png" width="231" height="1">
	</td>
	<td><img src="images/site/pixel.png" width="10" height="1"></td>
   <?
}
else
{
	?>
   <img src="images/site/pixel.png" width="241" height="1">
	</td>
   <?
}

?>
<!--  End Right Column -->
</tr>
</table>
<div align="center">
<font color="black" size="-2">
&copy; 2002 <a class="footer" href="copyright.php"><b>The art.gnome.org team</b></a>
</font> 
</div>
<p>
</body>
</html>
