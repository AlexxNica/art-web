<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// If cancel has been pressed, redirect to FAQ list
if(POST('cancel'))
{
	header ("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}");
}
/*
// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);
*/

// First step - link to editing, we will get $action as attribute (see line 117)
$action = $_GET['action'];

admin_header("Edit an FAQ Entry");
admin_auth(2);

// Little hacky, see below
$error_fallback = false;

// write the updated FAQ do the database
if(POST('save'))
{
  /* Validate data */
	$faqID = validate_input_regexp_default ($_POST['faqID'], '^[0-9]+$', false);
	$question = escape_string($_POST["question"]);
	$answer = escape_string($_POST["answer"]);

	if ($faqID)
	{
		if($question && $answer)
		{

			$faq_update_result = mysql_query("UPDATE faq SET question='$question', answer='$answer' WHERE faqID='$faqID' LIMIT 1");
			if($faq_update_result)
			{
				print("Successfully updated FAQ in database.");
				print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another FAQ entry.");
			}
			else
			{
				print("<p class=\"error\">Database Error, unable to update database.</p>");
			}
			admin_footer();
			die();
		}
		else
		{
			// Ok, we haven't POST('preview') but we need get preview again
			print("<p class=\"error\">Error, you must have a question and an answer.</p>");
			// So set $error_fallback = true and continue
			$error_fallback = true;
		}
	}
	else
	{
		print("<p class=\"error\">Error, invalid faqID.</p>");
	}
}
// display preview of the question and answer
// Here we go with $error_fallback :D
if((POST('preview')) or ($error_fallback))
{
  /* Validate data */
	$faqID = validate_input_regexp_default ($_POST['faqID'], '^[0-9]+$', false);
	$question = strip_string($_POST["question"]);
	$answer = strip_string($_POST["answer"]);

	if ($faqID)
	{
		// If $error_fallback==true we know there was an error msg on saving
		if (((!$question) or (!$answer)) and (!$error_fallback))
		{
			print("<p class=\"warning\">Warning, you must have a question and an answer.</p>");
		}

  /* We can use template in the future here */
		?>
FAQ preview:
<p class="message_box_phpBB">
	<span class="bold_text"><? echo html_parse_text($question); ?></span><br />
	<? echo html_parse_text($answer); ?>
</p>
<form class="message_box_edit" action="<? echo $_SERVER["PHP_SELF"]; ?>?action=preview" method="post">
	<p><label for="question"><strong>Question:</strong></label><br />
	<textarea class="phpBB_box" id="question" name="question" cols="50" rows="5"><? echo htmlspecialchars($question); ?></textarea></p>
	<p><label for="answer"><strong>Answer:</strong></label><br />
	<textarea class="phpBB_box" id="answer" name="answer" cols="50" rows="10"><? echo htmlspecialchars($answer); ?></textarea></p>
	<div class="message_box_ask">
		Would you like to save updated FAQ #<? echo $faqID; ?>?
	</div>
	<div class="message_box_buttons">
		<input tabindex="32" type="submit" name="preview" value="Preview"/>
		<input tabindex="33" type="submit" name="cancel" value="Cancel" />
		<input tabindex="34" type="submit" name="save" value="Save"/>
	</div>
	<input type="hidden" name="faqID" value="<? echo $faqID; ?>" />
</form>
		<?
		admin_footer();
		die();
	}
	else
	{
		print("<p class=\"error\">Error, invalid faqID.</p>");
	}
}
// display the question and answer in a textarea for editing
elseif($action == "edit")
{
  /* Validate data */
	$faqID = validate_input_regexp_default ($_GET['faqID'], '^[0-9]+$', -1);
	if ($faqID)
	{
		$faq_select_result = mysql_query("SELECT question,answer FROM faq WHERE faqID='$faqID' LIMIT 1");
		if(mysql_num_rows($faq_select_result) == 1)
		{
			list($question,$answer) = mysql_fetch_row($faq_select_result);

  /* We can use template in the future here */
			?>
<form class="message_box_edit" action="<? echo $_SERVER["PHP_SELF"]; ?>" method="post">
	<p><label for="question"><strong>Question:</strong></label><br />
	<textarea class="phpBB_box" name="question" id="question" cols="50" rows="5"><? echo htmlspecialchars($question); ?></textarea></p>
	<p><label for="answer"><strong>Answer:</strong></label><br />
	<textarea class="phpBB_box" name="answer" id="answer" cols="50" rows="10"><? echo htmlspecialchars($answer); ?></textarea>
	<div class="message_box_ask">
		<br />
	</div>
	<div class="message_box_buttons">
		<input tabindex="32" type="submit" name="cancel" value="Cancel" />
		<input tabindex="33" type="submit" name="preview" value="Preview"/>
	</div>
	<input type="hidden" name="faqID" value="<? echo $faqID; ?>" />
</form>
			<?
			admin_footer();
			die();
		}
		else
		{
			print("<p class=\"error\">Error, invalid faqID or database error.</p>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, invalid faqID or database error.</p>");
	}
}

$faq_select_result = mysql_query("SELECT faqID,question,answer FROM faq WHERE status != 'pending' ORDER by faqID");
if(mysql_num_rows($faq_select_result)==0)
{
	print("There are no FAQ entries available for editing.");
}
else
{
	print("<ol>\n");
	while(list($faqID,$question,$answer)=mysql_fetch_row($faq_select_result))
	{
		$question = html_parse_text($question);
		$answer = html_parse_text($answer);
		print("<li><span class=\"bold_text\">$question [<a href=\"" . $_SERVER["PHP_SELF"] . "?action=edit&amp;faqID=$faqID\">Edit</a>]</span>\n<p>$answer</p>\n</li>\n\n");
	}
	print("</ol>\n");
}
admin_footer();
?>
