<?php
require("mysql.inc.php");
require("common.inc.php");

//header("Content-type: application/rss+xml");
header("Content-type: text/plain");

print("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
print("<art>\n");


function list_backgrounds($parent)
{
	$background_select_result = mysql_query("SELECT * FROM background WHERE status = 'active' AND parent = $parent");

	while ($row = mysql_fetch_assoc($background_select_result))
	{
		$background_id = $row['backgroundID'];
		$background_name = $row['background_name'];
		$category = $row['category'];
		$thumbnail = $row['thumbnail_filename'];

		print("\t<background>\n");
		print("\t\t<background_name>$background_name</background_name>\n");
		print("\t\t<category>$category</category>\n");
		$author_select = mysql_query("SELECT realname FROM user WHERE userID = {$row['userID']}");
		list($realname) = mysql_fetch_row($author_select);
		print("\t\t<author>$realname</author>\n");
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
			print("\t\t\t<url>http://art.gnome.org/download/backgrounds/{$res_row['background_resolutionID']}/{$res_row['filename']}</url>\n");
			print("\t\t</background_resolution>\n");
		}

		list_backgrounds($background_id);
		print("\t</background>\n");
	}
}

list_backgrounds(0);


print("</art>\n");
	?>
