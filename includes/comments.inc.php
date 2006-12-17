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
				$form  = "\t<form action=\"{$_SERVER["PHP_SELF"]}\" method=\"post\">\n";
				$form .= "\t<div style=\"text-align:right;\" class=\"abuse\">\n";
				if ($_SESSION['userID'] == $userID) $form .= '	<a href="/edit_comment.php?commentID='.$commentID.'">Edit</a>';
				if ($_SESSION['userID'] == $userID) $form .= '	<a href="/delete_comment.php?commentID='.$commentID.'">Delete</a>';
				$form .= "\t<input type=\"hidden\" name=\"commentID\" value=\"$commentID\" />\n";
				$form .= "\t<input type=\"submit\" name=\"report\" value=\"(Report Abuse)\" class=\"link_button\" style=\"font-size: 0.8em;\" />\n";
				$form .= "\t</div>\n";
				$form .= "\t</form>\n";
			
				$template->add_var ('status', $form);
			}
			$result .= $template->parse ();
		}

	}
	return $result;
}

function report_comment($report, $commentID) 
{
	// First, validate comment ID
	$commentID = validate_input_regexp_default ($commentID, '^[0-9]+$', -1);

	if ($report && $commentID > -1) 
	{
		mysql_query("UPDATE comment SET status='reported' where commentID='$commentID' AND status!='deleted'");
		//status!='deleted' because otherwise a deleted comment could be set reported by a user
	}
}

function get_comment_form ($comment, $preview = false, $error_fallback = false)
{	
	$show_comment = strip_string($comment);

	/*
		If we want preview and it's not fallback (it shows own error on saving) and comment length < 10
		-or-
		Not preview but (0 < comment length < 10) - means do not show warning before we start writing the comment
	*/
	if ((($preview && !$error_fallback) and (strlen($show_comment) < 10)) or ((strlen($show_comment) < 10 && strlen($show_comment) != 0) and (!$preview)))
	{
		$error_message_length = "<p class=\"warning\">Warning, your comment is too short. Comments must be more than 10 letters long.</p>\n";
		$preview = true;
	}
	if ($preview)
	{
		$template = new template ('comments/preview.html');
		$template->add_var ('show-comment', htmlspecialchars($show_comment));
		$template->add_var ('comment-preview', html_parse_text($show_comment));
		$template->add_var ('error_log', $error_message_length);
		$template->add_var ('post-time', Date("j F Y - H:i:s"));
		$template->add_var ('user-name', $_SESSION['username']);
	}
	else
	{
		$template = new template ('comments/add.html');
		$template->add_var ('show-comment', $show_comment);
	}
	return $template->parse ();
}

function print_comment_form ($comment)
{
	print (get_comment_form ($comment));
}

function add_comment($artID, $type, $comment, $header)
{
	$comment = escape_string($comment);
	$artID = validate_input_regexp_default ($artID, '^[0-9]+$', -1);
	$type = escape_string($type); // Just prevet SQL injections, validated before calling this function
	if ($artID != -1)
	{
		if(strlen($comment) < 10)
		{
			return ("<p class=\"error\">Error, your comment is too short! Comments must be more than 10 letters long!</p>\n");
		}
		elseif(is_logged_in($header))
		{
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
	else
	{
			return ("<p class=\"error\">Error, Art ID is not valid!</p>\n");
	}
}



// Run any pending comment functions
$comment_message = '';

// Report Comment
$report = POST ('report');
$commentID = POST ('commentID');

if ($report && is_numeric ($commentID))
	report_comment($report, $commentID);
?>
