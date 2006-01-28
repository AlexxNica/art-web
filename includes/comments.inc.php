<?php

require_once ("common.inc.php");


function get_comments ($artID, $type)
{
	$result = '';

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

/*
	print('<a name="comments"></a>');
	create_title("$comment_count $msg");
*/
	if($comment_count > 0)
	{
		$count = 0;

		$template = new template ('comments/display.html');
		while(list($commentID, $status, $userID, $username, $user_comment, $comment_time)=mysql_fetch_row($comment_select_result))
		{
			$count++;
			$template->add_var ('username-url', '/users/'.rawurlencode ($username));
			$template->add_var ('username', htmlentities ($username));
			$template->add_var ('post-date', FormatRelativeDate(time(), $comment_time ) . date(" - H:i", ($comment_time + (3600 * ($timezone + 5)))));
			$template->add_var ('comment', html_parse_text ($user_comment));

			if ($status == "reported")
			{
				$template->add_var ('status', '(Reported)');
			}
			else if ($status == "approved")
			{
				$template->add_var ('status', '(Already Reviewed)');
			}
			else
			{
			
				$form = ("\t<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n");
				$form .= ("\t<div style=\"text-align:right;\" class=\"abuse\">\n");
				$form .= ("\t<input type=\"hidden\" name=\"commentID\" value=\"$commentID\" />\n");
				$form .= ("\t<input type=\"submit\" name=\"report\" value=\"(Report Abuse)\" class=\"link_button\" style=\"font-size: 0.8em;\" />\n");
				$form .= ("\t</div>\n");
				$form .= ("\t</form>\n");
			
				$template->add_var ('status', $form);
			}
			$result .= $template->parse ();
		}

	}
	return $result;
}

function report_comment($report, $commentID) 
{
	if ($report && $commentID > -1) 
	{
		mysql_query("UPDATE comment SET status='reported' where commentID='$commentID' AND status!='deleted'");
		//status!='deleted' because otherwise a deleted comment could be set reported by a user
	}
}

function get_comment_form ($comment)
{
	$show_comment = "";
	if (strlen($comment) < 10 && strlen($comment) != 0) 
	{
		$comment_msg = "You comment is too short!<br />\n";
		$show_comment = $comment;
	}

	$template = new template ('comments/add.html');
	$template->add_var ('show-comment', $show_comment);
	return $template->parse ();
}

function print_comment_form ($comment)
{
	print (get_comment_form ($comment));
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
			$comment = escape_string ($comment); // make sure it is safe for mysql
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



// Run any pending comment functions
$comment_message = '';

// Report Comment
$report = POST ('report');
$commentID = POST ('commentID');

if ($report && is_numeric ($commentID))
	report_comment($report, $commentID);

// Add Comment
$comment = POST ('comment'); // this is made safe later
list ($foo, $type, $category, $item) = explode ('/', $_SERVER['PHP_SELF']);
$type = substr ($type, 0, strlen ($type) -1 ); // Remove plural 's'

if ($comment)
	$comment_message = add_comment($item, $type, $comment, $header);



?>
