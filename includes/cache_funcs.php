<?php

function show_icons ($page, $num_per_page)
{
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
	print "<center>\n<form action=\"".$HTTP_SERVER_VARS['PHP_SELF']."\" method=\"POST\">\n";
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
	
	print("</select>\n<input type=\"submit\" value=\"Select\">\n</form>\n</center>");
	print "<table cellpadding=\"5\" border=\"0\" align=\"center\">\n";
	
	while ($cur_file < $num_per_page)
	{
		print "<tr align=\"center\" valign=\"middle\">\n";
	
		$i = 0;
		
		while (($i < $cols) && ($cur_file < $num_per_page))
		{
			print "\t<td><a href=\"".$GLOBALS['sys_http_icon_root']."/".$d_ary[$cur_file]."\">";
			print "<img src=\"".$GLOBALS['sys_http_icon_root']."/".$d_ary[$cur_file]."\" border=\"0\" alt=\"".$d_ary[$cur_file]."\">";
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
			print "<a href=\"".$HTTP_SERVER_VARS['PHP_SELF']."?page=".$i."&num_per_page="
				. $num_per_page."\">".$i."</a>";
		}
	}
	
	print "</p><p>\n";

}

?>
