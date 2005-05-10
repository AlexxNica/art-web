<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Edit a News Item");
admin_auth(2);

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if ($action == "Edit")
{

	$news_select_result = mysql_query("SELECT author,author_email,title,body FROM news WHERE newsID = $newsID");
	list($author, $author_email, $title, $body) = mysql_fetch_row($news_select_result);

	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">");
	print("<table border=\"0\">\n");
//	print("<tr><td><label for=\"month\"><strong>Date</strong></label>:</td><td><input type=\"text\" id=\"month\" name=\"month\" size=\"2\" maxlength=\"2\" value=\"$todays_month\" />/<input type=\"text\" name=\"day\" size=\"2\" maxlength=\"2\" value=\"$todays_day\" />/<input type=\"text\" name=\"year\" size=\"4\" maxlength=\"4\" value=\"$todays_year\" /></td></tr>\n");
	print("<tr><td><label for=\"author\"><strong>Author</strong></label>:</td><td><input type=\"text\" id=\"author\" name=\"author\" value=\"$author\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"author_email\"><strong>Author's Email</strong></label>:</td><td><input type=\"text\" id=\"author_email\" name=\"author_email\" value=\"$author_email\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"title\"><strong>Title</strong></label>:</td><td><input type=\"text\" name=\"title\" id=\"title\" value=\"$title\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"news_body\"><strong>News Body</strong></label>:</td><td><textarea name=\"body\" id=\"news_body\" cols=\"60\" rows=\"15\" wrap>$body</textarea></td></tr>\n");
	print("<tr><td><input type=\"hidden\" name=\"newsID\" value=\"$newsID\" /><input type=\"submit\" name=\"action\" value=\"Write\" /></td></tr>\n");
	print("</table>\n");
	print("</form>\n");


}
else
{
	if ($action == "Write")
	{
		$news_update_result = mysql_query("UPDATE news SET author='$author', author_email='$author_email', title='$title', body='$body' WHERE newsID=$newsID LIMIT 1");
		if (mysql_affected_rows() == 1)
		{
			print("Successfully updated news item<br>");
			print("<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click here</a> to edit another.");
		}
		else
			print("Database Error. Contact administrator.");
	}
	else
	{
		$news_select_result = mysql_query("SELECT newsID,title,date FROM news $user_sql ORDER BY date DESC");

		if (mysql_num_rows($news_select_result)==0)
		{
			print("There are no News entries available for editing.");
		}
		else
		{
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><div>");
			print("<label for=\"news\"><strong>News Item</strong></label><br />");
			print("<select name=\"newsID\" size=\"20\" id=\"news\">");
			while(list($newsID, $title, $date) = mysql_fetch_row($news_select_result))
				print("<option value=\"$newsID\">$newsID - ".html_parse_text($title)."</option>");
			print("</select><br /><input type=\"submit\" name=\"action\" value=\"Edit\" /></div></form>");
		}
	}
}

admin_footer();

?>
