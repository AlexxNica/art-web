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
			print("<p class=\"error\">Error, one or more of the background URLs was invalid.<br/>");
			print("URLs must start with http or ftp, and end in .png, .jpg, .svg, .tar.gz, .tar.bz2 or .tgz.</p>");
		}
	}
	else
	{
		print("<p class=\"error\">Error, you must fill out all of the form fields.</p>");
	}
}
else
{

	print("<p>If you would like to submit your background to art.gnome.org, please fill out the form below and provide a web address where we can download your background.\n</p>\n");
	print("<p class=\"info\">To help speed up your submission, please take a look at the <a href=\"http://live.gnome.org/GnomeArt_2fSubmissionPolicy\">Submission Policy</a> first.</p>\n");
}
?>

<form action="<?php print($_SERVER["PHP_SELF"]); ?>" method="post">
<table border="0">
<tr>
	<td><strong><label for="background_name">Background Name</label>:</strong></td>
	<td><input type="text" name="background_name" id="background_name" size="40" value="<?php print($background_name); ?>" /></td>
</tr>
<tr>
	<td><strong><label for="category">Category</label></strong></td>
	<td><?php print_select_box("category", array_combine($background_category_list, $background_category_list), $category); ?> </td>
</tr>
<tr>
	<td><strong><label for="variation">Variation of</label>:</strong></td>
	<td><select name="parentID" id="variation"><option value="0">N/A</option>
<?php
	$background_select_result = mysql_query("SELECT backgroundID,background_name,category FROM background WHERE userID = {$_SESSION['userID']} AND parentID=0");
	while(list($backID,$back_name)=mysql_fetch_row($background_select_result))
	{
		if ($backID == $parentID)
			$selected = "selected=\"true\"";
		else
			$selected = "";
		print("<option value=\"$backID\" $selected>$back_name ($category)</option>");
	}
?>
</select></td></tr>
<tr>
	<td><strong><label for="version">Version</label></strong></td>
	<td><input type="text" name="version" id="version" size="40" value="<?php print($version); ?>" /></td>
</tr>
<tr>
	<td><strong><label for="license">License</label></strong></td>
	<td><?php print_select_box("license", $license_config_array, $license); ?></td>
</tr>
<tr>
	<td><strong><label for="author">Background Author</label>:</strong></td>
	<td><input type="hidden" name="userID" id="author" value="<?php print($_SESSION['userID']) ?>" /><?php print($_SESSION['realname']); ?></td>
</tr>
<tr>
	<td><strong><label for="background_description">Description</label>:</strong></td>
	<td><textarea name="background_description" id="background_description" cols="40" rows="5" wrap="true"><?php print($background_description); ?></textarea></td>
</tr>
</table>

<p class="info">Tick the box next to each background type you wish to submit</p>
<table border="0">
<tr>
	<td style="text-align:center"><strong>X</strong></td><td><strong>Resolution</strong></td>
	<td><strong>URL</strong></td>
	<td style="text-align:center"><strong>X</strong></td>
	<td><strong>Resolution</strong></td>
	<td><strong>URL</strong></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1024x768]" id="jpg1024" /></td>
	<td><label for="jpg1024">JPG - 1024x768</label></td>
	<td><input type="text" class="jpg1024" name="backgrounds[jpg|1024x768]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1024x768]" id="png1024" /></td>
	<td><label for="png1024">PNG - 1024x768</label></td>
	<td><input type="text" name="backgrounds[png|1024x768]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1280x1024]" id="jpg1280" /></td>
	<td><label for="jpg1280">JPG - 1280x1024</label></td><td><input type="text" name="backgrounds[jpg|1280x1024]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1280x1024]" id="png1280" /></td>
	<td><label for="png1280">PNG - 1280x1024</label></td><td><input type="text" name="backgrounds[png|1280x1024]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1400x1050]" id="jpg1400" /></td>
	<td><label for="jpg1400">JPG - 1400x1050</label></td><td><input type="text" name="backgrounds[jpg|1400x1050]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1400x1050]" id="png1400" /></td>
	<td><label for="png1400">PNG - 1400x1050</label></td><td><input type="text" name="backgrounds[png|1400x1050]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1600x1200]" id="jpg1600" /></td>
	<td><label for="jpg1600">JPG - 1600x1200</label></td><td><input type="text" name="backgrounds[jpg|1600x1200]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1600x1200]" id="png1600" /></td>
	<td><label for="png1600">PNG - 1600x1200</label></td><td><input type="text" name="backgrounds[png|1600x1200]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1680x1050]" id="jpg1680" /></td>
	<td><label for="jpg1680">JPG - 1680x1050</label></td>
	<td><input type="text" name="backgrounds[jpg|1680x1050]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1680x1050]" id="png1680" /></td>
	<td><label for="png1680">PNG - 1680x1050</label></td>
	<td><input type="text" name="backgrounds[png|1680x1050]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[jpg|1920x1200]" id="jpg1920" /></td>
	<td><label for="jpg1920">JPG - 1920x1200</label></td>
	<td><input type="text" name="backgrounds[jpg|1920x1200]" /></td>
	<td><input type="checkbox" name="background_toggles[png|1920x1200]" id="png1920" /></td>
	<td><label for="png1920">PNG - 1920x1200</label></td>
	<td><input type="text" name="backgrounds[png|1920x1200]" /></td>
</tr>
<tr>
	<td><input type="checkbox" name="background_toggles[svg|scalable]" id="svg" /></td>
	<td><label for="svg">SVG</label></td>
	<td><input type="text" name="backgrounds[svg|scalable]" /></td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Submit Background" name="submit" /></td>
</tr>
</table>


</form>

<?
art_footer();

?>
