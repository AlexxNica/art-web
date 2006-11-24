<?php

require("mysql.inc.php");
require("common.inc.php");
require("art_headers.inc.php");

art_header("FAQ");

if ($_GET['mode'] == 'ask') $mode = 'ask';
create_title("ART.GNOME.ORG FAQ", "Frequently Asked Questions");
if($_POST['question'])
			{
				if(is_logged_in())
				{
					$query = "INSERT INTO `faq` (`faqID`, `question`, `answer`, `status`, `userID`) VALUES ('', '".$_POST['question']."', '', 'pending', '".$_SESSION['userID']."')";
					if(mysql_query($query));
						$thanks = 1;
				}
			}
if ($mode != "ask")
{
		$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq WHERE status!='pending' ORDER by faqID ");
		if(mysql_num_rows($faq_select_result)==0)
					print("In the future, this site will contain a FAQ.");
		else
		{
				if($thanks) print("Thank you for your submission.  It will be reviewed.");
				/* print out a list of questions */
				print("\t<ol>\n");
				while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
				{
						$question = html_parse_text($question);
						print("\t\t<li><a href=\"{$_SERVER["PHP_SELF"]}#q$faqID\">$question</a></li>\n");
				}
				print("\t</ol>\n");
				print("\t[<a href=\"faq.php?mode=ask\">Ask a Question</a>]\n");
				print("\t<hr /><br />\n\t<ol>\n");

				/* print out a list of questions and answers */
				$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq WHERE status!='pending'  ORDER by faqID");
				while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
				{
						$question = html_parse_text($question);
						$answer = html_parse_text($answer);
						print("\t\t<li><a id=\"q$faqID\"></a><span class=\"bold-text\">$question</span>\n<p>$answer</p></li>\n\n");
				}
				print("\t</ol>\n");
		}
} else {
		print("\t<form action=\"faq.php\" method=\"post\">\n");
		print("\t\t<p style=\"font-weight: bold;\"><label for=\"question\">New question:</label></p>\n");
		print("\t\t<div>\n\t\t\t<textarea rows=\"2\" cols=\"25\" name=\"question\" id=\"question\"></textarea><br />\n");
		print("\t\t\t<input type=\"submit\" value=\"Ask\">\n\t\t</div>\n");
		print("\t</form>");
		unset($mode);
}

art_footer();
?>
