<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobal stuff

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

$background_name = $_POST["background_name"];
$category = $_POST["category"];
$background_author = $_POST["background_author"];
$author_email = $_POST["author_email"];
$background_url = $_POST["background_url"];
$background_screenshot_url = $_POST["background_screenshot_url"];
$background_description = $_POST["background_description"];
$submission_id = $_POST["submission_id"];

ago_header("Background Submission");
create_middle_box_top("backgrounds");

if($_POST)
{
	if($background_name && $category && $background_author && $author_email && $background_url && $background_description)
	{
		$incoming_background_insert_query = "INSERT INTO incoming_background(backgroundID,status,background_name,category,author,author_email,background_url,background_screenshot_url,background_description) ";
		$incoming_background_insert_query .= "VALUES('','new','$background_name','$category','$background_author','$author_email','$background_url','$background_screenshot_url','$background_description')";
		$incoming_background_insert_result = mysql_query("$incoming_background_insert_query");
		if(mysql_affected_rows()==1)
		{
			print("Thank you, your background will be considered for inclusion in art.gnome.org.<br />");
			$id = mysql_insert_id();
			print("Your background submission tracking ID is $id. Use this to track the status of your submission and in any queries regarding your submission status.");
		}
		else
		{
			print("There were form submission errors, please try again.");
		}
	}
	else
	{
		if ($submission_id)
		{
			$submission_result = mysql_query("SELECT background_name,status FROM incoming_background WHERE backgroundID = $submission_id");
			list ($submission_name,$submission_status) = mysql_fetch_row($submission_result);
			print("<b>Submission Status</b><p>");
			switch ($submission_status)
			{
				case "new" : print("The background &quot;$submission_name&quot; is currently pending intial review."); break;
				case "added" : print("&quot;$submission_name&quot; has been added to the art.gnome.org database and should be available through the site."); break;
				case "rejected" : print("The background &quot;$submission_name&quot; has been removed from the submissions list and not been added to the site. This may happen if the background was inappropriate to art.gnome.org, appeared to be incomplete or unfinished, or could not be retrieved from the URL provided. If you have any queries regarding this status, please contact a memeber of the art.gnome.org team, quoting the submission tracking ID."); break;
				default: print("There was an error retrieving the status of the background submission ID you requested. Please check and try again.");
			}
		print("</p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Back to Background Submission Page</a>");
		}
		else
		{
			print("Error, you must fill out all of the previous form fields, please go back and try again.");
		}
	}
}
else
{

	print("If you would like to submit your background to art.gnome.org, please fill out the form below and provide a web address where we can download your background.\n<p>\n");
	print("To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">Submission Policy</a> first.");
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"\">Choose<option value=\"gnome\">GNOME<option value=\"other\">Other</select></td></tr>\n");
	print("<tr><td><b>Background Author:</b></td><td><input type=\"text\" name=\"background_author\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>URL of Background:</b></td><td><input type=\"text\" name=\"background_url\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<input type=\"submit\" value=\"Submit Background\">\n");
	print("</form>");


	print("<hr /><b>Submission tracking</b>");
	print("<p><form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">");
	print("Submission ID: <input name=\"submission_id\" />");
	print("<input type=\"submit\" value=\"Get Status\" /></form></p>");

}

create_middle_box_bottom();
ago_footer();

?>
