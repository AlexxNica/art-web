<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a News Item");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if($HTTP_POST_VARS)
{
	if($month && $day && $year && $title && $news_body)
   {
   	if (!get_magic_quotes_gpc())
      {
      	$title = addslashes($title);
         $news_body = addslashes($news_body);
      }
      $date = $year . "-" . $month . "-" . $day;
      $news_insert_result = mysql_query("INSERT INTO news(newsID,status,date,author,author_email,title,body) VALUES('','active','$date','$author','$author_email','$title','$news_body')");
   	$newsID = mysql_insert_id();
      print("Successfully added to news (newsID $newsID).<p>");
   }
   else
   {
   	print("Please fill in _all_ of the form fields and resumbit the form.");
   }
}
else
{
	// get today's month, day and year
   $todays_date = date("m-d-Y");
   list($todays_month,$todays_day,$todays_year) = explode("-",$todays_date);
   print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
   print("<table border=\"0\">\n");
   print("<tr><td>Date:</td><td><input type=\"text\" name=\"month\" size=\"2\" maxlength=\"2\" value=\"$todays_month\">/<input type=\"text\" name=\"day\" size=\"2\" maxlength=\"2\" value=\"$todays_day\">/<input type=\"text\" name=\"year\" size=\"4\" maxlength=\"4\" value=\"$todays_year\"></td></tr>\n");
	print("<tr><td>Author:</td><td><input type=\"text\" name=\"author\" size=\"30\"></td></tr>\n");
   print("<tr><td>Author's Email:</td><td><input type=\"text\" name=\"author_email\" size=\"30\"></td></tr>\n");
   print("<tr><td>Title:</td><td><input type=\"text\" name=\"title\" size=\"30\"></td></tr>\n");
   print("<tr><td>News Body:</td><td><textarea name=\"news_body\" cols=\"60\" rows=\"15\" wrap></textarea></td></tr>\n");
   print("</table>\n<p>");
   print("<input type=\"submit\" value=\"Add News Item\">\n");
   print("</form>\n");
}

admin_footer();

?>
