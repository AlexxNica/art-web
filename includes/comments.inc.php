<?php

function print_comments($artID, $type)
{
	$comment_select_result = mysql_query("SELECT comment.commentID, comment.status, comment.userID, user.username, comment.comment, comment.timestamp FROM comment, user WHERE user.userID=comment.userID AND type='$type' and artID='$artID' and comment.status!='deleted' ORDER BY comment.timestamp");

	if($_SESSION['userID']) {
		$timezone_select_result = mysql_query("SELECT timezone FROM user WHERE `userID` = '".$_SESSION['userID']."'");
		extract(mysql_fetch_array($timezone_select_result, MYSQL_ASSOC));
	}

	$comment_count = mysql_num_rows($comment_select_result);

	

	if ($comment_count == 1)
	{
		//Only one Comment
		$msg = "comment";
	}
	else
	{
		//More than one comment
		$msg = "comments";
	}

	create_title("Comments", "This $type has $comment_count $msg");

	if($comment_count > 0)
	{
		print("<br />");
		$count = 0;

		
		
		while(list($commentID, $status, $userID, $username, $user_comment, $comment_time)=mysql_fetch_row($comment_select_result))
		{
			$count++;
			print("<table class=\"comment\">\n");
			print("<tr><td class=\"comment_head\">\n");
			print("<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"left\">\n");
			print("<i>$count: <a href=\"/users/$username\">$username</a> posted on " . date("Y-m-d - H:i", ($comment_time + (3600 * ($timezone + 5)))) . "</i>\n");
			print("</td><td align=\"right\">\n");
			
			if ($status == "reported")
			{
				print("Reported");
			}
			else if ($status == "approved")
			{
				print("Approved");
			}
			else
			{
				print("<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\"><div>\n");
				print("<input type=\"hidden\" name=\"commentID\" value=\"$commentID\" />\n");
				print("<input type=\"submit\" name=\"report\" value=\"(Report Abuse)\" class=\"link_button\" style=\"font-size: 0.8em;\" />");
				print("</div></form>\n");
			}
			
			print("</td></tr></table></td></tr>");
			print("<tr><td class=\"comment\">" . html_parse_text($user_comment) . "</td></tr>");
			print("</table><br />\n");
		}

	}
}

function report_comment($report, $commentID) 
{
	if ($report && $commentID > -1) 
	{
		mysql_query("UPDATE comment SET status='reported' where commentID='$commentID' AND status!='deleted'");
		//status!='deleted' because otherwise a deleted comment could be set reported by a user
	}
}

function print_comment_form($comment)
{

	print("<div class=\"h2\">Add a new comment</div>");
	if(!array_key_exists("username", $_SESSION))
	{
		print("<p class=\"info\">Only <a href=\"/account.php\">logged in</a> users may post comments.</p>\n");
	}
	else
	{
		$show_comment = "";
		
		if (strlen($comment) < 10 && strlen($comment) != 0) 
		{
			$comment_msg = "You comment is too short!<br />\n";
			$show_comment = $comment;
		}
		
		print("<a name=\"comment\"/>\n");
		print("<br /><form name=\"comment\" action=\"" . $_SERVER["PHP_SELF"] . "#comment\" method=\"post\">\n");
		
		print("<textarea cols=\"60\" rows=\"10\" name=\"comment\">$show_comment</textarea><br /><br />\n");
		print("<input type=\"submit\" name=\"send\" value=\"Add Comment\" />\n");
		print("</form>\n");
	}
}

function add_comment($artID, $type, $comment)
{
	if (array_key_exists("username", $_SESSION) and ($comment != ""))
	{
		if(strlen($comment) < 10)
		{
			return ("<p class=\"warning\">Comments must be more than 10 letters long!</p>");
		}
		$comment = mysql_real_escape_string($comment); // make sure it is safe for mysql
		$comment_result = mysql_query("INSERT INTO comment(`artID`, `userID`, `type`, `timestamp`, `comment`) VALUES('$artID', '" . $_SESSION['userID'] . "', '$type', '" . time() . "', '" . $comment . "')");
		if ($comment_result === False)
		{
			return ("<p class=\"error\">There was an error adding your comment.</p>");
		}
	}
}



?>
