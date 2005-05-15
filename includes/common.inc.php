<?php

require("config.inc.php");

function create_title($title, $subtitle)
{
	print("<div class=\"h1\">$title</div>\n");
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
	else if( $numDays    < 365 ) return date( "F j", $thenTimestamp );
	else                         return "over a year ago";
}

function print_item_row($name, $thumbnail, $category, $author, $date, $link, $vars, $vote, $extra)
{
	global  $background_config_array,  $theme_config_array;

	if ($vars)
		$var_image = "<img src=\"/images/site/stock_color.png\" alt=\"Variations Available\" />";
	else
		$var_image = "";

	if (($category == "icon") or ($category == "metacity"))
		$thumbnail_class = "thumbnail_no_border";
	else
		$thumbnail_class = "thumbnail";

	if (($category == "gnome") or ($category == "other"))
		$category_name = "Backgrounds - " . $background_config_array["$category"]["name"];
	else
		$category_name = $theme_config_array["$category"]["name"];
	
	print("<table class=\"theme_row\">\n");
	print("\t<tr valign=\"top\">\n");
	print("\t\t<td class=\"theme_row_col1\"><a href=\"$link\"><img src=\"$thumbnail\" alt=\"Thumbnail\" class=\"$thumbnail_class\" /></a>$vote\t\t</td>\n");
	print("\t\t<td class=\"theme_row_detail\"><a href=\"$link\" class=\"h2\"><strong>".html_parse_text($name)."</strong></a><br /><span class=\"subtitle\">$category_name<br/>Date: $date<br />Author: $author<br />$var_image");
	foreach ($extra as $val)
		print($val."<br />");
	print("</span>\n\t\t</td>\n\t</tr>\n</table>\n");
}

function print_background_row($backgroundID, $view)
{
	global $background_config_array, $site_url;

	$background_select_result = mysql_query("SELECT background_name,category,username,release_date,thumbnail_filename,download_start_timestamp,download_count FROM background, user WHERE backgroundID='$backgroundID' AND background.userID = user.userID");

	$background_res_result = mysql_query("SELECT DISTINCT(resolution) FROM background_resolution WHERE backgroundID='$backgroundID'");
	$var_select_result = mysql_query("SELECT * FROM background WHERE parent = '$backgroundID'");
	if (mysql_num_rows($var_select_result) > 0) $vars = true; else $vars = false;

	$vote_select_result = mysql_query("SELECT SUM(rating) AS vote_sum, COUNT(rating) AS vote_count FROM `vote` WHERE artID='$backgroundID' AND type='background'");
	list($vote_sum, $vote_count) = mysql_fetch_row($vote_select_result);

	$extra[0] = "Resolutions: ";
	extract(mysql_fetch_array($background_select_result));
	while (list($background_res) = mysql_fetch_row($background_res_result))
		$extra[0] .= " $background_res";

	$release_date = fix_sql_date($release_date);
	if ($backgroundID < 1000) {
		$link = "{$site_url}backgrounds/$category/$backgroundID/";
		$thumbnail = "{$site_url}images/archive/thumbnails/backgrounds/$thumbnail_filename";
	} else {
		$link = "{$site_url}backgrounds/$category/$backgroundID/";
		$thumbnail = "{$site_url}images/thumbnails/backgrounds/$thumbnail_filename";
	}
	$popularity = calculate_downloads_per_day($download_count, $download_start_timestamp);

	if ($view != "compact")
	{
		$query = "SELECT COUNT(*) AS count FROM comment WHERE artID = '$backgroundID' AND type='background' AND status != 'deleted'";
		$comment_select = mysql_query($query);
		list($count) = mysql_fetch_array($comment_select);
		if ($count > 1) { $extra[1] = $count." comments"; }
		if ($count == 1) { $extra[1] = $count." comment"; }
	}

	if ($view == "compact")
	{
		$vote = "";
	}
	elseif ($vote_count < 5)
	{
		$vote = "";
	}
	else
	{
		$vote = rating_bar(calculate_rating($vote_sum, $vote_count));
		$vote .= "<div class=\"rating_text\">Rating: " . calculate_rating($vote_sum, $vote_count) . "%</div>\n";
	}
	
	if ($view == "icons")
	{
		print("<div class=\"icon_view\"><a href=\"$link\"><img style=\"padding: 2px; border: none;\" src=\"$thumbnail\" alt=\"Thumbnail\"/></a>$vote</div> ");
	}
	else
	{
		print_item_row($background_name, $thumbnail, $category, $username, $release_date, $link, $vars, $vote, $extra);
	}
}

function print_theme_row($themeID, $view)
{
	global $theme_config_array, $site_url;
	$theme_select_result = mysql_query("SELECT theme_name, category, user.username, release_date,small_thumbnail_filename,thumbnail_filename,download_filename FROM theme,user WHERE themeID='$themeID' AND user.userID = theme.userID");
	$var_select_result = mysql_query("SELECT * FROM theme WHERE parent = '$themeID'");
	if (mysql_num_rows($var_select_result) > 0)
		$vars = "<img src=\"/images/site/theme-24.png\" alt=\"Variations available\" height=\"16\" width=\"16\" />";
	else
		$vars = "";

	$vote_select_result = mysql_query("SELECT SUM(rating) AS vote_sum, COUNT(rating) AS vote_count FROM `vote` WHERE artID='$themeID' AND type='theme'");
	list($vote_sum, $vote_count) = mysql_fetch_row($vote_select_result);

	extract(mysql_fetch_array($theme_select_result));

	$release_date = fix_sql_date($release_date);

	if ($view != "compact")
	{
		$query = "SELECT COUNT(*) AS count FROM comment WHERE artID = '$themeID' AND type='theme' AND status != 'deleted'";
		$comment_select = mysql_query($query);
		list($count) = mysql_fetch_array($comment_select);
		if ($count > 1) { $extra[0] = $count." comments"; }
		if ($count == 1) { $extra[0] = $count." comment"; }
	}

	if ($themeID < 1000){
		$link = "{$site_url}themes/$category/$themeID/";
		$thumbnail = "{$site_url}images/archive/thumbnails/$category/$small_thumbnail_filename";
	} else {
		$link = "{$site_url}themes/$category/$themeID/";
		$thumbnail = "{$site_url}images/thumbnails/$category/$small_thumbnail_filename";
	}
	if ($view == "compact")
	{
		$vote = "";
	}
	elseif ($vote_count < 5)
	{
		$vote = "";
	}
	else
	{
		$vote = rating_bar(calculate_rating($vote_sum, $vote_count));
		$vote .= "<div class=\"rating_text\">Rating: " . calculate_rating($vote_sum, $vote_count) . "%</div>\n";
	}
	
	if ($view == "icons")
	{
		if (($category == "icon") or ($category == "metacity"))
			$thumbnail_class = "thumbnail_no_border";
		else
			$thumbnail_class = "thumbnail";
			print("<div class=\"icon_view\"><a href=\"$link\"><img class=\"$thumbnail_class\" src=\"$thumbnail\" alt=\"Thumbnail\"/></a>$vote</div> ");
	}
	else
	{
		print_item_row($theme_name, $thumbnail, $category, $username, $release_date, $link, $vars, $vote, $extra);
	}
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
	create_title("404 - File not Found","");
	print("<p>The URL you requested could not be found</p>");
	ago_footer();
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
		$order_query = "ORDER BY (vote_sum/vote_count) $order";
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
	$popularity = ($download_count / $days);
	$popularity = sprintf("%.1f",$popularity);
	return $popularity;
}

function calculate_rating($vote_sum, $vote_count)
{
	return sprintf("%.1f", ($vote_sum / $vote_count) * 100 / 5);
}

function rating_bar($rating)
{
	$percent = sprintf("%.0f", $rating);
	$bar = "<div class=\"rating-border\"><div class=\"rating\" style=\"width:$percent%\">&nbsp;</div></div>\n";

	return $bar;
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
