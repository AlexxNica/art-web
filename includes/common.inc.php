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
	else                         return "ages ago";
}

function add_vote($artID, $type, $vote)
{
	if ($vote != -1)
	{
		$vote_result = mysql_query("UPDATE $type SET vote_sum=vote_sum+$vote, vote_count=vote_count+1 WHERE " . $type . "ID='$artID'");
	}
}

function print_detailed_view($description, $type, $release_date, $add_timestamp, $version, $license, $download_count, $download_start_timestamp, $vote, $vote_sum, $vote_count, $extra_rows, $thumbnail_url)
{
	global $license_config_array;
	list($year, $month, $day) = explode("-", $release_date);

	$release_date = $year . "-" . $month . "-" . $day;

	$relative_date = FormatRelativeDate(time(), $add_timestamp);

	if ($vote_count < 5)
	{
		$rating = 0;
		$rating_text = "5 votes required";
		$rating_bar = "";
	}
	else 
	{
		$rating = calculate_rating($vote_sum, $vote_count);
		$rating_text = "Rating: " . $rating . "%";
		$rating_bar = rating_bar($rating);
	}
			
	if($license == "")
	{
		$license = "not available";
	}

	if($version == "0" || $version == "")
	{
		$version = "not available";
	}

	print("<p>" . htmlspecialchars($description) . "</p>");

	print("<table class=\"info\">");
	print("<tr><th>Release Date</th><td>$release_date (last updated $relative_date)</td></tr>");
	print("<tr><th>Version</th><td>$version</td></tr>");
	print("<tr><th>License</th><td>{$license_config_array[$license]}</td></tr>");
	$downloads_per_day = calculate_downloads_per_day($download_count, $download_start_timestamp);
	print("<tr><th>Popularity</th><td>$downloads_per_day Downloads per Day</td></tr>");

	print("<tr><th>Rating</th><td>");
	print("<div class=\"rating_text\" style=\"float:left\">");
	print($rating_bar);
	print("$rating_text, $vote_count votes</div>");
	if ($vote == -1)
	{
		print("<form class=\"rating_vote\" name=\"vote\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">Vote:\n");
		print("[worst]");
		print("<input type=\"submit\" class=\"link_button\" name=\"vote\" value=\"1\"/>\n");
		print("<input type=\"submit\" class=\"link_button\" name=\"vote\" value=\"2\"/>\n");
		print("<input type=\"submit\" class=\"link_button\" name=\"vote\" value=\"3\"/>\n");
		print("<input type=\"submit\" class=\"link_button\" name=\"vote\" value=\"4\"/>\n");
		print("<input type=\"submit\" class=\"link_button\" name=\"vote\" value=\"5\"/>\n");
		print("[best]");
		print("</form>");
	}
	else
	{
		print("<div class=\"rating_vote\">");
		print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Thanks for your vote</i>");
		print("</div>");
	}

	print("</td></tr>\n");
	print($extra_rows);

	print("</table>\n");

	print("<center><img src=\"$thumbnail_url\" vspace=\"2\" class=\"large_thumbnail\" alt=\"thumbnail\" /></center>");
}

function print_item_row($name, $thumbnail, $category, $author, $date, $link, $vars, $vote)
{
	if ($vars)
		$var_image = "<img src=\"/images/site/stock_color.png\" alt=\"Variations Available\" />";
	else
		$var_image = "";

	print(utf8_encode("<table class=\"theme_row\"><tr valign=\"top\"><td class=\"theme_row_col1\"><img vspace=\"2\" src=\"$thumbnail\" alt=\"$class\" />$vote</td><td><a href=\"$link\" class=\"h2\"><b>$name</b></a><br/><span class=\"subtitle\">$category<br/>$date<br/>$author<br/>$var_image</span></td></tr></table>"));
}

function print_comments($artID, $type)
{
	$comment_select_result = mysql_query("SELECT comment.commentID, comment.status, comment.userID, user.username, comment.comment, comment.timestamp FROM comment, user WHERE user.userID=comment.userID AND type='$type' and artID='$artID' and comment.status!='deleted' ORDER BY comment.timestamp");


	$comment_count = mysql_num_rows($comment_select_result);

	if ($comment_count == 1)
	{
		//Only one Comment
		$msg = "comment";
	}
	else 
	{
		//More than one comment
		$msg = "comments";
	}

	create_title("Comments", "This $type has $comment_count $msg");

	if($comment_count > 0)
	{
		print("<br/>");
		$count = 0;

		
		
		while(list($commentID, $status, $userID, $username, $user_comment, $comment_time)=mysql_fetch_row($comment_select_result))
		{
			$count++;
			print("<table class=\"comment\">\n");
			print("<tr><td class=\"comment_head\">");
			print("<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"left\">\n");
			print("<i>$count: <a href=\"/users/$userID\">$username</a> posted on " . date("Y-m-d - H:i", $comment_time) . "</i>\n");
			print("</td><td align=\"right\">\n");
			
			if ($status == "reported")
			{
				print("Reported");
			}
			else if ($status == "approved")
			{
				print("Approved");
			}
			else
			{
				print("<form name=\"report\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
				print("<input type=\"hidden\" name=\"commentID\" value=\"" . $commentID . "\">\n");
				print("<input type=\"submit\" name=\"report\" value=\"Report\" class=\"link_button\">");
				print("</form>\n");
			}
			
			print("</td></tr></table>");
			print("<tr><td class=\"comment\">" . parse_comment($user_comment) . "</td></tr>");
			print("</table><br/>\n");
		}

	}
}

function report_comment($report, $commentID) 
{
	if ($report && $commentID > -1) 
	{
		mysql_query("UPDATE comment SET status='reported' where commentID='$commentID' AND status!='deleted'");
		//status!='deleted' because otherwise a deleted comment could be set reported by a user
	}
}

function print_comment_form($comment)
{

	print("<div class=\"h2\">Add a new comment</div>");
	if(!array_key_exists("username", $_SESSION))
	{
		print("<p class=\"info\">Only <a href=\"/account.php\">logged in</a> users may post comments.</p>\n");
	}
	else
	{
		$show_comment = "";
		
		if (strlen($comment) < 10 && strlen($comment) != 0) 
		{
			$comment_msg = "You comment is too short!<br/>\n";
			$show_comment = $comment;
		}
		
		print("<a name=\"comment\"/>\n");
		print("<br/><form name=\"comment\" action=\"" . $_SERVER["PHP_SELF"] . "#comment\" method=\"POST\">\n");
		
		print("<textarea cols=\"60\" rows=\"10\" name=\"comment\">$show_comment</textarea><br/><br/>\n");
		print("<input type=\"submit\" name=\"send\" value=\"Send\" />\n");
		print("</form>\n");
	}
}

function add_comment($artID, $type, $comment)
{
	if (array_key_exists("username", $_SESSION) and ($comment != ""))
	{
		if(strlen($comment) < 10)
		{
			return ("<p class=\"warning\">Comments must be more than 10 letters long!</p>");
		}
		$comment = mysql_real_escape_string(htmlspecialchars($comment, ENT_QUOTES)); // make sure it is safe for html and mysql
		$comment_result = mysql_query("INSERT INTO comment(`artID`, `userID`, `type`, `timestamp`, `comment`) VALUES('$artID', '" . $_SESSION['userID'] . "', '$type', '" . time() . "', '" . mysql_real_escape_string($comment) . "')");
		if ($comment_result === False)
		{
			return ("<p class=\"error\">There was an error adding your comment.</p>");
		}
	}
}

function print_background_row($backgroundID, $view)
{
	global $background_config_array, $site_url;
	$background_select_result = mysql_query("SELECT background_name,category,userID,release_date,thumbnail_filename,download_start_timestamp,download_count,vote_sum,vote_count FROM background WHERE backgroundID='$backgroundID'");
	$var_select_result = mysql_query("SELECT * FROM background WHERE parent = '$backgroundID'");
	if (mysql_num_rows($var_select_result) > 0) $vars = true; else $vars = false;

	list($background_name,$category,$userID,$release_date,$thumbnail_filename,$download_start_timestamp,$download_count,$vote_sum,$vote_count) = mysql_fetch_row($background_select_result);

	$user_select_result = mysql_query("SELECT realname FROM user WHERE userID = $userID");
	list($author) = mysql_fetch_row($user_select_result);


	$release_date = fix_sql_date($release_date);
	$link = "{$site_url}backgrounds/$category/$backgroundID/";
	$category_name = $background_config_array["$category"]["name"];
	$popularity = calculate_downloads_per_day($download_count, $download_start_timestamp);
	$thumbnail = "{$site_url}images/thumbnails/backgrounds/$thumbnail_filename";
	
	if ($vote_count < 5) 
	{
		$vote = "<div class=\"rating_text\">5 votes required</div>\n";
	}
	else
	{
		$vote = rating_bar(calculate_rating($vote_sum, $vote_count));
		$vote .= "<div class=\"rating_text\">Rating: " . calculate_rating($vote_sum, $vote_count) . "%</div>\n";
	}
	
	if ($view == "icons")
	{
		print("<div class=\"icon_view\"><a href=\"$link\"><img vspace=\"2\" src=\"$thumbnail\" alt=\"Thumbnail\"/></a>$vote</div> ");
	}
	else
	{
		print_item_row($background_name, $thumbnail, "Backgrounds - $category_name", $author, $release_date, $link, $vars, $vote);
	}
}

function print_theme_row($themeID, $view)
{
	global $theme_config_array, $site_url;
	$theme_select_result = mysql_query("SELECT theme_name, category, userID, release_date,small_thumbnail_filename,thumbnail_filename,download_filename, vote_sum, vote_count FROM theme WHERE themeID='$themeID'");
	$var_select_result = mysql_query("SELECT * FROM theme WHERE parent = '$themeID'");
	if (mysql_num_rows($var_select_result) > 0)
		$vars = "<img src=\"/images/site/theme-24.png\" alt=\"Variations available\" height=\"16\" width=\"16\" />";
	else
		$vars = "";
	list($theme_name,$category,$userID,$release_date,$thumbnail_filename,$screenshot,$download,$vote_sum,$vote_count) = mysql_fetch_row($theme_select_result);
	
	$user_select_result = mysql_query("SELECT realname FROM user WHERE userID = $userID");
	list($author) = mysql_fetch_row($user_select_result);


	$release_date = fix_sql_date($release_date);
	$link = "{$site_url}themes/$category/$themeID/";
	$category_name = $theme_config_array["$category"]["name"];

	$thumbnail = "{$site_url}images/thumbnails/$category/$thumbnail_filename";

	if ($vote_count < 5) 
	{
		$vote = "<div class=\"rating_text\">5 votes required</div>\n";
	}
	else
	{
		$vote = rating_bar(calculate_rating($vote_sum, $vote_count));
		$vote .= "<div class=\"rating_text\">Rating: " . calculate_rating($vote_sum, $vote_count) . "%</div>\n";
	}
	
	if ($view == "icons")
	{
		print("<div class=\"icon_view\"><a href=\"$link\"><img vspace=\"2\" src=\"$thumbnail\" alt=\"Thumbnail\"/></a>$vote</div> ");
	}
	else
	{
		print_item_row($theme_name, $thumbnail, $category_name, $author, $release_date, $link, $vars, $vote);
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
	print("<select name=\"$name\">\n");
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

function print_thumbnails_per_page_form($thumbnails_per_page, $sort_by, $results_text, $view)
{
	global $thumbnails_per_page_array, $sort_by_array, $view_array;
	
	if($thumbnails_per_page == "")
	{
		$thumbnails_per_page = 12;
	}
	if($sort_by == "")
	{
		$sort_by = "name";
	}

	print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\"><p>");

	print("Sort By: ");
	print_select_box("sort_by", $sort_by_array, $sort_by);

	print(" $results_text: ");
	print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $thumbnails_per_page);

	print(" View: ");
	print_select_box("view", $view_array, $view);


	print(" <input type=\"submit\" value=\"Change\" />");
	print("</p></form>\n");
}

function display_search_box($search_text, $search_type, $thumbnails_per_page, $sort_by)
{
	global $search_type_array, $thumbnails_per_page_array, $sort_by_array;
		
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

function background_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_backgrounds, $view)
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
	print("<b>Showing " . ($start+1) . " through " . $end . " of $num_backgrounds results.</b><br />\n");

	if($sort_by == "popularity")
	{
		$order_query = "ORDER by perday DESC";
	}
	elseif($sort_by == "date")
	{
		$order_query = "ORDER BY add_timestamp DESC";
	}
	elseif($sort_by == "rating")
	{
		$order_query = "ORDER BY (vote_sum/vote_count) DESC";
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
	$background_select_result = mysql_query("SELECT backgroundID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM background WHERE $category_query $search_type LIKE '%$search_text%' AND parent='0' $order_query LIMIT $start, $thumbnails_per_page");
	while(list($backgroundID, $perday)=mysql_fetch_row($background_select_result))
	{
		print_background_row($backgroundID, $view);
	}
	return array($page, $num_pages);
}

function theme_search_result($search_text, $search_type, $category, $thumbnails_per_page, $sort_by, $page, $num_themes, $view)
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
	print("<b>Showing " . ($start+1) . " through " . $end . " of $num_themes results.</b><br />\n");

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
	$theme_select_result = mysql_query("SELECT themeID, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS perday FROM theme WHERE $category_query $search_type LIKE '%$search_text%' $order_query LIMIT $start, $thumbnails_per_page");
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

function parse_comment($comment)
{
	$comment = htmlspecialchars($comment);
	$comment = str_replace("\n", "<br/>", $comment);
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
			print("<select name=$var_name>");
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
