<?php

include "mysql.inc.php";
include "includes/headers.inc.php";
include "common.inc.php";

admin_header("Users Administration");

if (!array_key_exists("action", $_POST))
{
	print("<div class=\"h2\">Add user</div>");
	print("<form method=\"POST\" action=\"{$_SERVER['PHP_SELF']}\"><input type=\"hidden\" name=\"action\" value=\"new_user\" />");
	print("<table>");
	print("<tr><td>Username:</td><td><input name=\"username\" /></td></tr>");
	print("<tr><td>Password:</td><td><input name=\"password\" /></td></tr>");
	print("<tr><td>Realname:</td><td><input name=\"realname\" /></td></tr>");
	print("<tr><td>E-mail:</td><td><input name=\"email\" /></td></tr>");
	print("</table>");
	print("<input type=\"submit\">");
	print("</form>");

	print("<div class=\"h2\">Reset user password</div>");
	print("<form method=\"POST\" action=\"{$_SERVER['PHP_SELF']}\"><input type=\"hidden\" name=\"action\" value=\"pass_reset\" />");
	print("<table>");
	print("<tr><td>UserID:</td><td><input name=\"userID\" /></td></tr>");
	print("<tr><td>Password:</td><td><input name=\"password\" /></td></tr>");
	print("</table>");
	print("<input type=\"submit\">");
	print("</form>");
}
elseif ($_POST['action'] == "new_user")
{
	$username = addslashes($_POST['username']);
	$realname = addslashes($_POST['realname']);
	$email = addslashes($_POST['email']);
	$password = md5($_POST['password']);
	$new_user_result = mysql_query("INSERT INTO user (username,realname,password) VALUES ('$username','$realname','$password')");
	if (!$new_user_result)
	{
		print("<p>The following error occured while trying to create a new user:</p>");
		print("<tt>".mysql_error()."</tt>");
	}
	else
	{
		print("New user ($username) created.");
	}
}

admin_footer();


?>

