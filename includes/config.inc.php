<?php

/* $Id$ */
/* site configuration for art.gnome.org */

$linkbar = array (
	"news" => array(
   	"url" => "index.php",
      "indent" => "0",
      "alt" => "NEWS"),
	"updates" => array(
   	"url" => "updates.php",
      "indent" => "0",
      "alt" => "UPDATES"),
	"backgrounds" => array(
   	"url" => "backgrounds.php",
      "indent" => "0",
      "alt" => "BACKGROUNDS"),
   "backgrounds_gnome" => array(
   	"url" => "background_list.php?category=gnome",
      "indent" => "1",
      "alt" => "GNOME"),
   "backgrounds_other" => array(
   	"url" => "background_list.php?category=other",
      "indent" => "1",
      "alt" => "Other"),
   "themes" => array(
   	"url" => "themes.php",
      "indent" => "0",
      "alt" => "THEMES"),
   "themes_gdm_greeter" => array(
   	"url" => "theme_list.php?category=gdm_greeter",
      "indent" => "1",
      "alt" => "GDM Greeter"),
   "themes_gtk" => array(
   	"url" => "theme_list.php?category=gtk",
      "indent" => "1",
      "alt" => "GTK+ 1.2"),
   "themes_gtk2" => array(
   	"url" => "theme_list.php?category=gtk2",
      "indent" => "1",
      "alt" => "GTK+ 2.0"),
   "themes_icon" => array(
   	"url" => "theme_list.php?category=icon",
      "indent" => "1",
      "alt" => "Icon"),
   "themes_metacity" => array(
   	"url" => "theme_list.php?category=metacity",
      "indent" => "1",
      "alt" => "Metacity"),
   "themes_metatheme" => array(
   	"url" => "theme_list.php?category=metatheme",
      "indent" => "1",
      "alt" => "Metatheme"),
   "themes_nautilus" => array(
   	"url" => "theme_list.php?category=nautilus",
      "indent" => "1",
      "alt" => "Nautilus"),
   "themes_sawfish" => array(
   	"url" => "theme_list.php?category=sawfish",
      "indent" => "1",
      "alt" => "Sawfish"),
   "themes_sounds" => array(
   	"url" => "theme_list.php?category=sounds",
      "indent" => "1",
      "alt" => "Sounds"),
   "themes_splash_screens" => array(
   	"url" => "theme_list.php?category=splash_screens",
      "indent" => "1",
      "alt" => "Splash Screens"),
   "themes_other" => array(
   	"url" => "theme_list.php?category=other",
      "indent" => "1",
      "alt" => "Other..."),
   "icons" => array(
   	"url" => "icons.php",
      "indent" => "0",
      "alt" => "ICONS"),
   "tips" => array(
   	"url" => "tips.php",
      "indent" => "0",
      "alt" => "TIPS &amp; TRICKS"),
   "faq" => array(
   	"url" => "faq.php",
      "indent" => "0",
      "alt" => "FAQ"),
   "submit" => array(
   	"url" => "submit.php",
      "indent" => "0",
      "alt" => "SUBMIT"),
   "contact" => array(
   	"url" => "contact.php",
      "indent" => "0",
      "alt" => "CONTACT"),
	"links" => array(
   	"url" => "links.php",
      "indent" => "0",
      "alt" => "LINKS")
);

$pill_array = array (
	"news" => array(
   	"icon" => "news.png",
		"alt" => "NEWS"),
	"updates" => array(
   	"icon" => "updates.png",
		"alt" => "UPDATES"),
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

$sys_icon_dir = "/usr/local/www/art.gnome.org/images/icons";

$valid_image_ext = array ("png","xpm","gif","jpg","jpeg","tiff");

$thumbnails_per_page_array = array("12"=>12,"24"=>24,"All"=>1000);
?>
