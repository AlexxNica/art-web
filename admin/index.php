<?php

include "mysql.inc.php";
include "includes/headers.inc.php";
include "common.inc.php";

admin_header("ART.GNOME.ORG Administration");


if (array_key_exists('login', $_POST))
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$query_result = mysql_query("SELECT userID, password, level FROM user WHERE username = '$username'");

	list($userID, $cryptpass, $level) = mysql_fetch_row($query_result);

	if ( (md5($password) == $cryptpass) && ($level) )
	{
		$_SESSION['admin_username'] = $username;
		$_SESSION['admin_userID'] = $userID;
		$_SESSION['admin_level'] = $level;
	}
	else
	{
		print("Login failed");
	}

}
elseif (array_key_exists('logout', $_POST))
{
	session_destroy();
	$_SESSION = Array();
}
elseif (array_key_exists('change_pass', $_POST))
{
	$new_pass = md5($_POST['password']);
	$query_result = mysql_query("UPDATE user SET password='$new_pass' WHERE userID = {$_SESSION['admin_userID']}");
	if ($query_result !== FALSE)
		print("Password changed.<br /><a href=\"{$_SERVER['PHP_SELF']}\">Continue.</a>");
	else
		print("Password change failed.");
}

if (array_key_exists('admin_username', $_SESSION))
{
	create_title("User Account","Logged in as {$_SESSION['admin_username']}");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\" />");
	print("Change Password:<input value=\"\" name=\"password\" />");
	print("<input type=\"submit\" value=\"Change\" name=\"change_pass\" />");
	print("</form>");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\" />");
	print("<input type=\"submit\" value=\"Logout\" name=\"logout\" />");
	print("</form>");

	create_title("Submissions","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"show_submitted_backgrounds.php\">Submitted Backgrounds</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"show_submitted_themes.php\">Submitted Themes</a><br>");

	create_title("Backgrounds","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_background.php\">Add A New Background</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_background.php\">Edit A Background</a><br>");
	print("&nbsp;&nbsp;&nbsp;Delete A Background<br>");


	create_title("Themes", "");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_theme.php\">Add A New Theme</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_theme.php\">Edit A Theme</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"delete_theme.php\">Delete A Theme</a><br>");

	create_title("News","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_news_item.php\">Add a News Item</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_news_item.php\">Edit News Item</a><br>");
	print("&nbsp;&nbsp;&nbsp;Delete News Item<br>");

	create_title("FAQ","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_faq.php\">Add FAQ Entry</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_faq.php\">Edit FAQ Entry</a><br>");
	print("&nbsp;&nbsp;&nbsp;<a href=\"delete_faq.php\">Delete FAQ Entry</a><br>");
	print("&nbsp;&nbsp;&nbsp;Re-Order FAQ<br>");



}
else
{

	create_title("Please log in","");
	print("<p>");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">");
	print("Username: <input name=\"username\" /><br />");
	print("Password: <input name=\"password\" type=\"password\" /><br />");
	print("<input type=\"submit\" value=\"Login\" name=\"login\" />");
	print("</form>");
	print("</p>");
}

admin_footer();


?>

