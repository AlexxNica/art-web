<?php

include "mysql.inc.php";
include "common.inc.php";
include "includes/headers.inc.php";

admin_header("ART.GNOME.ORG Administration","");
$admin_level = admin_auth (1);
{
	create_title("Submissions","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"show_submitted_backgrounds.php\">Submitted Backgrounds</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"show_submitted_themes.php\">Submitted Themes</a><br />");

	create_title("Edit/Delete Artwork","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_art.php?type=background\">Background</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_art.php?type=theme\">Theme</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_art.php?type=screenshot\">Screenshot</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_art.php?type=contest\">Contest Item</a><br />");

	create_title("Comments","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"comments.php\">Moderate Comments</a><br />");

	create_title("News","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_news_item.php\">Add a News Item</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_news_item.php\">Edit News Item</a><br />");
	print("&nbsp;&nbsp;&nbsp;Delete News Item<br />");

	create_title("FAQ","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"add_faq.php\">Add FAQ Entry</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_faq.php\">Edit FAQ Entry</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"delete_faq.php\">Delete FAQ Entry</a><br />");
	print("&nbsp;&nbsp;&nbsp;<a href=\"show_submitted_faq.php\">Pending FAQ Entries</a><br />");
	print("&nbsp;&nbsp;&nbsp;Re-Order FAQ<br />");

	create_title("Users","");
	print("&nbsp;&nbsp;&nbsp;<a href=\"edit_user.php\">Edit a user</a><br />");
}

admin_footer();


?>

