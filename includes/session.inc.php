<?php

session_name("ARTSESSID");
session_start();
if(!session_is_registered("mirrorID"))
{
	session_register("mirrorID");
   $mirrorID = 1;
}
if(!session_is_registered("mirror_url"))
{
	$mirror_select_result = mysql_query("SELECT url FROM mirror WHERE mirrorID='$mirrorID'");
   list($url_choice) = mysql_fetch_row($mirror_select_result);
   $mirror_url = $url_choice;
}
if(!session_is_registered("site_theme"))
{
	session_register("site_theme");
   $site_theme = "lite";
}

?>
