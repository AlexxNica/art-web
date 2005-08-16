<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");
require("art_listings.inc.php");

ago_header("Search");
create_title("Search", "Search for themes and backgrounds");

$list = new search_result;
$list->get_view_options();

$list->print_search_form();
		
if($list->search_text)
{
	/* background name search */
	$list->select();
	
	if ($list->search_type != 'author') {
		$list->print_page_numbers();
		$list->print_shown_slice();
		$list->print_listing();
		$list->print_page_numbers();
	} else {
		$list->print_listing();
	}
}
else
{
	print("<p class=\"info\">Please enter some search terms in the box above.</p>");
}


ago_footer();

?>
