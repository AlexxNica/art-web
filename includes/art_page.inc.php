<?php
/* this file is the backend for backgrounds, screenshots, and themes
 * It is just needs to be included with $type set correctly. */

require_once("common.inc.php");
require_once("art_headers.inc.php");

list($foo, $unvalidated_category, $unvalidated_artID) = explode("/", $_SERVER["PATH_INFO"]);

/* get the real type */
/* XXX: this should probably be done in the original page. */
switch ($type)
{
case 'background':
	$config_array = $background_config_array;
	break;
case 'theme':
	$config_array = $theme_config_array;
	break;
case 'screenshot':
	$config_array = $screenshot_config_array;
	break;
case 'contest':
	$config_array = $contest_config_array;
	break;
default:
	art_file_not_found();
}

$new_rating = validate_input_regexp_default ($_POST["rating"], "^[1-5]$", -1);
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
		
		$list->get_view_options();
		$list->select($category);
		
		// LISTING OUTPUT /////////////////////////////////////////////
		art_header($header);
		create_title($header);
		
		$list->print_search_form();
		print('<hr />');
		
		$list->print_page_numbers();
		
		$list->print_listing();
		
		$list->print_page_numbers();
		
		art_footer();
	}
	
	/* print out the individual page */
	else if (array_key_exists($category, $config_array) && $unvalidated_artID != "")
	{
		require("art.inc.php");
		require("comments.inc.php");
		
		$artID = validate_input_regexp_error ($unvalidated_artID, "^[0-9]+$");
		
		$comment_result = add_comment($artID, $type, $comment, $header);
		report_comment($report, $commentID);
		
		add_vote($artID, $new_rating, $_SESSION['userID'], $type, $header);
		
		/* OUTPUT ############# */
		art_header($header);
		
		print_detailed_view($artID, $type);
		print_comments($artID, $type);
		print($comment_result);
		print_comment_form($comment);
		
		art_footer();
	}
	else
	{
		art_file_not_found();
	}

?>
