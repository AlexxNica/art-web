<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

ago_header("FAQ");
if ($_GET['mode'] == 'ask') $mode = 'ask';
create_title("ART.GNOME.ORG FAQ", "Frequently Asked Questions");
if($_POST['question'])
			{
				if(is_logged_in('faq'))
				{
					$query = "INSERT INTO `faq` (`faqID`, `question`, `answer`, `status`, `userID`) VALUES ('', '".$_POST['question']."', '', 'pending', '".$_SESSION['userID']."')";
					if(mysql_query($query));
						$thanks = 1;
				}
			}
if ($mode != "ask") {

	$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq WHERE status!='pending' ORDER by faqID ");
	if(mysql_num_rows($faq_select_result)==0)
	{
		print("In the future, this site will contain a FAQ.");
	}
	else
	{
		if($thanks) print("Thank you for your submission.  It will be reviewed.");
		/* print out a list of questions */
		print("<ol>\n");
		while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
		{
			$question = html_parse_text($question);
			print("<li><a href=\"{$_SERVER["PHP_SELF"]}#q$faqID\">$question</a></li>\n");
		}
		print("</ol>\n");
		print("[<a href=\"faq.php?mode=ask\">Ask a Question</a>]");
		print("<hr /><br />\n<ol>\n");

		/* print out a list of questions and answers */
		$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq WHERE status!='pending'  ORDER by faqID");
		while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
		{
			$question = html_parse_text($question);
			$answer = html_parse_text($answer);
			print("<li><a id=\"q$faqID\"></a><span class=\"bold-text\">$question</span>\n<p>$answer</p></li>\n\n");
		}
		print("</ol>\n");
	}
} else {
		print("<p><strong><label for=\"question\">New question:</label></strong></p>");
		print("<form action=\"faq.php\" method=\"post\">");
		print("<div><textarea rows=\"2\" cols=\"25\" name=\"question\" id=\"question\"></textarea><br />");
		print("<input type=\"submit\" value=\"Ask\"></div>");
		print("</form>");
		unset($mode);

}
ago_footer();

?>
