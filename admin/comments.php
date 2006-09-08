<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

admin_header("Moderate Comments","Back to <a href=\"/admin/\">admin panel</a>");
admin_auth(1);
if ($_POST['global_comment'] == true) {
	if ($_POST['global_comment_action'] == 'remove') $status_part = 'deleted';
	elseif ($_POST['global_comment_action'] == 'review') $status_part = 'reviewed';
	else  $status_part = false;

	if ($status_part)
	{
		$sql_query = "UPDATE comment SET status='".$status_part."' WHERE ";
		$new_remove_item_array = $_POST["g_remove_item"];
		$new_comment_id_arraw = $_POST["g_comment_ID"];

		$query_limit = 0;
		for ($r = 1; $r <= $_POST["comment_count"]; $r++) 
		{
			if ($new_remove_item_array[$r])
			{
				$where_part .= " OR commentID='".$new_comment_id_arraw[$r]."'";
				$query_limit++;
			}
		}

		if ($where_part)
		{
			$sql_query .= SubStr($where_part, 4).' LIMIT '.$query_limit;
			$result = mysql_query($sql_query);
			if ($result)
				echo "Sucessfully marked ".$query_limit." comments as ".$status_part.".  Click <a href=\"comments.php\">here</a> to return to the list.";
			else
			{
				echo "\t<p class=\"error\">An error occured in sql query.</p>\n";
				echo "\tSQL Query: ".$sql_query."<br />\n";
				echo "\tSQL Error: ".mysql_error()."<br />\n";
				echo "\tClick <a href=\"comments.php\">here</a> to return to the list.\n";
			}
		}
		else
		{
			echo "\t<p class=\"error\">You must select one or more comments to operate with.</p>\n";
			echo "\tClick <a href=\"comments.php\">here</a> to return to the list.\n";
		}
	}
	else
	{
		echo "\t<p class=\"error\">You must select an action.</p>\n";
		echo "\tClick <a href=\"comments.php\">here</a> to return to the list.\n";
	}
	admin_footer();
	die();
}
?>

	See also: <a href="/admin/show_recent_comments.php">Recent Comments</a><br />
	<hr />

<?php

// grab bad comments
$badcomments = "SELECT comment.commentID, comment.status, comment.userID, user.username, comment.comment, comment.timestamp, comment.type, comment.artID FROM comment, user WHERE user.userID=comment.userID AND comment.status='reported' ORDER BY comment.timestamp";
$badcomment_result = mysql_query($badcomments);
$badcomment_count = mysql_num_rows($badcomment_result);

switch ($badcomment_count)
{
	case 0:
		echo "\tThere are no reported comments at this time.<br />\n\t<hr />";
		break;
	case 1:
		echo "\tThere is 1 comment reported at this time.<br />\n\t<hr />";
		break;
	case 2:
		echo "\tThere are $badcomment_count reported comments at this time.<br />\n\t<hr />";
		break;
}

if($_SESSION['userID']) {
	$timezone_select_result = mysql_query("SELECT timezone FROM user WHERE `userID` = '".$_SESSION['userID']."'");
	extract(mysql_fetch_array($timezone_select_result, MYSQL_ASSOC));
}

if($badcomment_count > 0)
{
	
	$count = 0;

	echo "\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
	echo "\t\t<table class=\"comment_table\">\n";

	while(list($commentID, $status, $userID, $username, $comment, $comment_time, $comment_type, $comment_artID)=mysql_fetch_row($badcomment_result))
	{
		$sql = "SELECT category, name FROM $comment_type WHERE {$comment_type}ID = $comment_artID";
		$result = mysql_query($sql);
		list($category, $name) = mysql_fetch_row($result);
		
		$link = "../".$comment_type."s/$category/$comment_artID";
		$count++;
		// display comment header
?>
			<tr>
				<td class="comment_head" style="white-space: nowrap">
					&nbsp;<input type="checkbox" name="g_remove_item[<?php echo $count; ?>]" /> <?php echo $count; ?>

					<input type="hidden" name="g_comment_ID[<?php echo $count; ?>]" value="<?php echo $commentID; ?>" />
				</td>
				<td class="comment_head" style="white-space: nowrap">
					<strong>By:</strong> <a href="/users/<?php echo $username; ?>"><?php echo $username; ?></a>
				</td>
				<td class="comment_head" style="white-space: nowrap">
					<strong>Posted:</strong> <?php echo date("Y-m-d - H:i", ($comment_time + (3600 * ($timezone + 5)))); ?>

				</td>
				<td class="comment_head">
					<strong>Theme:</strong> <a href="<?php echo $link; ?>"><?php echo htmlentities($name); ?></a>
				</td>
			</tr>
			<tr>
				<td class="comment" colspan="5">
					<?php echo html_parse_text($comment); ?>

				</td>
			</tr>
<?php
	}
?>
		</table>
		<hr />
		All selected comments <select name="global_comment_action">
			<option value="" selected>- choose action -</option>
			<option value="remove">remove</option>
			<option value="review">mark as reviewed</option>
		</select> <input type="submit" name="global_comment" value="Do it"/>
		<input type="hidden" name="comment_count" value="<?php echo $count; ?>" />
	</form>
<?php
}

admin_footer();
?>
