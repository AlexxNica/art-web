<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

$view_old_news = validate_input_regexp_default($_GET["view_old_news"], "^1$", "0");

ago_header("Artwork &amp; Themes");

create_title("Latest News", "Latest news from art.gnome.org");
if($view_old_news == 1)
{
	$news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 1,20");
}
else
{
	$news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 1");
}
while($news_select_row=mysql_fetch_array($news_select_result))
{
	$date = fix_sql_date($news_select_row["date"]);
	$author = $news_select_row["author"];
	$author_email = spam_proof_email($news_select_row["author_email"]);
	$title =  $news_select_row["title"];
	$body =  html_parse_text($news_select_row["body"]);
	print("<div class=\"news_item\">\n");
	print("\t<div class=\"h2\">$title</div>\n");
	print("\t<div class=\"subtitle\">Posted by <a href=\"mailto:$author_email\">$author</a> &middot; $date </div>\n");
	print("\t<p>$body</p>\n");
	print("</div>\n");
}

if($view_old_news == 1)
{
	print("<div style=\"text-align: center\"><a href=\"" . $_SERVER["PHP_SELF"] . "\">View Recent News</a></div>\n");
}
else
{
	print("<div style=\"text-align: center\"><p><a href=\"" . $_SERVER["PHP_SELF"] . "?view_old_news=1\">View Older News</a></p></div>\n");

	print("<div style=\"width:48%; float:left; clear: left;\">");
	create_title("Recent Updates", "The latest five additions to art.gnome.org");

	$select = mysql_query("SELECT backgroundID, 0 AS themeID, add_timestamp FROM background UNION SELECT 0 AS backgroundID, themeID, add_timestamp FROM theme ORDER BY add_timestamp DESC LIMIT 5");
	while (list($backgroundID, $themeID) = mysql_fetch_row($select))
	{
		if ($backgroundID) print_background_row($backgroundID, 'list');
		if ($themeID) print_theme_row($themeID, 'list');
	}

	print("<div style=\"text-align:center\"><p><a href=\"/updates.php\">More updates</a> - <a href=\"backend.php\">RSS Updates Feed</a></p></div>");
	print("</div>");

	print("<div style=\"width:48%; float:right; clear: right;\">");
	create_title("Top Rated", "The five top rated items");

	$select = mysql_query("SELECT backgroundID, 0 AS themeID, rating FROM background UNION SELECT 0 AS backgroundID, themeID, rating FROM theme ORDER BY rating DESC LIMIT 5");
	while (list($backgroundID, $themeID) = mysql_fetch_row($select))
	{
		if ($backgroundID) print_background_row($backgroundID, 'list');
		if ($themeID) print_theme_row($themeID, 'list');
	}

	print("</div>");

}

ago_footer();

?>
