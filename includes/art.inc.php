<?php

require_once("common.inc.php");

function add_vote($artID, $rating, $userID, $type)
{
	// check for valid $type
	if (!($type == 'theme' or $type == 'background'))
		return -1;

	if ($rating != -1)
	{
		$checkvote_result = mysql_query("SELECT `voteID` FROM `vote` WHERE `userID`='$userID' AND `artID`='$artID' AND type='$type'");
		if (mysql_num_rows($checkvote_result) >= 1)
		{
			mysql_query("UPDATE vote SET rating='$rating' WHERE userID='$userID' AND artID='$artID' AND type='$type'");
		} else {
			mysql_query("INSERT INTO `vote` (`voteID`, `userID`, `artID`, `rating`, `type`) VALUES ('', '$userID', '$artID', '$rating', '$type')");
		}
		// Update cached version of rating in theme/background table
		$rating_sel = mysql_query("SELECT SUM(rating), COUNT(rating) FROM vote WHERE type='$type' AND artID='$artID'");
		list($rating,$count) = mysql_fetch_row($rating_sel);
		if ($count < 5) $rating = 0; else $rating = round($rating / $count, 4);
		mysql_query("UPDATE $type SET rating = $rating WHERE {$type}ID = $artID LIMIT 1");
		print mysql_error();
	}
}


function print_detailed_view($itemID, $type)
{
	global $license_config_array, $theme_config_array, $background_config_array;
	global $site_url;

	// check for valid $type
	if (!($type == 'theme' or $type == 'background'))
	{
		ago_file_not_found();
		return -1;
	}

	if ($type == "theme")
		$select_result = mysql_query("SELECT theme.*, theme.theme_name AS item_name,  user.username AS author FROM theme,user WHERE themeID='$itemID' AND theme.userID = user.userID");
	else
		$select_result = mysql_query("SELECT background.*, background.background_name AS item_name, thumbnail_filename AS small_thumbnail_filename, user.username AS author FROM background,user WHERE backgroundID='$itemID' AND background.userID = user.userID");

	if(mysql_num_rows($select_result)==0)
	{
		ago_file_not_found();
		return -1;
	}

	// Extract data from the database into variables
	extract(mysql_fetch_array($select_result));

	if ($type == "theme")
		$subtitle = "Desktop Themes - " . $theme_config_array[$category]["name"];
	else
		$subtitle = "Backgrounds - {$background_config_array[$category]["name"]}";

	if($category == "metacity" || $category == "gtk_engines" || $category == "icon" )
		$thumbnail_class = "thumbnail_no_border";
	else
		$thumbnail_class = "thumbnail";

	$rel_release_date = FormatRelativeDate(time(), strtotime($release_date));
	$rel_update_date = FormatRelativeDate(time(), $add_timestamp);
	$thumbnail_url = get_thumbnail_url($small_thumbnail_filename, $itemID, $type, $category);


	// Make download links
	if ($type == "theme")
	{
		global $sys_theme_dir;
		if ($itemID < 1000)
			$file_path = $sys_theme_dir . "/../archive/theme/$category/$download_filename";
		else
			$file_path = $sys_theme_dir . "/$category/$download_filename";
		$filesize = get_filesize_string($file_path);
		$download = "<a class=\"tar\" href=\"/download/themes/$category/$itemID/$download_filename\">$download_filename ($filesize)</a>";
	}
	else
	{
		$resolution_select = mysql_query("SELECT filename,resolution,type FROM background_resolution WHERE backgroundID=$itemID");
		while (list($download_filename,$resolution,$image_type) = mysql_fetch_row($resolution_select))
			$download .= "<a class=\"$image_type\" href=\"/download/backgrounds/$category/$itemID/$download_filename\"> $image_type - $resolution</a>&nbsp;&nbsp;";
	}

	if ($rating == 0)
		$rating_text = "5 votes required";
	else
		$rating_text = round($rating*20) . "%";

	// Get a count of the number of votes
	$vote_count_select = mysql_query("SELECT COUNT(rating) AS vote_count FROM vote WHERE type='$type' AND artID='$itemID'");
	list($vote_count) = mysql_fetch_row($vote_count_select);

	// Work around naming inconsitencies in database
	if ($background_description)
		$description = $background_description;


	// Start output ///////////////////////////////////////////////////////

	create_title(htmlentities($item_name) . " by <a href=\"/users/$author\">$author</a>", $subtitle);
	if ($category == "icon" || $category == "gtk2" || $category == "gdm_greeter" )
		print("<a href=\"".get_thumbnail_url($thumbnail_filename, $itemID, $type, $category) . "\">");

	print("<img src=\"$thumbnail_url\" alt=\"Thumbnail\" class=\"$thumbnail_class\" style=\"float:left;\" />");

	if ($category == "icon" || $category == "gtk2" || $category == "gdm_greeter" )
		print("</a>");

	print("<p>" . html_parse_text($description) . "</p>");

	print("<br/><table class=\"theme_info\">\n");
	print("\t<tr><th>Release</th><td>$rel_release_date</td></tr>\n");

	// if add timestamp and release date differ more than 24 hours (86400 seconds)
	if ($add_timestamp - strtotime($release_date) > 86400)
		print("\t<tr><th>Updated</th><td>$rel_update_date</td></tr>\n");

	if($version != "0" and $version != "")
		print("\t<tr><th>Version</th><td>$version</td></tr>\n");
	if ($license != "")
		print("\t<tr><th>License</th><td>".$license_config_array[$license]."</td></tr>\n");
	$downloads_per_day = calculate_downloads_per_day($download_count, $download_start_timestamp);
	print("\t<tr><th>Popularity</th><td>$downloads_per_day Downloads per Day ($download_count downloads in total)</td></tr>\n");

	print("\t<tr><th>Rating</th><td>\n");
	print("\t<div class=\"subtitle\" style=\"float:left\">\n");
	rating_bar($rating);
	print("\t($rating_text, $vote_count votes total)\n");

	if ($_SESSION['userID'])
	{

		if ($_SESSION['userID'] == $userID)
		{
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Sorry, you can't vote for your own work.</i>\n");
		}
		else
		{
			$user_rating_select = mysql_query("SELECT rating FROM vote WHERE type='$type' AND artID='$itemID' AND userID='{$_SESSION['userID']}'");
			list($user_rating) = mysql_fetch_row($user_rating_select);

			print("\t<form class=\"subtitle\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">");
			if (!$user_rating)
				print("Vote:\n");
			else
				print("\tChange Your Vote:\n");
			print("\t[worst]");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"1\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"2\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"3\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"4\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"5\"/>\n");
			print("\t[best]");
			if ($user_rating)
				print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>You rated this a $user_rating.</i>\n");
			print("\t</form>\n");
		}
	} else
	{
		print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Only <a href=\"/account.php\">registered users</a> may vote.</i>\n");
	}
	print("\t</div>\n");

	print("\t</td></tr>\n");
	print("\t<tr><th>Download</th><td>$download</td></tr>\n");
	print("</table>\n");

	// Get any variations
	$var_select_result = mysql_query("SELECT {$type}ID,category FROM $type WHERE parent = $itemID");
	if (mysql_num_rows($var_select_result) > 0)
	{
		create_title("Variations", "This $type has one or more variations");
		while (list($varID,$var_category) = mysql_fetch_row($var_select_result))
			print_item_row($varID, $type, "icons");
	}
}


?>
