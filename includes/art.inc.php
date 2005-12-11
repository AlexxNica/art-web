<?php

require_once("common.inc.php");
require_once("art_listings.inc.php");

function add_vote($artID, $rating, $userID, $type, $header)
{
	// check for valid $type
	if (!($type == 'theme' or $type == 'background'))
		return -1;
		
	if ($rating == -1)
		return -1;
	
	is_logged_in($header);
	
	/* prevent users from voting for their own artwork */
	$result = mysql_query("SELECT userID FROM $type WHERE {$type}ID='$artID'");
	list($authorID) = mysql_fetch_row($result);
	
	if ($_SESSION['userID'] == $authorID) {
		/* we do not need extra feedback */
		return -1;
	}

	$checkvote_result = mysql_query("SELECT `voteID` FROM `vote` WHERE `userID`='$userID' AND `artID`='$artID' AND type='$type'");
	if (mysql_num_rows($checkvote_result) >= 1)
	{
		mysql_query("UPDATE vote SET rating='$rating' WHERE userID='$userID' AND artID='$artID' AND type='$type'");
	} else {
		mysql_query("INSERT INTO `vote` (`voteID`, `userID`, `artID`, `rating`, `type`) VALUES ('', '$userID', '$artID', '$rating', '$type')");
	}
	
	// Update cached version of rating in theme/contest/background table
	$rating_sel = mysql_query("SELECT SUM(rating), COUNT(rating) FROM vote WHERE type='$type' AND artID='$artID'");
	list($rating,$count) = mysql_fetch_row($rating_sel);
	if ($count < 5) $rating = 0; else $rating = round($rating / $count, 4);
	mysql_query("UPDATE $type SET rating = $rating WHERE {$type}ID = $artID LIMIT 1");
}


function print_detailed_view($itemID, $type)
{
	global $license_config_link_array;
	global $site_url;

	// check for valid $type
	if (!($type == 'theme' or $type == 'background' or $type == 'contest' or $type == 'screenshot'))
	{
		art_file_not_found();
		return -1;
	}

	switch ($type) {
	case 'theme':
		$select_result = mysql_query("SELECT theme.*, theme.theme_name AS item_name,  user.username AS author FROM theme,user WHERE themeID='$itemID' AND theme.userID = user.userID");
	break;
	case 'contest':
		$select_result = mysql_query("SELECT contest.*, contest.contest AS category, contest.name AS item_name,  user.username AS author FROM contest,user WHERE contestID='$itemID' AND contest.userID = user.userID");
	break;
	case 'background':
		$select_result = mysql_query("SELECT background.*, background.background_name AS item_name, thumbnail_filename AS small_thumbnail_filename, user.username AS author FROM background,user WHERE backgroundID='$itemID' AND background.userID = user.userID");
	break;
	case 'screenshot':
		$select_result = mysql_query("SELECT screenshot.*, screenshot.name AS item_name, thumbnail_filename AS small_thumbnail_filename, user.username AS author FROM screenshot,user WHERE screenshotID='$itemID' AND screenshot.userID = user.userID");
	break;
	}

	if (mysql_num_rows($select_result) == 0)
	{
		art_file_not_found();
		return -1;
	}

	// Extract data from the database into variables
	extract(mysql_fetch_array($select_result));
	
	/* XXX: This now does not have an "Gnome Theme - " for applications, icons, etc. */
	$subtitle = get_category_name($type, $category);
	
	$thumbnail_class = get_thumbnail_class($category);

	$rel_release_date = FormatRelativeDate(time(), strtotime($release_date), true);
	$rel_update_date = FormatRelativeDate(time(), $add_timestamp);
	$thumbnail_url = get_thumbnail_url($small_thumbnail_filename, $itemID, $type, $category);
	$preview_image = get_thumbnail_url($thumbnail_filename, $itemID, $type, $category, true);

	// Make download links
	$download = get_download_links ($type, $category, $itemID, $download_filename);
	
	if ($rating == 0)
		$rating_text = "5 votes required";
	else
		$rating_text = round($rating*20) . "%";

	// Get a count of the number of votes
	/* XXX: maybe this should also be cached? */
	$vote_count_select = mysql_query("SELECT COUNT(rating) AS vote_count FROM vote WHERE type='$type' AND artID='$itemID'");
	list($vote_count) = mysql_fetch_row($vote_count_select);

	// Work around naming inconsitencies in database
	if ($background_description)
		$description = $background_description;


	// Start output ///////////////////////////////////////////////////////

	create_title(htmlentities($item_name) . " by <a href=\"/users/$author\">$author</a>", $subtitle);
	if ($category == 'icon' || $category == 'gtk2' || $category == 'gdm_greeter' || $type == 'screenshot' )
	{
		if ($type == 'screenshot')
			$preview_image = "screenshots/$category/$download_filename";
		list ($image_width, $image_height, $image_type, $image_attr) = getimagesize ('images/'.$preview_image);
		$ww = min($image_width + 100, 640);
		$wh = min($image_height + 100, 640);
		print('<a href="/preview.php?image='.$preview_image.'" onClick="window.open(\'/preview.php?image='.$preview_image.'\', \'Art Preview\', \'width='.$ww.',height='.$wh.',resizable=no,scrollbars=yes,status=no\'); return false;" rel="external">');
	}


	print("<img src=\"$thumbnail_url\" alt=\"Thumbnail\" class=\"$thumbnail_class\" style=\"float:left;\" />");

	if ($category == 'icon' || $category == 'gtk2' || $category == 'gdm_greeter' || $type == 'screenshot' )
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
		print("\t<tr><th>License</th><td>".$license_config_link_array[$license]."</td></tr>\n");
	if ($type != 'contest')
	{
		$downloads_per_day = calculate_downloads_per_day($download_count, $download_start_timestamp);
		if ($type != 'screenshot')
			print("\t<tr><th>Popularity</th><td>$downloads_per_day Downloads per Day ($download_count downloads in total)</td></tr>\n");
		print("\t<tr><th>Rating</th><td>\n");
		print("\t<div class=\"subtitle\" style=\"float:left\">\n");
		rating_bar($rating);
		print("\t($rating_text, $vote_count votes total)\n");

		if ($_SESSION['userID'] == $userID)
		{
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Sorry, you can not vote for your own work.</i>\n");
		}
		else
		{
			$user_rating_select = mysql_query("SELECT rating FROM vote WHERE type='$type' AND artID='$itemID' AND userID='{$_SESSION['userID']}'");
			list($user_rating) = mysql_fetch_row($user_rating_select);
			print("\t<form class=\"subtitle\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">");
			if ((!$user_rating) || (!$_SESSION['userID']))
				print("<div>Vote:\n");
			else
				print("\t<div>Change Your Vote:\n");
			print("\t[worst]");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"1\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"2\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"3\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"4\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"5\"/>\n");
			print("\t[best]");
			if (($user_rating) && ($_SESSION['userID']))
				print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>You rated this a $user_rating.</i>\n");
			print("\t</div></form>\n");
		}

/*	else
	{
		print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Only <a href=\"/account.php\">registered users</a> may vote.</i>\n");
	}*/
		print("\t</div>\n");

		print("\t</td></tr>\n");
	}
	if ($type != 'screenshot')
		print("\t<tr><th>Download</th><td>$download</td></tr>\n");
	print("</table>\n");

	// Get any variations
	$listing = new variations_list;
	$listing->type = $type;
	$listing->view = 'icons';
	$listing->select($itemID);
	$listing->print_listing();
}


?>
