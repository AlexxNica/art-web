<?
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
  	print("<div class=\"mb_lite-title\"><img src=\"/images/site/pill-icons/$icon\" alt=\"$alt\"> $alt</div>\n");
  	print("<div class=\"mb_lite-contents\">\n");
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
				print("<td><a href=\"/images/icons/$type/$file\"><img src=\"/images/icons/$type/$file\" border=\"0\"></a></td>");
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

function print_background_row($backgroundID)
{
	$background_select_result = mysql_query("SELECT background_name, category, author,release_date,thumbnail_filename FROM background WHERE backgroundID='$backgroundID'");
	list($background_name,$category,$author,$release_date,$thumbnail_filename) = mysql_fetch_row($background_select_result);
	$release_date = fix_sql_date($release_date,"/");
	print("<tr><td><a href=\"show_background.php?backgroundID=$backgroundID&category=$category\"><img src=\"images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\"></td><td><a class=\"screenshot\" href=\"show_background.php?backgroundID=$backgroundID&category=$category\">$background_name</a><br>$release_date<br>BACKGROUNDS - GNOME<br>$author</td></tr>\n");
}

function print_theme_row($themeID)
{
	global $linkbar;
	$theme_select_result = mysql_query("SELECT theme_name, category, author, release_date,small_thumbnail_filename FROM theme WHERE themeID='$themeID'");
	list($theme_name,$category,$author,$release_date,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
	$release_date = fix_sql_date($release_date,"/");
	$category_good = $linkbar["themes_" . $category]["alt"];
	print("<tr><td><a href=\"show_theme.php?themeID=$themeID&category=$category\"><img src=\"images/thumbnails/$category/$thumbnail_filename\" border=\"0\"></a></td><td><a class=\"screenshot\" href=\"show_theme.php?themeID=$themeID&category=$category\">$theme_name</a><br>$release_date<br>THEMES - $category_good<br>$author</td></tr>\n");
}

function get_latest_backgrounds($number)
{
	unset($big_array);
	$background_select_result = mysql_query("SELECT backgroundID,add_timestamp FROM background ORDER BY add_timestamp DESC LIMIT $number");
	while( list($backgroundID,$add_timestamp) = mysql_fetch_row($background_select_result) )
	{
		$big_array[] = $add_timestamp . "|background|". $backgroundID;
	}
   return $big_array;
}

function get_latest_themes($number)
{
	unset($big_array);
   $theme_select_result = mysql_query("SELECT themeID,add_timestamp FROM theme ORDER BY add_timestamp DESC LIMIT $number");
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

function print_thumbnails_per_page_form()
{
	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"post\">\n");
   print("Thumbnails per page: <select name=\"thumbs_per_page\">\n");
   while(list($key,$val)=each($GLOBALS["thumbnails_per_page_array"]))
   {
   	if($GLOBALS["thumbnails_per_page"] == $val)
      {
      	$selected = " selected";
      }
      else
      {
      	$selected = "";
      }
      print("<option value=\"$val\"$selected>$key\n");
   }
   print("</select>\n");
   print("<input type=\"hidden\" name=\"change_thumbnails_per_page\" value=\"1\">\n");
   print("<input type=\"hidden\" name=\"referrer\" value=\"".$GLOBALS["REQUEST_URI"]."\">\n");
   print("<input type=\"submit\" value=\"Change\">\n");
   print("</form>\n");
}

function fix_sql_date($sql_date,$delimiter)
{
	list($year,$month,$day)=explode("-",$sql_date);
   $good_date = $month . $delimiter . $day . $delimiter . $year;
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
		header("Location: index.php");
	}
}

function ago_file_not_found()
{
	print("art.gnome.org, file NOT found.");

}

function spam_proof_email($good_email)
{
	$spam_protected_email = ereg_replace("@"," _AT_ ",$good_email);
   return $spam_protected_email;
}

?>
