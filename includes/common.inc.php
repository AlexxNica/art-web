<?
require("config.inc.php");
function create_middle_box_top($pill)
{
	global $pill_array;
   global $site_theme;
   $attributes = $pill_array[$pill];
   $width = $attributes["width"];
   $heigh = $attributes["height"];
   $src =  $attributes["image"];
	$alt = $attributes["alt"];
   print("<!-- Center Column -->\n");
	print("<td width=\"100%\">\n");
	if($site_theme == "lite")
   {
   	print("<div class=\"mb_lite-title\">$alt</div>\n");
   	print("<div class=\"mb_lite-contents\">\n");
   }
   else
   {
      print("<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n");
		print("<tr><td width=\"1\"><img src=\"images/site/ART-Pill_l.png\"></td><td width=\"$width\"><img src=\"images/site/pills/$src\"></td><td width=\"100%\" class=\"horizontal-split\"></td><td><img src=\"images/site/LBOX-top_r.png\"></td></tr>\n");
		print("<tr><td width=\"1\" class=\"black-line\"></td><td colspan=\"2\" bgcolor=\"#a8a7b7\">\n");
      print("<div class=\"mb_standard-contents\">\n");
	}
}

function create_middle_box_bottom()
{
	global $site_theme;
   if($site_theme == "lite")
   {
   	print("</div>\n");
   }
   else
   {
   	print("</div>\n");
      print("</td><td width=\"13\" class=\"vertical-shadow\"></td></tr>\n");
		print("<tr><td colspan=\"3\" class=\"horizontal-bottom-shadow\"><img src=\"images/site/MBOX-shadow_l.png\"></td><td><img src=\"images/site/MBOX-shadow_r.png\"></td></tr>\n");
		print("</table>\n</td>\n");
	}
   print("<!-- End Center Column  -->\n");
}

function display_icons($type, $page)
{
	$icons_per_page = 64;
   if($type == "large")
   {
   	$num_columns = 4;
   }
   else
   {
   	$num_columns = 8;
   }
   if(is_dir($GLOBALS['sys_icon_dir'] . "/$type"))
   {
   	$dir_handle = dir($GLOBALS['sys_icon_dir'] . "/$type");
      
      //skip . and ..
      $dir_handle->read();
      $dir_handle->read();
      
      $num_icons = 0;
      
      // get the total number of icons
      while($file = $dir_handle->read())
      {
      	list($foo,$ext) = explode(".",$file);
         if(in_array($ext,$GLOBALS['valid_image_ext']))
         {
         	$num_icons ++;
         }
      }
      $num_pages = ceil($num_icons / $icons_per_page);
      
      rewinddir ($dir_handle->handle);
		$dir_handle->read();
		$dir_handle->read();
      
      $start_file = $icons_per_page * ($page - 1);
		for ($i=0;$i<$start_file;++$i)
		{
			$file = $dir_handle->read ();
		}
      
      unset($icon_array);
      $counter = 0;
      while( ($file = $dir_handle->read ()) && ($counter < $icons_per_page) )
		{
		   list($foo,$ext) = explode(".",$file);
         if(in_array($ext,$GLOBALS['valid_image_ext']))
			{
				$icon_array[] = $file;
				$counter++;
         }
      }
      
      print("<div align=\"center\">\n<table border=\"0\">\n<tr>");
      $counter = 0;
      while(list($foo,$file)=each($icon_array))
		{
			if($counter > 0 && (($counter % $num_columns) == 0))
         {
         	print("</tr>\n<tr>");
         }
         list($foo,$ext) = explode(".",$file);
         if(in_array($ext,$GLOBALS['valid_image_ext']))
			{
				print("<td><a href=\"images/icons/$type/$file\"><img src=\"images/icons/$type/$file\" border=\"0\"></a></td>");
				$counter++;
         }
		}
      print("</tr>\n</table>\n</div>\n");
      
      print("<p>\n");
      print("<div align=\"center\">\n");
      if($page > 1)
      {
      	$prev_page = $page -1;
         print(" <a href=\"" . $GLOBALS["PHP_SELF"] . "?type=$type&page=$prev_page\">[&lt;]</a>");
      }
		for($count=1;$count<=$num_pages;$count++)
		{
			if($count == $page)
   	   {
   	   	print("<span class=\"yellow-text\">[$count]</span> ");
			}
   	   else
   	   {
   	   	print("<a href=\"" . $GLOBALS["PHP_SELF"] . "?type=$type&page=$count\">[$count]</a> ");
   	   }
   	}
      if($page < $num_pages)
      {
      	$next_page = $page +1;
         print(" <a href=\"" . $GLOBALS["PHP_SELF"] . "?type=$type&page=$next_page\">[&gt;]</a>");
      }
      print("</div>\n");
   }
   else
   {
   	print("Invalid Directory\n<p>\n");
   }
}

function get_updates_array($number)
{
	unset($big_array);
	$background_select_result = mysql_query("SELECT backgroundID,add_timestamp FROM background ORDER BY add_timestamp DESC LIMIT $number");
	while( list($backgroundID,$add_timestamp) = mysql_fetch_row($background_select_result) )
	{
		$big_array[] = $add_timestamp . "|background|". $backgroundID;
	}
	$theme_select_result = mysql_query("SELECT themeID,add_timestamp FROM theme ORDER BY add_timestamp DESC LIMIT $number");
	while( list($backgroundID,$add_timestamp) = mysql_fetch_row($theme_select_result) )
	{
		$big_array[] = $add_timestamp . "|theme|". $backgroundID;
	}
	rsort($big_array);
   $return_array = array_slice($big_array,0,$number);
}
function spam_proof_email($good_email)
{
	$spam_protected_email = ereg_replace("@"," _AT_ ",$good_email);
   return $spam_protected_email;
}

?>
