<?php

include "mysql.inc.php";
include "common.inc.php";
include "includes/headers.inc.php";

admin_header("Users Administration");
admin_auth(2);

if (array_key_exists ('pass_reset', $_POST))
{
	$username = escape_string ($_POST['username']);
	$password = '';
	srand((double)microtime()*1000000);
	for ($rand = 0; $rand <= 8; $rand++)
	{
		$random = rand(97, 122);
		$password .= chr($random);
	}

	$password_enc = md5 ($password);

	$result = mysql_query ("UPDATE user SET password='$password_enc' WHERE username='$username' LIMIT 1");
	if ($result === FALSE || mysql_affected_rows() == 0)
		print ("\t<p class=\"error\">There was an error reseting the password for user \"$username\"</p>\n");
	else
		print ("\t<p class=\"info\">Password reset to \"$password\" for username \"$username\"</p>\n");

} elseif (array_key_exists ('set_level', $_POST))
{
	$username = escape_string ($_POST['username']);
	$level = validate_input_regexp_default ($_POST['level'], '^[0-2]$', '0');

	$levels = array('Normal', 'Moderator', 'Admin');

	$result = mysql_query ("UPDATE user SET level='$level' WHERE username='$username' LIMIT 1");

	if ($result === FALSE || mysql_affected_rows() == 0)
		print ("\t<p class=\"error\">There was an error seting the user level for user \"$username\" (may already be set)</p>\n");
	else
		print ("\t<p class=\"info\">User level set to ".$levels[$level]." for username \"$username\"</p>\n");
} elseif (array_key_exists ('set_global_level', $_POST))
{
	$level_array = $_POST['level'];
	$sql = "SELECT userID, level FROM user WHERE level > 0";
	$result = mysql_query($sql);
	$changes_ok = 0;
	$changes_skipped = 0;
	$changes_error = 0;
	while(list($userID, $level) = mysql_fetch_row($result))
	{
		if ($level_array[$userID] != $level)
		{
			$sql_update = "UPDATE user SET level='".$level_array[$userID]."' WHERE userID='$userID' LIMIT 1";
			$result_update = mysql_query ($sql_update);
			if ($result)
				$changes_ok++;
			else
				$changes_error++;
		}
		else
			$changes_skipped++;
	}
	print ("\t<p class=\"info\">Permissions changed for $changes_ok users, $changes_skipped times skipped (keep same permissions) and $changes_error times an error occured.</p>\n");
} elseif (array_key_exists ('set_block', $_POST))
{
	$username = escape_string ($_POST['username']);
	$result = mysql_query ("UPDATE user SET active=0 WHERE username='$username' LIMIT 1");
	if ($result === FALSE || mysql_affected_rows() == 0)
		print ("\t<p class=\"error\">There was an error blocking the user \"$username\"</p>\n");
	else
		print ("\t<p class=\"info\">User \"$username\" has been blocked.</p>\n");
} elseif (array_key_exists ('set_global_block', $_POST))
{
	$unblock_array = $_POST['unblock'];
	$sql = "SELECT userID FROM user WHERE active = 0";
	$result = mysql_query($sql);
	$changes_ok = 0;
	$changes_skipped = 0;
	$changes_error = 0;
	while(list($userID) = mysql_fetch_row($result))
	{
		if ($unblock_array[$userID])
		{
			$sql_update = "UPDATE user SET active=1 WHERE userID='$userID' AND active=0 LIMIT 1";
			$result_update = mysql_query ($sql_update);
			if ($result)
				$changes_ok++;
			else
				$changes_error++;
		}
		else
			$changes_skipped++;
			
	}
	print ("\t<p class=\"info\">$changes_ok users unblocked, $changes_skipped times skipped (keep user blocked) and $changes_error times an error occured.</p>\n");
}

?>

	<h2>Reset user password</h2>
	<form method="post" action="edit_user.php">
		<label>Username: <input name="username" /></label>
		<input type="submit" value="Reset" name="pass_reset" />
	</form>
	<hr />

	<h2>Set user level</h2>
	<form method="post" action="edit_user.php">
		<table>
			<tr><td>Username:</td><td><input name="username" /></td></tr>
			<tr>
				<td>User level:</td>
				<td>
					<select name="level">
						<option value="0">Normal</option>
						<option value="1">Moderator</option>
						<option value="2">Administrator</option>
					</select>
				</td>
			</tr>
			<tr><td><input type="submit" value="Set" name="set_level" /></td></tr>
		</table>
	</form>
	<br />

<?php
// DB output order
$order_output = false;
if ($_GET['order'] == 'id') $order_output = 'userID';
if ($_GET['order'] == 'login') $order_output = 'username';
if ($_GET['order'] == 'name') $order_output = 'realname';
if ($_GET['order'] == 'level') $order_output = 'level';
if (!$order_output) $order_output = 'userID';	// Default order

$sql = "SELECT userID, username, realname, level FROM user WHERE level > 0 ORDER BY $order_output ASC";
$result = mysql_query($sql);

// if records found, print table head
if (mysql_num_rows($result) > 0)
{
	print("\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
	print("\t\t<table class=\"comment_table\">\n\t\t\t<tr>\n\t\t\t\t<td class=\"table_topic\" colspan=\"5\">List of admins and moderators</h2></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n");
	foreach (array('id' => 'UserID', 'login' => 'Username', 'name' => 'Real name', 'level' => 'Level') as $name => $value)
	{
		print("\t\t\t\t<th class=\"comment_head\"><a href=\"".$_SERVER["PHP_SELF"]."?order=$name\">$value</a></th>\n");
	}
		print("\t\t\t\t<th class=\"comment_head\">Change status</th>\n");
	print ("\t\t\t</tr>\n");
}

while(list($userID, $username, $realname, $level) = mysql_fetch_row($result))
{
	print("\t\t\t<tr>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$userID</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$username</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$realname</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">");
	if ($level == 0) print("Normal user"); // Not listed, but for sure...
	elseif ($level == 1) print("Moderator");
	elseif ($level == 2) print("Administrator");
	else  print("<p class=\"error\" style=\"margin: 0px;\">Error (level: $level)</p>");
	print("</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">\n\t\t\t\t\t<select name=\"level[$userID]\">\n");
	print("\t\t\t\t\t\t<option value=\"0\">Normal</option>\n");
	print("\t\t\t\t\t\t<option value=\"1\"");
	if ($level == 1)
		print(" selected=\"selected\"");
	print (">Moderator</option>\n");
	print("\t\t\t\t\t\t<option value=\"2\"");
	if ($level == 2)
		print(" selected=\"selected\"");
	print (">Administrator</option>\n");
	print("\t\t\t\t\t</select>\n\t\t\t\t</td>\n\t\t\t</tr>\n");
}

// if records found, print table foot
if (mysql_num_rows($result) > 0)
{
	print("\t\t</table>\n");
	print("\t\t<br />\n\t\t<input type=\"submit\" value=\"Set\" name=\"set_global_level\" />\n");
	print("\t</form>\n\t<hr />\n");
}
?>

	<h2>Block user account</h2>
	<form method="post" action="edit_user.php">
		<label>Username: <input name="username" /></label>
		<input type="submit" value="Block user" name="set_block" />
	</form>
	<br />

<?php
// DB output order
$block_order_output = false;
if ($_GET['block_order'] == 'id') $block_order_output = 'userID';
if ($_GET['block_order'] == 'login') $block_order_output = 'username';
if ($_GET['block_order'] == 'name') $block_order_output = 'realname';
if (!$block_order_output) $block_order_output = 'userID';	// Default order

$sql_block = "SELECT userID, username, realname FROM user WHERE active = 0 ORDER BY $block_order_output ASC";
$result_block = mysql_query($sql_block) or die(mysql_error());

// if records found, print table head
if (mysql_num_rows($result_block) > 0)
{
	print("\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
	print("\t\t<table class=\"comment_table\">\n\t\t\t<tr>\n\t\t\t\t<td class=\"table_topic\" colspan=\"4\">List of blocked accounts</h2></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n");
	foreach (array('id' => 'UserID', 'login' => 'Username', 'name' => 'Real name') as $name => $value)
	{
		print("\t\t\t\t<th class=\"comment_head\"><a href=\"".$_SERVER["PHP_SELF"]."?block_order=$name\">$value</a></th>\n");
	}
		print("\t\t\t\t<th class=\"comment_head\">Unblock account</th>\n");
	print ("\t\t\t</tr>\n");
}

while(list($userID, $username, $realname) = mysql_fetch_row($result_block))
{
	print("\t\t\t<tr>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$userID</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$username</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">$realname</td>\n\t\t\t\t");
	print ("<td style=\"border-bottom: 1px dotted gray;\">\n\t\t\t\t\t<input type=\"checkbox\" name=\"unblock[$userID]\" id=\"remove_$userID\" />\n\t\t\t\t\t<label for=\"remove_$userID\">Unblock</label>\n\t\t\t\t</td>\n\t\t\t</tr>\n");
}

// if records found, print table foot
if (mysql_num_rows($result_block) > 0)
{
	print("\t\t</table>\n");
	print("\t\t<br />\n\t\t<input type=\"submit\" value=\"Change\" name=\"set_global_block\" />\n");
	print("\t</form>");
}

admin_footer();
?>
