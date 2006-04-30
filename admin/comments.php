<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

admin_header("Moderate Comments","Back to <a href=\"/admin/\">admin panel</a>");
admin_auth(1);

if ($_POST['delete'] == true) {
	mysql_query("UPDATE comment SET status='deleted' WHERE commentID='".$_POST['commentID']."'");
	echo "Sucessfully deleted comment.  Click <a href=\"comments.php\">here</a> to return to the list.";
	admin_footer();
	die();
}

if ($_POST['reviewed'] == true) {	
	mysql_query("UPDATE comment SET status='reviewed' WHERE commentID='".$_POST['commentID']."'");
	echo "Sucessfully marked comment as reviewed.  Click <a href=\"comments.php\">here</a> to return to the list.";
	admin_footer();
	die();
}

// grab bad comments
$badcomments = "SELECT comment.commentID, comment.status, comment.userID, user.username, comment.comment, comment.timestamp, comment.type, comment.artID FROM comment, user WHERE user.userID=comment.userID AND comment.status='reported' ORDER BY comment.timestamp";
$badcomment_result = mysql_query($badcomments);
$badcomment_count = mysql_num_rows($badcomment_result);

switch ($badcomment_count)
{
	case 0:
		echo "There are no reported comments at this time.<br />";
		break;
	case 1:
		echo "There is 1 comment reported at this time.<br />";
		break;
	case 2:
		echo "There are $badcomment_count reported comments at this time.<br />";
		break;
}

if($_SESSION['userID']) {
	$timezone_select_result = mysql_query("SELECT timezone FROM user WHERE `userID` = '".$_SESSION['userID']."'");
	extract(mysql_fetch_array($timezone_select_result, MYSQL_ASSOC));
}

if($badcomment_count > 0)
{
	
	echo "<br />";
	$count = 0;
	
	while(list($commentID, $status, $userID, $username, $comment, $comment_time, $comment_type, $comment_artID)=mysql_fetch_row($badcomment_result))
	{
		$sql = "SELECT category, name FROM $comment_type WHERE {$comment_type}ID = $comment_artID";
		$result = mysql_query($sql);
		list($category, $name) = mysql_fetch_row($result);
		
		$link = "../".$comment_type."s/$category/$comment_artID";
		$count++;
		// display comment header
		print("<table class=\"comment\">\n");
		print("<tr><td class=\"comment_head\">");
		print("<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"left\">\n");
		print("<i>$count: <a href=\"/users/$username\">$username</a> posted on " . date("Y-m-d - H:i", ($comment_time + (3600 * ($timezone + 5)))) . " to <a href=\"$link\">".htmlentities($name)."</a> </i>\n");
		print("</td><td align=\"right\">\n");
		// mod stuff here.
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<input type=\"hidden\" name=\"commentID\" value=\"" . $commentID . "\" />\n");
		print("<input type=\"submit\" name=\"delete\" value=\"Delete\" class=\"link_button\" />");
		print("<input type=\"submit\" name=\"reviewed\" value=\"Reviewed\" class=\"link_button\" />");
		print("</form>\n");
		// print comment
		print("</td></tr></table>");
		print("<tr><td class=\"comment\">" . html_parse_text($comment) . "</td></tr>");
		print("</table><br/>\n");
	}
}




admin_footer();
?>
