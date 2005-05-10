<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

admin_header("Edit an FAQ Entry");
admin_auth(2);

// write the updated FAQ do the database
if($action == "write")
{
	if($faqID && $question && $answer)
	{
		$faq_update_result = mysql_query("UPDATE faq SET question='$question', answer='$answer' WHERE faqID='$faqID'");
		if(mysql_affected_rows() == 1)
		{
			print("Successfully updated FAQ in database.");
			print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
		}
		else
		{
			print("Database Error, unable to update database.  Contact Alex.");
		}	
	}
	else
	{
		print("Error, you must have a question and an answer.");
	}
}
// display the question and answer in a textarea for editing
elseif($action == "edit")
{
	$faq_select_result = mysql_query("SELECT question,answer FROM faq WHERE faqID='$faqID'");
	if(mysql_num_rows($faq_select_result) == 0)
	{
		print("Error, invalid faqID.");
	}
	else
	{
		list($question,$answer) = mysql_fetch_row($faq_select_result);
		$question = htmlspecialchars($question);
		$answer = htmlspecialchars($answer);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<p><label for=\"question\"><strong>Question:</strong></label><br />\n");
		print("<textarea name=\"question\" id=\"question\" cols=\"60\" rows=\"6\">$question</textarea></p>\n");
		print("<p><label for=\"answer\"><strong>Answer:</strong></label><br />\n");
		print("<textarea name=\"answer\" id=\"answer\" cols=\"60\" rows=\"16\">$answer</textarea>\n");
		print("<input type=\"hidden\" name=\"action\" value=\"write\" />\n");
		print("<input type=\"hidden\" name=\"faqID\" value=\"$faqID\" /></p>\n");
		print("<p><input type=\"submit\" value=\"Update FAQ\" /></p>\n");
		print("</form>\n");
	}
}
else
{
	$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq ORDER by faqID");
	if(mysql_num_rows($faq_select_result)==0)
	{
		print("There are no FAQ entries available for editing.");
	}
	else
	{
		print("<ol>\n");
		while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
		{
			$question = html_parse_text($question);
			$answer = html_parse_text($answer);
			print("<li><span class=\"yellow-text\">$question [<a href=\"" . $_SERVER["PHP_SELF"] . "?action=edit&amp;faqID=$faqID\">Edit</a>]</span>\n<p>$answer</p>\n</li>\n\n");
		}
		print("</ol>\n");
	}
}

admin_footer();
?>
