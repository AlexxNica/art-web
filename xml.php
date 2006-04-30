<?php
require("mysql.inc.php");
require("common.inc.php");

header("Content-type: text/plain; charset=ISO-8859-1");


//The Art:
//10 = Backgrounds Gnome
//11 = Backgrounds Other
//12 = Backgrounds All
//13 = Backgrounds Nature
//14 = Backgrounds Abstract

//20 = Desktop Themes Application
//21 = Desktop Themes Window Border
//22 = Desktop Themes Icons

//30 = Other Themes Login Manager
//31 = Other Themes Splash Screens
//32 = Other Themes Gtk+ Engines

$art = $_GET['art'];


function list_backgrounds($parent, $category)
{
	
	if ($category == "all")
		$sql = "SELECT * FROM background, user WHERE status = 'active' AND parent = $parent AND background.userID=user.userID";
	else
		$sql = "SELECT * FROM background, user WHERE status = 'active' AND parent = $parent AND category = '" . $category . "' AND background.userID=user.userID";


	$background_select_result = mysql_query($sql);

	while ($row = mysql_fetch_assoc($background_select_result))
	{
		$background_id = $row['backgroundID'];
		$background_name = $row['name'];
		$background_description = $row['background_description'];
		$background_category = $row['category'];
		$background_license = $row['license'];
		$background_vote_sum = $row['vote_sum'];
		$background_vote_count = $row['vote_count'];
		$background_release_date = $row['release_date'];
		$background_download_start_timestamp = $row['download_start_timestamp'];
		$background_download_count = $row['download_count'];
	
		$username = $row['username'];
		
		$thumbnail = $row['thumbnail_filename'];

		$archive = "";

		if ($background_id < 1000)
			$archive = "archive/";

		print("\t<background release_date=\"$background_release_date\" vote_sum=\"$background_vote_sum\" vote_count=\"$background_vote_count\" download_start_timestamp=\"$background_download_start_timestamp\" download_count=\"$background_download_count\">\n");
		print("\t\t<name>" . htmlspecialchars($background_name) . "</name>\n");
		print("\t\t<description>" . htmlspecialchars($background_description) . "</description>\n");
		print("\t\t<category>" . htmlspecialchars($background_category) . "</category>\n");
		print("\t\t<author>" . htmlspecialchars($username) . "</author>\n");
		print("\t\t<license>" . htmlspecialchars($background_license) . "</license>\n");

		print("\t\t<thumbnail>http://art.gnome.org/images/{$archive}thumbnails/backgrounds/$thumbnail</thumbnail>\n");

		foreach ($row as $key => $val)
		{
//			if ($key != "status" and $key != "parent")
//			print("\t\t<$key>$val</$key>\n");

		}

		$resolution_select_result = mysql_query("SELECT * FROM background_resolution WHERE backgroundID = '$background_id'");
		while ($res_row = mysql_fetch_assoc($resolution_select_result))
		{
			print("\t\t<background_resolution>\n");
			print("\t\t\t<type>{$res_row['type']}</type>\n");
			print("\t\t\t<resolution>{$res_row['resolution']}</resolution>\n");
			print("\t\t\t<url>http://art.gnome.org/download/backgrounds/{$category}/{$res_row['background_resolutionID']}/{$res_row['filename']}</url>\n");
			print("\t\t</background_resolution>\n");
		}

		list_backgrounds($background_id, $category);
		print("\t</background>\n");
	}
}




function list_themes($parent, $category)
{
	$sql = "SELECT * FROM theme, user WHERE status = 'active' AND parent = $parent AND category = '" . $category . "' AND theme.userID=user.userID";

	$theme_select_result = mysql_query($sql);

	while ($row = mysql_fetch_assoc($theme_select_result))
	{
		$theme_id = $row['themeID'];
		$theme_name = $row['name'];
		$theme_description = $row['description'];
		$theme_category = $row['category'];
		$username = $row['username'];
		$theme_license = $row['license'];
		$theme_vote_sum = $row['vote_sum'];
		$theme_vote_count = $row['vote_count'];
		$theme_download_start_timestamp = $row['download_start_timestamp'];
		$theme_download_count = $row['download_count'];
		$theme_release_date = $row['release_date'];
		
		$thumbnail = $row['preview_filename'];
		$small_thumbnail = $row['thumbnail_filename'];

		$archive = "";

		if ($theme_id < 1000)
			$archive = "archive/";

		print("\t<theme release_date=\"$theme_release_date\" vote_sum=\"$theme_vote_sum\" vote_count=\"$theme_vote_count\" download_start_timestamp=\"$theme_download_start_timestamp\" download_count=\"$theme_download_count\">\n");
		print("\t\t<name>" . htmlspecialchars($theme_name) . "</name>\n");
		print("\t\t<description>" . htmlspecialchars($theme_description) . "</description>\n");
		print("\t\t<category>" . htmlspecialchars($category) . "</category>\n");
		print("\t\t<author>" . htmlspecialchars($username) . "</author>\n");
		print("\t\t<license>" . htmlspecialchars($theme_license) . "</license>\n");
		print("\t\t<thumbnail>http://art.gnome.org/images/{$archive}thumbnails/{$theme_category}/{$thumbnail}</thumbnail>\n");
		print("\t\t<small_thumbnail>http://art.gnome.org/images/{$archive}thumbnails/{$theme_category}/{$small_thumbnail}</small_thumbnail>\n");
		print("\t\t<url>http://art.gnome.org/download/themes/{$theme_category}/{$theme_id}/{$row['download_filename']}</url>\n");
		foreach ($row as $key => $val)
		{
//			if ($key != "status" and $key != "parent")
//			print("\t\t<$key>$val</$key>\n");

		}


		list_themes($theme_id, $category);
		print("\t</theme>\n");
	}
}

print("<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n");
print("<art>\n");


switch ($art) {
case 10:
	list_backgrounds(0, "gnome");
	break;
case 11: 
	list_backgrounds(0, "other");
	break;
case 12: 
	list_backgrounds(0, "all");
	break;
case 13:
	list_backgrounds(0, "nature");
	break;
case 14:
	list_backgrounds(0, "abstract");
	break;
case 20:
	list_themes(0, "gtk2");
	break;
case 21:
	list_themes(0, "metacity");
	break;
case 22:
	list_themes(0, "icon");
	break;
case 30:
	list_themes(0, "gdm_greeter");
	break;
case 31: 
	list_themes(0, "splash_screens");
	break;
case 32: 
	list_themes(0, "gtk_engines");
	break;
default:
	list_backgrounds(0, "gnome");
}


print("</art>\n");
?>
