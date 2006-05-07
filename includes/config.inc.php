<?php

/* $Id$ */
/* site configuration for art.gnome.org */

/* backgrounds */

$background_config_array = array (
	'gnome' => array (
		'name' => 'GNOME',
		'url' => '/backgrounds/gnome/',
		'active' => '1',
		'description' => 'The GNOME project has built a complete, free and easy-to-use desktop environment for the user, as well as a powerful application framework for the software developer'
		),
	'nature' => array (
		'name' => 'Nature',
		'url' => '/backgrounds/nature/',
		'active' => '1',
		'description' => 'Nature pictures'
		),
	'abstract' => array (
		'name' => 'Abstract',
		'url' => '/backgrounds/abstract/',
		'active' => '1',
		'description' => 'Abstract pictures'
		),
	'other' => array (
		'name' => 'Other',
		'url' => '/backgrounds/other/',
		'active' => '1',
		'description' => 'Backgrounds featuring other GNOME based companies such as Ximian, Codefactory, RedHat, etc.'
		)

);

/* screenshots */

$screenshot_config_array = array (
	"gnome214" => array (
		"name" => "GNOME 2.14",
		"url" => "/screenshots/gnome214/",
		"active" => "1",
		"description" => "Screenshots of GNOME 2.14"
		),
	"gnome213" => array (
		"name" => "GNOME 2.13",
		"url" => "/screenshots/gnome213/",
		"active" => "1",
		"description" => "Screenshots of GNOME 2.13"
		),
	"gnome212" => array (
		"name" => "GNOME 2.12",
		"url" => "/screenshots/gnome212/",
		"active" => "1",
		"description" => "Screenshots of GNOME 2.12"
		),
	"gnome210" => array (
		"name" => "GNOME 2.10",
		"url" => "/screenshots/gnome210/",
		"active" => "1",
		"description" => "Screenshots of GNOME 2.10"
		),
);

/* themes */

$theme_config_array = array (

/*	"gtk" => array (
		"name" => "GTK+ 1.0",
		"url" => "/themes/gtk/",
		"active" => "0",
		"description" => "GTK+ 1.2 themes alter the appearance of the GTK+ 1.2 widgets. In the GNOME desktop, this means the appearance of all your GNOME applications."
		), */
	"gtk2" => array (
		"name" => "Application Themes",
		"url" => "/themes/gtk2/",
		"active" => "1",
		"description" => "GTK+ 2.0 themes control the appearance of your GNOME 2.0 programs."
		),
	"icon" => array (
		"name" => "Icons",
		"url" => "/themes/icon/",
		"active" => "1",
		"description" => "GNOME 2.2.x system icon themes change the themes in nautilus, file-roller, etc.."
		),
	"metacity" => array (
		"name" => "Window Borders",
		"url" => "/themes/metacity/",
		"active" => "1",
		"description" => "Metacity is the default window manager for GNOME 2.2.x and beyond."
		),
/*	"metatheme" => array (
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
*/	"gdm_greeter" => array (
		"name" => "Login Manager",
		"url" => "/themes/gdm_greeter/",
		"active" => "1",
		"description" => "GDM Greeter themes change the appearance of the GNOME 2.0 login screen."
		),
	"splash_screens" => array (
		"name" => "Splash Screens",
		"url" => "/themes/splash_screens/",
		"active" => "1",
		"description" => "Splash Screens are what you first see when you log into GNOME."
		),
/*	"other" => array (
		"name" => "Other ...",
		"url" => "/themes/other/index.php",
		"active" => "1",
		"description" => "Other themes."
		),
*/	"gtk_engines" => array (
		"name" => "GTK+ Engines",
		"url" => "/themes/gtk_engines/",
		"active" => "1",
		"description" => "GTK+ Engines are pieces of code that draw your theme."
		),
/*	"desktop" => array (
		"name" => "Desktop Themes",
		"url" => "/themes/desktop_themes/",
		"active" => "1",
		"description" => "Desktop themes are complete themes that cover all aspects of your desktop environment."
		)
*/
);

$contest_config_array = array (
	"2.12-splash" => array (
		"name" => "2.12 Splash screens contest",
		"url" => "/contests/2.12-splash",
		"active" => "1",
		"description" => "The contest for the GNOME 2.12 splash screen."
		),
	"2.14-artwork" => array (
		"name" => "GNOME 2.14 artwork contest",
		"url" => "/contests/2.14-artwork",
		"active" => "1",
		"description" => "The contest for a GDM Greeter, Splash and Background for GNOME 2.14."
		)
);

$license_config_array = array (
		'cc-atrib' => 'CC Attribution',
		'cc-atrib-sharealike' => 'CC Attribution-ShareAlike',
		'cc-atrib-noderivs' => 'CC Attribution-NoDerivs',
		'cc-attrib-noncom' => 'CC Attribution-NonCommercial',
		'cc-atrib-noncom-sharealike' => 'CC Attribution-NonCommercial-ShareAlike',
		'cc-atrib-noncom-noderivs' => 'CC Attribution-NonCommercial-NoDerivs',
		'gnu-gpl' => 'GNU General Public License',
		'gnu-lgpl' => 'GNU Lesser General Public License',
		'pub-dom' => 'Public Domain',
		'free-art' => 'Free Art License');

$license_config_link_array = array (
		'cc-atrib' => '<a href="http://creativecommons.org/licenses/by/2.0/">CC Attribution</a>',
		'cc-atrib-sharealike' => '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC Attribution-ShareAlike</a>',
		'cc-atrib-noderivs' => '<a href="http://creativecommons.org/licenses/by-nd/2.0/">CC Attribution-NoDerivs</a>',
		'cc-attrib-noncom' => '<a href="http://creativecommons.org/licenses/by-nc/2.0/">CC Attribution-NonCommercial</a>',
		'cc-atrib-noncom-sharealike' => '<a href="http://creativecommons.org/licenses/by-nc-sa/2.0/">CC Attribution-NonCommercial-ShareAlike</a>',
		'cc-atrib-noncom-noderivs' => '<a href="http://creativecommons.org/licenses/by-nc-nd/2.0/">CC Attribution-NonCommercial-NoDerivs</a>',
		'gnu-gpl' => '<a href="http://creativecommons.org/licenses/GPL/2.0/">GNU General Public License</a>',
		'gnu-lgpl' => '<a href="http://creativecommons.org/licenses/LGPL/2.1/">GNU Lesser General Public License</a>',
		'pub-dom' => '<a href="http://web.resource.org/cc/PublicDomain/">Public Domain</a>',
		'free-art' => '<a href="http://artlibre.org/licence/lal/en/">Free Art License</a>');


$mirror_url = 'http://ftp.gnome.org/pub/GNOME/teams/art.gnome.org';
$site_url = 'http://' . $_SERVER['SERVER_NAME'];
$site_name = $_SERVER['SERVER_NAME'];
$admin_email = 'art-web-admin@gnome.org';

$sys_ftp_dir = "/ftp/pub/gnome/teams/art.gnome.org/";

$background_image_types = array ("png", "jpg", "svg");

$search_type_array = array ("all" => "Backgrounds and Themes", "background_name" => "Background Name", "theme_name" => "Theme Name", "author" => "Author Name");
$thumbnails_per_page_array = array("12" => "12", "24" => "24", "48" => "48", "all" => "All");
$order_array = array("ASC"=>"Ascending", "DESC"=>"Descending");
$sort_by_array = array("name" => "Name", "date" => "Date", "popularity" => "Popularity", "rating" => "Rating");
/* XXX: add_timestamp and downloads_per_day kept for compatibility. Please remove at some point. */
$sort_by_convert = array("name" => "name", "date" => "add_timestamp", "popularity" => "downloads_per_day", "rating" => "rating", "add_timestamp" => "add_timestamp", "downloads_per_day" => "downloads_per_day");
$view_array = array("icons" => "Icon", "list" => "List");
$status_array = Array("new" => "New", "approved" => "Approved", "added" => "Added", "rejected" => "Rejected");

$resolution_array = Array("all" => "All", "1024x768" => "1024x768", "1280x1024" => "1280x1024", "1600x1200" => "1600x1200", "1400x1050" => "1400x1050", "1680x1050" => "1680x1050", "1920x1200" => "1920x1200", "scalable" => "Scalable (SVG)");

?>
