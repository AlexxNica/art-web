<?php

function ago_header($title)
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

	<html>
	<head>
	<title>
	<?
	print("$title");
	?> - art.gnome.org</title>
	<link rel="icon" type="image/png" href="/images/site/gnome-16.png">
	<link rel="stylesheet" href="/main.css" type="text/css">
	</head>
	<body>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?
	print("<tr valign=\"middle\"><td colspan=\"2\" class=\"horizontal-menu-bar-lite\">&nbsp;&nbsp;&nbsp;");

	/* FIXME, temperarily disable mirror selection
	for($count=1;$count<5;$count++)
	{
		if($count == 1)
   	{
   		if($count == $mirrorID)
      	{
      		print("<span class=\"yellow-text\">Main</span>");
      	}
      	else
      	{
        		print("<b><a href=\"/change_mirror.php?new_mirrorID=1\">Main</a></b>");
			}
   	}
   	else
   	{
   		if($count == $mirrorID)
      	{
        		print("<span class=\"yellow-text\">Mirror " . ($count-1) . "</span>");
      	}
      	else
      	{
        		print("<b><a href=\"/change_mirror.php?new_mirrorID=".$count."\">Mirror " . ($count-1) . "</a></b>");
   		}
   	}
   	if($count != 4)
   	{
   		print(" - ");
   	}
	}
	*/
	print("</td></tr>\n");

	?>

	<tr class="horizontal-gradient-menu-bar"><td><img src="/images/site/LOGO-Pill.png" alt="GNOME"></td><td class="align-right"><a href="http://www.gnome.org/"><img border="0" src="/images/site/LOGO-Elliptic.png" alt="GNOME Foot"></a></td></tr>
	<tr valign="middle"><td colspan="2" class="horizontal-menu-bar-lite">&nbsp;&nbsp;<a class="screenshot" href="/index.php">art.gnome.org</a>&nbsp;&nbsp;-&nbsp;&nbsp;<b>Enhance your GNOME desktop!</b></td></tr>
	</table>
	<p>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
	<img src="/images/site/pixel.png" width="10" height="1" alt=" ">
	</td>
	<!-- Left Column -->

	<td width="182">
	<div class="mb_lite-title"><img src="/images/site/pill-icons/art.png" alt=""> ART</div>
	<div class="mb_lite-contents">
	<?
	print("<font size=\"+1\"><a href=\"/index.php\">NEWS</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/updates.php\">UPDATES</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/search.php\">SEARCH</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/backgrounds/index.php\">BACKGROUNDS</a></font><br>\n");
	while(list($key,$val) = each($GLOBALS["background_config_array"]))
	{
		$name = $val["name"];
		$url = $val["url"];
		print("&nbsp;&nbsp;&nbsp;<a href=\"$url\">$name</a><br>\n");
	}
	print("<font size=\"+1\"><a href=\"/themes/index.php\">THEMES</a></font><br>\n");
	while(list($key,$val) = each($GLOBALS["theme_config_array"]))
	{
		$active = $val["active"];
		if($active)
		{
			$name = $val["name"];
			$url = $val["url"];
			print("&nbsp;&nbsp;&nbsp;<a href=\"$url\">$name</a><br>\n");
		}
	}
	print("<font size=\"+1\"><a href=\"/art-icons/index.php\">ICONS</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/tips.php\">TIPS & TRICKS</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/faq.php\">FAQ</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/submit.php\">SUBMIT</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/contact.php\">CONTACT</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/links.php\">LINKS</a></font><br>\n");
	
	?>
	</div>
	<img src="/images/site/pixel.png" width="182" height="1" alt=" ">
	</td>
	<td><img src="/images/site/pixel.png" width="10" alt=" "></td>
	<!-- End Left Column -->
<?
}

function ago_footer()
{
	print("<td><img src=\"/images/site/pixel.png\" width=\"10\" alt=\" \"></td>\n");
	?>
	<!-- Right Column -->
	<td width="225">

	<!-- Screenshot -->
	<div class="mb_lite-title"><img src="/images/site/pill-icons/screenshot.png" alt=""> SCREENSHOT</div>
	<div class="mb_lite-contents">

	<?php
	print("<div align=\"center\">\n");
	$screenshot_select_result = mysql_query("SELECT screenshotID, thumbnail_filename FROM screenshot ORDER BY date DESC LIMIT 1");
	list($screenshotID,$thumbnail_filename) = mysql_fetch_row($screenshot_select_result);
	print("<a href=\"/screenshots/$screenshotID.php\"><img src=\"/images/thumbnails/screenshots/$thumbnail_filename\" border=\"0\" alt=\" \"></a>\n");
	print("<br><a href=\"/screenshots/index.php\">More ...</a>");
	print("</div>\n</div>\n");
	?>
	<!-- End Screenshot -->
	<br> 
	<!-- Background -->
	<div class="mb_lite-title"><img src="/images/site/pill-icons/background.png" alt=""> BACKGROUND</div>
	<div class="mb_lite-contents">

	<?php
	print("<div align=\"center\">\n");
	$background_select_result = mysql_query("SELECT backgroundID,category,thumbnail_filename FROM background WHERE status='active' ORDER BY add_timestamp DESC LIMIT 1");
	list($backgroundID,$background_category,$thumbnail_filename) = mysql_fetch_row($background_select_result);
	print("<a href=\"/backgrounds/$background_category/$backgroundID.php\"><img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\" alt=\"thumbnail\"></a>\n");
	print("</div>\n</div>\n");
	?>
	<!-- End Background -->
	<br>
	<!-- Theme -->
	<div class="mb_lite-title"><img src="/images/site/pill-icons/theme.png" alt=""> THEME</div>
	<div class="mb_lite-contents">

	<?php
	print("<div align=\"center\">\n");
	$theme_select_result = mysql_query("SELECT themeID,category,small_thumbnail_filename FROM theme WHERE status='active' ORDER BY add_timestamp DESC LIMIT 1");
	list($themeID,$theme_category,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
	print("<a href=\"/themes/$theme_category/$themeID.php\"><img src=\"/images/thumbnails/$theme_category/$thumbnail_filename\" border=\"0\" alt=\"thumbnail\"></a>\n");
	print("</div>\n</div>\n");
	?>
	<!-- End Theme -->

	<img src="/images/site/pixel.png" width="225" height="1" alt=" ">
	</td>
	<td><img src="/images/site/pixel.png" width="10" height="1" alt=" "></td>
	<!--  End Right Column -->

	</tr>
	</table>
	<div align="center">
	<font color="black" size="-2">
	Copyright &copy; 2002 - 2003<br><a class="footer" href="/copyright.php"><b>The art.gnome.org team</b></a>
	</font> 
	</div>
	<p>
	</body>
	</html>
<?
}
?>
