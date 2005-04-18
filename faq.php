<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("FAQ");
create_title("ART.GNOME.ORG FAQ", "Frequently Asked Questions");

$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq ORDER by faqID");
if(mysql_num_rows($faq_select_result)==0)
{
	print("In the future, this site will contain a FAQ.");
}
else
{
	/* print out a list of questions */
	print("<ol>\n");
	while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
	{
		$question = html_parse_text($question);
		print("<li><a href=\"{$_SERVER["PHP_SELF"]}#q$faqID\">$question</a></li>\n");
	}
	print("</ol>\n<hr /><br />\n<ol>\n");

	/* print out a list of questions and answers */
	$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq ORDER by faqID");
	while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
	{
		$question = html_parse_text($question);
		$answer = html_parse_text($answer);
		print("<li><a id=\"q$faqID\"></a><span class=\"bold-text\">$question</span>\n<p>$answer</p></li>\n\n");
	}
	print("</ol>\n");
}

ago_footer();

?>
