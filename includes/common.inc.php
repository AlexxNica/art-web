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

function show_icons_alex ($type, $page, $num_per_page)
{
	if (is_dir ( $GLOBALS['sys_icon_dir'] . "/$type" ) )
	{
		$d = dir ($GLOBALS['sys_icon_dir']."/$type");
	}
	else
	{
		print "bad directory";
		exit ();
	}
	
	// skip . and ..
	$d->read();
	$d->read();
	
	// get a count for number of files in the directory
	$num_files = 0;
	
	while ($file = $d->read ())
	{
		$pos = strrpos ($file, ".") + 1;
		$extension = substr ($file, $pos);
	
		if (in_array ($extension, $GLOBALS['sys_valid_images_array']))
		{
			++$num_files;
		}
	}
	
	// Find out how many pages are need to display all the images given the current 
	// number of images per page
	
	$num_pages = ceil ($num_files / $num_per_page);
	
	// Position the directory handle to the files for the page we want
	
	rewinddir ($d->handle);
	$d->read();
	$d->read();
	
	$start_file = $num_per_page * ($page - 1);
	
	for ($i = 0; $i < $start_file; ++$i)
	{
		$file = $d->read ();
	}
	
	// If a file has a valid extension for graphics, add it to the files array
	$i = 0;
	
	while (($file = $d->read ()) && ($i < $num_per_page))
	{
		$pos = strrpos ($file, ".") + 1;
		$extension = substr ($file, $pos);
	
		if (in_array ($extension, $GLOBALS['sys_valid_images_array']))
		{
			$d_ary[] = $file;
		}
	}

	$cols = $num_per_page / 8;
	$cur_file = 0;
	
   print "<center>\n<form action=\"$PHP_SELF\" method=\"get\">\n";
	print "Icons per page \n<select name=\"num_per_page\">\n";
	foreach ($GLOBALS['sys_number_images_array'] as $value)
	{
		print "<option value=\"".$value."\"";
		
		if ($value == $num_per_page)
		{
			print " selected";
		}
	
		print ">".$value."\n";
	}
	
	print("</select>\n<input type=\"submit\" value=\"Select\">\n");
   print("<input type=\"hidden\" name=\"type\" value=\"$type\">");
   print("</form>\n</center>");
	print "<table cellpadding=\"5\" border=\"0\" align=\"center\">\n";
	
	while ($cur_file < $num_per_page)
	{
		print "<tr align=\"center\" valign=\"middle\">\n";
	
		$i = 0;
		
		while (($i < $cols) && ($cur_file < $num_per_page))
		{
			print "\t<td><a href=\"".$GLOBALS['sys_http_icon_root']."/$type/".$d_ary[$cur_file]."\">";
			print "<img src=\"".$GLOBALS['sys_http_icon_root']."/$type/".$d_ary[$cur_file]."\" border=\"0\" alt=\"".$d_ary[$cur_file]."\">";
			print "</a></td>\n";
			
			++$i;
			++$cur_file;
		}
	
		print "</tr>\n";
	}
	
	print "</table>\n";
   
   // print the page listing
	
	print "<p align=\"center\"><span class=\"yellow-text\">Page</span> ";
	
	for ($i = 1; $i <= $num_pages; ++$i)
	{
		print " ";
		
		if ($i == $page)
		{
			print "<b><span class=\"yellow-text\">".$i."</span></b>";
		}
		else
		{
			print "<a href=\"$PHP_SELF?page=$i&num_per_page=$num_per_page&type=$type\">$i</a>";
		}
	}
	
	print "</p><p>\n";

}

function display_icons($type, $page)
{
	$icons_per_page = 64;
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
      
      print("<table border=\"0\">\n<tr>");
      $counter = 1;
      while(list($foo,$file)=each($icon_array))
		{
			if($counter > 1 && (($counter % 8) == 0))
         {
         	print("</tr>\n<tr>");
         }
         list($foo,$ext) = explode(".",$file);
         if(in_array($ext,$GLOBALS['valid_image_ext']))
			{
				print("<td><img src=\"images/icons/$type/$file\"></td>");
				$counter++;
         }
			print("</tr>\n");
      }
      print("</table>");
   }
   else
   {
   	print("Invalid Directory\n<p>\n");
   }
}

?>
