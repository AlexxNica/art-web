<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("ICONS");
create_middle_box_top("icons");

if (!$page || $page == "") 
{
	$page = 1;
}
if(!$type || $type == "")
{
	$type = "other";
}
display_icons ($type,$page);

create_middle_box_bottom();
ago_footer();
?>
