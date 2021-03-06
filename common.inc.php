<?php

require_once ('config.inc.php');

if (!function_exists('array_combine'))
{
	function array_combine($keys, $vals) {
		$keys = array_values((array) $keys);
		$vals = array_values((array) $vals);
		$n = max(count($keys), count($vals));
		$r = array();
		for ($i=0; $i<$n; $i++)
		{
			$r[$keys[$i]] = $vals[$i];
		}
		return $r;
	}
}

function create_filename($name, $category, $filename, $extra = '')
{
	$categories = Array("metacity" => "MCity", "splash_screens" => "Splash","desktop" => "Theme","gdm_greeter" => "GDM");

	$base = ereg_replace("[_|-]", " ", $name);
	$base = ereg_replace('[^a-zA-Z0-9\s]', " ", $base);
	$base = ucwords($base);
	$base = str_replace(" ", "", $base);
	if (array_key_exists($category, $categories))
		$base = $categories[$category] . "-$base";
	else
		$base = strtoupper($category) . "-$base";
	if ($extra) $base = $base . $extra;
	if (ereg("\.tar\.gz$", $filename)) $ext=".tar.gz";
	elseif (ereg("\.tar\.bz2$", $filename)) $ext = ".tar.bz2";
	elseif (ereg("\.tgz$", $filename)) $ext = ".tar.gz";
	elseif (ereg("\.svg", $filename)) $ext = ".svg";
	elseif (ereg("\.png", $filename)) $ext = ".png";
	elseif (ereg("\.jpg", $filename)) $ext = ".jpg";
	return $base . $ext;

}

function create_breadcrumb ($replace_array = null)
{
	global $theme_config_array, $background_config_array;
	$path = trim ($_SERVER['PHP_SELF'], '/');
	$path_array = explode("/", $path);
	$path_array = array_reverse ($path_array);
	$current_path = '/';
	$result = '<a href="/">Home</a> ';
	while ($foo = array_pop ($path_array))
	{
		if (array_key_exists ($foo, $theme_config_array))
			$name = $theme_config_array[$foo]['name'];
		elseif (array_key_exists ($foo, $background_config_array))
			$name = $background_config_array[$foo]['name'];
		elseif ($replace_array && array_key_exists ($foo, $replace_array))
			$name = $replace_array[$foo];
		else

			$name = ucwords ($foo);
			
		$current_path .= $foo .'/';
		if ($path_array[0] == '') 
			return $result . ' &gt; '.$name;
		$result .= ' &gt; <a href="'.$current_path.'">'.$name.'</a>';
	}
	return $result;
}

function create_title($title, $subtitle=null)
{
	print("\t<h1>$title</h1>\n");
	if ($subtitle)
		print("\t<div class=\"subtitle\">$subtitle</div>\n");
}

function FormatRelativeDate( $nowTimestamp, $thenTimestamp, $isdate = false )
{
	// Taken from Qwikiwiki

	/* $isdate indicates if thenTimestamp is a date (not date and time)
	 * and therefore we shouldn't return any time interval less than a day */


	// Compute the difference
	$numSeconds = $nowTimestamp - $thenTimestamp;
	$numMinutes = round( $numSeconds / 60 );
	$numHours   = round( $numSeconds / 60 / 60 );
	$numDays    = round( $numSeconds / 60 / 60 / 24 );
	$numWeeks   = round( $numSeconds / 60 / 60 / 24 / 7  );



	if ( $isdate && $numHours < 24 ) return "today";
	else if( $numSeconds <  60 ) return "moments ago";
	else if( $numMinutes ==  1 ) return "1 minute ago";
	else if( $numMinutes <  60 ) return "$numMinutes minutes ago";
	else if( $numHours   ==  1 ) return "1 hour ago";
	else if( $numHours   <  24 ) return "$numHours hours ago";
	else if( $numDays    ==  1 ) return "yesterday";
	else if( $numDays    <   7 ) return "$numDays days ago";
	else if( $numWeeks   ==  1 ) return "last week";
	else if( $numWeeks   <   4 ) return "$numWeeks weeks ago";
	else                         return date( "j F Y", $thenTimestamp );
}

function get_thumbnail_url($filename, $itemID, $type, $category, $relative=false)
{
	global $site_url;
	if ($type == "theme")
	{
		if ($itemID < 1000)
			$thumbnail_url="archive/thumbnails/$category/$filename";
		else
			$thumbnail_url="thumbnails/$category/$filename";
	}
	elseif ($type == "background")
	{
		if ($itemID < 1000)
			$thumbnail_url="archive/thumbnails/backgrounds/$filename";
		else
			$thumbnail_url="thumbnails/backgrounds/$filename";
	}
	else
	{
		$thumbnail_url = "thumbnails/{$type}s/$category/$filename";
	}

	return ($relative) ? $thumbnail_url : $site_url . '/images/' . $thumbnail_url;
}

function get_download_links($type, $category, $itemID, $download_filename, $format = 'html')
{
	global $sys_ftp_dir, $site_url;
	switch ($type) {
	case 'theme':
		if ($itemID < 1000)
			$file_path = $sys_ftp_dir . "/archive/themes/$category/$download_filename";
		else
			$file_path = $sys_ftp_dir . "/themes/$category/$download_filename";
		
		switch ($format)
		{
			case 'html':
				$filesize = get_filesize_string($file_path);
				$result = "<a class=\"tar\" href=\"/download/themes/$category/$itemID/$download_filename\">$download_filename ($filesize)</a>";
			break;
			case 'atom':
				$result = "<link rel=\"enclosure\" title=\"Download\" theme:relation=\"$category\" href=\"$site_url/download/themes/$category/$itemID/$download_filename\" />\n";
			break;
		}
	break;

	case 'contest':
		$file_path = $sys_ftp_dir . "/contests/$category/$download_filename";
		
		switch ($format)
		{
			case 'html':
				$filesize = get_filesize_string($file_path);
				$result = "<a class=\"tar\" href=\"/download/contest/$category/$itemID/$download_filename\">$download_filename ($filesize)</a>";
			break;
			case 'atom':
				$result = "<link rel=\"enclosure\" title=\"Download\" theme:relation=\"download\" href=\"$site_url/download/themes/$category/$itemID/$download_filename\" />\n";
			break;
		}
	break;

	case 'background':
		$result = '';
		$resolution_select = mysql_query("SELECT background_resolutionID,filename,resolution,type FROM background_resolution WHERE backgroundID=$itemID");
		
		switch ($format)
		{
			case 'html':
				while (list($resID,$download_filename,$resolution,$image_type) = mysql_fetch_row($resolution_select))
					$result .= "<a class=\"$image_type\" href=\"/download/backgrounds/$category/$resID/$download_filename\"> $image_type - $resolution</a>&nbsp;&nbsp;";
			break;
			case 'atom':
				while (list($resID,$download_filename,$resolution,$image_type) = mysql_fetch_row($resolution_select))
					$result .= "<link rel=\"enclosure\" title=\"Download $resolution ".strtoupper($image_type)."\" theme:relation=\"background\" theme:resolution=\"$resolution\" href=\"$site_url/download/backgrounds/$category/$resID/$download_filename\" />\n";
			break;
		}
	break;
	}
	return $result;
}

function get_thumbnail_class($category)
{
	if ($category == "metacity" || $category == "gtk_engines" || $category == "icon" )
		return "thumbnail_no_border";
	else
		return "thumbnail";
}

function get_category_name($type, $category)
{
	global $theme_config_array, $background_config_array, $contest_config_array, $screenshot_config_array;
	
	if ($type == "theme") {
		return $theme_config_array[$category]['name'];
	} elseif ($type == 'contest') {
		return $contest_config_array[$category]['name'];
	} elseif ($type == 'screenshot') {
		return $screenshot_config_array[$category]['name'];
	} else {
		return 'Backgrounds - '.$background_config_array[$category]['name'];
	}
}

function get_action_url()
{
	if ($_SERVER['QUERY_STRING'])
		return $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	else
		return $_SERVER['PHP_SELF'];
}

function is_logged_in($header = FALSE)
{
	if ($_SESSION['userID'])
		return true; /* the user is logged in, return true */
	else
	{
		$url = get_action_url();
		
		if ($header) art_header($header);
		
		print('<p class="warning">');
		print("The feature you are trying to access is for registered users only.  Please login.</p>");
		create_title("Please log in","Log in to access your account");

		print("<form action=\"$url\" method=\"post\">\n");
		print("<table>\n");
		print("<tr><td><label for=\"musername\">Username</label>:</td><td><input tabindex=\"21\" name=\"username\" class=\"username\" id=\"musername\" /></td></tr>\n");
		print("<tr><td><label for=\"mpassword\">Password</label>:</td><td><input tabindex=\"22\" name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td></tr>\n");
		print("<tr><td><input tabindex=\"23\" type=\"submit\" value=\"Login\" name=\"login\" /></td><td><a href=\"/account.php\" style=\"font-size:0.8em;\">(Register)</a></td></tr>\n");
		print("<tr><td>");
		foreach($_POST as $k => $v)
		{
			/* $_POST is now always quoted */
			$v = stripslashes($v);
			
			$k = htmlentities($k);
			$v = htmlentities($v);
			
			if (($k != "username") && ($k != "password"))
				print("<input type=\"hidden\" value=\"$v\" name=\"$k\" />");
		}
		print("</td></tr>\n");
		print("</table>\n");
		print("</form>\n");
		
		/* stop processing of the page */
		art_footer();
		die();
	}
}

function fix_sql_date($sql_date)
{
	list($year,$month,$day)=explode("-",$sql_date);
	$good_date = $year . "-" . $month . "-" . $day;
	return $good_date;
}

function art_file_not_found()
{
	if (!headers_sent()) {
		header("HTTP/1.0 404 Not Found"); /* send 404 error */
		art_header("404 - File not found");
	}
	create_title("404 - File not found");
	print("<p class=\"error\">The page you requested could not be found</p>");
	art_footer();
	die();
}

function art_fatal_error($header, $title, $message)
{
	art_header($header);
	create_title($title);
	print('<p class="error">'.$message.'</p>');
	art_footer();
	exit();
}

function create_select_box ($name, $array, $selected, $tabindex = false)
{
	$tabindex = validate_input_regexp_default ($tabindex, "^[0-9]+$", false);
	if ($tabindex) $tabindex = ' tabindex="'.$tabindex.'"';
	$result = "<select name=\"$name\" id=\"$name\"$tabindex>\n";
	while(list($key,$val) = each($array))
	{
		if($key == $selected)
		{
			$result .= "\t\t\t\t\t<option value=\"$key\" selected=\"selected\">$val</option>\n";
		}
		else
		{
			$result .= "\t\t\t\t\t<option value=\"$key\">$val</option>\n";
		};
	}
	$result .= "\t\t\t\t</select>";

	return $result;
}

function print_select_box($name,$array,$selected)
{
	echo create_select_box ($name, $array, $selected);
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
	if ($days == 0)
		return "--";
	$popularity = ($download_count / $days);
	$popularity = sprintf("%.1f",$popularity);
	return $popularity;
}

function rating_bar($rating, $count = 5)
{
	global $site_url;
	if ($count < 5)
		return "(5 votes required, $count votes total)";
	$result = ('<small>');
	for ($i=1; $i <= $rating; $i++) 
		$result .= ("<img src=\"{$site_url}/images/site/stock_about.png\" alt=\"*\"/>");
	$result .= (' ('.$count.' votes total)</small>');
	return $result;
}

function user_rating_bar($rating)
{
	global $site_url;
	$active_star = '<img src="/images/site/stock_about.png" alt="*" />';
	$inactive_star = '<img src="/images/site/stock_about_disabled.png" alt="*" />';

	$result = '';
	$i = 0;
	while (++$i <= 5)
	{
		if ($i <= $rating)
			$star = $active_star;
		else
			$star = $inactive_star;
		$result .= '<a href="?rating='.$i.'">'.$star.'</a>';
	}
	if ($rating > 0) $result .= ' (<a href="?rating=0">Remove my vote</a>)';
	return $result;
}

function html_parse_text($comment)
{
	$comment = strip_tags($comment);
	// when there's time, change below to ereg/preg with [img]javascript in front of the string, and [/img] at the end.  but this will work for now.
	$comment = str_replace("[img]javascript:", "noscript", $comment);
	$comment = str_replace("[img]javascript", "noscript", $comment);
	$bbcode = array("&", "]\n", "]\r\n",
		"<", ">",
                "[list]", "[*]", "[/*]", "[/list]", 
		"[**]", "[/**]",
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
	$htmlcode = array("&amp;", "](NL)", "](NL)",
		"&lt;", "&gt;",
                "</p><ul>", "<li>", "</li>", "</ul><p>",
		"<li class=\"indent\">", "</li>",
                "<img src=\"", "\" class=\"thumbnail\" alt=\"\" />", 
                "<strong>", "</strong>", 
                "<span style=\"text-decoration: underline\">", "</span>", 
                "<em>", "</em>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
                '">');
	$comment = str_replace($bbcode, $htmlcode, $comment);

	$comment = preg_replace ('/([^\"]|^)(http:[\S]*)/i', '\1<a href="\2">\2</a> ', $comment);
	$comment = preg_replace ('/([^\"\S]|^)(www[\S]*)/i', '\1<a href="http://\2">\2</a> ', $comment);

	$comment = nl2br($comment);
	$comment = str_replace(">(NL)", ">\n", $comment);

	$comment = ereg_replace(":-\)|:\)", "<img src=\"/images/site/emoticons/stock_smiley-1.png\" alt=\":)\" />", $comment);
	$comment = ereg_replace(";\)|;-\)", "<img src=\"/images/site/emoticons/stock_smiley-3.png\" alt=\":)\" />", $comment);
	$comment = ereg_replace(":-P|:-p|:P|:p", "<img src=\"/images/site/emoticons/stock_smiley-10.png\" alt=\":-P\" />", $comment);
	$comment = ereg_replace(":\(|:-\(", "<img src=\"/images/site/emoticons/stock_smiley-4.png\" alt=\":(\" />", $comment);
	$comment = ereg_replace(":-D|:D", "<img src=\"/images/site/emoticons/stock_smiley-6.png\" alt=\":(\" />", $comment);


	return $comment;
}

function xmlentities($text)
{
	$text = htmlentities($text);

	$htmlEntities = array_values(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES));
	$entitiesDecoded = array_keys(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES));
	$num = count($entitiesDecoded);

	for ($u = 0; $u < $num; $u++)
		$utf8Entities[$u] = '&#'.ord($entitiesDecoded[$u]).';';
	
	return str_replace ($htmlEntities, $utf8Entities, $text);
}

function set_session_var_default($var_name, $default)
{
	if (!isset($_SESSION))
		return $default;
	
	if (!array_key_exists($var_name, $_SESSION))
		$_SESSION[$var_name] = $default;
	return $_SESSION[$var_name];
}

////////////////////////////////
// Input Validation Functions //
////////////////////////////////

function POST ($var)
{
	if (array_key_exists ($var, $_POST))
		return $_POST[$var];
	else
		return null;
}

function GET ($var)
{
	if (array_key_exists ($var, $_GET))
		return $_GET[$var];
	else
		return null;
}

function GET_COOKIE ($name, $default=null)
{
  $set = GET ($name);
  if ($set)
  {
    setcookie ($name, $set, 0, '/');
    $value = $set;
  }
  else
    $value = (array_key_exists ($name, $_COOKIE)) ? $_COOKIE[$name] : $default;

  return $value;
}

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
		art_file_not_found ();
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
		art_file_not_found ();
		die ();
	}
	else
	{
		return $input;
	}
}

function escape_string($foo)
{
	if (!get_magic_quotes_gpc())
		return mysql_real_escape_string($foo);
	else
		return $foo;
}

function strip_string ($foo)
{
	if (!get_magic_quotes_gpc())
		return $foo;
	else
		return stripslashes ($foo);
}

function validate_submit_url($url)
{
        return ereg("^(http(s){0,1}://|ftp://).*(\.tar\.gz|\.tar\.bz2|\.tgz|\.svg|\.png|\.jpg)$", $url);
}


function validate_login ($username, $password, $session = true)
{
	/* validate login and set up user's session if required */

	$query_result = mysql_query ("SELECT userID, realname, password, active FROM user WHERE username = '$username' LIMIT 1");
	list ($userID, $realname, $cryptpass, $active) = mysql_fetch_row ($query_result);

	if ($active == 1)
	{
		if ( md5 ($password) == $cryptpass )
		{
			if ($session)
			{
				$_SESSION['username'] = $username;
				$_SESSION['userID'] = $userID;
				$_SESSION['realname'] = $realname;
				mysql_query ("UPDATE user SET lastlog=NOW() WHERE userid=$userID; LIMIT 1");
			}
			return true; /* user validated! */
		}
		return false; /* user didn't validate */
	}
	return false; // User is blocked
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


function file_chooser($var_name, $dir, $filter="")
{
// Open a known directory, and proceed to read its contents
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			print("\t\t\t\t\t<select name=\"$var_name\">\n\t\t\t\t\t\t<option></option>\n");
			$dir_array = false;
			$item_no = 0;
			while (($file = readdir($dh)) !== false)
			{
				if ($filter != '')
					if (stristr($file, $filter) === FALSE)
						continue;
				if (($file != ".") and ($file != ".."))
				{
					// Insert file into array
					$dir_array[$item_no] = $file;
					$item_no++;
				}
			}
			// Sort files array...
			if (Count($dir_array) > 0) sort($dir_array);
			// ...and print it out
			for ($p = 0; $p < Count($dir_array); $p++) echo "\t\t\t\t\t\t<option value=\"$dir_array[$p]\">$dir_array[$p]</option>\n";
			print("\t\t\t\t\t</select>\n");
			closedir($dh);
		}
	}
}

function conditional_get($etag, $update_time)
{
	$time = gmdate('D, d M Y H:i:s', $update_time) . ' GMT';
	
	$header = apache_request_headers ();
	if (!$header)
		return;

	if (array_key_exists ('If-Modified-Since', $header) || array_key_exists ('If-None-Match', $header))
	{
		$modified = 0;
		if (array_key_exists ('If-Modified-Since', $header))
			$modified = $header['If-Modified-Since'] != $time;
		if (!$modified && array_key_exists ('If-None-Match', $header))
			$modified = $header['If-None-Match'] != $etag;
		
		if (!$modified)
		{
			/* nothing was modified */
			header('HTTP/1.0 304 Not Modified');
			header("ETag: $etag");
			
			exit;
		}
	}
	header("Last-Modified: $time");
	header("ETag: $etag");
}

function generate_feed($list, $format)
{
	global $site_url, $site_name;
	header("Content-type: text/xml");
	$header = new template("$format/header.xml");
	$header->add_var('site_name', $site_name);
	$header->add_var('site_url', $site_url);
	$last_updated = $list->last_updated();

	/* we don't need to think about the session here ... */
	$etag = md5($_SERVER['REQUEST_URI']."-1.".$last_updated);
	conditional_get($etag, $last_updated);

	if ($format == 'atom')
	{
		$header->add_var('request_uri', xmlentities($_SERVER['REQUEST_URI']));
		$header->add_var('update_time', gmdate('Y-m-d\TH:i:s\Z', $last_updated));
	}

	$footer = new template("$format/footer.xml");

	$header->write();
	$list->print_listing();
	$footer->write();
}

?>
