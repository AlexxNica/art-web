<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";

// Little hacky, see below
$error_fallback = false;

$redirect = false;
$error_message_length = false;
is_logged_in('Edit Comment');

// Ok, we want to save comment
if (POST ('save'))
{
  /* make sure all data are clean before SQL query */
	$commentID = validate_input_regexp_default ($_POST['commentID'], '^[0-9]+$', -1);
	$comment_text = escape_string($_POST['comment_text']);

  /* Is the comment long enough? */
	if (strlen($comment_text) < 10 && strlen($comment_text) != 0) 
	{
			// Ok, we haven't POST('preview') but we need get preview again
			$error_message_length = "<p class=\"error\">Error, your comment is too short!</p>\n";
			// So set $error_fallback = true and continue
			$error_fallback = true;
	}
	else
	{
	   /* we need a valid comment id! */
		if ($commentID == -1)
			art_fatal_error ('Edit Comment', 'Save Comment', 'Invalid comment ID');
	
		$comment_result = mysql_query ("SELECT userID FROM comment WHERE commentID = $commentID");
		$comment_array = mysql_fetch_array ($comment_result);

	   /* check this user owns the comment */
		if ($_SESSION['userID'] != $comment_array['userID'])
			art_fatal_error ('Edit Comment', 'Edit Comment', 'Go away! You cannot edit other people\'s comments');

	   /* Update database... */
		$time = time ();
		mysql_query ("UPDATE comment SET comment = '$comment_text', edit_time = '$time' WHERE commentID = $commentID LIMIT 1");
		$redirect = true;
	}

}

// Preview of the comment
// Here we go with $error_fallback :D
if((POST('preview')) or ($error_fallback))
{
  /* get post data and make sure there are no magic quotes as we are not adding to sql */
	$commentID = validate_input_regexp_default ($_POST['commentID'], '^[0-9]+$', -1);
	$comment_text = strip_string($_POST['comment_text']);

  /* We have to use escape_string() because of SQL query below */
	$comment_artID = escape_string($_POST['artID']);
	$comment_type = escape_string($_POST['type']);

  /* we need a valid comment id! */
	if ($commentID == -1)
		art_fatal_error ('Edit Comment', 'Edit Comment', 'Invalid comment ID');

	$comment_result = mysql_query ("SELECT `userID` FROM `comment` WHERE `commentID` = '$commentID' LIMIT 1");
	$comment_array = mysql_fetch_array ($comment_result);

  /* check this user owns the comment */
	if ($_SESSION['userID'] != $comment_array['userID'])
		art_fatal_error ('Edit Comment', 'Edit Comment', 'You cannot edit other people\'s comments');

	$art_result = mysql_query ("SELECT name, category FROM {$comment_type} WHERE {$comment_type}ID = $comment_artID");
	list ($art_name, $art_category) = mysql_fetch_array ($art_result);

	$art_link = "http://{$_SERVER['SERVER_NAME']}/{$comment_type}s/$art_category/$comment_artID";

	art_header ('Edit Comment (Preview)');
	if ((strlen($comment_text) < 10 && strlen($comment_text) != 0) and (!$error_fallback))
	{
		$error_message_length = "<p class=\"warning\">Warning, your comment is too short.</p>\n";
	}
	$template = new template ('edit_comment_preview.html');
	$template->add_var ('art_link', $art_link);
	$template->add_var ('art_name', $art_name);
	$template->add_var ('comment_text', htmlspecialchars($comment_text));
	$template->add_var ('comment_text_preview', html_parse_text($comment_text));
	$template->add_var ('commentID', $commentID);
	$template->add_var ('comment_artID', $comment_artID);
	$template->add_var ('comment_type', $comment_type);
	$template->add_var ('art_category', $art_category);
	$template->add_var ('error_log', $error_message_length);
	$template->add_var ('post_time', Date("j F Y - H:i:s"));
	$template->add_var ('user_name', $_SESSION['username']);
	$template->write ();
	art_footer ();
}

// No changes today
if (POST ('cancel') || $redirect)
{
	$type = $_POST['type'] . 's';
	$artID = $_POST['artID'];
	$category = $_POST['category'];
	header ("Location: http://{$_SERVER['SERVER_NAME']}/$type/$category/$artID");
}

// Validate comment ID
$commentID = validate_input_regexp_default ($_GET['commentID'], '^[0-9]+$', -1);

if ($commentID == -1)
	art_fatal_error ('Edit Comment', 'Edit Comment', 'Invalid comment ID');

// Read comment data from db
$comment_result = mysql_query ("SELECT comment, artID, type, userID FROM comment WHERE commentID = $commentID LIMIT 1");
$comment_array = mysql_fetch_array ($comment_result);

if ($_SESSION['userID'] != $comment_array['userID'])
	art_fatal_error ('Edit Comment', 'Edit Comment', 'You cannot edit other people\'s comments');

$comment_text = strip_string($comment_array['comment']);
$comment_artID = $comment_array['artID'];
$comment_type = $comment_array['type'];

if ($comment_type == 'background')
	$name_field = 'background_name';
else
	$name_field = 'name';

$art_result = mysql_query ("SELECT name, category FROM {$comment_type} WHERE {$comment_type}ID = $comment_artID LIMIT 1");
list ($art_name, $art_category) = mysql_fetch_array ($art_result);

$art_link = "http://{$_SERVER['SERVER_NAME']}/{$comment_type}s/$art_category/$comment_artID";

art_header ('Edit Comment');
$template = new template ('edit_comment.html');
$template->add_var ('art_link', $art_link);
$template->add_var ('art_name', $art_name);
$template->add_var ('comment_text', htmlspecialchars($comment_text));
$template->add_var ('commentID', $commentID);
$template->add_var ('comment_artID', $comment_artID);
$template->add_var ('comment_type', $comment_type);
$template->add_var ('art_category', $art_category);
$template->write ();
art_footer ();
?>
