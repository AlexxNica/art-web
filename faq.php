<?php

require("mysql.inc.php");
require("session.inc.php");
require("common.inc.php");
include("header.inc.php");
create_middle_box_top("faq");

$faq_select_result = mysql_query("SELECT question,answer FROM faq ORDER by faqID");
if(mysql_num_rows($faq_select_result)==0)
{
	print("In the future, this site will contain a FAQ.");
}
else
{
	print("<ol>\n");
   while(list($question,$answer)=mysql_fetch_row($faq_select_result))
   {
   	print("<li><span class=\"yellow-text\">$question</span>\n<p>$answer\n\n");
   }
   print("</ol>\n");
}

create_middle_box_bottom();
include("footer.inc.php");
?>
