<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobal stuff

$theme_name = mysql_real_escape_string($_POST["theme_name"]);
//$category = validate_input_array_default($_POST["category"], $theme_config_array, "");
$category = mysql_real_escape_string($_POST["category"]);
$version = validate_input_regexp_default($_POST["version"], "^[0-9]+$", "0");
//$license = validate_input_regexp_default($_POST["license"], $license_config_array, "");
$license = mysql_real_escape_string($_POST["license"]);
$theme_author = mysql_real_escape_string($_POST["theme_author"]);
$theme_url = mysql_real_escape_string($_POST["theme_url"]);
$theme_description = mysql_real_escape_string($_POST["theme_description"]);

$update = validate_input_regexp_default($_POST["update"], "^[0-9]+$", "");

ago_header("Theme Submission");
create_title("Theme Submission", "");

if (!array_key_exists('username', $_SESSION))
{
	print("<p class=\"error\">You need to <a href=\"/account.php\">login</a> first.</p>");
	ago_footer();
	die();
}

if($theme_name)
{
	if($theme_name && $category && $theme_url && $theme_description && $license)
	{
		$date = date("Y-m-d");
		$incoming_theme_insert_query  = "INSERT INTO incoming_theme(themeID,userID,status,date,theme_name,version,license,parentID,category,theme_url,theme_description,updateID) ";
		$incoming_theme_insert_query .= "VALUES('','{$_SESSION['userID']}','new','$date','$theme_name','$version','$license','$parentID','$category','$theme_url','$theme_description','$update')";
		$incoming_theme_insert_result = mysql_query("$incoming_theme_insert_query");
		if(mysql_affected_rows()==1)
		{
			print("<p class=\"info\">Thank you, your theme will be considered for inclusion in art.gnome.org.</p>");
			print("<ul>");
			print("<li><a href=\"{$_SERVER['PHP_SELF']}\">Submit another theme</a></li>");
			print("<li><a href=\"/account.php\">Back to account page</a></li>");
			print("</ul>");
		}
		else
		{
			print("<p class=\"error\">There were form submission errors, please try again.</p>");
			print("<tt>".mysql_error()."</tt>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the previous form fields, please go back and try again.</p>");
	}
}
else
{
	print("<p>If you would like to submit your theme to art.gnome.org, please fill out the form below and provide a web address where we can download your theme.\n</p>\n");
	print("<p class=\"info\">To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">Submission Policy</a> first.</p>");
	
	if ($update)
	{
		$theme_select_result = mysql_query("SELECT theme_name,category,license,version,theme_description,parentID FROM incoming_theme WHERE themeID=$update AND userID={$_SESSION['userID']}");
		list($name,$category,$license,$version,$description,$parentID) = mysql_fetch_row($theme_select_result);
	}
	
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">");
	print("<tr><td><strong>Theme Name:</strong></td><td><input type=\"text\" name=\"theme_name\" value=\"$name\" size=\"40\"></td></tr>\n");
	print("<tr><td><strong>Category</strong></td><td>"); print_select_box("category", Array(""=>"Choose", "desktop"=>"Desktop Theme", "gtk2"=>"Applications (gtk+)", "icon"=>"Icon", "gdm_greeter" => "Login Manager (gdm)", "splash_screens"=>"Splash Screens","metacity"=>"Window Borders (metacity)"), $category); print("</td></tr>\n");
	print("<tr><td><strong>Variation of:</strong></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>\n");
	$theme_select_result = mysql_query("SELECT themeID, theme_name FROM theme WHERE userID = {$_SESSION['userID']}");
	while(list($themeID,$theme_name)=mysql_fetch_row($theme_select_result))
	{
		if ($themeID = $parentID) $selected = "selected=\"true\""; else $selected = "selected=\"false\"";
		print("<option value=\"$themeID\" $selected>$theme_name</option>");
	}
	print("<tr><td><strong>License</strong></td><td>");print_select_box("license", $license_config_array, $license); print("</td></tr>\n");
	print("<tr><td><strong>Version</strong></td><td><input type=\"text\" name=\"version\" size=\"40\" value=\"$version\"></td></tr>\n");
	print("<tr><td><strong>Theme Author:</strong></td><td><input type=\"hidden\" name=\"userID\" value=\"{$_SESSION['userID']}\" />{$_SESSION['realname']}</td></tr>\n");
	print("<tr><td><strong>URL of Theme:</strong></td><td><input type=\"text\" name=\"theme_url\" size=\"40\"></td></tr>\n");
	print("<tr><td><strong>Description:</strong></td><td><textarea name=\"theme_description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<input type=\"hidden\" name=\"update\" value=\"$update\"/>");
	print("<input type=\"submit\" value=\"Submit Theme\">\n");
	print("</form>\n");

}

ago_footer();

?>
