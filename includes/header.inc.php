<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>art.gnome.org</title>
<link rel="stylesheet" href="main.css" type="text/css">
<?
require("config.inc.php");
if($site_theme == "slick")
{
?>
<script language="JavaScript">
<!--
if (document.images)
{
	imgmb_mainon=new Image();
	imgmb_mainon.src="images/site/mb_mainH.png";
	imgmb_mainoff=new Image();
	imgmb_mainoff.src="images/site/mb_main.png";

	imgmb_mirror1on=new Image();
	imgmb_mirror1on.src="images/site/mb_mirror1H.png";
	imgmb_mirror1off=new Image();
	imgmb_mirror1off.src="images/site/mb_mirror1.png";

	imgmb_mirror2on=new Image();
	imgmb_mirror2on.src="images/site/mb_mirror2H.png";
	imgmb_mirror2off=new Image();
	imgmb_mirror2off.src="images/site/mb_mirror2.png";
	
   imgmb_mirror3on=new Image();
	imgmb_mirror3on.src="images/site/mb_mirror3H.png";
	imgmb_mirror3off=new Image();
	imgmb_mirror3off.src="images/site/mb_mirror3.png";
   
   imgtheme_slickon=new Image();
	imgtheme_slickon.src="images/site/theme_slickH.png";
	imgtheme_slickoff=new Image();
	imgtheme_slickoff.src="images/site/theme_slick.png";
   
   imgtheme_liteon=new Image();
	imgtheme_liteon.src="images/site/theme_liteH.png";
	imgtheme_liteoff=new Image();
	imgtheme_liteoff.src="images/site/theme_lite.png";


<?
//require("config.inc.php");
//if($site_theme == "slick")
//{
	reset ($linkbar);
	while (list($key,$val) = each($linkbar))
	{
		print("\timg" . $key . "on=new Image();\n");
		print("\timg" . $key . "on.src=\"images/site/linkbar/" . $key . "H.png\";\n");
		print("\timg" . $key . "off=new Image();\n");
		print("\timg" . $key . "off.src=\"images/site/linkbar/" . $key . ".png\";\n");
	}
//}	
?>
}

function imgOn(imgName)
{
	if (document.images)
   {
		document[imgName].src = eval(imgName + "on.src");
   }
}

function imgOff(imgName)
{
	if (document.images)
   {
		document[imgName].src = eval(imgName + "off.src");
   }
}
// -->
</script>
<?
}
?>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
if($site_theme == "slick")
{
	print("<tr valign=\"middle\"><td colspan=\"2\" class=\"horizontal-menu-bar\"><img src=\"images/site/mb_pat.png\" width=\"10\" height=\"20\">");
}
else
{
	print("<tr valign=\"middle\"><td colspan=\"2\" class=\"horizontal-menu-bar-lite\">&nbsp;&nbsp;&nbsp;");
}

/* FIXME, temperarily disable mirror selection
for($count=1;$count<5;$count++)
{
	if($count == 1)
   {
   	if($count == $mirrorID)
      {
      	if($site_theme == "slick")
         {
         	print("<img src=\"images/site/mb_mainS.png\" border=\"0\">");
      	}
         else
         {
         	print("<span class=\"yellow-text\">Main</span>");
         }
      }
      else
      {
      	if($site_theme == "slick")
         {
         	print("<a href=\"change_mirror.php?new_mirrorID=1\" onMouseOver=\"imgOn('imgmb_main')\" onMouseOut=\"imgOff('imgmb_main')\"><img name=\"imgmb_main\" src=\"images/site/mb_main.png\" border=\"0\"></a>");
   		}
         else
         {
         	print("<b><a href=\"change_mirror.php?new_mirrorID=1\">Main</a></b>");
			}
      }
   }
   else
   {
   	if($count == $mirrorID)
      {
      	if($site_theme == "slick")
         {
         	print("<img src=\"images/site/mb_mirror".($count-1)."S.png\" border=\"0\">");
      	}
         else
         {
         	print("<span class=\"yellow-text\">Mirror " . ($count-1) . "</span>");
         }
      }
      else
      {
      	if($site_theme == "slick")
         {
         	print("<a href=\"change_mirror.php?new_mirrorID=".$count."\" onMouseOver=\"imgOn('imgmb_mirror".($count-1)."')\" onMouseOut=\"imgOff('imgmb_mirror".($count-1)."')\"><img name=\"imgmb_mirror".($count-1)."\" src=\"images/site/mb_mirror".($count-1).".png\" border=\"0\"></a>");
   		}
         else
         {
         	print("<b><a href=\"change_mirror.php?new_mirrorID=".$count."\">Mirror " . ($count-1) . "</a></b>");
   		}
      }
   }
   if($count != 4)
   {
   	if($site_theme == "slick")
      {
      	print("<img src=\"images/site/mb_minus.png\">");
   	}
      else
      {
      	print(" - ");
      }
   }
}
*/
if($site_theme == "slick")
{
	print("</td><td class=\"horizontal-menu-bar\" align=\"right\">");
	print("<img src=\"images/site/theme.png\">");
   print("<a href=\"change_site_theme.php?new_site_theme=lite\" onMouseOver=\"imgOn('imgtheme_lite')\" onMouseOut=\"imgOff('imgtheme_lite')\"><img name=\"imgtheme_lite\" src=\"images/site/theme_lite.png\" border=\"0\"></a>");
	print("<img src=\"images/site/theme_minus.png\">");
   print("<img src=\"images/site/theme_slickS.png\" border=\"0\">");
   print("<img src=\"images/site/mb_pat.png\" width=\"10\" height=\"20\">");
}
else
{
	print("</td><td class=\"horizontal-menu-bar-lite\" align=\"right\"><b>THEME:</b> ");
   print("<span class=\"yellow-text\">Lite</span> - <b><a href=\"change_site_theme.php?new_site_theme=slick\">Slick</a></b>&nbsp;&nbsp;&nbsp;");
}
print("</td></tr>\n");

?>

<tr class="horizontal-gradient-menu-bar"><td><img src="images/site/LOGO-Pill.png"></td><td></td><td class="align-right"><img src="images/site/LOGO-Elliptic.png"></td></tr>

<?
if($site_theme == "slick")
{
	print("<tr><td colspan=\"3\" class=\"horizontal-menu-bar\"><img src=\"images/site/mb_pat.png\" width=\"10\" height=\"20\"><img src=\"images/site/TB_art.png\"><img src=\"images/site/mb_minus.png\"><img src=\"images/site/TB_slogan.png\"></td></tr>\n");
	print("<tr><td colspan=\"3\" class=\"horizontal-shadow\"><img src=\"images/site/TB_shadow.png\"></td></tr>\n");
}
else
{
	print("<tr valign=\"middle\"><td colspan=\"3\" class=\"horizontal-menu-bar-lite\">&nbsp;&nbsp;<a class=\"screenshot\" href=\"/index.php\">art.gnome.org</a>&nbsp;&nbsp;-&nbsp;&nbsp;<b>Enhance your GNOME desktop!</b></td></tr>\n");	
}
?>
</table>
<p>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
<img src="images/site/pixel.png" width="20" height="1">
</td>
<!-- Left Column -->
<?

if($site_theme == "lite")
{
?>
	<td width="182">
   <div class="mb_lite-title">
   ART
   </div>
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
	<img src="images/site/pixel.png" width="182" height="1">
   </td>
   <td><img src="images/site/pixel.png" width="10"></td>
<?
}
else
{
?>
<td width="182">

<table border="0" width="182" cellpadding="0" cellspacing="0">
<tr><td width="1"><img src="images/site/ART-Pill_l.png"></td><td width="135"><img src="images/site/ART-Pill_r.png"></td><td width="33" class="horizontal-split"></td><td><img src="images/site/LBOX-top_r.png"></td></tr>
<tr><td width="1" class="black-line"></td><td colspan="2" bgcolor="#a8a7b7">

<!-- Start Link Bar -->
<?
reset($linkbar);
while(list($key,$val)=each($linkbar))
{
	$url = $val["url"];
   $width = $val["width"];
   $heigh = $val["height"];
   $alt = $val["alt"];
   print("<a href=\"$url\" onMouseOver=\"imgOn('img".$key."')\" onMouseOut=\"imgOff('img".$key."')\"><img name=\"img".$key."\" src=\"images/site/linkbar/$key.png\" border=\"0\" alt=\"$alt\"></a><br>\n");
}
?>
<!-- End Link Bar -->

</td><td width="13" class="vertical-shadow"></td></tr>
<tr><td colspan="4"><img src="images/site/LBOX-Shadow.png" width="182"></td></tr>
</table>

</td>
<?
}
?>
<!-- End Left Column -->
