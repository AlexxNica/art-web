<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

$view_old_news = validate_input_regexp_default($_GET["view_old_news"], "^1$", "0");

ago_header("User Account");

create_title("News", "Latest news from art.gnome.org");
if($view_old_news == 1)
{
	$news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 3,20");
}
else
{
	$news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 3");
}
while($news_select_row=mysql_fetch_array($news_select_result))
{
	$date = fix_sql_date($news_select_row["date"]);
	$author = $news_select_row["author"];
	$author_email = spam_proof_email($news_select_row["author_email"]);
	$title =  $news_select_row["title"];
	$body =  $news_select_row["body"];
	print("<div class=\"news_item\"><div class=\"h2\">$title</div>");
	print("<div class=\"subtitle\">Posted by <a href=\"mailto:$author_email\">$author</a> &middot; $date </div>");
	print("<p>$body</p>\n");
	print("</div>\n");
}

if($view_old_news == 1)
{
	print("<div align=\"center\"><a href=\"backend.php\">RSS News Feed</a> | <a href=\"" . $_SERVER["PHP_SELF"] . "\">View Recent News</a></div>\n");
}
else
{
	print("<div align=\"center\"><a href=\"backend.php\">RSS News Feed</a> | <a href=\"" . $_SERVER["PHP_SELF"] . "?view_old_news=1\">View Older News</a></div>\n");
}

ago_footer();

?>
