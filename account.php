<?php


include "mysql.inc.php";
include "ago_headers.inc.php";
include "common.inc.php";

ago_header("Account");

if (array_key_exists('login', $_POST))
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$query_result = mysql_query("SELECT userID, realname, password FROM user WHERE username = '$username'");
	$referer = validate_input_regexp_default($_POST['referer'], "^[a-z0-9\./]+$", "/account.php");

	list($userID, $realname, $cryptpass ) = mysql_fetch_row($query_result);

	if ( (md5($password) == $cryptpass)  )
	{
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $userID;
		$_SESSION['realname'] = $realname;
		mysql_query("UPDATE user SET lastlog=NOW() WHERE userid=$userID;");
		create_title("Login Successful", "");
		print("<p>You are now logged in as $username. <a href=\"$referer\">Continue...</a></p>");
	}
	else
	{
		create_title("Login failed","");
		print("<p>Please <a href=\"{$_SERVER["PHP_SELF"]}\">try again</a>.</p>");
	}

}
elseif (array_key_exists('logout', $_POST))
{
	session_destroy();
	$_SESSION = Array();
	create_title("Logout","");
	print("<p>You have been logged out.  <a href=\"{$_SERVER["PHP_SELF"]}\">Continue...</a></p>");
}
elseif (array_key_exists('change_profile', $_POST))
{
	if (get_magic_quotes_runtime() == 1)
	{
		$_POST['password'] = stripslashes ($_POST['password']);
		$_POST['info'] = stripslashes ($_POST['info']);
		$_POST['realname'] = stripslashes ($_POST['realname']);
		$_POST['email'] = stripslashes ($_POST['email']);
		$_POST['homepage'] = stripslashes ($_POST['homepage']);
	}

	$new_pass = mysql_real_escape_string ($_POST['password']);
	$info = mysql_real_escape_string ($_POST['info']);
	$realname = mysql_real_escape_string ($_POST['realname']);
	$email = mysql_real_escape_string ($_POST['email']);
	$homepage = mysql_real_escape_string ($_POST['homepage']);

	$pass_sql = "";
	if ($new_pass != "")
	{
		$pass_sql = " password='".md5($new_pass)."', ";
	}
	$query_result = mysql_query("UPDATE user SET $pass_sql realname='$realname', info='$info', email='$email', homepage='$homepage' WHERE userID = {$_SESSION['userID']}");
	if ($query_result !== FALSE)
	{
		create_title("Profile updated.","");
		print("<a href=\"{$_SERVER['PHP_SELF']}\">Continue...</a>");
		ago_footer(); die();
	}
	else
	{
		create_title("Profile update failed","");
		print("Please contact the administrator. <br/>");
		print(mysql_error());
	}
}
elseif (array_key_exists("register", $_POST))
{
	if (get_magic_quotes_runtime() == 1)
	{
		$_POST['password'] = stripslashes ($_POST['password']);
		$_POST['username'] = stripslashes ($_POST['username']);
		$_POST['realname'] = stripslashes ($_POST['realname']);
		$_POST['email'] = stripslashes ($_POST['email']);
	}

	$username = mysql_real_escape_string ($_POST['username']);
	$realname = mysql_real_escape_string ($_POST['realname']);
	$email = mysql_real_escape_string ($_POST['email']);
	$password = md5($_POST['password']);

	if ($username && $realname && $email && $password)
	{
		$new_user_result = mysql_query("INSERT INTO user (username,realname,password,email) VALUES ('$username','$realname','$password','$email')");
		if (!$new_user_result)
		{
			print("<p>The following error occured while trying to create a new user:</p>");
			print("<tt>".mysql_error()."</tt>");
		}
		else
		{
			print("<p class=\"info\">New user ($username) created. You may now login with this username and your password.</p>");
			print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Back</a></p>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the previous form fields, please go back and try again.</p>");
	}
}
elseif (array_key_exists('username', $_SESSION))
{
	create_title("My Profile","Logged in as {$_SESSION['username']}");
	print("<ul><li><a href=\"/users/{$_SESSION['userID']}/\">Public Profile Page</a></li></ul>");
	$query_result = mysql_query("SELECT realname,email,homepage,info FROM user WHERE userID = '{$_SESSION['userID']}'");
	list($realname,$email,$homepage,$info) = mysql_fetch_row($query_result);
	$realname = htmlspecialchars ($realname, ENT_QUOTES);
	$email = htmlspecialchars ($email, ENT_QUOTES);
	$homepage = htmlspecialchars ($homepage, ENT_QUOTES);
	$info = htmlspecialchars ($info, ENT_QUOTES);
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" />");
	print("<table>\n");
	print("<tr><th><label for=\"password\">Password</label></th><td><input value=\"\" type=\"password\" name=\"password\" id=\"password\" size=\"20\" /> (leave blank to remain unchanged)</td></tr>\n");
	print("<tr><th><label for=\"realname\">Name</label></th><td><input value=\"$realname\" name=\"realname\" id=\"realname\" size=\"20\" /></td></tr>\n");
	print("<tr><th><label for=\"email\">E-mail</label></th><td><input value=\"$email\" name=\"email\" id=\"email\" size=\"20\" /></td></tr>\n");
	print("<tr><th><label for=\"homepage\">Homepage</label></th><td><input value=\"$homepage\" name=\"homepage\" id=\"homepage\" size=\"20\" /></td></tr>\n");
	print("<tr><th><label for=\"info\">Info</label></th><td><textarea name=\"info\" rows=\"2\" cols=\"20\" id=\"info\">$info</textarea></td></tr>\n");
	print("<tr><td colspan=\"2\"><br /><input type=\"submit\" value=\"Change\" name=\"change_profile\" /></td></tr>");
	print("<tr><td colspan=\"2\"><input type=\"submit\" value=\"Logout\" name=\"logout\" /></td></tr>");
	print("</table>\n");
	print("</form>");

	create_title("Submissions","");
	print("<ul>");
	print("<li><a href=\"/submit_theme.php\">Submit a theme</a></li>");
	print("<li><a href=\"/submit_background.php\">Submit a background</a></li>");
	print("</ul>");
	print("<div class=\"h2\">Theme submissions</div><div class=\"subtitle\">Status of submitted theme</div>");
	$submissions_select_result = mysql_query("SELECT themeID,theme_name,category,status,comment FROM incoming_theme WHERE userID = '{$_SESSION['userID']}' ");
	if (mysql_num_rows($submissions_select_result) < 1 )
	{
		print("<p>(None)</p>");
	}
	else
	{
		print("<table><tr><th>Name</th><th>Category</th><th>Status</th></tr>");
		while (list($themeID,$theme_name,$category,$status,$comment) = mysql_fetch_row($submissions_select_result) )
		{
			if ($status == "new")
				$status = "pending";
			elseif ($status == "rejected")
				if ($comment == "")
					$status = "Removed from the submissions list. Please read the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">submission guidelines</a> to find the possible reasons.";
				else
					$status = "Removed - " . html_parse_text($comment);

			print ("<tr><td style=\"border-bottom: 1px gray dashed\">$theme_name</td><td style=\"border-bottom: 1px gray dashed\">$category</td><td style=\"border-bottom: 1px gray dashed\">$status</td>");
			if ($status == "added")
				print ("<td><form action=\"/submit_theme.php\" method=\"post\"><div><input type=\"hidden\" name=\"update\" value=\"$themeID\" /><input type=\"submit\" value=\"Update\"/></div></form></td>");
			print ("</tr>");
		}
		print("</table>");
	}
	print("<br />");
	print("<div class=\"h2\">Background submissions</div><div class=\"subtitle\">Status of submitted backgrounds</div>");
	$submissions_select_result = mysql_query("SELECT backgroundID,background_name,category,status,comment FROM incoming_background WHERE userID = '{$_SESSION['userID']}' ");
	if (mysql_num_rows($submissions_select_result) < 1 )
	{
		print("<p>(None)</p>");
	}
	else
	{
		print("<table><tr><th>Name</th><th>Category</th><th>Status</th></tr>");
		while (list($backgroundID,$background_name,$category,$status,$comment) = mysql_fetch_row($submissions_select_result) )
		{
			if ($status == "new")
				$status = "pending";
			elseif ($status == "rejected")
				if ($comment == "")
					$status = "Removed from the submissions list. Please read the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">submission guidelines</a> to find the possible reasons.";
				else
					$status = "Removed - " . html_parse_text($comment);
			print ("<tr><td style=\"border-bottom: 1px gray dashed\">$background_name</td><td style=\"border-bottom: 1px gray dashed\">$category</td><td style=\"border-bottom: 1px gray dashed\">$status</td></tr>");
		}
		print("</table>");
	}
}
else
{

	create_title("Please log in","Log in to access your account");
	print("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n");
	print("<table>\n");
	print("<tr><td><label for=\"musername\">Username</label>:</td><td><input name=\"username\" class=\"username\" id=\"musername\" /></td></tr>\n");
	print("<tr><td><label for=\"mpassword\">Password</label>:</td><td><input name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td></tr>\n");
	print("<tr><td colspan=\"2\"><input type=\"submit\" value=\"Login\" name=\"login\" /></td></tr>\n");
	print("</table>\n");
	print("</form>\n");


	create_title("Register","Register as a new user for art.gnome.org");
	print("<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">");
	print("<table>");
	print("<tr><td><label for=\"nusername\">Username</label>:</td><td><input name=\"username\" id=\"nusername\" /></td></tr>");
	print("<tr><td><label for=\"npassword\">Password</label>:</td><td><input name=\"password\" id=\"npassword\" type=\"password\" /></td></tr>");
	print("<tr><td><label for=\"realname\">Realname</label>:</td><td><input name=\"realname\" id=\"realname\" /></td></tr>");
	print("<tr><td><label for=\"email\">E-mail</label>:</td><td><input name=\"email\" id=\"email\" /></td></tr>");
	print("<tr><td colspan=\"2\"><input type=\"submit\" name=\"register\" value=\"Register\" /></td></tr>");
	print("</table>");
	print("</form>");


}

ago_footer();


?>

