<?php

function add_vote($artID, $rating, $userID, $type)
{
	if ($rating != -1)
	{
		$checkvote_result = mysql_query("SELECT `voteID` FROM `vote` WHERE `userID`='$userID' AND `artID`='$artID' AND type='$type'");
		if (mysql_num_rows($checkvote_result) >= 1)
		{
			mysql_query("UPDATE `vote` SET `rating` = '$rating' WHERE `userID`='$userID' AND `artID`='$artID' AND type='$type'");
		} else {
			mysql_query("INSERT INTO `vote` (`voteID`, `userID`, `artID`, `rating`, `type`) VALUES ('', '$userID', '$artID', '$rating', '$type')");
		}
	}
}

function print_detailed_view($description, $type, $release_date, $add_timestamp, $version, $license, $download_count, $download_start_timestamp, $vote, $vote_sum, $vote_count, $extra_rows, $thumbnail_url, $userrating)
{
	global $license_config_array;
	list($year, $month, $day) = explode("-", $release_date);

	$release_date = $year . "-" . $month . "-" . $day;

	$relative_date = FormatRelativeDate(time(), $add_timestamp);

	if ($vote_count < 5)
	{
		$rating = 0;
		$rating_text = "5 votes required";
		$rating_bar = "";
	}
	else
	{
		$rating = calculate_rating($vote_sum, $vote_count);
		$rating_text = "Rating: " . $rating . "%";
		$rating_bar = rating_bar($rating);
	}
			
	if($license == "")
		$license = "Not available";
	else
		$license = $license_config_array[$license];

	if($version == "0" || $version == "")
		$version = "Not available";

	print("<p>" . html_parse_text($description) . "</p>");

	print("<table class=\"info\">\n");
	print("\t<tr><th>Release Date</th><td>$release_date (last updated $relative_date)</td></tr>\n");
	print("\t<tr><th>Version</th><td>$version</td></tr>\n");
	print("\t<tr><th>License</th><td>$license</td></tr>\n");
	$downloads_per_day = calculate_downloads_per_day($download_count, $download_start_timestamp);
	print("\t<tr><th>Popularity</th><td>$downloads_per_day Downloads per Day ($download_count downloads in total)</td></tr>\n");

	print("\t<tr><th>Rating</th><td>\n");
	print("\t<div class=\"rating_text\" style=\"float:left\">\n");
	print($rating_bar);
	print("\t$rating_text, $vote_count votes</div>\n");
	switch ($vote) {
		case -1:
			print("\t<form class=\"rating_vote\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\"><div style=\"vertical-align: middle\">Vote:\n");
			print("\t[worst]");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"1\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"2\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"3\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"4\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"5\"/>\n");
			print("\t[best]");
			print("\t</div></form>\n");
		break;

		case 0:
			print("\t<form class=\"rating_vote\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n");
			print("\t<div class=\"rating_vote\">Change Your Vote:\n");
			print("\t[worst]");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"1\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"2\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"3\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"4\"/>\n");
			print("\t<input type=\"submit\" class=\"link_button\" name=\"rating\" value=\"5\"/>\n");
			print("\t[best]");
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>You rated this a $userrating.</i>\n");
			print("\t</div></form>\n");
		break;
	
		case 1:
			print("\t<div class=\"rating_vote\">\n");
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Only <a href=\"/account.php\">registered users</a> may vote.</i>\n");
			print("\t</div>\n");
		break;

		case 2:
			print("\t<div class=\"rating_vote\">\n");
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Sorry, you can't vote for your own work.</i>\n");
			print("\t</div>\n");
		break;

		default:
			print("\t<div class=\"rating_vote\">\n");
			print("\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Thanks for your vote.</i>\n");
			print("\t</div>\n");
		break;
	}

	print("\t</td></tr>\n");
	print($extra_rows);
	if ($type == "theme")
		print("</table>\n");

	print("<div style=\"text-align: center\"><img src=\"$thumbnail_url\" style=\"padding: 2px; border: none;\" class=\"large_thumbnail\" alt=\"thumbnail\" /></div>\n");
}


?>
