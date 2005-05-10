<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

$question = mysql_real_escape_string($_POST["question"]);
$answer = mysql_real_escape_string($_POST["answer"]);

admin_header("Add a FAQ");
admin_auth(2);

if($HTTP_POST_VARS)
{
	if( $question && $answer )
	{
		$faq_insert_result = mysql_query("INSERT INTO faq(faqID,question,answer) VALUES('','$question','$answer')");
		if($faq_insert_result)
		{
			print("Successfully added FAQ to database.");
			print("<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to add another.");
		}
		else
		{
			print("Error updating db, make sure magic quotes are turned on.");
		}
	}
	else
	{
		print("Error, all of the form fields are not filled in.");
	}
}
else
{
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<p><label for=\"question\"><strong>Question:</strong></label><br />\n");
	print("<textarea name=\"question\" cols=\"60\" rows=\"6\" id=\"question\"></textarea></p>\n");
	print("<p><label for=\"answer\"><strong>Answer:</strong></label><br />\n");
	print("<textarea name=\"answer\" id=\"answer\" cols=\"60\" rows=\"16\"></textarea></p>\n");
	print("<p><input type=\"submit\" value=\"Add FAQ\" /></p>\n");
	print("</form>\n");
}
admin_footer();
?>
