<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

escape_gpc_array($_GET);

$view_old_news = $_GET["view_old_news"];

ago_header("GNOME artwork &amp; themes");
create_middle_box_top("news");

if (!isset($view_old_news))
{
	print("<table border=\"0\">\n");
	print("<tr><td><img src=\"images/site/art-icon.png\" alt=\"news-item\"></td><td><span class=\"bold-text\"><font size=\"+1\">Latest Additions</font></span></td></tr>\n");
	print("<tr><td>&nbsp;</td><td><div class=\"news-body\">");

	$big_array = get_updates_array(4);
	for($count=0;$count<count($big_array);$count++)
	{
		list($add_timestamp,$type,$ID) = explode("|",$big_array[$count]);
		if($type == "background")
		{
			$background_select_result = mysql_query("SELECT category,thumbnail_filename FROM background WHERE backgroundID = $ID");
			list($category,$thumbnail_filename) = mysql_fetch_array($background_select_result);
			print("<a href=\"/backgrounds/$category/$ID/\"><img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" class=\"thumbnail-border\" style=\"margin: 0.2em;\"></a>");
		}
		else
		{
			$theme_select_result = mysql_query("SELECT category,small_thumbnail_filename FROM theme WHERE themeID = $ID");
			list($category,$thumbnail_filename) = mysql_fetch_array($theme_select_result);
			print("<a href=\"/themes/$category/$ID/\"><img src=\"/images/thumbnails/$category/$thumbnail_filename\" class=\"thumbnail-border\" style=\"margin: 0.2em;\"></a>&nbsp;&nbsp;");
		}
	}
	
	print("</div></td></tr>\n");
	print("</table>\n");
	print("<hr style=\"border: none; border-top: dashed gray 1px; margin-bottom: 1em;\">");
}

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
	print("<table border=\"0\">\n");
   print("<tr><td><img src=\"images/site/News-Item.png\" alt=\"news-item\"></td><td><span class=\"bold-text\"><font size=\"+1\">$title</font></span></td></tr>\n");
   print("<tr><td>&nbsp;</td><td><font size=\"-1\">$date - <a href=\"mailto:$author_email\">$author</a></font></td></tr>\n");
   print("<tr><td>&nbsp;</td><td><div class=\"news-body\">$body</div></td></tr>\n");
   print("</table>\n");
   print("<p>&nbsp;<p>\n");
}

print("<p>\n");
if($view_old_news == 1)
{
	print("<div align=\"center\"><a href=\"backend.php\">RSS News Feed</a> | <a href=\"$PHP_SELF\">View Recent News</a></div>\n");
}
else
{
	print("<div align=\"center\"><a href=\"backend.php\">RSS News Feed</a> | <a href=\"$PHP_SELF?view_old_news=1\">View Older News</a></div>\n");
}
print("<p>&nbsp;\n");

create_middle_box_bottom();
ago_footer();

?>
