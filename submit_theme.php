<?php

require("mysql.inc.php");
require("common.inc.php");
require("art_headers.inc.php");

// superglobal stuff
$theme_name = escape_string($_POST["theme_name"]);
$theme_author = escape_string($_POST["theme_author"]);
$theme_url = escape_string($_POST["theme_url"]);
$theme_description = escape_string($_POST["theme_description"]);
$category = validate_input_array_default($_POST["category"], array_keys($theme_config_array), "");
$license = validate_input_array_default($_POST["license"], array_keys($license_config_array), "");
$version = validate_input_regexp_default($_POST["version"], "^[0-9\.]+$", "0");
$update = validate_input_regexp_default($_POST["update"], "^[0-9]+$", "");
$parentID = validate_input_regexp_default($_POST["parentID"], "^[0-9]+$", "");

art_header("Theme Submission");
create_title("Theme Submission", "");

if (!array_key_exists('username', $_SESSION))
{
	print("<p class=\"error\">You need to <a href=\"/account.php\">login</a> first.</p>");
	art_footer();
	die();
}

if($_POST['submit'])
{
	if (!validate_submit_url($theme_url))
	{
		print("<p class=\"error\">Error, &quot;$theme_url&quot; is not a valid submission url.<br/>");
		print("URLs must start with http or ftp, and end in .png, .jpg, .tar.gz, .tar.bz2 or .tgz.</p>");
	}
	elseif($theme_name && $category && $theme_url && $theme_description && $license)
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
			art_footer();
			die();
		}
		else
		{
			print("<p class=\"error\">There were form submission errors, please try again.</p>");
			print("<tt>".mysql_error()."</tt>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the form fields.</p>");
	}
}
else
{
	print("<p>If you would like to submit your theme to art.gnome.org, please fill out the form below and provide a web address where we can download your theme.\n</p>\n");
	print("<p class=\"info\">To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">Submission Policy</a> first.</p>");
}
	if ($update)
	{
		$theme_select_result = mysql_query("SELECT theme_name,category,license,version,theme_description,parentID FROM incoming_theme WHERE themeID=$update AND userID={$_SESSION['userID']}");
		extract(mysql_fetch_array($theme_select_result));
	}
	
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">");
	print("<tr><td><strong><label for=\"theme_name\">Theme Name</label>:</strong></td><td><input type=\"text\" name=\"theme_name\" value=\"$theme_name\" id=\"theme_name\" size=\"40\" /></td></tr>\n");
	print("<tr><td><strong><label for=\"category\">Category</label></strong></td><td>"); print_select_box("category", Array(""=>"Choose", "gtk2"=>"Applications (gtk+)", "desktop"=>"Desktop Theme", "gtk_engines"=>"GTK+ Engines", "icon"=>"Icon", "gdm_greeter" => "Login Manager (gdm)", "splash_screens"=>"Splash Screens", "metacity"=>"Window Borders (metacity)"), $category); print("</td></tr>\n");
	print("<tr><td><strong><label for=\"variation\">Variation of</label>:</strong></td><td><select name=\"parentID\" id=\"variation\"><option value=\"0\">N/A</option>\n");
	$theme_select_result = mysql_query("SELECT themeID,theme_name,category FROM theme WHERE userID = {$_SESSION['userID']}");
	while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($theme_select_result))
	{
		if ($var_themeID == $parentID) $selected = "selected=\"true\""; else $selected = "";
		print("<option value=\"$var_themeID\" $selected>$var_theme_name ($var_category)</option>");
	}
	print("</select></td></tr>");
	print("<tr><td><strong><label for=\"license\">License</label></strong></td><td>");print_select_box("license", $license_config_array, $license); print("</td></tr>\n");
	print("<tr><td><strong><label for=\"version\">Version</label></strong></td><td><input type=\"text\" name=\"version\" size=\"40\" value=\"$version\" id=\"version\" /></td></tr>\n");
	print("<tr><td><strong><label for=\"author\">Theme Author</label>:</strong></td><td><input type=\"hidden\" name=\"userID\" id=\"author\" value=\"{$_SESSION['userID']}\" />{$_SESSION['realname']}</td></tr>\n");
	print("<tr><td><strong><label for=\"theme_url\">URL of Theme</label>:</strong></td><td><input type=\"text\" name=\"theme_url\" id=\"theme_url\" size=\"40\" value=\"$theme_url\" /></td></tr>\n");
	print("<tr><td><strong><label for=\"theme_description\">Description</label>:</strong></td><td><textarea name=\"theme_description\" id=\"theme_description\" cols=\"40\" rows=\"5\" wrap>$theme_description</textarea></td></tr>\n");
	print("<tr><td><input type=\"hidden\" name=\"update\" value=\"$update\" />\n");
	print("<input type=\"submit\" name=\"submit\" value=\"Submit Theme\" /></td></tr>\n");
	print("</table>\n");
	print("</form>\n");



art_footer();

?>
