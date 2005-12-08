<?php

require_once ("mysql.inc.php");
require_once ("templates.inc.php");

$page_title = '';

function microtime_float ()
{
	list ($usec, $sec) = explode (" ", microtime ());
	return ((float)$usec + (float)$sec);
}
$time_start= microtime_float ();

ini_set ("session.use_only_cookies", "1");
ini_set ("session.gc_maxlifetime", "86400"); // set session data lifetime to 48 hours
session_set_cookie_params (86400); // Set cookie lifetime to 48 hours
session_start ();

if (array_key_exists ("login", $_POST))
{
	/* is the user trying to log in? */
	if (array_key_exists ('login', $_POST)) {
		$username = escape_string ($_POST['username']);
		$password = escape_string ($_POST['password']);
		$query_result = mysql_query ("SELECT userID, realname, password FROM user WHERE username = '$username'");

		list ($userID, $realname, $cryptpass ) = mysql_fetch_row ($query_result);

		if ( (md5 ($password) == $cryptpass)  )
		{
			$_SESSION['username'] = $username;
			$_SESSION['userID'] = $userID;
			$_SESSION['realname'] = $realname;
			mysql_query ("UPDATE user SET lastlog=NOW () WHERE userid=$userID;");
		}
		else
		{
			art_header ("Login error");
			print ("<h1>Login failed</h1>");
			print ('<p class="warning">Incorrect username and password. Please try again.  To reset your password, click <a href="account?mode=lostpassword">here</a></p>');
			print ("<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n");
			print ("<table>\n");
			print ("<tr><td><label for=\"musername\">Username</label>:</td><td><input name=\"username\" class=\"username\" id=\"musername\" /></td></tr>\n");
			print ("<tr><td><label for=\"mpassword\">Password</label>:</td><td><input name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td></tr>\n");
			print ("<tr><td colspan=\"1\"><input type=\"submit\" value=\"Login\" name=\"login\" /></td><td><a href=\"/account.php?mode=lostpassword\" style=\"font-size:0.8em;\">(Lost your password?)</a></td></tr>\n");
			print ("</table>\n");
			print ("</form>\n");
			art_footer ();
			exit ();
		}
	}
}

function is_ie () {
	if (strstr ($_SERVER['HTTP_USER_AGENT'], "MSIE") && !strstr ($_SERVER['HTTP_USER_AGENT'], "Opera"))
		return true;
	else
		return false;
}

function art_header ($title)
{
	global $page_title;
	$page_title = $title;
	ob_start ();
}

function art_footer ()
{
	global $time_start, $page_title;

	if (array_key_exists ('username', $_SESSION))
	{
		$t_usermenu = new template ('main/usermenu.html');
		$t_usermenu->add_var ('username', $_SESSION['username']);
	}
	else
	{
		$t_usermenu = new template ('main/login.html');
	}
	$usermenu = $t_usermenu->parse ();

	$template = new template ('main.html');
	$content = ob_get_contents ();
	ob_end_clean ();
	$template->add_var ('content', $content);
	$template->add_var ('page-title', $page_title);
	$template->add_var ('user-menu', $usermenu);
	$template->write ();


	$time_end = microtime_float ();
	$time = round ($time_end - $time_start, 2);
	print ("<!-- Page generated in $time seconds -->\n");
}
?>
