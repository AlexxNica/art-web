<?php


include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";



function get_status_comment($status, $comment)
{
	$reject_comments = Array(
			"not_rel" => "Not relevent, or unsuitable for art.gnome.org",
			"bad_url" => "Invalid URL - please <a href=\"mailto:$admin_email\">contact admin</a>.",
			"distro" => "Distribution Specific.",
			"low_quality" => "Low quality or unfinished.",
			"copyright" => "Possible use of copyright material without permission.",
			"badform" => "There were problems when attempting to load the theme.",
			"duplicate" => "Submitted multiple times."
			);

	if ($status == "new")
	{
		return "Pending";
	}elseif ($status == "rejected")
	{
		if ($comment == "")
		{
			return "Removed - Please see the <a href=\"http://live.gnome.org/GnomeArt/SubmissionPolicy\">submission guidelines</a> for more information.";
		}
		elseif (array_key_exists($comment, $reject_comments))
		{
			return "Removed - " . $reject_comments[$comment] . " See the <a href=\"http://live.gnome.org/GnomeArt/SubmissionPolicy\">submission guidelines</a> for more information.";
		}else
		{
			return "Removed - " . html_parse_text($comment);
		}
	}else
		return ucfirst($status);
}



if($_GET['mode'] == "lostpassword") {
	art_header('Reset password');
	create_title('Reset password', 'This will generate a new password');
	if(!$_POST['lusername'] && !$_POST['lemail'] && !$_SESSION['username']) {
		print("<form action=\"account.php?mode=lostpassword\" method=\"post\">\n");
		print("<table>\n");
		print("<tr><td><label for=\"lusername\">Username</label>:</td><td><input name=\"lusername\" class=\"username\" id=\"lusername\" /></td></tr>\n");
		print("<tr><td><label for=\"lemail\">Email Address</label>:</td><td><input name=\"lemail\" class=\"username\" id=\"lemail\" /></td></tr>\n");
		print("<tr><td colspan=\"2\"><input type=\"submit\" value=\"Reset\" name=\"reset\" /></td></tr>\n");
		print("</table>\n");
		print("</form>\n");
	} elseif ($_SESSION['username']) {
		print ('<p class="warning">You are already logged in as '.$_SESSION['username'].'.  To change your password, please visit <a href="account.php">your account page</a>.</p>');
	}

	if($_POST['lusername'] && $_POST['lemail']) {
		/* test to see if user is full of crap */
		$_SESSION['lusername'] = escape_string($_POST['lusername']);
		$_SESSION['lemail'] = escape_string($_POST['lemail']);
	
		$query = 'SELECT userID FROM user WHERE username = \''.$_SESSION['lusername'].'\' AND email = \''.$_SESSION['lemail'].'\'';
		$result = mysql_query($query);
	
		if (mysql_num_rows($result) != 1) {
			// sleep('5')
			session_destroy();
			printf('<p class="error">Wrong username/email combination.  We\'re sorry, but the username and email address does not match our records, please <a href="account.php?mode=lostpassword">try again</a>.</p>');
		} else {
			$_SESSION['reset'] = 1;
		}
	}

	if($_SESSION['reset']) {
		$username = $_SESSION['lusername'];
		$email = $_SESSION['lemail'];
		session_destroy();
		$password = '';
		srand((double)microtime()*1000000);
		for ($rand = 0; $rand <= 8; $rand++)
		{
			$random = rand(97, 122);
			$password .= chr($random);
		}

		$password_enc = md5 ($password);

		$result = mysql_query ("UPDATE user SET password='$password_enc' WHERE username='$username' LIMIT 1");
		if ($result === FALSE || mysql_affected_rows() == 0) {
			print ('<p class="error">There was an error resetting the password for user "'.$username.'  Please contact an admin for help"</p>');
		} else {
			$message = "Your password has been reset to $password.\nTo change your password, please login and update your profile.\nThe URL is $site_url\nThank you for using art.gnome.org!";
			$headers = 'From: $admin_email' . "\r\n".'X-Mailer: PHP/' . phpversion();;
			if(mail($email, "New password for $username at $site_name", $message, $headers)) {
				print ('<p class="info">Password was reset.  The new password has been emailed to "'.$email.'".  If you do not recieve the password in five minutes, please contact an administrator for assistance.</p>');
			} else {
				print('<p class="info">Password was reset.  However, there was difficulty in sending the new password to your email address.  Please contact an administrator for assistance.</p>');
			}
		}
	}

}
elseif (array_key_exists('logout', $_POST))
{
	session_destroy();
	$_SESSION = Array();
	art_header("Account");
	create_title("Logout","");
	print("<p>You have been logged out.  <a href=\"{$_SERVER["PHP_SELF"]}\">Continue...</a></p>");
}
elseif (array_key_exists('change_profile', $_POST))
{
	art_header("Account");

	$new_pass = escape_string ($_POST['password']);
	$new_pass_re = escape_string ($_POST['passwordre']);
	$info = escape_string ($_POST['info']);
	$realname = escape_string ($_POST['realname']);
	$email = escape_string ($_POST['email']);
	$homepage = escape_string ($_POST['homepage']);
	$timezone = escape_string ($_POST['timezone']);
	$location = escape_string ($_POST['location']);

	$pass_sql = "";
	$pass_typing_error = false;
	if (($new_pass != "") and ($new_pass_re != ""))
	{
		if ($new_pass == $new_pass_re)
		{
			$pass_sql = " password='".md5($new_pass)."', ";
		}
		else
		{
			$pass_typing_error = true;
		}
	}
	if (!$pass_typing_error)
	{
		$query_result = mysql_query("UPDATE user SET $pass_sql realname='$realname', info='$info', email='$email', homepage='$homepage', timezone='$timezone', location='$location' WHERE userID = {$_SESSION['userID']}");
		if ($query_result !== FALSE)
		{
			create_title("Profile updated.","");
			print("<a href=\"{$_SERVER['PHP_SELF']}\">Continue...</a>");
			art_footer(); die();
		}
		else
		{
			create_title("Profile update failed","");
			print("<p class=\"warning\">Please contact the administrator.</p>");
			print(mysql_error());
		}
	}
	else
	{
		create_title("Profile update failed","");
		print("<p class=\"error\">Error, passwords don't match. Profile not updated.</p>");
		print(mysql_error());
	}
}
elseif (array_key_exists("register", $_POST))
{
	art_header("Account");

	$username = escape_string ($_POST['username']);
	$realname = escape_string ($_POST['realname']);
	$email = escape_string ($_POST['email']);
	$password = md5(escape_string($_POST['password']));
	$passwordre = md5(escape_string($_POST['passwordre']));

	if ($username && $realname && $email && $password)
	{
		if ($password == $passwordre)
		{
			$new_user_result = mysql_query("INSERT INTO user (username,realname,password,email) VALUES ('$username','$realname','$password','$email')");
			if (!$new_user_result)
			{
				if (mysql_errno() == 1062)
				{
					print("<p class=\"error\">A user already exists with that username. Please <a href=\"{$_SERVER["PHP_SELF"]}\">choose another.</a></p>");
				}
				else
				{
					print("<p class=\"error\">The following error occured while trying to create a new user:</p>");
					print("<tt>".mysql_error()."</tt>");
				}
			}
			else
			{
				print("<p class=\"info\">New user ($username) created. You may now login with this username and your password.</p>");
				print("<p><a href=\"{$_SERVER["PHP_SELF"]}\">Back</a></p>");
			}
		}
		else
		{
			print("<p class=\"error\">Error, passwords don't match. Please <a href=\"/account.php\">go back and try again</a>.</p>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the previous form fields, please <a href=\"/account.php\">go back and try again</a>.</p>");
	}
}
elseif (array_key_exists('username', $_SESSION))
{
	art_header("Account");
	create_title("My Profile","Logged in as <a href=\"/users/{$_SESSION['userID']}/\" name=\"Public Profile Page\">{$_SESSION['username']}</a>");
	print("\t<ul>\n\t\t<li><a href=\"/users/{$_SESSION['username']}/\">Public Profile Page</a></li>\n\t</ul>\n");
	$query_result = mysql_query("SELECT realname,email,homepage,info,timezone,location FROM user WHERE userID = '{$_SESSION['userID']}'");
	extract(mysql_fetch_array($query_result));	
	$realname = htmlspecialchars ($realname, ENT_QUOTES);
	$email = htmlspecialchars ($email, ENT_QUOTES);
	$homepage = htmlspecialchars ($homepage, ENT_QUOTES);
	$info = htmlspecialchars ($info, ENT_QUOTES);
	$location = htmlspecialchars($location, ENT_QUOTES);
	$timezone_array = array("-12"=>"UTC -12", "-11"=>"UTC -11", "-10"=>"UTC -10", "-9"=>"UTC -9","-8"=>"UTC -8","-7"=>"UTC -7","-6"=>"UTC -6","-5"=>"UTC -5","-4"=>"UTC -4","-3.5"=>"UTC -3.5","-3"=>"UTC -3","-2"=>"UTC -2","-1"=>"UTC -1",
			"0"=>"UTC 0","1"=>"UTC +1","2"=>"UTC +2", "3"=>"UTC +3", "3.5"=>"UTC +3.5", "4"=>"UTC +4","4.5","UTC +4.5", "5"=>"UTC +5","5.5"=>"UTC +5.5", "6"=>"UTC +6", "7"=>"UTC +7", "8"=>"UTC +8", "9"=>"UTC +9", "9.5"=>"UTC +9.5", "10"=>"UTC +10", "11"=>"UTC+11", "12"=>"UTC+12");
	if ($timezone < -12 || $timezone > 12)
		$timezone = 0;

	print("\t<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" />");
	print("\t\t<table>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"password\">Password</label></th>\n\t\t\t\t<td><input value=\"\" type=\"password\" name=\"password\" id=\"password\" size=\"20\" maxlength=\"12\" /> (leave blank to remain unchanged)</td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"passwordre\">Password again</label></th>\n\t\t\t\t<td><input value=\"\" type=\"password\" name=\"passwordre\" id=\"passwordre\" size=\"20\" maxlength=\"12\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"realname\">Name</label></th>\n\t\t\t\t<td><input value=\"$realname\" name=\"realname\" id=\"realname\" size=\"20\" maxlength=\"50\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"email\">E-mail</label></th>\n\t\t\t\t<td><input value=\"$email\" name=\"email\" id=\"email\" size=\"20\" maxlength=\"50\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"homepage\">Homepage</label></th>\n\t\t\t\t<td><input value=\"$homepage\" name=\"homepage\" id=\"homepage\" size=\"20\" maxlength=\"100\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"location\">Location</label></th>\n\t\t\t\t<td><input value=\"$location\" name=\"location\" id=\"location\" size=\"20\" maxlength=\"50\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"timezone\">Timezone</label></th>\n\t\t\t\t<td>");print_select_box("timezone",$timezone_array,$timezone);print(" (Current adjusted time: ".date("h:i a", mktime()+(($timezone) *3600)).")</td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<th><label for=\"info\">Info</label></th>\n\t\t\t\t<td><textarea name=\"info\" rows=\"2\" cols=\"20\" id=\"info\">$info</textarea></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td colspan=\"2\"><br /><input type=\"submit\" value=\"Change\" name=\"change_profile\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td colspan=\"2\"><input type=\"submit\" value=\"Logout\" name=\"logout\" /></td>\n\t\t\t</tr>\n");
	print("\t\t</table>\n");
	print("\t</form>\n");

	create_title("Submissions","");
	print("<ul>");
	print("<li><a href=\"/submit_theme.php\">Submit a theme</a></li>");
	print("<li><a href=\"/submit_background.php\">Submit a background</a></li>");
	print("<li><a href=\"/submit_screenshot.php\">Submit a screenshot</a></li>");
	print("</ul>");
	print("<div class=\"h2\">Theme submissions</div><div class=\"subtitle\">Status of submitted themes</div>");
	$submissions_select_result = mysql_query("SELECT themeID,name,category,status,comment FROM incoming_theme WHERE userID = '{$_SESSION['userID']}' ");
	if (mysql_num_rows($submissions_select_result) < 1 )
	{
		print("<p>(None)</p>");
	}
	else
	{
		print("<table><tr><th>Name</th><th>Category</th><th>Status</th></tr>");
		while (list($themeID,$theme_name,$category,$status,$comment) = mysql_fetch_row($submissions_select_result) )
		{
			$status = get_status_comment($status, $comment);
			print ("<tr><td style=\"border-bottom: 1px gray dashed\">$theme_name</td><td style=\"border-bottom: 1px gray dashed\">$category</td><td style=\"border-bottom: 1px gray dashed\">$status</td>");
			if ($status == "added")
				print ("<td><form action=\"/submit_theme.php\" method=\"post\"><div><input type=\"hidden\" name=\"update\" value=\"$themeID\" /><input type=\"submit\" value=\"Update\"/></div></form></td>");
			print ("</tr>");
		}
		print("</table>");
	}
	print("<br />");
	print("<div class=\"h2\">Background submissions</div><div class=\"subtitle\">Status of submitted backgrounds</div>");
	$submissions_select_result = mysql_query("SELECT backgroundID,name,category,status,comment FROM incoming_background WHERE userID = '{$_SESSION['userID']}' ");
	if (mysql_num_rows($submissions_select_result) < 1 )
	{
		print("<p>(None)</p>");
	}
	else
	{
		print("<table><tr><th>Name</th><th>Category</th><th>Status</th></tr>");
		while (list($backgroundID,$background_name,$category,$status,$comment) = mysql_fetch_row($submissions_select_result) )
		{
			$status = get_status_comment($status, $comment);
			print ("<tr><td style=\"border-bottom: 1px gray dashed\">$background_name</td><td style=\"border-bottom: 1px gray dashed\">$category</td><td style=\"border-bottom: 1px gray dashed\">$status</td></tr>");
		}
		print("</table>");
	}
}
else
{

	art_header("Account");
	create_title("Please log in","Log in to access your account");
	print("\t<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n");
	print("\t\t<table>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"musername\">Username</label>:</td>\n\t\t\t\t<td><input name=\"username\" class=\"username\" id=\"musername\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"mpassword\">Password</label>:</td>\n\t\t\t\t<td><input name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td colspan=\"1\"><input type=\"submit\" value=\"Login\" name=\"login\" /></td>\n\t\t\t\t<td><a href=\"/account.php?mode=lostpassword\" style=\"font-size:0.8em;\">(Lost your password?)</a></td>\n\t\t\t</tr>\n");
	print("\t\t</table>\n");
	print("\t</form>\n");


	create_title("Register","Register as a new user for art.gnome.org");
	print("\t<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\n");
	print("\t\t<table>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"nusername\">Username</label>:</td>\n\t\t\t\t<td><input name=\"username\" id=\"nusername\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"npassword\">Password</label>:</td>\n\t\t\t\t<td><input name=\"password\" id=\"npassword\" type=\"password\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"npasswordre\">Password again</label>:</td>\n\t\t\t\t<td><input name=\"passwordre\" id=\"npasswordre\" type=\"password\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"realname\">Realname</label>:</td>\n\t\t\t\t<td><input name=\"realname\" id=\"realname\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td><label for=\"email\">E-mail</label>:</td>\n\t\t\t\t<td><input name=\"email\" id=\"email\" /></td>\n\t\t\t</tr>\n");
	print("\t\t\t<tr>\n\t\t\t\t<td colspan=\"2\"><input type=\"submit\" name=\"register\" value=\"Register\" /></td>\n\t\t\t</tr>\n");
	print("\t\t</table>\n");
	print("\t</form>");


}

art_footer();


?>

