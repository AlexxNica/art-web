<?php

include "ago_headers.inc.php";



function admin_header($title)
{
	session_start();
	ago_header("ART.GNOME.ORG Admin");
	create_title($title,"");
}


function admin_footer()
{
	ago_footer();
}

?>
