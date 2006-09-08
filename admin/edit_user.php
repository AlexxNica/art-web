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
		print ('<p class="error">There was an error reseting the password for user "'.$username.'"</p>');
	else
		print ('<p class="info">Password reset to "'.$password.'" for username "'.$username.'"</p>');

} elseif (array_key_exists ('set_level', $_POST))
{
	$username = escape_string ($_POST['username']);
	$level = validate_input_regexp_default ($_POST['level'], '^[0-2]$', '0');

	$levels = array('Normal', 'Moderator', 'Admin');

	$result = mysql_query ("UPDATE user SET level='$level' WHERE username='$username' LIMIT 1");

	if ($result === FALSE || mysql_affected_rows() == 0)
		print ('<p class="error">There was an error seting the user level for user "'.$username.'" (may already be set)</p>');
	else
		print ('<p class="info">User level set to '.$levels[$level].' for username "'.$username.'"</p>');
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
			$sql_update = "UPDATE user SET level='$level_array[$userID]' WHERE userID='$userID' LIMIT 1";
			$result_update = mysql_query ($sql_update);
			if ($result)
				$changes_ok++;
			else
				$changes_error++;
		}
		else
			$changes_skipped++;
	}
	print ("<p class=\"info\">Permissions changed for $changes_ok users, $changes_skipped times skipped (keep same permissions) and $changes_error times an error occured.</p>");
}

?>

	<h2>Reset user password</h2>
	<form method="POST" action="edit_user.php">
		<label>Username: <input name="username" /></label>
		<input type="submit" value="Reset" name="pass_reset" />
	</form>

	<h2>Set user level</h2>
	<form method="POST" action="edit_user.php">
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

<?php
// DB output order
$order_output = false;
if ($_POST['order_by_id']) $order_output = 'userID';
if ($_POST['order_by_login']) $order_output = 'username';
if ($_POST['order_by_name']) $order_output = 'realname';
if ($_POST['order_by_level']) $order_output = 'level';
if (!$order_output) $order_output = 'userID';	// Default order

$sql = "SELECT userID, username, realname, level FROM user WHERE level > 0 ORDER BY $order_output ASC";
$result = mysql_query($sql);

// if records found, print table head
if (mysql_num_rows($result) > 0)
{
	print("\t<h2>List of admins and moderators</h2>\n");
	print("\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
	print("\t\t<table class=\"comment_table\">\n");
	print("\t\t\t<tr>\n");
	foreach (array('oder_by_id' => 'UserID', 'order_by_login' => 'Username', 'order_by_name' => 'Real name', 'order_by_level' => 'Level') as $name => $value)
	{
		print("\t\t\t\t<th class=\"comment_head\">\n\t\t\t\t\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
		print("\t\t\t\t\t\t<input class=\"link_button\" type=\"submit\" name=\"$name\" value=\"$value\" />\n");
		print("\t\t\t\t\t</form>\n\t\t\t\t</th>\n");
	}
		print("\t\t\t\t<th class=\"comment_head\">Change status</th>");
	print ("\t\t\t</tr>\n");
}

while(list($userID, $username, $realname, $level) = mysql_fetch_row($result))
{
	print("\t\t\t<tr>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">".$userID."</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">".$username."</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">".$realname."</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">");
	if ($level == 0) print("Normal user"); // Not listed, but for sure...
	elseif ($level == 1) print("Moderator");
	elseif ($level == 2) print("Administrator");
	else  print("<p class=\"error\" style=\"margin: 0px;\">Error (level: ".$level.")</p>");
	print("</td>\n\t\t\t\t<td style=\"border-bottom: 1px dotted gray;\">\n\t\t\t\t\t<select name=\"level[".$userID."]\">\n");
	print("\t\t\t\t\t<option value=\"0\">Normal</option>\n");
	print("\t\t\t\t\t<option value=\"1\"");
	if ($level == 1)
		print(" selected=\"selected\"");
	print (">Moderator</option>\n");
	print("\t\t\t\t\t<option value=\"2\"");
	if ($level == 2)
		print(" selected=\"selected\"");
	print (">Administrator</option>\n");
	print("\t\t\t\t</select></td>\n\t\t\t</tr>\n");
}

// if records found, print table foot
if (mysql_num_rows($result) > 0)
{
	print("\t\t</table>\n");
	print("\t\t<br />\n\t\t<input type=\"submit\" value=\"Set\" name=\"set_global_level\" />");
	print("\t</form>\n");
}

admin_footer();
?>
