<?php

/* $Id$ */
/* site configuration for art.gnome.org */

/* backgrounds */

$background_config_array = array (
	"gnome" => array (
		"name" => "GNOME",
		"url" => "/backgrounds/gnome/index.php",
		"active" => "1",
		"description" => "The GNOME project has built a complete, free and easy-to-use desktop environment for the user, as well as a powerful application framework for the software developer"
		),
	"other" => array (
		"name" => "Other",
		"url" => "/backgrounds/other/index.php",
		"active" => "1",
		"description" => "Backgrounds featuring other GNOME based companies such as Ximian, Codefactory, RedHat, etc."
		)		

);

/* themes */

$theme_config_array = array (
	"gdm_greeter" => array (
		"name" => "GDM Greeter",
		"url" => "/themes/gdm_greeter/index.php",
		"active" => "1",
		"description" => "GDM Greeter themes change the appearance of the GNOME 2.0 login screen."
		),
	"gtk" => array (
		"name" => "GTK+ 1.0",
		"url" => "/themes/gtk/index.php",
		"active" => "1",
		"description" => "GTK+ 1.2 themes alter the appearance of the GTK+ 1.2 widgets. In the GNOME desktop, this means the appearance of all your GNOME applications."
		),
	"gtk2" => array (
		"name" => "GTK+ 2.0",
		"url" => "/themes/gtk2/index.php",
		"active" => "1",
		"description" => "GTK+ 2.0 themes control the appearance of your GNOME 2.0 programs."
		),
	"icon" => array (
		"name" => "Icon",
		"url" => "/themes/icon/index.php",
		"active" => "1",
		"description" => "GNOME 2.2.x system icon themes change the themes in nautilus, file-roller, etc.."
		),
	"metacity" => array (
		"name" => "Metacity",
		"url" => "/themes/metacity/index.php",
		"active" => "1",
		"description" => "Metacity is the default window manager for GNOME 2.2.x and beyond."
		),
	"metatheme" => array (
		"name" => "Metatheme",
		"url" => "/themes/metatheme/index.php",
		"active" => "0",
		"description" => ""
		),
	"nautilus" => array (
		"name" => "Nautilus",
		"url" => "/themes/nautilus/index.php",
		"active" => "0",
		"description" => ""
		),
	"sawfish" => array (
		"name" => "Sawfish",
		"url" => "/themes/sawfish/index.php",
		"active" => "1",
		"description" => "Sawfish was the default window manager in GNOME 1.4.x and GNOME 2.0.x"
		),
	"sounds" => array (
		"name" => "Sounds",
		"url" => "/themes/sounds/index.php",
		"active" => "1",
		"description" => "Collection of sounds to compliment the GNOME desktop"
		),
	"splash_screens" => array (
		"name" => "Splash Screens",
		"url" => "/themes/splash_screens/index.php",
		"active" => "1",
		"description" => "Splash Screens are what you first see when you log into GNOME."
		),
	"other" => array (
		"name" => "Other ...",
		"url" => "/themes/other/index.php",
		"active" => "1",
		"description" => "Other themes."
		)
);

$pill_array = array (
	"news" => array(
   	"icon" => "news.png",
		"alt" => "NEWS"),
	"updates" => array(
   	"icon" => "updates.png",
		"alt" => "UPDATES"),
	"search" => array(
   	"icon" => "search.png",
		"alt" => "Search"),
	"backgrounds" => array(
   	"icon" => "background.png",
		"alt" => "BACKGROUNDS"),
   "backgrounds_gnome" => array(
   	"icon" => "backgrounds_gnome.png",
		"alt" => "BACKGROUNDS - GNOME"),
   "backgrounds_other" => array(
   	"icon" => "backgrounds_other.png",
		"alt" => "BACKGROUNDS - OTHER"),
   "themes" => array(
   	"icon" => "theme.png",
		"alt" => "THEMES"),
   "themes_gdm_greeter" => array(
   	"icon" => "themes_gdm_greeter.png",
		"alt" => "THEMES - GDM GREETER"),
   "themes_gtk" => array(
   	"icon" => "themes_gtk.png",
		"alt" => "THEMES - GTK+ 1.2"),
   "themes_gtk2" => array(
   	"icon" => "themes_gtk.png",
		"alt" => "THEMES - GTK+ 2.0"),
   "themes_icon" => array(
   	"icon" => "themes_icon.png",
		"alt" => "THEMES - Icon"),
   "themes_metacity" => array(
   	"icon" => "themes_metacity.png",
		"alt" => "THEMES - Metacity"),
   "themes_metatheme" => array(
   	"icon" => "themes_metatheme.png",
		"alt" => "THEMES - Metatheme"),
   "themes_nautilus" => array(
   	"icon" => "themes_nautilus.png",
		"alt" => "THEMES - Nautilus"),
   "themes_sawfish" => array(
   	"icon" => "themes_sawfish.png",
		"alt" => "THEMES - Sawfish"),
   "themes_sounds" => array(
   	"icon" => "themes_sounds.png",
		"alt" => "THEMES - Sounds"),
   "themes_splash_screens" => array(
   	"icon" => "themes_splash_screens.png",
		"alt" => "THEMES - Splash Screens"),
   "themes_other" => array(
   	"icon" => "theme.png",
		"alt" => "THEMES - Other"),
	"icons" => array(
   	"icon" => "icons.png",
		"alt" => "ICONS"),
   "tips" => array(
   	"icon" => "tips.png",
		"alt" => "Tips &amp; Tricks"),
   "faq" => array(
   	"icon" => "faq.png",
		"alt" => "FAQ"),
   "contact" => array(
   	"icon" => "contact.png",
		"alt" => "CONTACT"),
	"links" => array(
   	"icon" => "links.png",
		"alt" => "LINKS"),
	"copyright" => array(
   	"icon" => "copyright.png",
		"alt" => "COPYRIGHT"),
   "screenshots" => array(
   	"icon" => "screenshot.png",
		"alt" => "SCREENSHOTS"),
	"submit" => array(
   	"icon" => "submit.png",
		"alt" => "SUBMIT")
);

$mirror_url = "http://ftp.gnome.org/pub/GNOME/teams/art.gnome.org";

$sys_icon_dir = "/usr/local/www/art.gnome.org/images/icons";
//$sys_icon_dir = "/home/aldug/www/art-web/images/icons";

$valid_image_ext = array ("png","xpm","gif","jpg","jpeg","tiff");

$search_type_array = array ("background" => "Background Name", "theme" => "Theme Name", "author" => "Author Name");
$thumbnails_per_page_array = array("12" => "12", "24" => "24", "1000" => "All");
$sort_by_array = array("name" => "Name", "date" => "Date");
	
?>
