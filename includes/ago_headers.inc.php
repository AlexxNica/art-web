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
	<link rel="stylesheet" href="/new_layout.css" type="text/css">
	</head>
	<body>
	<div id="body">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
	<img src="/images/site/pixel.png" width="10" height="1" alt=" ">
	</td>
	<!-- Left Column -->

	<td width="182">
	<div class="mb-lite-title"><img src="/images/site/pill-icons/art.png" alt=""> ART</div>
	<div class="mb-lite-contents">
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
	<div class="mb-lite-title"><img src="/images/site/pill-icons/screenshot.png" alt=""> SCREENSHOT</div>
	<div class="mb-lite-contents">

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
	<div class="mb-lite-title"><img src="/images/site/pill-icons/background.png" alt=""> BACKGROUND</div>
	<div class="mb-lite-contents">

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
	<div class="mb-lite-title"><img src="/images/site/pill-icons/theme.png" alt=""> THEME</div>
	<div class="mb-lite-contents">

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
	</div>
	
	<div align="center">
	<font color="black" size="-2">
	Copyright &copy; 2002 - 2003<br><a class="footer" href="/copyright.php"><b>The art.gnome.org team</b></a>
	</font> 
	</div>
	
	<p>
	<div id="hdr">
      <a href="http://developer.gnome.org/"><img id="logo" src="/images/site/gnome-64.png" alt="Home" title="Back to the Gnome Developer's home page"/></a>
      <p class="none"></p>
      <div id="hdrNav">
	<a href="http://www.gnome.org/">Users</a> &middot;
	<a href="http://developer.gnome.org/">Developers</a> &middot;
	<a href="http://cvs.gnome.org/lxr/">LXR</a> &middot;
	<a href="http://cvs.gnome.org/bonsai/">Bonsai</a> &middot;
	<a href="http://ftp.gnome.org/pub/GNOME/MIRRORS.html">FTP</a> &middot;
	<a href="http://bugzilla.gnome.org/">Bugzilla</a> &middot;
	<a href="http://www.gnome.org/softwaremap/">Software Map</a> &middot;
	<a href="/"><b>Art &amp; Themes</b></a> &middot;
	<a href="mailto:webmaster@gnome.org">Contact</a>
      </div>
    </div>

    <!--
	 <div id="copyright">
	Last modified 2003/04/25 16:57:34<br />
      Copyright &copy; 2003, <a href="http://www.gnome.org/">The GNOME Project</a>.<br />

      <a href="http://validator.w3.org/check/referer">Optimised</a>
      for <a href="http://www.w3.org/">standards</a>.
      Hosted by <a href="http://www.redhat.com/">Red Hat</a>.
    </div>
	 -->
	</body>
	</html>
<?
}
?>
