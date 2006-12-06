<?php

require("mysql.inc.php");
require("common.inc.php");
require("art_headers.inc.php");

// superglobal stuff
// escape_string() will also call mysql_real_escape_string() so we don't need to check it again
$theme_name = escape_string($_POST["theme_name"]);
$theme_author = escape_string($_POST["theme_author"]);
$theme_url = escape_string($_POST["theme_url"]);
$theme_description = escape_string($_POST["theme_description"]);
$theme_status = escape_string($_POST["status"]);

$category = validate_input_array_default($_POST["category"], array_keys($theme_config_array), "");
$license = validate_input_array_default($_POST["license"], array_keys($license_config_array), "");
$version = validate_input_regexp_default($_POST["version"], "^[0-9\.]+$", "0");
$update = validate_input_regexp_default($_POST["update"], "^[0-9]+$", "");
$parentID = validate_input_regexp_default($_POST["parentID"], "^[0-9]+$", "");
$theme_updateID = validate_input_regexp_default ($_POST["updateID"], "^[0-9]+$", "0");

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
	elseif($theme_name && $category && $theme_url && $theme_description && $license && $theme_status)
	{
		// Theme status must be new -or- update and update ID must be > 0 (N/A = 0 and invalid input = 0)
		if (($theme_status == 'update' && $theme_updateID > 0) or ($theme_status != 'update'))
		{
			// NULL or update ID?
			if ($theme_status == 'update') $update_data = "'$theme_updateID'";
								 else {
										$update_data = "NULL";
										// If there already exists theme with same name, add 'Another ' prefix to theme name
										$count_with_same_name_query = "SELECT COUNT(*) FROM theme WHERE name = '$theme_name' AND category = '$category'";
										$count_with_same_name = mysql_result(mysql_query($count_with_same_name_query), 0);
										if ($count_with_same_name > 0) $theme_name = 'Another '.$theme_name;
										}
			$date = date("Y-m-d");
			$incoming_theme_insert_query  = "INSERT INTO incoming_theme(themeID,userID,status,date,name,version,license,parentID,category,theme_url,description,updateID,update_of) ";
			$incoming_theme_insert_query .= "VALUES('','{$_SESSION['userID']}','$theme_status','$date','$theme_name','$version','$license','$parentID','$category','$theme_url','$theme_description','$update',$update_data)";
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
			print("<p class=\"error\">If you want to do an update, you have to select theme you want to update.</p>");
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
	print("<p class=\"info\">To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt/SubmissionPolicy\">Submission Policy</a> first.</p>");
}
	if ($update)
	{
		$theme_select_result = mysql_query("SELECT name,category,license,version,description,parentID FROM incoming_theme WHERE themeID=$update AND userID={$_SESSION['userID']}");
		extract(mysql_fetch_array($theme_select_result));
		$theme_name = $name;
		$theme_description = $description;
	}
	
	$theme_select_result = mysql_query("SELECT themeID,name,category FROM theme WHERE userID = {$_SESSION['userID']}");
	while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($theme_select_result))
	{
		if ($var_themeID == $parentID) $selected = "selected=\"true\""; else $selected = "";
		$variation_list .= "\t\t\t\t\t<option value=\"$var_themeID\" $selected>$var_theme_name ($var_category)</option>\n";
		if ($var_themeID == $theme_updateID) $selected = "selected=\"true\""; else $selected = "";
		$update_list .= "\t\t\t\t\t<option value=\"$var_themeID\" $selected>$var_theme_name ($var_category)</option>\n";
	}

	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("\t<table border=\"0\">\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"theme_name\">Theme Name</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<input type=\"text\" name=\"theme_name\" value=\"$theme_name\" id=\"theme_name\" size=\"40\" />\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"category\">Category</label></strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t"); print_select_box("category", Array(""=>"Choose", "gtk2"=>"Applications (gtk+)", "desktop"=>"Desktop Theme", "gtk_engines"=>"GTK+ Engines", "icon"=>"Icon", "gdm_greeter" => "Login Manager (gdm)", "splash_screens"=>"Splash Screens", "metacity"=>"Window Borders (metacity)"), $category); print("\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"variation\">Variation of</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<select name=\"parentID\" id=\"variation\">\n\t\t\t\t\t<option value=\"0\">N/A</option>\n$variation_list\t\t\t\t</select>\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"license\">License</label></strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t");print_select_box("license", $license_config_array, $license); print("\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"version\">Version</label></strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<input type=\"text\" name=\"version\" size=\"40\" value=\"$version\" id=\"version\" />\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"author\">Theme Author</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<input type=\"hidden\" name=\"userID\" id=\"author\" value=\"{$_SESSION['userID']}\" />{$_SESSION['realname']}\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"theme_url\">URL of Theme</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<input type=\"text\" name=\"theme_url\" id=\"theme_url\" size=\"40\" value=\"$theme_url\" />\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"theme_description\">Description</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<textarea name=\"theme_description\" id=\"theme_description\" cols=\"40\" rows=\"5\" wrap>$theme_description</textarea>\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"status\">Status</label></strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t");print_select_box("status", $submit_type_config_array, $theme_status); print("\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<strong><label for=\"update\">Update of</label>:</strong>\n\t\t\t</td>\n\t\t\t<td>\n\t\t\t\t<select name=\"updateID\" id=\"update\">\n\t\t\t\t\t<option value=\"0\">N/A</option>\n$update_list\t\t\t\t</select>\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t\t<tr>\n\t\t\t<td>\n\t\t\t\t<input type=\"hidden\" name=\"update\" value=\"$update\" />\n");
	print("\t\t\t\t<input type=\"submit\" name=\"submit\" value=\"Submit Theme\" />\n\t\t\t</td>\n\t\t</tr>\n");
	print("\t</table>\n");
	print("</form>\n");



art_footer();

?>
