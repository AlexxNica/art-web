<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
include("header.inc.php");
create_middle_box_top("tips");

$tip_select_result = mysql_query("SELECT tipID,type,title,tip_url FROM tip");
if(mysql_num_rows($tip_select_result)==0)
{
	print("In the future, this section will contain cool tips and tricks for the gnome desktop");
}
else
{
	print("<table border=\"0\">\n");
   while(list($tipID,$type,$title,$tip_url) = mysql_fetch_row($tip_select_result))
	{
		if($type == "normal")
      {
      	print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"show_tip.php?tipID=$tipID\">$title</a></td></tr>\n");
		}
      else
      {
      	print("<tr><td><img src=\"images/site/circle.png\"></td><td>&nbsp;<a href=\"$tip_url\">$title</a></td></tr>\n");
		}
   }
	print("</table>\n");
}


print("<p>\n");
create_middle_box_bottom();
include("footer.inc.php");
?>
