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

	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\"><input type=\"hidden\" name=\"newsID\" value=\"$newsID\">");
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
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">");
			print("<select name=\"newsID\" size=\"20\">");
			while(list($newsID, $title, $date) = mysql_fetch_row($news_select_result))
				print("<option value=\"$newsID\">$title</option>");
			print("</select><input type=\"submit\" name=\"action\" value=\"Edit\"></form>");
		}
	}
}

admin_footer();

?>
