<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>art.gnome.org</title>
<link rel="stylesheet" href="/main.css" type="text/css">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
print("<tr valign=\"middle\"><td colspan=\"2\" class=\"horizontal-menu-bar-lite\">&nbsp;&nbsp;&nbsp;");

/* FIXME, temperarily disable mirror selection
for($count=1;$count<5;$count++)
{
	if($count == 1)
   {
   	if($count == $mirrorID)
      {
      	print("<span class=\"yellow-text\">Main</span>");
      }
      else
      {
        	print("<b><a href=\"/change_mirror.php?new_mirrorID=1\">Main</a></b>");
		}
   }
   else
   {
   	if($count == $mirrorID)
      {
        	print("<span class=\"yellow-text\">Mirror " . ($count-1) . "</span>");
      }
      else
      {
        	print("<b><a href=\"/change_mirror.php?new_mirrorID=".$count."\">Mirror " . ($count-1) . "</a></b>");
   	}
   }
   if($count != 4)
   {
   	print(" - ");
   }
}
*/
print("</td></tr>\n");

?>

<tr class="horizontal-gradient-menu-bar"><td><img src="/images/site/LOGO-Pill.png" alt="GNOME"></td><td class="align-right"><a href="http://www.gnome.org/"><img border="0" src="/images/site/LOGO-Elliptic.png" alt="GNOME Foot"></a></td></tr>
<tr valign="middle"><td colspan="2" class="horizontal-menu-bar-lite">&nbsp;&nbsp;<a class="screenshot" href="/index.php">art.gnome.org</a>&nbsp;&nbsp;-&nbsp;&nbsp;<b>Enhance your GNOME desktop!</b></td></tr>
</table>
<p>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
<img src="/images/site/pixel.png" width="10" height="1" alt=" ">
</td>
<!-- Left Column -->

<td width="182">
<div class="mb_lite-title"><img src="/images/site/pill-icons/art.png" alt="ART"> ART</div>
<div class="mb_lite-contents">
<?
reset($linkbar);
while(list($key,$val)=each($linkbar))
{
   $url = $val["url"];
   $alt = $val["alt"];
   $indent = $val["indent"];
   if($indent)
   {
      $a_head = "&nbsp;&nbsp;&nbsp;";
      $a_foot = "";
   }
   else
   {
      $a_head = "<font size=\"+1\">";
      $a_foot = "</font>";
   }
   print("$a_head<a href=\"$url\">$alt</a>$a_foot<br>\n");

}
?>
</div>
<img src="/images/site/pixel.png" width="182" height="1" alt=" ">
</td>
<td><img src="/images/site/pixel.png" width="10" alt=" "></td>
<!-- End Left Column -->
