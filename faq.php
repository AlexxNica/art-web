<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("FAQ");
create_middle_box_top("faq");

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
   	print("<li><a href=\"$PHP_SELF#$faqID\">$question</a>\n");
   }
	print("</ol>\n<p>\n<ol>\n");
	
   /* print out a list of questions and answers */
	$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq ORDER by faqID");
	while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
   {
   	print("<li><a name=\"$faqID\"></a><span class=\"yellow-text\">$question</span>\n<p>$answer\n\n");
   }
   print("</ol>\n");
}

create_middle_box_bottom();
ago_footer();

?>
