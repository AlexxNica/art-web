<?php

function ago_header($title)
{
	ini_set("session.use_only_cookies", "1");
	session_start();

	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
	print("<html>");
	print("<head>");
	print("<link type=\"text/css\" rel=\"stylesheet\" title=\"Default\" href=\"/default.css\" />");
	print("<link type=\"text/css\" rel=\"alternate stylesheet\" title=\"Left sidebar\" href=\"/left-sidebar.css\" />");
	print("<link rel=\"icon\" type=\"image/png\" href=\"http://www.gnome.org/img/logo/foot-16.png\" />");
	print("<link rel=\"alternate\" href=\"/backend.php\" type=\"application/rss+xml\" title=\"art.gnome.org\" />");
	print("<script type=\"text/javascript\" src=\"/styleswitcher.js\"></script>");
	print("<title>GNOME Art - $title</title></head>");
	print("<body>");
	print("<div id=\"header\">");
	print("<div id=\"header-left\">&nbsp;</div><div id=\"header-right\">&nbsp;</div>");
	print("<div id=\"header-links\">");
	print("<a href=\"http://www.gnome.org/about/\">About GNOME</a> &middot; ");
	print("<a href=\"http://www.gnome.org/start/stable/\">Download</a> &middot; ");
	print("<a href=\"http://www.gnome.org/\">Users</a> &middot; ");
	print("<a href=\"/\"><b>Art &amp; Themes</b></a> &middot; ");
	print("<a href=\"http://developer.gnome.org/\">Developers</a> &middot; ");
	print("<a href=\"http://foundation.gnome.org/\">Foundation</a> &middot; ");
	print("<a href=\"http://www.gnome.org/contact/\">Contact</a> ");
	print("</div></div>");

	print("<div id=\"sidebar\">");
	print("<img src=\"/images/site/gnome-graphics.png\" alt=\"Art\" height=\"48px\" width=\"48px\" align=\"middle\" /> Art");
	print("<ul>");
	print("<li><a href=\"/\">News</a></li>");
	print("<li><a href=\"/updates.php\">Updates</a></li>");
	print("<li><a href=\"/search.php\">Search</a></li>");
	print("<li><a href=\"/users/\">Authors</a></li>");
	print("<li><a href=\"/faq.php\">FAQ</a></li>");
	print("<li><a href=\"http://live.gnome.org/GnomeArt_2fTutorials\">Tutorials</a></li>");
	print("</ul>");
	print("<br />");
	print("<img src=\"/images/site/wallpaper.png\" alt=\"Wallpapers\" height=\"48px\" width=\"48px\" align=\"middle\" /> Backgrounds");
	print("<ul>");
	print("<li><a href=\"/backgrounds/gnome/\">GNOME</a></li>");
	print("<li><a href=\"/backgrounds/other/\">Other</a></li>");
	print("</ul>");
	print("<br />");
	print("<img src=\"/images/site/theme.png\" alt=\"Themes\" height=\"48px\" width=\"48px\" align=\"middle\" /> Desktop Themes");
	print("<ul>");
	print("<li><a href=\"/themes/gtk2/\">Application</a></li>");
	print("<li><a href=\"/themes/metacity/\">Window Border</a></li>");
	print("<li><a href=\"/themes/icon/\">Icons</a></li>");
	print("</ul>");
	print("<img src=\"/images/site/Themes.png\" alt=\"Themes\" height=\"48px\" width=\"48px\" align=\"middle\" /> Other Themes");
	print("<ul>");
	print("<li><a href=\"/themes/gdm_greeter/\">Login Manager</a></li>");
	print("<li><a href=\"/themes/splash_screens/\">Splash Screen</a></li>");
	print("<li><a href=\"/themes/gtk_engines/\">GTK+ Engines</a></li>");
	print("</ul>");
	print("<br/><br/>");
	if (!(array_key_exists("login", $_POST) or array_key_exists("logout", $_POST)))
	{
	if (array_key_exists("username", $_SESSION))
	{
		print("<p>Logged in as {$_SESSION['username']}");
		print("<ul>");
		print("<li><a href=\"/account.php\">My Account</a></li>");
		print("</ul>");
	}
	else
	{
		print("<center>");
		print("<form action=\"/account.php\" method=\"post\">");
		print("<input name=\"username\" class=\"username\" size=\"10\" /><br/>");
		print("<input name=\"password\" type=\"password\" class=\"password\" size=\"10\" /><br/>");
		print("<input type=\"hidden\" value=\"{$_SERVER['PHP_SELF']}\" name=\"referer\" />");
		print("<input type=\"submit\" value=\"Login\" name=\"login\" />");
		print("</form>");
		print("<a href=\"/account.php\" style=\"font-size:0.8em;\">(Register)</a>");
		print("</center>");
	}
	}


	print("</div>");

	print("<div id=\"content\">");

}

function ago_footer()
{
	print("<div align=\"center\" style=\"font-size: 0.7em; margin-top:3em; clear: left;\">");
	print("<p>Copyright &copy; 2002 - 2005<br /><a href=\"/copyright.php\"><b>The art.gnome.org team</b></a></p>");
	print("</div>");
	print("</div>");
	print("</body>");
	print("</html>");
}
?>
