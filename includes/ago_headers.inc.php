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
	print("<font size=\"+1\"><a href=\"/index.php\">News</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/updates.php\">Updates</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/search.php\">Search</a></font><br><br>\n");
	print("<font size=\"+1\">Backgrounds</font><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/backgrounds/gnome/\">GNOME</a><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/backgrounds/other/\">Other</a><br><br>\n");
	
	print("<font size=\"+1\">Desktop Themes</font><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/gtk2/\">Applications</a><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/metacity/\">Window Borders</a><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/icon/\">Icon</a><br><br>\n");
	
	print("<font size=\"+1\">Other Themes</font><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/gdm_greeter/\">Login Manager</a><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/splash_screens/\">Splash Screens</a><br>\n");
	print("&nbsp;&nbsp;&nbsp;<a href=\"/themes/gtk_engines/\">GTK+ Engines</a><br><br>\n");
	//print("&nbsp;&nbsp;&nbsp;<a href=\"/legacy_themes.php\">Legacy</a><br><br>\n");
	
	print("<font size=\"+1\"><a href=\"/art-icons/\">Icons</a></font><br>\n");
	//print("<font size=\"+1\"><a href=\"/screenshots/index.php\">Screenshots</a></font><br>\n");
	//print("<font size=\"+1\"><a href=\"/tips.php\">Tips &amp; Tricks</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/faq.php\">FAQ</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/submit.php\">Submit</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/contact.php\">Contact</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"http://gnomesupport.org/forums/index.php?c=6\">Forums</a></font><br>\n");
	print("<font size=\"+1\"><a href=\"/links.php\">Links</a></font><br>\n");
	
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
	
	</tr>
	</table>
	</div>
	
	<p>
	<div align="center">
	<font color="black" size="-2">
	Copyright &copy; 2002 - 2003<br><a href="/copyright.php"><b>The art.gnome.org team</b></a>
	</font> 
	</div>
	
	<p>
	<div id="hdr">
      <a href="/"><img id="logo" src="/images/site/gnome-64.png" alt="Home" title="Back to the Gnome Developer's home page"/></a>
      <p class="none"></p>
      <div id="hdrNav">
			<a href="http://www.gnome.org/about/">About GNOME</a> &middot;
			<a href="http://www.gnome.org/start/stable/">Download</a> &middot;
			<a href="http://www.gnome.org/">Users</a> &middot;
			<a href="http://developer.gnome.org/">Developers</a> &middot;
			<a href="http://foundation.gnome.org/">Foundation</a> &middot;
			<a href=""><b>Art &amp; Themes</b></a> &middot;
			<a href="http://www.gnome.org/contact/">Contact</a>
		</div>
	</div>
	</body>
	</html>
<?
}
?>
