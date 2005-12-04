<?php

require("mysql.inc.php");
require("common.inc.php");
require("art_headers.inc.php");

$background_category_list = array_keys($background_config_array);

// superglobal stuff
$background_name = escape_string($_POST["background_name"]);
$background_url = escape_string($_POST["background_url"]);
$background_description = escape_string($_POST["background_description"]);

$category = validate_input_array_default($_POST["category"], $background_category_list, "");
$license = validate_input_array_default($_POST["license"], array_keys($license_config_array), "");
$parentID = validate_input_regexp_default ($_POST["parentID"], "^[0-9]+$", "0");
$version = validate_input_regexp_default($_POST["version"], "^[0-9\.]+$", "0");
$background_toggles = $_POST['background_toggles']; // This is an array, which is validated later
$backgrounds = $_POST['backgrounds']; // This is an array, which is validated later


art_header("Background Submission");
create_title("Submit a background","");

if (!array_key_exists('username', $_SESSION))
{
	print("<p class=\"error\">You need to <a href=\"/account.php\">login</a> first.</p>");
	art_footer();
	die();
}

if(array_key_exists("submit",$_POST))
{
	if($background_name && $category && $background_description && (count($background_toggles)>0))
	{
		$valid_urls = True;
		foreach ($background_toggles as $key => $val)
		{
			$background_validate = addslashes($backgrounds[$key]);
			if (!validate_submit_url($background_validate))
				$valid_urls = False;
		}
		if ($valid_urls)
		{
			$date = date("Y-m-d");
			$incoming_background_insert_query = "INSERT INTO incoming_background(backgroundID,status,date,version,license,background_name,category,userID,parentID,background_description) ";
			$incoming_background_insert_query .= "VALUES('','new','$date','$version','$license','$background_name','$category','{$_SESSION['userID']}','$parentID','$background_description')";
			$incoming_background_insert_result = mysql_query("$incoming_background_insert_query");
			$backgroundID = mysql_insert_id();
			foreach ($background_toggles as $key => $val)
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
			art_footer();
			die();
		}
		else
		{
			$message = '<p class="error">Error, one or more of the background URLs was invalid.<br/>';
			$message .= 'URLs must start with http or ftp, and end in .png, .jpg, .svg, .tar.gz, .tar.bz2 or .tgz.</p>';
		}
	}
	else
	{
		$message = '<p class="error">Error, you must fill out all of the form fields.</p>';
	}
}


$template = new template ('submit_background.html');
$template->add_var ('message', $message);
$template->add_var ('background-name', $background_name);
$template->add_var ('category-list', create_select_box("category", array_combine($background_category_list, $background_category_list), $category));
$variation_list = '<select name="parentID" id="variation"><option value="0">N/A</option>';
$background_select_result = mysql_query("SELECT backgroundID,background_name,category FROM background WHERE userID = {$_SESSION['userID']} AND parentID=0");
while(list($backID,$back_name) = mysql_fetch_row($background_select_result))
{
	if ($backID == $parentID)
		$selected = "selected=\"true\"";
	else
		$selected = "";
	$variation_list .= ("<option value=\"$backID\" $selected>$back_name ($category)</option>");
}
$variation_list .= '</select>';
$template->add_var ('variation-list', $variation_list);
$template->add_var ('version', $version);
$template->add_var ('license-list', create_select_box("license", $license_config_array, $license));
$template->add_var ('realname', $_SESSION['realname']);
$template->add_var ('background-description', $background_description);
$template->write();

art_footer();

?>
