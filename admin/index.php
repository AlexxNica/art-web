<?php

include "mysql.inc.php";
include "includes/headers.inc.php";
include "common.inc.php";

admin_header("ART.GNOME.ORG Administration");

if (array_key_exists('login', $_POST))
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$query_result = mysql_query("SELECT userID, password, admin FROM user WHERE username = '$username'");

	list($userID, $cryptpass, $admin) = mysql_fetch_row($query_result);

	if ( (md5($password) == $cryptpass) && ($admin) )
	{
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $userID;
		$_SESSION['admin_level'] = $admin;
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
	$query_result = mysql_query("UPDATE user SET password='$new_pass' WHERE userID = {$_SESSION['userID']}");
	if ($query_result !== FALSE)
		print("Password changed.<br /><a href=\"{$_SERVER['PHP_SELF']}\">Continue.</a>");
	else
		print("Password change failed.");
}

if (array_key_exists('username', $_SESSION))
{
	print("<b>Logged in as {$_SESSION['username']}.</b>");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\" />");
	print("Change Password:<input value=\"\" name=\"password\" />");
	print("<input type=\"submit\" value=\"Change\" name=\"change_pass\" />");
	print("</form>");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\" />");
	print("<input type=\"submit\" value=\"Logout\" name=\"logout\" />");
	print("</form>");
?>


<hr />
<h3>Backgrounds</h3>
&nbsp;&nbsp;&nbsp;<a href="add_background.php">Add A New Background</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_background.php">Edit A Background</a><br>
&nbsp;&nbsp;&nbsp;Delete A Background<br>


<h3>Themes</h3>
&nbsp;&nbsp;&nbsp;<a href="add_theme.php">Add A New Theme</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_theme.php">Edit A Theme</a><br>
&nbsp;&nbsp;&nbsp;<a href="delete_theme.php">Delete A Theme</a><br>

<h3>News</h3>
&nbsp;&nbsp;&nbsp;<a href="add_news_item.php">Add a News Item</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_news_item.php">Edit News Item</a><br>
&nbsp;&nbsp;&nbsp;Delete News Item<br>

<h3>FAQ</h3>
&nbsp;&nbsp;&nbsp;<a href="add_faq.php">Add FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_faq.php">Edit FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;<a href="delete_faq.php">Delete FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;Re-Order FAQ<br>

<h3>Submissions</h3>
&nbsp;&nbsp;&nbsp;<a href="show_submitted_backgrounds.php">Submitted Backgrounds</a><br>
&nbsp;&nbsp;&nbsp;<a href="show_submitted_themes.php">Submitted Themes</a><br>

<?php

}
else
{

	print("Please log in<hr />");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">");
	print("Username: <input name=\"username\" />");
	print("Password: <input name=\"password\" type=\"password\" />");
	print("<input type=\"submit\" value=\"Login\" name=\"login\" />");
	print("</form>");
}

admin_footer();


?>

