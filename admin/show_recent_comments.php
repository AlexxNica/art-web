<?php
require("mysql.inc.php");
require("common.inc.php");
require("comments.inc.php");
require("includes/headers.inc.php");

if (POST ('report_comment'))
{
	$comment_id = validate_input_regexp_default (POST ('comment_id'), '^[0-9]+$', -1);
	if ($comment_id > -1)
		report_comment (true, $comment_id);
}

// OUTPUT /////////////////////////////////////////////////////////////////////
admin_header ('Recent Comments', '20 Most Recent Comments');
?>

See also:
<a href="/admin/comments.php">Moderate Comments</a>;
<a href="/admin/">Administration Menu</a>
<hr/>
<?php

$result = mysql_query ("SELECT commentID, comment, timestamp, username, type, artID, status FROM comment INNER JOIN user ON comment.userID = user.userID ORDER BY timestamp DESC LIMIT 20");
while (list ($comment_id, $comment, $comment_time, $comment_username, $comment_type, $comment_artID, $comment_status) = mysql_fetch_row ($result))
{
	$art_result = mysql_query ("SELECT category, name FROM $comment_type WHERE {$comment_type}ID = $comment_artID");
	list ($comment_art_category, $comment_art_name) = mysql_fetch_row ($art_result);
	$comment_date = FormatRelativeDate (time (), $comment_time);
	echo <<<EOF
	<form action="{$_SERVER['PHP_SELF']}" method="post">
		<input type="hidden" name="comment_id" value="$comment_id"/>
		<input type="submit" name="report_comment" value="Report"/> - $comment_status - 
		<strong>Comment for <a href="/{$comment_type}s/$comment_art_category/$comment_artID">$comment_art_name</a></strong><br/>
		<span class="subtitle">$comment_date</span>
		<p style="margin:0px 0px 1em 0px;">$comment</p>
	</form>
	<hr/>
EOF;
}

admin_footer ();
?>
