<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Add a FAQ");

if($HTTP_POST_VARS)
{
	if( $question && $answer )
   {
   	$faq_insert_result = mysql_query("INSERT INTO faq(faqID,question,answer) VALUES('','$question','$answer')");
      if($faq_insert_result)
      {	
      	print("Successfully added FAQ to database.");
         print("<p><a href=\"$PHP_SELF\">Click Here</a> to add another.");
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
	print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   print("<p>Question:<br>\n");
   print("<textarea name=\"question\" cols=\"60\" rows=\"6\"></textarea>\n");
   print("<p>Answer:<br>\n");
   print("<textarea name=\"answer\" cols=\"60\" rows=\"16\"></textarea>\n");
   print("<p><input type=\"submit\" value=\"Add FAQ\">\n");
   print("</form>\n");
}
admin_footer();
?>
