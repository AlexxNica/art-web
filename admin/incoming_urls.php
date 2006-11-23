<?php
require("mysql.inc.php");
require("includes/headers.inc.php");
require("common.inc.php");

admin_header("Incoming URLs");
admin_auth(2);

   $result = mysql_query("SELECT theme_url FROM incoming_theme WHERE status='new'");
while($row = mysql_fetch_array($result))
{
	$url = $row[theme_url];
	print("$url <br>");
}
?>
