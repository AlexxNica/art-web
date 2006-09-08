<?php
require("mysql.inc.php");
require("common.inc.php");
require("comments.inc.php");
require("includes/headers.inc.php");

if (POST ('report_comment'))
{
	$comment_id = validate_input_regexp_default (POST ('comment_id'), '^[0-9]+$', -1);
	if ($comment_id > -1)
		mysql_query("UPDATE comment SET status='reported' WHERE commentID='".$comment_id."'");
}
if (POST ('unreport_comment'))
{
	$comment_id = validate_input_regexp_default (POST ('comment_id'), '^[0-9]+$', -1);
	if ($comment_id > -1)
		mysql_query("UPDATE comment SET status='new' WHERE commentID='".$comment_id."'");
}
if (POST ('delete_comment')) 
{
	$comment_id = validate_input_regexp_default (POST ('comment_id'), '^[0-9]+$', -1);
	if ($comment_id > -1)
		mysql_query("UPDATE comment SET status='deleted' WHERE commentID='".$comment_id."'");
}
if (POST ('undelete_comment'))
{
	$comment_id = validate_input_regexp_default (POST ('comment_id'), '^[0-9]+$', -1);
	if ($comment_id > -1)
		mysql_query("UPDATE comment SET status='reviewed' WHERE commentID='".$comment_id."'");
}

// OUTPUT /////////////////////////////////////////////////////////////////////
admin_header ('Recent Comments', 'Recent Comments listing');
?>

	See also:
	<a href="/admin/comments.php">Moderate Comments</a>;
	<a href="/admin/">Administration Menu</a>
	<hr/>

<?php

// listing things :) default is 0 (recent comments)
$last_start = validate_input_regexp_default ($_POST['last_start'], '^[0-9]+$', -1);
if ($last_start == -1) $last_start = 0;
if ($_POST['list_prev'])
{
	$start_listing = $last_start-20;
	if ($start_listing < 0) $start_listing = 0;
}
elseif ($_POST['list_next'])
{
	$start_listing = $last_start+20;
	if ($start_listing < 0) $start_listing = 0;
}
else
{
	$start_listing = $last_start;
}

$output_exists = false;
$result = mysql_query ("SELECT commentID, comment, timestamp, username, type, artID, status FROM comment INNER JOIN user ON comment.userID = user.userID ORDER BY timestamp DESC LIMIT $start_listing, 20");
while (list ($comment_id, $comment, $comment_time, $comment_username, $comment_type, $comment_artID, $comment_status) = mysql_fetch_row ($result))
{
	$art_result = mysql_query ("SELECT category, name FROM $comment_type WHERE {$comment_type}ID = $comment_artID");
	list ($comment_art_category, $comment_art_name) = mysql_fetch_row ($art_result);
	$comment_date = FormatRelativeDate (time (), $comment_time);

	print("\t<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\"");
	if ($comment_status == 'deleted')
		print(" style=\"color: gray;\"");
	print(">\n");
	print("\t\t<input type=\"hidden\" name=\"comment_id\" value=\"".$comment_id."\"/>\n");
	print("\t\t<input type=\"hidden\" name=\"last_start\" value=\"".$start_listing."\"/>\n");

	if ($comment_status == 'reported')
		print("\t\t<input type=\"submit\" name=\"unreport_comment\" value=\"Unreport\" />\n");
	else
		print("\t\t<input type=\"submit\" name=\"report_comment\" value=\"Report\" />\n");

	if ($comment_status == 'deleted')
		print("\t\t<input type=\"submit\" name=\"undelete_comment\" value=\"Undelete\" />\n");
	else
		print("\t\t<input type=\"submit\" name=\"delete_comment\" value=\"Delete\" />\n");

	print("\t\t - ".$comment_status." - \n");
	print("\t\t<strong>Comment for <a href=\"/".$comment_type."s/".$comment_art_category."/".$comment_artID."\">".$comment_art_name."</a></strong><br/>\n");
	print("\t\t<span class=\"subtitle\">".$comment_date."</span>\n");
	print("\t\t<p style=\"margin:0px 0px 1em 0px;");
	if ($comment_status == 'deleted')
		print(" text-decoration: line-through;");
	print("\">\n\t\t\t".$comment."\n\t\t</p>\n");
	print("\t</form>\n");
	print("\t<hr />\n");
	$output_exists = true;
}
print("\t<div style=\"text-align: center;\">\n");
print("\t\t<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n");
print("\t\t\t<input type=\"hidden\" name=\"last_start\" value=\"".$start_listing."\"/>\n");
print("\t\t\t<input type=\"submit\" name=\"list_prev\" value=\"&lt; Previous 20 comments\"");
if ($start_listing < 20)
	print(" disabled=\"disabled\"");
print(" />\n");
print("\t\t\t&nbsp;Page number: ".(($start_listing/20)+1)."&nbsp;\n");
print("\t\t\t<input type=\"submit\" name=\"list_next\" value=\"Next 20 comments &gt;\"");
if (!$output_exists)
	print(" disabled=\"disabled\"\n");
print(" />\n");
print("\t\t</form>\n");
print("\t</div>\n");
print("\t<hr />\n");

admin_footer ();
?>
