<?php
print ("<html><body>");
require("mysql.inc.php");

   $result = mysql_query("SELECT theme_url FROM incoming_theme WHERE status='new'");
while($row = mysql_fetch_array($result))
{
	$url = $row[theme_url];
	print("$url <br>");
}
print ("</html></body>");
?>
