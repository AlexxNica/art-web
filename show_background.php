<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");

$temp = "backgrounds_" . $category;
create_middle_box_top($temp);
$background_select_result = mysql_query("SELECT * FROM background WHERE backgroundID='$backgroundID'");
if(mysql_num_rows($background_select_result)==0)
{
   print("Invalid Background");
}
else
{
   $background_select_row = mysql_fetch_array($background_select_result);
   $thumbnail_filename = $background_select_row["thumbnail_filename"];
   $screenshot_filename = $background_select_row["screenshot_filename"];
   $name = $background_select_row["background_name"];
   $author = $background_select_row["author"];
   $author_email = $background_select_row["author_email"];
   $author_email = spam_proof_email($author_email);
   $release_date = $background_select_row["release_date"];
   list($year,$month,$day)=explode("-",$release_date);
   $release_date = $month . "/" . $day . "/" . $year;
   $background_description = $background_select_row["background_description"];
   $screenshot_description = $background_select_row["screenshot_description"];
   $background_resolution_select_result = mysql_query("SELECT type, resolution, filename FROM background_resolution WHERE backgroundID='$backgroundID' ORDER BY type,resolution");
   print("<table border=\"0\">\n<tr><td valign=\"top\">");
   print("<img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" class=\"shot2\">\n");
   print("</td><td>\n");
   print("<span class=\"yellow-text\">Name:</span> $name<br>\n<span class=\"yellow-text\">Author:</span> <a href=\"mailto:$author_email\">$author</a><br>\n<span class=\"yellow-text\">Release Date:</span> $release_date<br><span class=\"yellow-text\">Resolutions:</span>");
   while(list($type,$resolution,$filename)=mysql_fetch_row($background_resolution_select_result))
   {
      if($current_type != $type)
      {
         print("<br>\n&nbsp;&nbsp;");
         $current_type = $type;
      }
      else
      {
      	print(" ;\n");
      }
      print("<a href=\"$mirror_url/backgrounds/$filename\">" . $type . "-" . $resolution . "</a>\n");
   }
   print("<br>\n");
   if($screenshot_filename != "")
   {
   	print("<a href=\"images/screenshots/$screenshot_filename\" class=\"screenshot\">Screenshot</a><p>\n");
   }
   print("</td></tr></table>\n");
   print("<p>\n<span class=\"yellow-text\">Info (Picture):</span> $background_description\n");
   if($screenshot_description != "")
   {
   	print("<p>\n<span class=\"yellow-text\">Info (Screenshot):</span> $screenshot_description\n");
   }
   $back_select_result = mysql_query("SELECT backgroundID,background_name,category FROM background WHERE parent='$backgroundID'");
   if(mysql_num_rows($back_select_result)>0)
   {
      print("<p>\n<span class=\"yellow-text\">Variations:</span> ");
      while(list($backID,$back_name,$back_cat)=mysql_fetch_row($back_select_result))
      {
         if($first_toggle)
         {
            print(" - ");
         }
         else
         {
            $first_toggle = 1;
         }
         print("<a href=\"$PHP_SELF?backgroundID=$backID&category=$back_cat\">$back_name</a>");
      }
   }
}

create_middle_box_bottom();
include("footer.inc.php");
?>
