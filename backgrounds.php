<?php
require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");

$request = $PATH_INFO;
$request = ereg_replace("/+", "/", $request);
$sections = explode("/", $request);
$category = $sections[1];
$filename = $sections[2];
if(!$filename || $filename == "")
{
	$filename = "index.php";
}

if($category == "index.php" || $category == "" || !$category)
{
	include("header.inc.php");
	create_middle_box_top("backgrounds");

	print("Backgrounds are images for use on your desktop as the desktop background, sometimes known as wallpapers.");
	print("<p>\n<table border=\"0\" cellpadding=\"4\">");
	print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"background_list.php?category=gnome\">GNOME</a> - The GNOME project has built a complete, free and easy-to-use desktop environment for the user, as well as a powerful application framework for the software developer.</td></tr>\n");
	print("<tr><td valign=\"top\"><img src=\"images/site/circle.png\"></td><td><a class=\"screenshot\" href=\"background_list.php?category=other\">Other</a> - Backgrounds featuring other GNOME based companies such as Ximian, Codefactory, RedHat, etc.</td></tr>\n");
	print("</table>\n");
	
	create_middle_box_bottom();
	include("footer.inc.php");
}
else if(($category == "gnome" || $category == "other") && ($filename == "index.php" || $filename=="" || !$filename) )
{
	include("header.inc.php");
	$temp = "backgrounds_" . $category;
   create_middle_box_top($temp);
	print("<div align=\"center\">\n");
	if(!$page)
	{
		$page = 1;
	}
	$background_select_all_result = mysql_query("SELECT * FROM background WHERE category='$category' AND parent='0' ORDER BY add_timestamp DESC");
	$num_backgrounds = mysql_num_rows($background_select_all_result);
	if($num_backgrounds == 0)
   {
   	print("No backgrounds have been added to this section yet.");
   }
   else
   {
	   $num_pages = ceil($num_backgrounds/$thumbnails_per_page);
		if($page > $num_pages)
      {
      	$page = $num_pages;
      }
		$start = (($page - 1) * $thumbnails_per_page);
		$background_select_result = mysql_query("SELECT * FROM background WHERE category='$category' AND parent='0' ORDER BY add_timestamp DESC LIMIT $start, $thumbnails_per_page");
		print_thumbnails_per_page_form();
      print("<p>\n");
      while($background_select_row = mysql_fetch_array($background_select_result))
		{
			$backgroundID = $background_select_row["backgroundID"];
		   $thumbnail_filename = $background_select_row["thumbnail_filename"];
		   $background_name = $background_select_row["background_name"];
		   //print("<a href=\"show_background.php?backgroundID=$backgroundID&category=$category\" title=\"$background_name\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" class=\"shot\" border=\"0\" alt=\"$background_name\"></a>\n");
			print("<a href=\"/backgrounds/$category/$backgroundID" . ".html\" title=\"$background_name\"><img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" class=\"shot\" border=\"0\" alt=\"$background_name\"></a>\n");
		}
		print("<p>\n");
      if($page > 1)
      {
      	$prev_page = $page -1;
         print(" <a href=\"/backgrounds/$category/$filename?page=$prev_page\">[&lt;]</a>");
      }
		for($count=1;$count<=$num_pages;$count++)
		{
			if($count == $page)
   	   {
   	   	print("<span class=\"yellow-text\">[$count]</span> ");
			}
   	   else
   	   {
   	   	print("<a href=\"/backgrounds/$category/$filename?page=$count\">[$count]</a> ");
   	   }
   	}
      if($page < $num_pages)
      {
      	$next_page = $page +1;
         print(" <a href=\"/backgrounds/$category/$filename?page=$next_page\">[&gt;]</a>");
      }
      print("<br>\n");
      print("<form action=\"show_background.php\" method=\"get\">\n");
      print("<select name=\"backgroundID\">\n");
      $background_select_result = mysql_query("SELECT backgroundID, background_name FROM background WHERE category='$category' AND parent='0' ORDER BY background_name");
		while(list($backgroundID,$background_name) = mysql_fetch_row($background_select_result))
      {
      	print("<option value=\"$backgroundID\">$background_name</option>\n");
      }
      print("</select>\n");
      print("<input type=\"hidden\" name=\"category\" value=\"$category\">\n");
      print("<input type=\"submit\" value=\"Go\">\n");
      print("</form>");
   }
   print("</div>\n");
   create_middle_box_bottom();
	include("footer.inc.php");
}
else if(($category == "gnome" || $category == "other") && ereg("^([0-9]{1,5})\.html",$filename))
{
   $backgroundID = ereg_replace(".html","",$filename);
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
      print("<img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" class=\"shot2\">\n");
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
}
else
{
	print("$filename - 404");
}

?>
