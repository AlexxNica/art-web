<?php

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");

// superglobal stuff
$background_name = addslashes($_POST["background_name"]);
$category = validate_input_array_default($_POST["category"], array("gnome", "other"), "");
$parentID = validate_input_regexp_default ($_POST["parentID"], "^[0-9]+$", "0");
$version = validate_input_regexp_default($_POST["version"], "^[0-9]+$", "0");
$license = validate_input_array_default($_POST["license"], array_keys($license_config_array), "None");
$background_url = addslashes($_POST["background_url"]);
$background_description = htmlspecialchars($_POST["background_description"]);
$background_toggles = $_POST['background_toggles']; // This is an array, which is validated later
$backgrounds = $_POST['backgrounds']; // This is an array, which is validated later

ago_header("Background Submission");
create_title("Submit a background","");

if (!array_key_exists('username', $_SESSION))
{
	print("<p class=\"error\">You need to <a href=\"/account.php\">login</a> first.</p>");
	ago_footer();
	die();
}

if(array_key_exists("submit",$_POST))
{

	if($background_name && $category && $background_description && (count($background_toggles)>0) )
	{
		$date = date("Y-m-d");
		$incoming_background_insert_query = "INSERT INTO incoming_background(backgroundID,status,date,version,license,background_name,category,userID,parentID,background_description) ";
		$incoming_background_insert_query .= "VALUES('','new','$date','$version','$license','$background_name','$category','{$_SESSION['userID']}','$parentID','$background_description')";
		$incoming_background_insert_result = mysql_query("$incoming_background_insert_query");
		$backgroundID = mysql_insert_id();
		while(list($key,$val)=each($background_toggles))
		{
			list($type,$resolution)=explode("|",$key);
			$type = addslashes($type);
			$resolution = addslashes($resolution);
			$background = addslashes($backgrounds[$key]);
			$incoming_background_resolution_insert_query  = "INSERT INTO incoming_background_resolution(background_resolutionID,backgroundID,type,resolution,filename) ";
			$incoming_background_resolution_insert_query .= "VALUES('','$backgroundID','$type','$resolution','$background')";
			$incoming_background_resolution_insert_result = mysql_query($incoming_background_resolution_insert_query);
		}
		if($incoming_background_insert_result && $incoming_background_resolution_insert_result)
		{
			print("<p class=\"info\">Thank you, your background will be considered for inclusion in art.gnome.org.</p>");
			print("<ul>");
			print("<li><a href=\"{$_SERVER['PHP_SELF']}\">Submit another background</a></li>");
			print("<li><a href=\"/account.php\">Back to account page</a></li>");
			print("</ul>");
		}
		else
		{
			print("<p class=\"error\">There were form submission errors, please try again.</p>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the previous form fields, please go back and try again.</p>");
	}
}
else
{

	print("<p>If you would like to submit your background to art.gnome.org, please fill out the form below and provide a web address where we can download your background.\n<p>\n");
	print("<p class=\"info\">To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">Submission Policy</a> first.</p>");

	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"\">Choose<option value=\"gnome\">GNOME<option value=\"other\">Other</select></td></tr>\n");
	print("<tr><td><b>Variation of:</b></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>\n");
	$background_select_result = mysql_query("SELECT backgroundID, background_name FROM incoming_background WHERE userID = {$_SESSION['userID']} AND parentID=0");
	while(list($backID,$back_name)=mysql_fetch_row($background_select_result))
	{
		print("<option value=\"$backID\">$back_name</option>");
	}
	print("<tr><td><b>Version</b></td><td><input type=\"text\" name=\"version\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>License</b></td><td>");print_select_box("license", $license_config_array, ""); print("</td></tr>\n");
	print("<tr><td><b>Background Author:</b></td><td><input type=\"hidden\" name=\"userID\" value=\"{$_SESSION['userID']}\">{$_SESSION['realname']}</td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	print("<p class=\"info\">Tick the box next to each background type you wish to submit</p>");
	print("<table border=\"0\">\n");
	print("<tr><td><b>X</b></td><td><b>Type/Resolution</b></td><td><b>URL</b></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1024x768]\"></td><td>JPG - 1024x768</td><td><input type=\"text\" name=\"backgrounds[jpg|1024x768]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1280x1024]\"></td><td>JPG - 1280x1024</td><td><input type=\"text\" name=\"backgrounds[jpg|1280x1024]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1400x1050]\"></td><td>JPG - 1400x1050</td><td><input type=\"text\" name=\"backgrounds[jpg|1400x1050]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1600x1200]\"></td><td>JPG - 1600x1200</td><td><input type=\"text\" name=\"backgrounds[jpg|1600x1200]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1920x1200]\"></td><td>JPG - 1920x1200</td><td><input type=\"text\" name=\"backgrounds[jpg|1920x1200]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1680x1050]\"></td><td>JPG - 1680x1050</td><td><input type=\"text\" name=\"backgrounds[jpg|1680x1050]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1024x768]\"></td><td>PNG - 1024x768</td><td><input type=\"text\" name=\"backgrounds[png|1024x768]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1280x1024]\"></td><td>PNG - 1280x1024</td><td><input type=\"text\" name=\"backgrounds[png|1280x1024]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1400x1050]\"></td><td>PNG - 1400x1050</td><td><input type=\"text\" name=\"backgrounds[png|1400x1050]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1600x1200]\"></td><td>PNG - 1600x1200</td><td><input type=\"text\" name=\"backgrounds[png|1600x1200]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1920x1200]\"></td><td>PNG - 1920x1200</td><td><input type=\"text\" name=\"backgrounds[png|1920x1200]\"></td></tr>\n");
	print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1680x1050]\"></td><td>PNG - 1680x1050</td><td><input type=\"text\" name=\"backgrounds[png|1680x1050]\"></td></tr>\n");
	print("</table>\n");

	print("<input type=\"submit\" value=\"Submit Background\" name=\"submit\">\n");
	print("</form>");
}

ago_footer();

?>
