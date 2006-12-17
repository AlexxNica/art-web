<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

if (POST ('cancel'))
{
	header ("Location: http://{$_SERVER['SERVER_NAME']}/admin/");
}

admin_header("Add a FAQ");
admin_auth(2);

// Little hacky, see below
$error_fallback = false;

if (POST ('save'))
{
  /* Validate data */
	$question = escape_string($_POST["question"]);
	$answer = escape_string($_POST["answer"]);

  /* We need both of them of course */
	if($question && $answer)
	{
		$faq_insert_result = mysql_query("INSERT INTO faq(faqID,question,answer) VALUES('','$question','$answer')");
		if($faq_insert_result)
		{
			print("Successfully added FAQ to database.");
			print("<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to add another.");
		}
		else
		{
			print("<p class=\"error\">Error updating db, make sure magic quotes are turned on.</p>");
		}
		admin_footer();
		die();
	}
	else
	{
		// Ok, we haven't POST('preview') but we need get preview again
		print("<p class=\"error\">Error, all of the form fields are not filled in.</p>");
		// So set $error_fallback = true and continue
		$error_fallback = true;
	}
}

if((POST('preview')) or ($error_fallback))
{
  /* Validate data */
	$question = strip_string($_POST["question"]);
	$answer = strip_string($_POST["answer"]);

  /* If $error_fallback==true we know there was an error msg on saving */
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
	<form class="message_box_edit" action="<? echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<p><label for="question"><strong>Question:</strong></label><br />
		<textarea class="phpBB_box" tabindex="30" name="question" cols="50" rows="5"><? echo htmlspecialchars($question); ?></textarea></p>
		<p><label for="answer"><strong>Answer:</strong></label><br />
		<textarea class="phpBB_box" tabindex="31" name="answer" cols="50" rows="10"><? echo htmlspecialchars($answer); ?></textarea></p>
		<div class="message_box_ask">
			<br />
		</div>
		<div class="message_box_buttons">
			<input tabindex="32" type="submit" name="preview" value="Preview"/>
			<input tabindex="33" type="submit" name="cancel" value="Cancel" />
			<input tabindex="34" type="submit" name="save" value="Add"/>
		</div>
	</form>
<?
	admin_footer();
	die();
}

  /* We can use template in the future here */
?>
	<form class="message_box_edit" action="<? echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<p><label for="question"><strong>Question:</strong></label><br />
		<textarea class="phpBB_box" tabindex="30" name="question" id="question" cols="50" rows="5"><? echo strip_string($question); ?></textarea></p>
		<p><label for="answer"><strong>Answer:</strong></label><br />
		<textarea class="phpBB_box" tabindex="31" name="answer" id="answer" cols="50" rows="10"><? echo strip_string($answer); ?></textarea></p>
		<div class="message_box_ask">
			<br />
		</div>
		<div class="message_box_buttons">
			<input tabindex="32" type="submit" name="cancel" value="Cancel" />
			<input tabindex="33" type="submit" name="preview" value="Preview"/>
		</div>
	</form>
<?
admin_footer();
?>
