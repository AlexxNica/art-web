<?php
require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

print("<html>\n<head><title>Edit a FAQ Entry</title></head>\n<body>\n");
print("<div align=\"center\">");
print("<font size=\"+2\">Edit a FAQ Entry</font>\n<p>\n");
print("</div>\n");

// write the updated FAQ do the database
if($action == "write")
{
	if($faqID && $question && $answer)
   {
   	$faq_update_result = mysql_query("UPDATE faq SET question='$question', answer='$answer' WHERE faqID='$faqID'");
      if(mysql_affected_rows() == 1)
      {
      	print("Successfully updated FAQ in database.");
         print("<p>\n<a href=\"$PHP_SELF\">Click Here</a> to edit another.");
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
      print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   	print("<p>Question:<br>\n");
   	print("<textarea name=\"question\" cols=\"60\" rows=\"6\">$question</textarea>\n");
   	print("<p>Answer:<br>\n");
   	print("<textarea name=\"answer\" cols=\"60\" rows=\"16\">$answer</textarea>\n");
   	print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
      print("<input type=\"hidden\" name=\"faqID\" value=\"$faqID\">\n");
      print("<p><input type=\"submit\" value=\"Update FAQ\">\n");
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
	   	print("<li><span class=\"yellow-text\">$question [<a href=\"$PHP_SELF?action=edit&faqID=$faqID\">Edit</a>]</span>\n<p>$answer\n\n");
	   }
	   print("</ol>\n");
	}
}

print("</body>\n</html>\n");
?>