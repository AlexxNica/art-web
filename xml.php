<?php
require("mysql.inc.php");
require("common.inc.php");

header("Content-type: text/plain");


//The Art:
//10 = Backgrounds Gnome
//11 = Backgrounds Other
//12 = backgrounds All

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
		$background_name = $row['background_name'];
		$background_category = $row['category'];
		$user_realname = $row['realname'];
		
		$thumbnail = $row['thumbnail_filename'];

		print("\t<background>\n");
		print("\t\t<background_name>" . htmlspecialchars($background_name) . "</background_name>\n");
		print("\t\t<category>" . htmlspecialchars($background_category) . "</category>\n");
		print("\t\t<author>" . htmlspecialchars($user_realname) . "</author>\n");
		print("\t\t<thumbnail>http://art.gnome.org/images/thumbnails/backgrounds/$thumbnail</thumbnail>\n");

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
		$theme_name = $row['theme_name'];
		$theme_category = $row['category'];
		$user_realname = $row['realname'];
		
		$thumbnail = $row['thumbnail_filename'];
		$small_thumbnail = $row['small_thumbnail_filename'];

		print("\t<theme>\n");
		print("\t\t<theme_name>" . htmlspecialchars($theme_name) . "</theme_name>\n");
		print("\t\t<category>" . htmlspecialchars($category) . "</category>\n");
		print("\t\t<author>" . htmlspecialchars($realname) . "</author>\n");
		print("\t\t<thumbnail>http://art.gnome.org/images/thumbnails/{$theme_category}/{$thumbnail}</thumbnail>\n");
		print("\t\t<small_thumbnail>http://art.gnome.org/images/thumbnails/{$theme_category}/{$small_thumbnail}</small_thumbnail>\n");
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

print("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
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
