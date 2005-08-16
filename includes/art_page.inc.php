<?php
/* this file is the backend for backgrounds and themes
 * It is just needs to be included with $type set correctly. */

require("ago_headers.inc.php");
require("common.inc.php");

list($foo, $unvalidated_category, $unvalidated_artID) = explode("/", $_SERVER["PATH_INFO"]);

/* get the real type */
if ($type == 'background') {
	$config_array = $background_config_array;
} elseif ($type == 'theme') {
	$config_array = $theme_config_array;
} else ago_file_not_found();

$new_rating = validate_input_regexp_default ($_POST["rating"], "^[1-5]$", -1);
if  (get_magic_quotes_gpc() == 1)
	$comment = stripslashes($_POST["comment"]); // This is validated later
else
	$comment = $_POST["comment"];

$commentID = validate_input_regexp_default ($_POST["commentID"], "^[0-9]+$", -1);
$report = $_POST["report"];

/* print out the list of background categories */
if ($unvalidated_category == "")
{
	if ($type == 'background') {
		ago_header('Backgrounds');
		create_title('Backgrounds', 'Backgrounds are images for use on your desktop as the desktop background, sometimes known as wallpapers.');
	} else {
		ago_header('Themes');
		create_title('Themes', 'Desktop Themes');
	}
	
	print("<ul>\n");
	
	foreach ($config_array as $key => $val)
	{
		if ($val['active'])
		{
			print('<li><a class="bold-link" href="'.$val['url'].'">');
			print(get_category_name ($type, $key));
			print('</a>');
			print("</li>\n");
		}
	}
	print("</ul>\n");
	
	ago_footer();
}
else
{
	$category = validate_input_regexp_error ($unvalidated_category, "^[0-9a-z_\-]+$");
	$header   = $config_array[$category]["name"];
	if ($type == 'background') {
		$header .= ' Backgrounds';
	}
	
	/* print out the background preview pages */
	if (array_key_exists($category, $config_array) && $unvalidated_artID == "")
	{
		require("art_listings.inc.php");
		
		if ($type == 'background') {
			$list = new background_list;
		} else {
			$list = new theme_list;
		}
		
		$list->get_view_options();
		$list->select($category);
		
		// LISTING OUTPUT /////////////////////////////////////////////
		ago_header($header);
		create_title($header);
		
		$list->print_search_form();
		print('<hr/>');
		
		$list->print_page_numbers();
		
		$list->print_listing();
		
		$list->print_page_numbers();
		
		ago_footer();
	}
	
	/* print out the individual background page */
	else if (array_key_exists($category, $config_array) && $unvalidated_artID != "")
	{
		require("art.inc.php");
		require("comments.inc.php");
		
		$artID = validate_input_regexp_error ($unvalidated_artID, "^[0-9]+$");
		
		$comment_result = add_comment($artID, $type, $comment, $header);
		report_comment($report, $commentID);
		
		add_vote($artID, $new_rating, $_SESSION['userID'], $type, $header);
		
		/* OUTPUT ############# */
		ago_header($header);
		
		print_detailed_view($artID, $type);
		print_comments($artID, $type);
		print($comment_result);
		print_comment_form($comment);
		
		ago_footer();
	}
	else
	{
		ago_file_not_found();
	}
}

?>
