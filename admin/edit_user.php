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
		<tr><td>User level:</td><td>
		<select name="level">
			<option value="0">Normal</option>
			<option value="1">Moderator</option>
			<option value="2">Administrator</option>
		</select></td></tr>
		<tr><td><input type="submit" value="Set" name="set_level" /></td></tr>
	</table>
</form>
<?php admin_footer(); ?>
