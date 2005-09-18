<?php

require_once("common.inc.php");

function print_comments($artID, $type)
{
	$comment_select_result = mysql_query("SELECT comment.commentID, comment.status, comment.userID, user.username, comment.comment, comment.timestamp FROM comment, user WHERE user.userID=comment.userID AND type='$type' and artID='$artID' and comment.status!='deleted' ORDER BY comment.timestamp DESC");

	if($_SESSION['userID']) {
		$timezone_select_result = mysql_query("SELECT timezone FROM user WHERE `userID` = '".$_SESSION['userID']."'");
		extract(mysql_fetch_array($timezone_select_result, MYSQL_ASSOC));
	}

	$comment_count = mysql_num_rows($comment_select_result);

	if ($comment_count == 1)
	{
		//Only one Comment
		$msg = "Comment";
	}
	else
	{
		//More than one comment
		$msg = "Comments";
	}

	print('<a name="comments"></a>');
	create_title("$comment_count $msg");

	if($comment_count > 0)
	{
		print("<br />");
		$count = 0;

		while(list($commentID, $status, $userID, $username, $user_comment, $comment_time)=mysql_fetch_row($comment_select_result))
		{
			$count++;
			print("<div class=\"comment\">\n");
			print("\t<div class=\"h2\">From <a href=\"/users/".urlencode("$username")."\">".htmlentities($username)."</a></div>\n");
			print("\t\t<div class=\"subtitle\">Posted ".FormatRelativeDate(time(), $comment_time ) . date(" - H:i", ($comment_time + (3600 * ($timezone + 5))))."</div>\n");

			print("\t\t<p>". html_parse_text($user_comment) . "</p>\n");

			if ($status == "reported")
			{
				print("\t<div style=\"text-align:right;\" class=\"abuse\">(Reported)</div>\n");
			}
			else if ($status == "approved")
			{
				print("\t<div style=\"text-align:right;\" class=\"abuse\">(Already Reviewed)</div>\n");
			}
			else
			{
				print("\t<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n");
				print("\t<div style=\"text-align:right;\" class=\"abuse\">\n");
				print("\t<input type=\"hidden\" name=\"commentID\" value=\"$commentID\" />\n");
				print("\t<input type=\"submit\" name=\"report\" value=\"(Report Abuse)\" class=\"link_button\" style=\"font-size: 0.8em;\" />\n");
				print("\t</div>\n");
				print("\t</form>\n");
			}
			print("</div>\n");
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
	$show_comment = "";

	if (strlen($comment) < 10 && strlen($comment) != 0) 
	{
		$comment_msg = "You comment is too short!<br />\n";
		$show_comment = $comment;
	}
		
	print("<a name=\"comment\"></a>\n");
	print("<br /><form name=\"comment\" action=\"" . $_SERVER["PHP_SELF"] . "#comment\" method=\"post\">\n");
		
	print("<textarea cols=\"60\" rows=\"10\" name=\"comment\">$show_comment</textarea><br /><br />\n");
	print("<input type=\"submit\" name=\"send\" value=\"Add Comment\" />\n");
	print("</form>\n");

}

function add_comment($artID, $type, $comment, $header)
{
	if ($comment)
	{
		if(strlen($comment) < 10)
		{
			return ("<p class=\"warning\">Comments must be more than 10 letters long!</p>");
		}
		elseif(is_logged_in($header))
		{
			$comment = mysql_real_escape_string($comment); // make sure it is safe for mysql
			$comment_result = mysql_query("INSERT INTO comment(`artID`, `userID`, `type`, `timestamp`, `comment`) VALUES('$artID', '" . $_SESSION['userID'] . "', '$type', '" . time() . "', '" . $comment . "')");
			if ($comment_result === False)
			{
				return ("<p class=\"error\">There was an error adding your comment.</p>");
			}
			else
			{
				/* redirect */
				global $server_url;
				/* XXX: removes the jump to the anchor ... little hacky maybe :) */
				list($file) = explode('#', $_SERVER['PHP_SELF']);
				header('Location: '.$server_url.$file);
				die();
			}
		}
	}
}



?>
