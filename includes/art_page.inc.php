<?php
/* this file is the backend for backgrounds, screenshots, and themes
 * It is just needs to be included with $type set correctly. */

require_once("common.inc.php");

list($foo, $unvalidated_category, $unvalidated_artID) = explode("/", $_SERVER["PATH_INFO"]);

/* get the real type */
/* XXX: this should probably be done in the original page. */
switch ($type)
{
case 'background':
	$config_array = $background_config_array;
	$detail_template = 'backgrounds/detail.html';
	break;
case 'theme':
	$config_array = $theme_config_array;
	$detail_template = 'themes/detail.html';
	break;
case 'screenshot':
	$config_array = $screenshot_config_array;
	$detail_template = 'screenshots/detail.html';
	break;
case 'contest':
	$config_array = $contest_config_array;
	$detail_template = 'contests/detail.html';
	break;
default:
	art_file_not_found();
}

$new_rating = validate_input_regexp_default ($_POST["rating"], "^[1-5]$", -1);
$format = validate_input_array_default($_GET['format'], array('rss', 'atom', 'html'), 'html');
if ($format != 'html') /* XXX: just hope that noone gets the idea to access format=atom on pages */
	$prevent_session = TRUE;

require_once("art_headers.inc.php");

if  (get_magic_quotes_gpc() == 1)
	$comment = stripslashes($_POST["comment"]); // This is validated later
else
	$comment = $_POST["comment"];

$commentID = validate_input_regexp_default ($_POST["commentID"], "^[0-9]+$", -1);
$report = $_POST["report"];

if ($unvalidated_category == '')
{
	$category = '%';
	$header = 'All ' . ucfirst($type) . 's';
}
else
{
	$category = validate_input_regexp_error ($unvalidated_category, "^[0-9a-z_\.\-]+$");
	$header   = $config_array[$category]["name"];
	if ($type == 'background') {
		$header .= ' Backgrounds';
	}
}
	
	/* print out the listings pages */
	if ($unvalidated_artID == "")
	{
		require_once("art_listings.inc.php");
		
		if ($type == 'background') {
			$list = new background_list;
		} elseif ($type == 'theme') {
			$list = new theme_list;
		} elseif ($type == 'screenshot') {
			$list = new screenshot_list;
		} else {
			$list = new contest_list;
		}
		
		$list->format = $format;
		$list->get_view_options();
		$list->select($category);
		
		
		// LISTING OUTPUT /////////////////////////////////////////////
		if ($format == "html")
		{
			art_header($header);
			create_title($header);
			
			$list->print_search_form();
			print('<hr />');
			
			$list->print_page_numbers();
			
			$list->print_listing();
			
			$list->print_page_numbers();
			
			art_footer();
		}
		else
		{
			generate_feed($list, $format);
		}
	}
	
	/* print out the individual page */
	else if (array_key_exists($category, $config_array) && $unvalidated_artID != "")
	{
		require("art.inc.php");
		require("comments.inc.php");

		$artID = validate_input_regexp_error ($unvalidated_artID, "^[0-9]+$");

		if (array_key_exists ('rating', $_GET))
		{
			$rating = $_GET['rating'];
			if (is_numeric ($rating))
				add_vote ($artID, $rating, $_SESSION['userID'], $type, $header);
		}

		/* OUTPUT ############# */
		art_header($header);

		$template = new template ($detail_template);
		$info_result = mysql_query ("SELECT * FROM $type INNER JOIN user ON $type.userID = user.userID WHERE {$type}ID = $artID");

		if (!$info_result)
			art_file_not_found();
		
		$info = mysql_fetch_array ($info_result);
		
		if ($info['status'] != 'active')
			art_file_not_found();
		
		/* encode things correctly */
		$info['name'] = htmlentities ($info['name']);
		$info['username'] = htmlentities ($info['username']);
		$info['description'] = html_parse_text ($info['description']);
		$template->add_vars ($info);
		
		$template->add_var ('release-date', FormatRelativeDate (time(), strtotime ($info ['release_date']), true));

		$rate_count_result = mysql_query ("SELECT COUNT(voteID) FROM vote WHERE artID = $artID AND type='{$type}'");
		list ($rate_count) = mysql_fetch_row ($rate_count_result);
		$template->add_var ('rating-bar', rating_bar (ceil ($info['rating']), $rate_count));

		if ($_SESSION['userID'] == $info['userID'])
			$template->add_var ('edit-link', "<br/><div><a href=\"/edit_$type.php?{$type}ID={$info[$type.'ID']}\">Edit theme details</a></div>");
		else
			$template->add_var ('edit-link', '');

		if ($_SESSION['userID'] == $info['userID'])
			$user_rating_bar = 'Sorry, you may not vote for your own work!';
		else
		{
			$user_rate_result = mysql_query ("SELECT rating FROM vote WHERE userID = '{$_SESSION['userID']}' AND artID = '$artID' AND type='$type'");
			list ($user_rating) = mysql_fetch_row ($user_rate_result);
			$user_rating_bar = user_rating_bar ($user_rating);
		}
		$template->add_var ('user-rating', $user_rating_bar);

		$download_filename = "${type}s/$category/". $info['download_filename'];
		if (($type == 'background' || $type == 'theme') && $artID < 1000)
			$download_filename = 'archive/' . $download_filename;
		$template->add_var ('file-size', get_filesize_string ($sys_ftp_dir . $download_filename));

		if (in_array ($category, array('gtk2', 'icon', 'metacity')))
			$install_instructions = 'Drag and drop this theme into the theme manager to install';
		else
			$install_instructions = '';

		if (($type == 'background' || $type == 'theme') && $artID < 1000)
			$template->add_var ('archive', 'archive/');
		else
			$template->add_var ('archive', '');

		if (in_array ($category, array ('metacity', 'icon')))
			$template->add_var ('thumbnail-class', 'thumbnail_no_border');
		else
			$template->add_var ('thumbnail-class', 'thumbnail');

		$template->add_var ('category-name', $config_array[$category]['name']);
		$template->add_var ('install-instructions', $install_instructions);

		$template->add_var ('license-link', $license_config_link_array [$info['license']]);
		$template->add_var ('comments', get_comments ($artID, $type));
		$template->add_var ('comment-form', get_comment_form ($comment));
		$template->add_var ('comment-message', $comment_message);

		if ($type == 'background')
		{
			$resolution_result = mysql_query ("SELECT * FROM background_resolution WHERE backgroundID = $artID");
			while ($res = mysql_fetch_array ($resolution_result))
				$download_list .= '<a href="/download/backgrounds/'.$info['category'].'/'.$res['background_resolutionID'].'/'.$res['filename'].'" class="'.$res['type'].'" >'.$res['resolution'].'</a>';
			$template->add_var ('download-list', $download_list);
		}
		
		if ($type == 'contest')
		{
			if ($category == '2.14-artwork')
			{
				$template->add_var ('file-size', 'screenshot');
				$template->add_var ('download-url',"/images/thumbnails/contests/$category/{$info['thumbnail_filename']}");
			}
			else
				$template->add_var ('download-url',"/download/$download_filename");
			$template->add_var ('release-date', FormatRelativeDate (time(), $info ['add_timestamp'], true));
		}


		$variations = new variations_list ($artID, $type);
		$template->add_var ('variations', $variations->return_listing ());

		$template->write ();
		art_footer ();

	}
	else
	{
		art_file_not_found();
	}

?>
