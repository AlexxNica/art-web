<?php
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
if($tipID)
{
	include("header.inc.php");
	create_middle_box_top("tips");
	
   $tip_select_result = mysql_query("SELECT title,body, FROM tip WHERE tipID='$tipID'");
	if(mysql_num_rows($tip_select_result)==0)
   {
   	print("Invalid Tip.");
   }
   else
   {
   	list($title,$body) = mysql_fetch_row($tip_select_result);
      print("<span class=\"yellow-text\"><font size=\"+1\">$title</font></span>\n");
      print("<p>\n$body");
   }

	create_middle_box_bottom();
	include("footer.inc.php");
}
else
{
	header("Location: index.html");
}
?>
