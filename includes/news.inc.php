<?php

include_once "mysql.inc.php";
include_once "common.inc.php";

class artweb_news
{

	var $news_select_result;

	function select_news($number)
	{
		$this->news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 1,$number");
	}

	function print_news()
	{
		while($news_select_row=mysql_fetch_array($this->news_select_result))
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
	}

}

?>
