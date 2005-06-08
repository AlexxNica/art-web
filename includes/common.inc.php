<?php

require("config.inc.php");

function create_title($title, $subtitle="")
{
	print("<div class=\"h1\">$title</div>\n");
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
			$thumbnail_url="{$site_url}images/archive/thumbnails/$category/$filename";
		else
			$thumbnail_url="{$site_url}images/thumbnails/$category/$filename";
	}
	else
	{
		if ($itemID < 1000)
			$thumbnail_url="{$site_url}images/archive/thumbnails/backgrounds/$filename";
		else
			$thumbnail_url="{$site_url}images/thumbnails/backgrounds/$filename";
	}
	return $thumbnail_url;
}

function try_login($actionrequired='')
{	
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$query_result = mysql_query("SELECT userID, realname, password FROM user WHERE username = '$username'");
	$referer = validate_input_regexp_default($_POST['referer'], "^[a-z0-9#\?\&\=\./]+$", "/account.php");

	list($userID, $realname, $cryptpass ) = mysql_fetch_row($query_result);

	if ( (md5($password) == $cryptpass)  )
	{
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $userID;
		$_SESSION['realname'] = $realname;
		mysql_query("UPDATE user SET lastlog=NOW() WHERE userid=$userID;");
		create_title("Login Successful", "");
		if ($_SERVER['QUERY_STRING'])
			$referer = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
		else
			$referer = $_SERVER['PHP_SELF'];
		switch ($actionrequired)
		{
		case 'faq':
			$query = "INSERT INTO `faq` (`faqID`, `question`, `answer`, `status`, `userID`) VALUES ('', '".mysql_real_escape_string($_POST['question'])."', '', 'pending', '".$_SESSION['userID']."')";
			if(mysql_query($query))
				print("<p>Thank you $username.  Your question has been successfully submitted.  <a href=\"$referer\">Continue...</a></p>");
				ago_footer();
				die();
		break;
		case 'comments':
			list($f00, $unvalidated_type, $category, $artID) = explode("/", $_SERVER['PHP_SELF']);
			if ($unvalidated_type == 'themes')
				$type = 'theme';
			elseif ($unvalidated_type == 'backgrounds')
				$type = 'background';
			else
				++$fail;
			if(!ereg("^[0-9]+$", $artID))
				++$fail;
			$comment = mysql_real_escape_string($_POST['comment']); // make sure it is safe for mysql
			if(!$fail)
				$comment_result = mysql_query("INSERT INTO comment(`artID`, `userID`, `type`, `timestamp`, `comment`) VALUES('$artID', '" . $_SESSION['userID'] . "', '$type', '" . time() . "', '" . $comment . "')");
			if ($comment_result === False)
			{
				return ("<p class=\"error\">There was an error adding your comment.</p>");
			} else
				print("<p>Thank you $username.  Your comment was successfully entered.  <a href=\"$referer?time=".time()."#comment\">Continue...</a></p>");
			ago_footer();
			die();
		break;
		case 'vote':
			list($f00, $unvalidated_type, $category, $artID) = explode("/", $_SERVER['PHP_SELF']);
			if ($unvalidated_type == 'themes')
				$type = 'theme';
			elseif ($unvalidated_type == 'backgrounds')
				$type = 'background';
			else
				$fail++;
			if(!ereg("^[0-9]+$", $artID))
				$fail++;
			if(!ereg("^[1-5]+$", $_POST['rating']))
				$fail++;
			if(!$fail)
			{
				$result = mysql_query("SELECT userID FROM $type WHERE {$type}ID='$artID'");
				list($authorID) = mysql_fetch_row($result);

				if($_SESSION['userID'] != $authorID)
				{
					add_vote($artID, mysql_real_escape_string($_POST['rating']), $_SESSION['userID'], $type);
					print("<p>Thanks for your vote, $username.  <a href=\"$referer?time=".time()."\">Continue...</a></p>");
				}		
				else
					print("<p>Thanks for logging in, $username, but you are not allowed to vote for your own artwork.  <a href=\"$referer?time=".time()."\">Continue...</a></p>");

			}
			else
				print("<p class=\"error\">Poop.There was an error counting your vote.  Please contact an administrator</p>");
			ago_footer();
			die();
		break;
		default:
			print("<p>You are now logged in as $username. <a href=\"$referer\">Continue...</a></p>");
		break;
		}
	}
	else
	{
		create_title("Login failed","");
		print("<p>Please <a href=\"{$_SERVER["PHP_SELF"]}\">try again</a>.</p>");
	}

}

function is_logged_in($actionrequired)
{
	if ($_SESSION['userID'])
		return true;
	else
	{
		if ($_SERVER['QUERY_STRING'])
			$referer = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
		else
			$referer = $_SERVER['PHP_SELF'];

		if ($actionrequired == 'comments')
			$referer = $referer."#comment";
		if(array_key_exists('login', $_POST))
			try_login($actionrequired);
		else {
			print('<p class="warning">');
			print("The feature you are trying to access is for registered users only.  Please login.</p>");
			create_title("Please log in","Log in to access your account");

			print("<form action=\"$referer\" method=\"post\">\n");
			print("<table>\n");
			print("<tr><td><label for=\"musername\">Username</label>:</td><td><input name=\"username\" class=\"username\" id=\"musername\" /></td></tr>\n");
			print("<tr><td><label for=\"mpassword\">Password</label>:</td><td><input name=\"password\" type=\"password\" class=\"password\" id=\"mpassword\" /></td></tr>\n");
			print("<tr><td><input type=\"hidden\" value=\"$referer\" name=\"referer\" /><input type=\"submit\" value=\"Login\" name=\"login\" /></td><td><a href=\"/account.php\" style=\"font-size:0.8em;\">(Register)</a></td></tr>\n");
			print("<tr><td>");
			foreach($_POST as $k => $v)
				print("<input type=\"hidden\" value=\"$v\" name=\"$k\" />");
			print("</td></tr>\n");
			print("</table>\n");
			print("</form>\n");
			ago_footer();
			die();
		}
	}
}

function print_item_row($itemID, $type, $style="list", $absolute_url=false)
{
	global  $background_config_array,  $theme_config_array, $site_url;

	if ($type == "theme")
		$select = mysql_query("SELECT theme_name, category, add_timestamp, small_thumbnail_filename, rating FROM $type WHERE themeID = $itemID");
	else
		$select = mysql_query("SELECT background_name, category, add_timestamp, thumbnail_filename, rating FROM $type WHERE backgroundID = $itemID");

	list($name,$category,$date,$thumbnail_filename, $rating) = mysql_fetch_row($select);

	$date = ucfirst(FormatRelativeDate(time(), $date));

	$thumbnail = get_thumbnail_url($thumbnail_filename, $itemID, $type, $category);

	if (($category == "icon") or ($category == "metacity"))
		$thumbnail_class = "thumbnail_no_border";
	else
		$thumbnail_class = "thumbnail";

	if ($type == 'background')
	{
		$category_name = "Backgrounds - " . $background_config_array["$category"]["name"];
	}
	else
	{
		$category_name = $theme_config_array["$category"]["name"];
	}
	if ($absolute_url)
		$link = $site_url;
	$link .= "/{$type}s/$category/$itemID";

	if ($style == "icons")
	{
		print("<div class=\"icon_view\">\n<a href=\"$link\">");
		print("<img src=\"$thumbnail\" alt=\"Thumbnail of $item_name\" class=\"$thumbnail_class\" />");
		print("</a><br/>\n");
		rating_bar($rating);
		print("</div>\n");
	} else
	{
		print("<table border=\"0\" style=\"margin-bottom:1em;\"><tr>\n");
		print("\t<td style=\"width:120px\"><a href=\"$link\"><img src=\"$thumbnail\" alt=\"Thumbnail\" class=\"$thumbnail_class\"/></a>");
		print("</td>\n");
		print("\t<td><a href=\"$link\" class=\"h2\"><strong>".html_parse_text($name)."</strong></a><br/>");
		print("\t\t<span class=\"subtitle\">$category_name<br/>$date</span><br/>");
		rating_bar($rating);
		print("</td>\n");
		print("</tr></table>\n");
	}
}

function print_background_row($backgroundID, $view, $absolute_url=false)
{
	print_item_row($backgroundID, 'background', $view, $absolute_url);
}

function print_theme_row($themeID, $view, $absolute_url=false)
{
	print_item_row($themeID, 'theme', $view, $absolute_url);
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
	if (!headers_sent())
		ago_header("404 - File not found");
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

function print_thumbnails_per_page_form($thumbnails_per_page, $sort_by, $results_text, $view, $order)
{
	global $thumbnails_per_page_array, $sort_by_array, $view_array, $order_array;
	
	if($thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if($sort_by == "")
	{
		$sort_by = "name";
	}
	if($order == "")
	{
		$order = "DESC";
	}

	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\"><p>");

	print("Sort By: ");
	print_select_box("sort_by", $sort_by_array, $sort_by);

	print(" $results_text: ");
	print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $thumbnails_per_page);

	print(" View: ");
	print_select_box("view", $view_array, $view);

	print(" Order: ");
	print_select_box("order", $order_array, $order);

	print(" <input type=\"submit\" value=\"Change\" />");
	print("</p></form>\n");
}

function display_search_box($search_text, $search_type, $thumbnails_per_page, $sort_by, $order)
{
	global $search_type_array, $thumbnails_per_page_array, $sort_by_array, $order_array;
		
	if($search_type == "")
	{
		$search_type = "theme_name";
	}
	if($thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if($sort_by == "")
	{
		$sort_by = "name";
	}
	if($order == "")
	{
		$order = "DESC";
	}
	
	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\">");
	print("<table border=\"0\">\n");
	
	print("\t<tr><td>Search in:</td><td>");
	print_select_box("search_type", $search_type_array, $search_type);
	print("</td></tr>\n");
	
	print("\t<tr><td>For The Text:</td><td><input type=\"text\" name=\"search_text\" value=\"$search_text\"/></td></tr>\n");
	
	print("\t<tr><td>Sort By:</td><td>");
	print_select_box("sort_by", $sort_by_array, $sort_by);
	print("</td></tr>\n");
	
	print("\t<tr><td>Results Per Page:</td><td>");
	print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $thumbnails_per_page);
	print("</td></tr>\n");

	print("\t<tr><td>Order:</td><td>");
	print_select_box("order", $order_array, $order);
	print("</td></tr>\n");
	
	print("\t<tr><td colspan=\"2\"><input type=\"submit\" value=\"Search\"/></td></tr>");

	print("</table>\n");
	print("</form>\n");
}

function background_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_backgrounds, $view, $order="DESC")
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
	print("<strong>Showing " . ($start+1) . " through " . $end . " of $num_backgrounds results.</strong><br />\n");

	if($sort_by == "popularity")
	{
		$order_query = "ORDER by perday $order";
	}
	elseif($sort_by == "date")
	{
		$order_query = "ORDER BY add_timestamp $order";
	}
	elseif($sort_by == "rating")
	{
		$order_query = "ORDER BY (rating) $order";
	}
	else
	{
		$order_query = "ORDER BY background_name $order";
	}

	if($category != "")
	{
		$category_query = "category='$category' AND";
	}
	else
	{
		$category_query = "";
	}
	$background_select_result = mysql_query("SELECT backgroundID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM background WHERE $category_query $search_type LIKE '%$search_text%' AND parent='0' AND status='active' $order_query LIMIT $start, $thumbnails_per_page");
	while(list($backgroundID, $perday)=mysql_fetch_row($background_select_result))
	{
		print_background_row($backgroundID, $view);
	}
	return array($page, $num_pages);
}

function theme_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_themes, $view, $order="DESC")
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
	print("<strong>Showing " . ($start+1) . " through " . $end . " of $num_themes results.</strong><br />\n");

	if($sort_by == "popularity")
	{
		$order_query = "ORDER by perday $order";
	}
	elseif($sort_by == "date")
	{
		$order_query = "ORDER BY add_timestamp $order";
	}
	else
	{
		$order_query = "ORDER BY theme_name $order";
	}
	
	if($category != "")
	{
		$category_query = "category='$category' AND";
	}
	else
	{
		$category_query = "";
	}
	$theme_select_result = mysql_query("SELECT themeID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM theme WHERE $category_query $search_type LIKE '%$search_text%' AND status='active' $order_query LIMIT $start, $thumbnails_per_page");
	while(list($themeID)=mysql_fetch_row($theme_select_result))
	{
		print_theme_row($themeID, $view);
	}
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
	if ($days == 0)
		return "--";
	$popularity = ($download_count / $days);
	$popularity = sprintf("%.1f",$popularity);
	return $popularity;
}

function rating_bar($rating)
{
	//$percent = sprintf("%.0f", $rating);
	//$bar = "<div class=\"rating-border\"><div class=\"rating\" style=\"width:$percent%\">&nbsp;</div></div>\n";
	$rating = ceil($rating);
	for ($i=1; $i <= $rating; $i++) print("<img src=\"/images/site/stock_about.png\" alt=\"star\"/>");
}

function is_ie() {
	if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") && !strstr($_SERVER['HTTP_USER_AGENT'], "Opera"))
		return true;
	else
		return false;
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
	print("<select name=\"$name\">\n");
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
