<?
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");

if($new_site_theme)
{
	if($new_site_theme == "slick" || $new_site_theme == "lite")
   {
   	$site_theme = $new_site_theme;
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
