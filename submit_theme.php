<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobal stuff

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

$theme_name = $_POST["theme_name"];
$category = $_POST["category"];
$theme_author = $_POST["theme_author"];
$author_email = $_POST["author_email"];
$theme_url = $_POST["theme_url"];
$theme_description = $_POST["theme_description"];
$submission_id = $_POST["submission_id"];

ago_header("Theme Submission");
create_middle_box_top("themes");

if($_POST)
{
	if($theme_name && $category && $theme_author && $author_email && $theme_url && $theme_description)
	{
		$incoming_theme_insert_query  = "INSERT INTO incoming_theme(themeID,status,theme_name,category,author,author_email,theme_url,theme_description) ";
		$incoming_theme_insert_query .= "VALUES('','new','$theme_name','$category','$theme_author','$author_email','$theme_url','$theme_description')";
		$incoming_theme_insert_result = mysql_query("$incoming_theme_insert_query");
		if(mysql_affected_rows()==1)
		{
			print("Thank you, your theme will be considered for inclusion in art.gnome.org.<br />");
			$id = mysql_insert_id();
			print("Your theme submission tracking ID is $id. Use this to track the status of your submission and in any queries regarding your submission status.");
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
			$submission_result = mysql_query("SELECT theme_name,status FROM incoming_theme WHERE themeID = $submission_id");
			list ($submission_name,$submission_status) = mysql_fetch_row($submission_result);
			print("<b>Submission Status</b><p>");
			switch ($submission_status)
			{
				case "new" : print("The theme &quot;$submission_name&quot; is currently pending intial review."); break;
				case "added" : print("&quot;$submission_name&quot; has been added to the art.gnome.org database and should be available through the site."); break;
				case "rejected" : print("The theme &quot;$submission_name&quot; has been removed from the submissions list and not been added to the site. This may happen if the theme was inappropriate to art.gnome.org, appeared to be incomplete or unfinished, or could not be retrieved from the URL provided. If you have any queries regarding this status, please contact a memeber of the art.gnome.org team, quoting the submission tracking ID."); break;
				default: print("There was an error retrieving the status of the theme submission ID you requested. Please check and try again.");
			}
			print("</p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Back to Theme Submission Page</a>");
		}
		else
		{
			print("Error, you must fill out all of the previous form fields, please go back and try again.");
		}
	}
}
else
{
	print("If you would like to submit your theme to art.gnome.org, please fill out the form below and provide a web address where we can download your theme.\n<p>\n");
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">");
	print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"\">Choose<option value=\"desktop\">Desktop Theme<option value=\"gtk2\">Applications (gtk+)<option value=\"icon\">Icon<option value=\"gdm_greeter\">Login Manager (gdm)<option value=\"splash_screens\">Splash Screens<option value=\"metacity\">Window Borders (metacity)</select></td></tr>\n");
	print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>URL of Theme:</b></td><td><input type=\"text\" name=\"theme_url\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"theme_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<input type=\"submit\" value=\"Submit Theme\">\n"); 
	print("</form>\n");

	print("<hr /><b>Submission tracking</b>");
	print("<p><form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">");
	print("Submission ID: <input name=\"submission_id\" />");
	print("<input type=\"submit\" value=\"Get Status\" /></form></p>");
}

create_middle_box_bottom();
ago_footer();

?>
