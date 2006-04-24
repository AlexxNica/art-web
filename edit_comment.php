<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";


$redirect = false;
is_logged_in('Edit Comment');

if (POST ('save'))
{
	$commentID = validate_input_regexp_default ($_POST['commentID'], '^[0-9]+$', -1);
	if ($commentID == -1)
		art_fatal_error ('Edit Comment', 'Save Comment', 'Invalid comment ID');
	
	$comment_result = mysql_query ("SELECT userID FROM comment WHERE commentID = $commentID");
	$comment_array = mysql_fetch_array ($comment_result);

	if ($_SESSION['userID'] != $comment_array['userID'])
		art_fatal_error ('Edit Comment', 'Edit Comment', 'Go away! You cannot edit other people\'s comments');
	$comment_text = escape_string ($_POST['comment_text']);
	$time = time ();
	mysql_query ("UPDATE comment SET comment = '$comment_text', edit_time = '$time' WHERE commentID = $commentID");
	$redirect = true;
}

if (POST ('cancel') || $redirect)
{
	$type = $_POST['type'] . 's';
	$artID = $_POST['artID'];
	$category = $_POST['category'];
	header ("Location: http://{$_SERVER['SERVER_NAME']}/$type/$category/$artID");
}


$commentID = validate_input_regexp_default ($_GET['commentID'], '^[0-9]+$', -1);

if ($commentID == -1)
	art_fatal_error ('Edit Comment', 'Edit Comment', 'Invalid comment ID');


$comment_result = mysql_query ("SELECT * FROM comment WHERE commentID = $commentID");
$comment_array = mysql_fetch_array ($comment_result);

if ($_SESSION['userID'] != $comment_array['userID'])
	art_fatal_error ('Edit Comment', 'Edit Comment', 'You cannot edit other people\'s comments');

$comment_text = htmlspecialchars($comment_array['comment']);
$comment_artID = $comment_array['artID'];
$comment_type = $comment_array['type'];

if ($comment_type == 'background')
	$name_field = 'background_name';
else
	$name_field = 'name';

$art_result = mysql_query ("SELECT {$comment_type}_name AS name, category FROM {$comment_type} WHERE {$comment_type}ID = $comment_artID");
list ($art_name, $art_category) = mysql_fetch_array ($art_result);

$art_link = "http://{$_SERVER['SERVER_NAME']}/{$comment_type}s/$art_category/$comment_artID";

art_header ('Edit Comment');
$template = new template ('edit_comment.html');
$template->add_var ('art_link', $art_link);
$template->add_var ('art_name', $art_name);
$template->add_var ('comment_text', $comment_text);
$template->add_var ('commentID', $commentID);
$template->add_var ('comment_artID', $comment_artID);
$template->add_var ('comment_type', $comment_type);
$template->add_var ('art_category', $art_category);
$template->write ();
art_footer ();
?>
