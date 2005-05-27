<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a News Item");
admin_auth(2);

if($HTTP_POST_VARS)
{
	if(get_magic_quotes_gpc() == 1)
		foreach ($_POST as $k => $v)
			$_POST[$k] = stripslashes($_POST[$k]);

	$month = validate_input_regexp_default ($_POST["month"], "^[0-9]+$", "");
	$day = validate_input_regexp_default ($_POST["day"], "^[0-9]+$", "");
	$year  = validate_input_regexp_default ($_POST["year"], "^[0-9]+$", "");

	$author = mysql_real_escape_string($_POST["author"]);
	$author_email = mysql_real_escape_string($_POST["author_email"]);

	$title = mysql_real_escape_string($_POST["title"]);
	$news_body = mysql_real_escape_string($_POST["news_body"]);

	if($month && $day && $year && $title && $news_body)
	{
		$date = $year . "-" . $month . "-" . $day;
		$news_insert_result = mysql_query("INSERT INTO news(newsID,status,date,author,author_email,title,body) VALUES('','active','$date','$author','$author_email','$title','$news_body')");
		$newsID = mysql_insert_id();
		print("<p class=\"info\">Successfully added to news (newsID $newsID).</p>");
	}
	else
	{
		print("<p class=\"error\">Please fill in all of the form fields and resumbit the form.</p>");
	}
}
else
{
	// get today's month, day and year
	$todays_date = date("m-d-Y");
	list($todays_month,$todays_day,$todays_year) = explode("-",$todays_date);
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><label for=\"month\"><strong>Date</strong></label>:</td><td><input type=\"text\" id=\"month\" name=\"month\" size=\"2\" maxlength=\"2\" value=\"$todays_month\" />/<input type=\"text\" name=\"day\" size=\"2\" maxlength=\"2\" value=\"$todays_day\" />/<input type=\"text\" name=\"year\" size=\"4\" maxlength=\"4\" value=\"$todays_year\" /></td></tr>\n");
	print("<tr><td><label for=\"author\"><strong>Author</strong></label>:</td><td><input type=\"text\" id=\"author\" name=\"author\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"author_email\"><strong>Author's Email</strong></label>:</td><td><input type=\"text\" id=\"author_email\" name=\"author_email\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"title\"><strong>Title</strong></label>:</td><td><input type=\"text\" name=\"title\" id=\"title\" size=\"30\" /></td></tr>\n");
	print("<tr><td><label for=\"news_body\"><strong>News Body</strong></label>:</td><td><textarea name=\"news_body\" id=\"news_body\" cols=\"60\" rows=\"15\" wrap></textarea></td></tr>\n");
	print("<tr><td><input type=\"submit\" value=\"Add News Item\" /></td></tr>\n");
	print("</table>\n");
	print("</form>\n");
}

admin_footer();

?>
