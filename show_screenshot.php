<?php
/* this script needs to be flushed out and made better */
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");

if($screenshotID && $screenshotID != "")
{
	$screenshot_select_result = mysql_query("SELECT image_filename,description FROM screenshot WHERE screenshotID='$screenshotID'");
   if(mysql_num_rows($screenshot_select_result) == 0)
   {
   	header("Location: index.php");
   }
   else
   {
   	list($image_filename, $description) = mysql_fetch_row($screenshot_select_result);
      print("<html>\n<body>\n");
      print("<img src=\"images/screenshots/$image_filename\">\n");
      print("<p>$description");
      print("</body></html>\n");
   }
}
else
{
	header("Location: index.html");
}
?>
