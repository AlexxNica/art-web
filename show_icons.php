<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("icons");


/*
if (!in_array ($num_per_page, $GLOBALS['sys_number_images_array']))
{
	$num_per_page = $GLOBALS['sys_number_images_array'][0];
}
*/
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
include("footer.inc.php");
?>
