<?
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");

if($new_mirrorID)
{
	$mirror_select_result = mysql_query("SELECT url FROM mirror WHERE mirrorID='$new_mirrorID'");
   if(mysql_num_rows($mirror_select_result)==1)
   {
   	$mirrorID = $new_mirrorID;
      list($url_choice) = mysql_fetch_row($mirror_select_result);
   	$mirror_url = $url_choice;
   	if($HTTP_REFERER)
   	{
   		header("Location: $HTTP_REFERER");
		}
   	else
   	{
   		header("Location: index.html");
   	}
   }   
	else
   {
   	if($HTTP_REFERER)
   	{
   		header("Location: $HTTP_REFERER");
		}
   	else
   	{
   		header("Location: index.php");
   	}
	}
}
else
{
	if($HTTP_REFERER)
   {
   	header("Location: $HTTP_REFERER");
	}
   else
   {
   	header("Location: index.php");
   }
}
?>
