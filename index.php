<?php

/* $Id$ */

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
require("change_site_prefs.php");
require("ago_headers.inc.php");

ago_header("GNOME artwork &amp; themes");
create_middle_box_top("news");

$news_count_select_result = mysql_query("SELECT * FROM news WHERE status='active'");
$news_count = mysql_num_rows($news_count_select_result);

$news_select_result = mysql_query("SELECT * FROM news WHERE status='active' ORDER BY newsID DESC LIMIT 3");
while($news_select_row=mysql_fetch_array($news_select_result))
{
	$date = $news_select_row["date"];
   $author = $news_select_row["author"];
   $author_email = $news_select_row["author_email"];
   $title =  $news_select_row["title"];
   $body =  $news_select_row["body"];
   list($year,$month,$day) = explode("-",$date);
   $date = $month . "/" . $day . "/" . $year;
	print("<table border=\"0\">\n");
   print("<tr><td><img src=\"images/site/News-Item.png\" alt=\"news-item\"></td><td><span class=\"yellow-text\"><font size=\"+1\">$title</font></span></td></tr>\n");
   print("<tr><td>&nbsp;</td><td><font size=\"-1\"><span class=\"dark-violet-text\">$date - </span><a href=\"mailto:$author_email\" class=\"news-link\">$author</a></font></td></tr>\n");
   print("<tr><td>&nbsp;</td><td><div class=\"news-body\">$body</div></td></tr>\n");
   print("</table>\n");
   print("<p>&nbsp;<p>\n");
}

print("<p>\n");
if($news_count > 3)
{
	print("<div align=\"center\"><a href=\"old_news.php\">View Older News</a></div>\n");
}
print("<p>&nbsp;\n");

create_middle_box_bottom();
ago_footer();

?>
