<?php

require("config.inc.php");

function create_title($title, $subtitle="")
{
	print("<h1>$title</h1>\n");
	if ($subtitle)
		print("<div class=\"subtitle\">$subtitle</div>\n");
}

function FormatRelativeDate( $nowTimestamp, $thenTimestamp )
{
	// Taken from Qwikiwiki

	// Compute the difference
	$numSeconds = $nowTimestamp - $thenTimestamp;
	$numMinutes = round( $numSeconds / 60 );
	$numHours   = round( $numSeconds / 60 / 60 );
	$numDays    = round( $numSeconds / 60 / 60 / 24 );
	$numWeeks   = round( $numSeconds / 60 / 60 / 24 / 7  );
	     if( $numSeconds <  60 ) return "moments ago";
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

function get_thumbnail_url($filename, $itemID, $type, $category)
{
	global $site_url;
	if ($type == "theme")
	{
		if ($itemID < 1000)
			$thumbnail_url="{$site_url}/images/archive/thumbnails/$category/$filename";
		else
			$thumbnail_url="{$site_url}/images/thumbnails/$category/$filename";
	}
	else
	{
		if ($itemID < 1000)
			$thumbnail_url="{$site_url}/images/archive/thumbnails/backgrounds/$filename";
		else
			$thumbnail_url="{$site_url}/images/thumbnails/backgrounds/$filename";
	}
	return $thumbnail_url;
}

function get_download_links($type, $category, $itemID, $download_filename)
{
	if ($type == "theme")
	{
		global $sys_theme_dir;
		if ($itemID < 1000)
			$file_path = $sys_theme_dir . "/../archive/theme/$category/$download_filename";
		else
			$file_path = $sys_theme_dir . "/$category/$download_filename";
		
		$filesize = get_filesize_string($file_path);
		$result = "<a class=\"tar\" href=\"/download/themes/$category/$itemID/$download_filename\">$download_filename ($filesize)</a>";
	}
	else
	{
		$result = '';
		$resolution_select = mysql_query("SELECT background_resolutionID,filename,resolution,type FROM background_resolution WHERE backgroundID=$itemID");
		while (list($resID,$download_filename,$resolution,$image_type) = mysql_fetch_row($resolution_select))
			$result .= "<a class=\"$image_type\" href=\"/download/backgrounds/$category/$resID/$download_filename\"> $image_type - $resolution</a>&nbsp;&nbsp;";
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
	global $theme_config_array, $background_config_array;
	
	if ($type == "theme")
		return $theme_config_array[$category]['name'];
	else
		return 'Backgrounds - '.$background_config_array[$category]['name'];
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
		
		if ($header) ago_header($header);
		
		print('<p class="warning">');
		print("The feature you are trying to access is for registered users only.  Please login.</p>");
		create_title("Please log in","Log in to access your account");

		print("<form action=\"$url\" method=\"post\">\n");
		print("<table>\n");
		print("<tr><td><label for=\"musername\">Username</label>:</td><td><input name=\"username\" class=\"username\" id=\"musername\" /></td></tr>\n");
		print("<tr><td><label for=\"mpassword\">Password</label>:</td><td><input name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td></tr>\n");
		print("<tr><td><input type=\"submit\" value=\"Login\" name=\"login\" /></td><td><a href=\"/account.php\" style=\"font-size:0.8em;\">(Register)</a></td></tr>\n");
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
		ago_footer();
		die();
	}
}

function fix_sql_date($sql_date)
{
	list($year,$month,$day)=explode("-",$sql_date);
	$good_date = $year . "-" . $month . "-" . $day;
	return $good_date;
}

function ago_file_not_found()
{
	if (!headers_sent()) {
		header("HTTP/1.0 404 Not Found"); /* send 404 error */
		ago_header("404 - File not found");
	}
	create_title("404 - File not found");
	print("<p class=\"error\">The page you requested could not be found</p>");
	ago_footer();
	die();
}

function print_select_box($name,$array,$selected)
{
	print("<select name=\"$name\" id=\"$name\">\n");
	while(list($key,$val) = each($array))
	{
		if($key == $selected)
		{
			print("<option value=\"$key\" selected=\"selected\">$val</option>");
		}
		else
		{
			print("<option value=\"$key\">$val</option>");
		};
	}
	print("</select>");
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

function rating_bar($rating)
{
	global $site_url;
	$rating = ceil($rating);
	for ($i=1; $i <= $rating; $i++) print("<img src=\"{$site_url}/images/site/stock_about.png\" alt=\"star\"/>");
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
	$comment = nl2br($comment);
	$comment = str_replace(">(NL)", ">\n", $comment);
	$comment = ereg_replace(":-\)|:\)", "<img src=\"/images/site/emoticons/stock_smiley-1.png\" alt=\":)\" />", $comment);
	$comment = ereg_replace(";\)|;-\)", "<img src=\"/images/site/emoticons/stock_smiley-3.png\" alt=\":)\" />", $comment);
	$comment = ereg_replace(":-P|:-p|:P|:p", "<img src=\"/images/site/emoticons/stock_smiley-10.png\" alt=\":-P\" />", $comment);
	$comment = ereg_replace(":\(|:-\(", "<img src=\"/images/site/emoticons/stock_smiley-4.png\" alt=\":(\" />", $comment);
	$comment = ereg_replace(":-D|:D", "<img src=\"/images/site/emoticons/stock_smiley-6.png\" alt=\":(\" />", $comment);

	return $comment;
}

function set_session_var_default($var_name, $default)
{
	if (!array_key_exists($var_name, $_SESSION))
		$_SESSION[$var_name] = $default;
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

function escape_string($foo)
{
	if (!get_magic_quotes_gpc())
		return mysql_real_escape_string($foo);
	else
		return $foo;
}


function validate_submit_url($url)
{
        return ereg("^(http(s){0,1}://|ftp://).*(\.tar\.gz|\.tar\.bz2|\.tgz|\.png|\.jpg)$", $url);
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


function create_select_box($name,$options,$selected)
{
	$select = "selected";
	print("<select id=\"$name\" name=\"$name\">\n");
	while ( list($key,$val) = each($options) )
	{
		if($key == $selected)
		{
			print("<option value=\"$key\" $select>$val</option>\n");
		}
		else
		{
			print("<option value=\"$key\">$val</option>\n");
		}
	}
	print("</select>\n");
}


function file_chooser($var_name, $dir)
{
// Open a known directory, and proceed to read its contents
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			print("<select name=$var_name><option></option>");
			while (($file = readdir($dh)) !== false)
			{
				if (($file != ".") and ($file != ".."))
					echo "<option>$file</option>";
			}
			print("</select>");
			closedir($dh);
		}
	}
}

?>
