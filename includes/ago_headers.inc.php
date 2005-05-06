<?php

function ago_header($title)
{
	ini_set("session.use_only_cookies", "1");
	ini_set("session.gc_maxlifetime", "86400"); // set session data lifetime to 48 hours
	session_set_cookie_params(86400); // Set cookie lifetime to 48 hours
	session_start();
	header("Content-Type: text/html; charset=ISO-8859-1");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n");
//	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n");
//	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
	print("<html lang=\"en\">\n");
	print("<head>\n");
	print("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />\n");
	print("<meta name=\"description\" content=\"Themes and backgrounds for use with the GNOME desktop environment\" />\n");
	print("<meta name=\"author\" content=\"The art.gnome.org team\" />\n");
	print("<meta name=\"keywords\" content=\"art.gnome.org art backgrounds wallpapers images icons gdm linux artwork splash screens download screen metacity gtk login manager engines themes gnome desktop environment\" />\n");
	print("<link type=\"text/css\" rel=\"stylesheet\" title=\"Default\" href=\"/default.css\" />\n");
	print("<link type=\"text/css\" rel=\"alternate stylesheet\" title=\"Left sidebar\" href=\"/left-sidebar.css\" />\n");
	print("<link rel=\"icon\" type=\"image/png\" href=\"http://www.gnome.org/img/logo/foot-16.png\" />\n");
	print("<link rel=\"alternate\" href=\"/backend.php\" type=\"application/rss+xml\" title=\"art.gnome.org\" />\n");
	print("<script type=\"text/javascript\" src=\"/styleswitcher.js\"></script>\n");
	print("<title>GNOME Art - $title</title>\n");
	print("</head>\n");
	print("<body>\n");
	print("<div id=\"header\">\n");
	print("\t<div id=\"header-left\"><a href=\"/\">&nbsp;</a></div><div id=\"header-right\"><a href=\"/\">&nbsp;</a></div>\n");
	print("\t<div id=\"header-links\">\n");
	print("\t\t<a href=\"http://www.gnome.org/about/\">About GNOME</a> &middot;\n");
	print("\t\t<a href=\"http://www.gnome.org/start/stable/\">Download</a> &middot;\n");
	print("\t\t<a href=\"http://www.gnome.org/\">Users</a> &middot;\n");
	print("\t\t<a href=\"/\"><strong>Art &amp; Themes</strong></a> &middot;\n");
	print("\t\t<a href=\"http://developer.gnome.org/\">Developers</a> &middot;\n");
	print("\t\t<a href=\"http://foundation.gnome.org/\">Foundation</a> &middot;\n");
	print("\t\t<a href=\"http://www.gnome.org/contact/\">Contact</a>\n");
	print("\t</div>\n");
	print("</div>\n");

	print("<div id=\"sidebar\">\n");
	print("\t<a href=\"/\"><img src=\"/images/site/gnome-graphics.png\" alt=\"Art\" height=\"48\" width=\"48\" class=\"sidebar\" /> Art</a>\n");
	print("\t<ul>\n");
	print("\t\t<li><a href=\"/\">News</a></li>\n");
	print("\t\t<li><a href=\"/updates.php\">Updates</a></li>\n");
	print("\t\t<li><a href=\"/search.php\">Search</a></li>\n");
	print("\t\t<li><a href=\"/users/\">Authors</a></li>\n");
	print("\t\t<li><a href=\"/faq.php\">FAQ</a></li>\n");
	print("\t\t<li><a href=\"http://live.gnome.org/GnomeArt_2fTutorials\">Tutorials</a></li>\n");
	print("\t\t<li><a href=\"http://gnomesupport.org/forums/index.php?c=6\">Forums</a></li>\n");
	print("\t</ul>\n");
	print("\t<br />\n");
	print("\t<a href=\"/backgrounds\">");

	if (is_ie())
                print("<img src=\"/images/site/spacer.gif\" alt=\"Wallpapers\" height=\"48\" width=\"48\" class=\"sidebar\" style=\"filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/site/wallpaper.png', sizingMethod='scale');\" /> Backgrounds");
	else
		print("<img src=\"/images/site/wallpaper.png\" alt=\"Wallpapers\" height=\"48\" width=\"48\" class=\"sidebar\" /> Backgrounds");

	print("</a>\n");
	print("\t<ul>\n");
	print("\t\t<li><a href=\"/backgrounds/gnome/\">GNOME</a></li>\n");
	print("\t\t<li><a href=\"/backgrounds/other/\">Other</a></li>\n");
	print("\t</ul>\n");
	print("\t<br />\n");
	print("\t<a href=\"/themes\"><img src=\"/images/site/theme.png\" alt=\"Themes\" height=\"48\" width=\"48\" class=\"sidebar\" /> Desktop Themes</a>\n");
	print("\t<ul>\n");
	print("\t\t<li><a href=\"/themes/gtk2/\">Application</a></li>\n");
	print("\t\t<li><a href=\"/themes/metacity/\">Window Border</a></li>\n");
	print("\t\t<li><a href=\"/themes/icon/\">Icons</a></li>\n");
	print("\t</ul>\n");
	print("\t<a href=\"/themes\">");
	
	if (is_ie())
		print("<img src=\"/images/site/spacer.gif\" alt=\"Themes\" height=\"48\" width=\"48\" class=\"sidebar\" style=\"filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/site/Themes.png', sizingMethod='scale');\" /> Other Themes");
	else
                print("<img src=\"/images/site/Themes.png\" alt=\"Themes\" height=\"48\" width=\"48\" class=\"sidebar\" /> Other Themes");
	
	print("</a>\n");
	print("\t<ul>\n");
	print("\t\t<li><a href=\"/themes/gdm_greeter/\">Login Manager</a></li>\n");
	print("\t\t<li><a href=\"/themes/splash_screens/\">Splash Screen</a></li>\n");
	print("\t\t<li><a href=\"/themes/gtk_engines/\">GTK+ Engines</a></li>\n");
	print("\t</ul>\n");
	print("\t<br /><br />\n");
	if (!(array_key_exists("login", $_POST) or array_key_exists("logout", $_POST)))
	{
	if (array_key_exists("username", $_SESSION))
	{
		print("\t<p>Logged in as {$_SESSION['username']}</p>\n");
		print("\t<ul>\n");
		print("\t\t<li><a href=\"/account.php\">My Account</a></li>\n");
		print("\t</ul>\n");
	}
	else
	{
		print("<div style=\"text-align: center\">\n");
		print("\t<form action=\"/account.php\" method=\"post\"><p>\n");
		print("\t<label for=\"username\">User</label> <input id=\"username\" name=\"username\" class=\"username\" size=\"10\" /><br />\n");
		print("\t<label for=\"password\">Pass</label> <input id=\"password\" name=\"password\" type=\"password\" class=\"password\" size=\"10\" /><br />\n");
		print("\t<input type=\"hidden\" value=\"{$_SERVER['PHP_SELF']}\" name=\"referer\" />\n");
		print("\t<input type=\"submit\" value=\"Login\" name=\"login\" />\n");
		print("\t</p></form>\n");
		print("\t<a href=\"/account.php\" style=\"font-size:0.8em;\">(Register)</a>\n");
		print("</div>\n");
	}
	}


	print("<br /><br /></div>\n");

	print("<div id=\"content\">\n");

}

function ago_footer()
{
	print("\n<div style=\"text-align: center; font-size: 0.7em; margin-top:3em; clear: left;\">\n");
	print("\t<p>Copyright &copy; 2002 - 2005<br /><a href=\"/copyright.php\"><strong>The art.gnome.org team</strong></a></p>\n");
	print("</div>\n");
	print("</div>\n");
	print("</body>\n");
	print("</html>");
}
?>
