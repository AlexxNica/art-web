<?php

require_once("common.inc.php");
require_once("art_listings.inc.php");

function add_vote($artID, $rating, $userID, $type, $header)
{
	// check for valid $type
	if (!($type == 'theme' or $type == 'background' or $type == 'screenshot'))
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

?>
