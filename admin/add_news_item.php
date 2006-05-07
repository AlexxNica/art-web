<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a News Item");
admin_auth(2);

if(POST ('add'))
{
	$month = validate_input_regexp_default ($_POST["month"], "^[0-9]+$", "");
	$day = validate_input_regexp_default ($_POST["day"], "^[0-9]+$", "");
	$year  = validate_input_regexp_default ($_POST["year"], "^[0-9]+$", "");

	$author = escape_string ($_POST['author']);
	$author_email = escape_string ($_POST['author_email']);

	$title = escape_string ($_POST['title']);
	$news_body = escape_string ($_POST['news_body']);

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



if (POST ('preview'))
{
	$month = $_POST['month'];
	$day = $_POST['day'];
	$year = $_POST['year'];
	$author = strip_string ($_POST['author']);
	$author_email = strip_string ($_POST['author_email']);
	$title = strip_string ($_POST['title']);
	$month = strip_string ($_POST['month']);
	$news_body = strip_string ($_POST['news_body']);
}
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table border="0">
<tr>
	<td><label for="month"><strong>Date</strong></label>:</td>
	<td><input type="text" id="month" name="month" size="2" maxlength="2" value="<?php echo $month; ?>" />/<input type="text" name="day" size="2" maxlength="2" value="<?php echo $day; ?>" />/<input type="text" name="year" size="4" maxlength="4" value="<?php echo $year; ?>" /></td>
</tr>
<tr>
	<td><label for="author"><strong>Author</strong></label>:</td>
	<td><input type="text" id="author" name="author" size="30" value="<?php echo htmlentities($author); ?>"/></td>
</tr>
<tr>
	<td><label for="author_email"><strong>Author's Email</strong></label>:</td>
	<td><input type="text" id="author_email" name="author_email" size="30" value="<?php echo $author_email; ?>" /></td>
</tr>
<tr>
	<td><label for="title"><strong>Title</strong></label>:</td>
	<td><input type="text" name="title" id="title" size="30" value="<?php echo htmlentities($title); ?>" /></td>
</tr>
<tr>
	<td><label for="news_body"><strong>News Body</strong></label>:</td>
	<td><textarea name="news_body" id="news_body" cols="60" rows="15" wrap><?php echo htmlentities($news_body); ?></textarea></td>
</tr>
<tr>
	<td><input type="submit" value="Add News Item" name="add" /><input type="submit" value="Preview" name="preview" /></td>
</tr>
</table>
</form>

<?php

if (POST ('preview'))
{
	$news_body = html_parse_text ($news_body);
	$title = html_parse_text ($title);
	print ('<hr/><h1>Preview</h1><div style="border:red solid 1px; padding:1em;margin:1em;">');
	print("\t<h2>$title</h2>\n");
	print("\t<div class=\"subtitle\">Posted by <a href=\"mailto:$author_email\">$author</a> &middot; $day-$month-$year </div>\n");
	print("<div class=\"news_item\">\n");
	print("\t<p>$news_body</p>\n");
	print("</div></div>\n");
}

admin_footer();
?>
