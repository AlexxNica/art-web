<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a News Item");
admin_auth(2);

if($HTTP_POST_VARS && array_key_exists('author', $_POST))
{
	if(get_magic_quotes_gpc() == 1)
		foreach ($_POST as $k => $v)
			$_POST[$k] = stripslashes($_POST[$k]);
                                                
	$month = validate_input_regexp_default ($_POST["month"], "^[0-9]+$", "");
	$day = validate_input_regexp_default ($_POST["day"], "^[0-9]+$", "");
	$year  = validate_input_regexp_default ($_POST["year"], "^[0-9]+$", "");

	$author = $_POST['author'];
	$author_email = $_POST['author_email'];

	$title = $_POST['title'];
	$news_body = $_POST['news_body'];

	if($month && $day && $year && $title && $news_body)
	{
		$date = $year . "-" . $month . "-" . $day;
		$author = mysql_real_escape_string($author);
		$author_email = mysql_real_escape_string($author_email);
		$title = mysql_real_escape_string($title);
		$news_body = mysql_real_escape_string($news_body);
		$author = mysql_real_escape_string($author);
		
		$news_insert_result = mysql_query("INSERT INTO news(newsID,status,date,author,author_email,title,body) VALUES('','active','$date','$author','$author_email','$title','$news_body')");
		$newsID = mysql_insert_id();
		
		print("<p class=\"info\">Successfully added to news (newsID $newsID).</p>");
		admin_footer();
		exit();
	}
	else
	{
		print("<p class=\"error\">Please fill in all of the form fields and resumbit the form.</p>");
	}
}

// get today's month, day and year
$todays_date = date("m-d-Y");
if (!isset($month))
	list($month,$day,$year) = explode("-",$todays_date);

print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
print("<table border=\"0\">\n");
print("<tr><td><label for=\"month\"><strong>Date</strong></label>:</td><td><input type=\"text\" id=\"month\" name=\"month\" size=\"2\" maxlength=\"2\" value=\"$month\" />/<input type=\"text\" name=\"day\" size=\"2\" maxlength=\"2\" value=\"$day\" />/<input type=\"text\" name=\"year\" size=\"4\" maxlength=\"4\" value=\"$year\" /></td></tr>\n");
print("<tr><td><label for=\"author\"><strong>Author</strong></label>:</td><td><input type=\"text\" id=\"author\" name=\"author\" size=\"30\" value=\"".htmlentities($author)."\"/></td></tr>\n");
print("<tr><td><label for=\"author_email\"><strong>Author's Email</strong></label>:</td><td><input type=\"text\" id=\"author_email\" name=\"author_email\" size=\"30\" value=\"$author_email\" /></td></tr>\n");
print("<tr><td><label for=\"title\"><strong>Title</strong></label>:</td><td><input type=\"text\" name=\"title\" id=\"title\" size=\"30\" value=\"".htmlentities($title)."\" /></td></tr>\n");
print("<tr><td><label for=\"news_body\"><strong>News Body</strong></label>:</td><td><textarea name=\"news_body\" id=\"news_body\" cols=\"60\" rows=\"15\" wrap>".htmlentities($news_body)."</textarea></td></tr>\n");
print("<tr><td><input type=\"submit\" value=\"Add News Item\" /></td></tr>\n");
print("</table>\n");
print("</form>\n");
admin_footer();

?>
