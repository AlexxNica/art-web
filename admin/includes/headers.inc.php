<?php

include "ago_headers.inc.php";

function admin_header($title)
{
	session_start();
	ago_header("ART.GNOME.ORG Admin");
	create_title($title,"");
}

function admin_auth($reqlevel)
{
	$authcheck_query = "SELECT userID, level FROM user WHERE username = '".mysql_real_escape_string($_SESSION['username'])."'";
	$authcheck_result = mysql_query($authcheck_query);
	list($userID, $level) = mysql_fetch_row($authcheck_result);
	if ($level >= $reqlevel)
	{
		return $level;
	} else
	{
		sleep('5'); //change value later...
		print("<p class=\"error\"><strong>Authorization Failed</strong></p><p>Please check your username and password and try again.</p>");
		ago_footer();
		die();
	}
}

function admin_footer()
{
	ago_footer();
}

?>
