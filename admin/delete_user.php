
<?php

include "mysql.inc.php";
include "includes/headers.inc.php";
include "common.inc.php";

admin_header("Users Administration");
admin_auth(2);

if (!array_key_exists("action", $_POST))
{
	print("<div class=\"h2\">Delete User</div>");
	print("<form method=\"POST\" action=\"{$_SERVER['PHP_SELF']}\"><input type=\"hidden\" name=\"action\" value=\"del_user\" />");
	print("<select name=\"userID\">");
	$user_select_result = mysql_query("SELECT userID, username FROM user WHERE active = 1");
	while (list($userID,$username) = mysql_fetch_row($user_select_result))
	{
		print("<option value=\"$userID\">$username</option>");
	}
	print("</select>");
	print("<input type=\"submit\" value=\"Delete\">");
	print("</form>");
}
else
{
	$userID = validate_input_regexp_default ($_POST['userID'], "^[0-9]+$", "-1");
	$del_user_result = mysql_query("UPDATE user SET active=0 WHERE userID = $userID LIMIT 1");
	if (!$del_user_result)
	{
		print("<p>The following error occured while trying delete the user:</p>");
		print("<tt>".mysql_error()."</tt>");
	}
	else
	{
		print("User $userID deleted.");
	}
}

admin_footer();


?>

