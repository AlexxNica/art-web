<?php

require("config.inc.php");

function create_middle_box_top($pill)
{
	global $pill_array;
   global $site_theme;
   $attributes = $pill_array[$pill];
   $alt = $attributes["alt"];
   $icon = $attributes["icon"];
	print("<!-- Center Column -->\n");
	print("<td width=\"100%\">\n");
  	print("<div class=\"mb-lite-title\"><img src=\"/images/site/pill-icons/$icon\" alt=\"\"> $alt</div>\n");
  	print("<div class=\"mb-lite-contents\">\n");
}

function create_middle_box_bottom()
{
	global $site_theme;
   print("</div>\n");
   print("<!-- End Center Column  -->\n");
}

function display_icons($type, $page)
{
	$icons_per_page = 64;
   /* get the number of columns from the database */
	$icon_select_result = mysql_query("SELECT num_columns FROM icon WHERE name='$type'");
	list($num_columns) = mysql_fetch_row($icon_select_result);
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
				print("<td><a href=\"/images/icons/$type/$file\"><img src=\"/images/icons/$type/$file\" border=\"0\"></a></td>");
				$counter++;
         }
		}
      print("</tr>\n</table>\n</div>\n");
      
      print("<p>\n");
      print("<div align=\"center\">\n");
      /* Page Navigation System */
		if($page > 1)
      {
      	$prev_page = $page -1;
         print(" <a href=\"" . $GLOBALS["PHP_SELF"] . "?&page=$prev_page\">[&lt;]</a>");
      }
		for($count=1;$count<=$num_pages;$count++)
		{
			if($count == $page)
   	   {
   	   	print("<span class=\"bold-text\">[$count]</span> ");
			}
   	   else
   	   {
   	   	print("<a href=\"" . $GLOBALS["PHP_SELF"] . "?page=$count\">[$count]</a> ");
   	   }
   	}
      if($page < $num_pages)
      {
      	$next_page = $page +1;
         print(" <a href=\"" . $GLOBALS["PHP_SELF"] . "?page=$next_page\">[&gt;]</a>");
      }
      print("</div>\n");
   }
   else
   {
   	print("Invalid Directory\n<p>\n");
   }
}

function print_background_row($backgroundID)
{
	global $background_config_array;
	$background_select_result = mysql_query("SELECT background_name, category, author,release_date,thumbnail_filename,download_start_timestamp,download_count FROM background WHERE backgroundID='$backgroundID'");
	list($background_name,$category,$author,$release_date,$thumbnail_filename,$download_start_timestamp,$download_count) = mysql_fetch_row($background_select_result);
	$release_date = fix_sql_date($release_date);
	$link = "/backgrounds/$category/$backgroundID/";
	$category_name = $background_config_array["$category"]["name"];
	$popularity = calculate_downloads_per_day($download_count, $download_start_timestamp);
	print("<tr><td><a href=\"$link\"><img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" class=\"thumbnail-border\"></td><td><a class=\"bold-link\" href=\"$link\">$background_name</a><br>$release_date<br>$category_name<br>$popularity Downloads per Day<br>$author</td></tr>\n");

}

function print_theme_row($themeID)
{
	global $theme_config_array;
	$theme_select_result = mysql_query("SELECT theme_name, category, author, release_date,small_thumbnail_filename,download_start_timestamp,download_count FROM theme WHERE themeID='$themeID'");
	list($theme_name,$category,$author,$release_date,$thumbnail_filename,$download_start_timestamp,$download_count) = mysql_fetch_row($theme_select_result);
	$release_date = fix_sql_date($release_date);
	$link = "/themes/$category/$themeID/";
	$category_name = $theme_config_array["$category"]["name"];
	$popularity = calculate_downloads_per_day($download_count, $download_start_timestamp);
	if($category == "icon")
	{
		$class = "thumbnail";
	}
	else
	{
		$class = "thumbnail-border";
	}
	print("<tr><td><a href=\"$link\"><img src=\"/images/thumbnails/$category/$thumbnail_filename\" class=\"$class\"></td><td><a class=\"bold-link\" href=\"$link\">$theme_name</a><br>$release_date<br>$category_name<br>$popularity Downloads per Day<br>$author</td></tr>\n");
}

function get_latest_backgrounds($number)
{
	unset($big_array);
	$background_select_result = mysql_query("SELECT backgroundID,add_timestamp FROM background WHERE status='active' ORDER BY add_timestamp DESC LIMIT $number");
	while( list($backgroundID,$add_timestamp) = mysql_fetch_row($background_select_result) )
	{
		$big_array[] = $add_timestamp . "|background|". $backgroundID;
	}
   return $big_array;
}

function get_latest_themes($number)
{
	unset($big_array);
   $theme_select_result = mysql_query("SELECT themeID,add_timestamp FROM theme WHERE status='active' ORDER BY add_timestamp DESC LIMIT $number");
	while( list($backgroundID,$add_timestamp) = mysql_fetch_row($theme_select_result) )
	{
		$big_array[] = $add_timestamp . "|theme|". $backgroundID;
	}
	return $big_array;
}

function get_updates_array($number)
{
	$background_array = get_latest_backgrounds($number);
	$theme_array = get_latest_themes($number);
	$big_array = array_merge($background_array,$theme_array);
	rsort($big_array);
   
   if($number < count($big_array))
   {
   	$return_array = array_slice($big_array,0,$number);
   }
   else
   {
   	$return_array = $big_array;
   }
   
   return $return_array;
}



function fix_sql_date($sql_date)
{
	list($year,$month,$day)=explode("-",$sql_date);
	$good_date = $year . "-" . $month . "-" . $day;
	return $good_date;
}

function ago_redirect($referrer)
{
	if($referrer)
	{
		header("Location: $referrer");
	}
	else
	{
		header("Location: /index.php");
	}
}

function ago_file_not_found()
{
	ago_header("404 - File Not Found");
	create_middle_box_top("");
	print("404 - File not Found.");
	create_middle_box_bottom();
	
	ago_footer();
	

}

function print_select_box($name,$array,$selected)
{
	print("<select name=\"$name\">\n");
	while(list($key,$val) = each($array))
	{
		if($key == $selected)
		{
			print("<option value=\"$key\" selected>$val\n");
		}
		else
		{
			print("<option value=\"$key\">$val\n");
		};
	}
	print("</select>\n");
}

function print_thumbnails_per_page_form($thumbnails_per_page, $sort_by, $results_text)
{
	global $thumbnails_per_page_array, $sort_by_array;
	
	if($thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if($sort_by == "")
	{
		$sort_by = "name";
	}
	
	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\">");
	print("<table border=\"0\">\n");
	
	print("<tr><td>Sort By:</td><td>");
	print_select_box("sort_by", $sort_by_array, $sort_by);
	print("</td></tr>\n");
	
	print("<tr><td>$results_text:</td><td>");
	print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $thumbnails_per_page);
	print("</td></tr>\n");
	
	print("</table>\n");
	
	print("<input type=\"submit\" value=\"Change\">\n");
	print("</form>\n");	
}

function display_search_box($search_text, $search_type, $thumbnails_per_page, $sort_by)
{
	global $search_type_array, $thumbnails_per_page_array, $sort_by_array;
		
	if($search_type == "")
	{
		$search_type = "background";
	}
	if($thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if($sort_by == "")
	{
		$sort_by = "name";
	}
	
	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\">");
	print("<table border=\"0\">\n");
	
	print("<tr><td>Search in:</td><td>");
	print_select_box("search_type", $search_type_array, $search_type);
	print("</td></tr>\n");
	
	print("<tr><td>For The Text:</td><td><input type=\"text\" name=\"search_text\" value=\"$search_text\"></td></tr>\n");
	
	print("<tr><td>Sort By:</td><td>");
	print_select_box("sort_by", $sort_by_array, $sort_by);
	print("</td></tr>\n");
	
	print("<tr><td>Results Per Page:</td><td>");
	print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $thumbnails_per_page);
	print("</td></tr>\n");
	
	print("</table>\n");
	
	print("<input type=\"submit\" value=\"Search\">\n");
	print("</form>\n");
}

function background_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_backgrounds)
{
	$num_pages = ceil($num_backgrounds/$thumbnails_per_page);

	if($page > $num_pages)
   {
   	$page = $num_pages;
   }
	$start = (($page - 1) * $thumbnails_per_page);
	$end = $start + $thumbnails_per_page;
	if($end > $num_backgrounds)
	{
		$end = $num_backgrounds;
	}
	print("<b>Showing " . ($start+1) . " through " . $end . " of $num_backgrounds results.</b>\n");

	if($sort_by == "popularity")
	{
		$order_query = "ORDER by perday DESC";
	}
	elseif($sort_by == "date")
	{
		$order_query = "ORDER BY add_timestamp DESC";
	}
	else
	{
		$order_query = "ORDER BY background_name";
	}

	if($category != "")
	{
		$category_query = "category='$category' AND";
	}
	else
	{
		$category_query = "";
	}
	$background_select_result = mysql_query("SELECT backgroundID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM background WHERE $category_query background_name LIKE '%$search_text%' AND parent='0' $order_query LIMIT $start, $thumbnails_per_page");
	print("<table>\n");
	while(list($backgroundID, $perday)=mysql_fetch_row($background_select_result))
	{
		print_background_row($backgroundID);
	}
	print("</table>");
	return array($page, $num_pages);
}

function theme_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_themes)
{
	$num_pages = ceil($num_themes/$thumbnails_per_page);

	if($page > $num_pages)
   {
   	$page = $num_pages;
   }
	$start = (($page - 1) * $thumbnails_per_page);
	$end = $start + $thumbnails_per_page;
	if($end > $num_themes)
	{
		$end = $num_themes;
	}
	print("<b>Showing " . ($start+1) . " through " . $end . " of $num_themes results.</b>\n");

	if($sort_by == "popularity")
	{
		$order_query = "ORDER by perday DESC";
	}
	elseif($sort_by == "date")
	{
		$order_query = "ORDER BY add_timestamp DESC";
	}
	else
	{
		$order_query = "ORDER BY theme_name";
	}
	
	if($category != "")
	{
		$category_query = "category='$category' AND";
	}
	else
	{
		$category_query = "";
	}
	$theme_select_result = mysql_query("SELECT themeID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM theme WHERE $category_query theme_name LIKE '%$search_text%' $order_query LIMIT $start, $thumbnails_per_page");
	print("<table>\n");
	while(list($themeID)=mysql_fetch_row($theme_select_result))
	{
		print_theme_row($themeID);
	}
	print("</table>");
	return array($page, $num_pages);
}

function get_filesize_string($file_path)
{
	if(file_exists($file_path))
	{
		$return_array = stat($file_path);
		$bytes = $return_array["size"];

		if($bytes > (1024*1024))
		{
			$return_string = sprintf("%.1f",round(($bytes / (1024*1024)),1)) . " mb";
		}
		else
		{
			$return_string = sprintf("%.1f",round(($bytes / 1024),1)) . " kb";
		}
		return $return_string;
	}
}

function calculate_downloads_per_day($download_count, $start_timestamp)
{
	$now = time();
	$difference = $now - $start_timestamp;
	$days = $difference / (60*60*24);
	$popularity = ($download_count / $days);
	$popularity = sprintf("%.1f",$popularity);
	return $popularity;
}

////////////////////////////////
// Input Validation Functions //
////////////////////////////////

function validate_input_regexp_default ($input, $regexp, $default)
{
	if (ereg ($regexp, $input))
	{
		return $input;
	}
	else
	{
		// FIXME:  We may want to do some type of alert here, but for the moment, try to continue gracefully
		return $default;
	}
}

function validate_input_regexp_error ($input, $regexp)
{
	if (ereg ($regexp, $input))
	{
		return $input;
	}
	else
	{
		ago_file_not_found ();
		die ();
	}
}

function validate_input_array_default ($input, $search_array, $default)
{
	// FIXME: For some reason array_search doesn't look at the first element in the array
	if (in_array ($input, $search_array) == TRUE)
	{
		return $input;
	}
	else
	{
		return $default;
	}
}

function validate_input_array_error ($input, $array)
{
	if (in_array ($input, $array) == FALSE)
	{
		ago_file_not_found ();
		die ();
	}
	else
	{
		return $input;
	}
}

/* escape_gpc_array() - ensures special characters are escaped in gpc variables */
function escape_gpc_array (&$array)
{
	if (!get_magic_quotes_gpc()) {
		foreach ($array as $key => $value) {
			if (!is_array($value))
				$array[$key] = mysql_escape_string($value);
		}
	}
}

function spam_proof_email($good_email)
{
	$spam_protected_email = ereg_replace("@"," _AT_ ",$good_email);
   return $spam_protected_email;
}

?>
