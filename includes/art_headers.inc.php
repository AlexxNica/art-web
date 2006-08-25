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


if (!$prevent_session)
{
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
			if (!validate_login ($username, $password))
			{
				art_header ("Login error");
?>
	<h1>Login failed</h1>
	<p class="warning">Incorrect username and password. Please try again.  To reset your password, click <a href="/account.php?mode=lostpassword">here</a></p>
	<form action="{<?php echo $_SERVER['PHP_SELF']; ?>}" method="post">
		<table>
			<tr>
				<td><label for="musername">Username</label>:</td>
				<td><input name="username" class="username" id="musername" /></td>
			</tr>
			<tr>
				<td><label for="mpassword">Password</label>:</td>
				<td><input name="password" type="password" class="password" id="mpassword" /></td>
			</tr>
			<tr>
				<td colspan="1"><input type="submit" value="Login" name="login" /></td>
				<td><a href="/account.php?mode=lostpassword" style="font-size:0.8em;">(Lost your password?)</a></td>
			</tr>
		</table>
	</form>
<?php
				art_footer ();
				exit ();
			}
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
