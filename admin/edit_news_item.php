<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a News Item");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if ($action == "Edit")
{

	$news_select_result = mysql_query("SELECT author,author_email,title,body FROM news WHERE newsID = $newsID");
	list($author, $author_email, $title, $body) = mysql_fetch_row($news_select_result);

	print("<form action=\"$PHP_SELF\" method=\"post\"><input type=\"hidden\" name=\"newsID\" value=\"$newsID\">");
	print("<table border=\"0\">\n");
//	print("<tr><td>Date:</td><td><input type=\"text\" name=\"month\" size=\"2\" maxlength=\"2\" value=\"$todays_month\">/<input type=\"text\" name=\"day\" size=\"2\" maxlength=\"2\" value=\"$todays_day\">/<input type=\"text\" name=\"year\" size=\"4\" maxlength=\"4\" value=\"$todays_year\"></td></tr>\n");
	print("<tr><td>Author:</td><td><input type=\"text\" name=\"author\" value=\"$author\" size=\"30\"></td></tr>\n");
	print("<tr><td>Author's Email:</td><td><input type=\"text\" name=\"author_email\" value=\"$author_email\" size=\"30\"></td></tr>\n");
	print("<tr><td>Title:</td><td><input type=\"text\" name=\"title\" value=\"$title\" size=\"30\"></td></tr>\n");
	print("<tr><td>News Body:</td><td><textarea name=\"body\" cols=\"60\" rows=\"15\" wrap>$body</textarea></td></tr>\n");
	print("</table><p>");
	print("<input type=\"submit\" name=\"action\" value=\"Write\">\n");
	print("</form>");


}
else
{
	if ($action == "Write")
	{
		$news_update_result = mysql_query("UPDATE news SET author='$author', author_email='$author_email', title='$title', body='$body' WHERE newsID=$newsID LIMIT 1");
		if (mysql_affected_rows() == 1)
		{
			print("Successfully updated news item<br>");
			print("<a href=\"$PHP_SELF\">Click here</a> to edit another.");
		}
		else
			print("Database Error. Contact administrator.");
	}
	else
	{
		$news_select_result = mysql_query("SELECT newsID,title,date FROM news ORDER BY date DESC");

		if (mysql_num_rows($news_select_result)==0)
		{
			print("There are no News entries available for editing.");
		}
		else
		{
			print("<form action=\"$PHP_SELF\" method=\"POST\">");
			print("<select name=\"newsID\" size=\"10\">");
			while(list($newsID, $title, $date) = mysql_fetch_row($news_select_result))
				print("<option value=\"$newsID\">$title</option>");
			print("</select><input type=\"submit\" name=\"action\" value=\"Edit\"></form>");
		}
	}
}

/*
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
   print("<form action=\"$PHP_SELF\" method=\"post\">\n");
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
*/
admin_footer();

?>
