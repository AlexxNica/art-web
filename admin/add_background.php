<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a Background");
admin_auth(2);

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

$background_category_list = array_keys($background_config_array);
$submitID = validate_input_regexp_default($_POST['submitID'], "^[0-9]+$", "");

if($add_background)
{
	if($background_name && $userID && $month && $day && $year && $background_description && $thumbnail_filename && (count($background_toggles)>0) )
	{
		$date = $year . "-" . $month . "-" . $day;
		$timestamp = time();
		if($background_type == "new")
		{
			$parentID = "0";
		}
		$background_insert_query  = "INSERT INTO background(backgroundID,status,userID,name,parent,version,license,category,add_timestamp,release_date,description,thumbnail_filename, download_start_timestamp) ";
		$background_insert_query .= "VALUES('','active','$userID','$background_name','$parentID','$version','$license','$category','$timestamp','$date','$background_description','$thumbnail_filename', UNIX_TIMESTAMP())";
		print(mysql_error());
		$background_insert_result = mysql_query($background_insert_query);
		$backgroundID = mysql_insert_id();
		while(list($key,$val)=each($background_toggles))
		{
			list($type,$resolution)=explode("|",$key);
			$background_resolution_insert_query  = "INSERT INTO background_resolution(background_resolutionID,backgroundID,type,resolution,filename) ";
			$background_resolution_insert_query .= "VALUES('','$backgroundID','$type','$resolution','$backgrounds[$key]')";
			//print($background_resolution_insert_query);
			$background_resolution_insert_result = mysql_query($background_resolution_insert_query);
		}
		if($background_insert_result && $background_resolution_insert_result)
		{
			print('<p class="info">Successfully added background to the database.</p>');
			if ($submitID)
			{
				$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='added' WHERE backgroundID='$submitID'");
				print("<hr />Successfully marked background as added.<p><a href=\"/admin/show_submitted_backgrounds.php\">Click here</a> to return to incoming backgrounds list.");
			}
		}
		else
		{
			print("There were database errors adding background into database.");
		}
	}
	else
  	{
		print("Error, all of the form fields are not filled in.");
	}
}
else
{
	if ($submitID != "")
	{
		$submit_result = mysql_query("SELECT * FROM incoming_background WHERE backgroundID=$submitID");
		$row = mysql_fetch_array($submit_result);
		extract($row);
	}
	$date = date("m/d/Y");
	list($month,$day,$year) = explode("/",$date);
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><strong>Background Name:</strong></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$name\"></td></tr>\n");
	print("<tr><td><strong>Category</strong></td><td>");print_select_box("category", array_combine($background_category_list, $background_category_list), $category); print("</td></tr>\n");
	print("<tr><td><strong>License</strong></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
	print("<tr><td><strong>Version:</strong></td><td><input type=\"text\" name=\"version\" size=\"40\" value=\"$version\"></td></tr>\n");
	print("<tr><td><strong>Variation</strong></td><td><select name=\"parentID\"><option value=\"\">N/A</option>");
	$background_select_result = mysql_query("SELECT backgroundID,name FROM background WHERE userID=$userID AND parent=0");
	while(list($backID,$back_name)=mysql_fetch_row($background_select_result))
	{
		if ($backID == $parentID)
			$selected = " selected=\"true\"";
		else
			$selected = "";
		print("<option$selected value=\"$backID\">$back_name</option>");
	}
	print("</select></td></tr>\n");
	print("<tr><td><strong>User:</strong></td><td><input type=\"hidden\" name=\"userID\" value=\"$userID\">$userID</td></tr>\n");
	print("<tr><td><strong>Release Date:</strong></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
	print("<tr><td><strong>Background Description:</strong></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
	print("<tr><td><strong>Thumbnail Filename:</strong></td><td>");file_chooser("thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/backgrounds", $category);print("</td></tr>\n");
	print("</table>\n<p>\n");

	if ($submitID)
	{
		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4px\"  >");
		print("<tr><td><strong>X&nbsp;&nbsp;Type/Resolution</strong></td><td><strong>Filename</strong></td></tr>\n");
		$background_resolution_result = mysql_query("SELECT type,resolution,filename FROM incoming_background_resolution WHERE backgroundID=$submitID");
		while (list($type,$resolution,$filename) = mysql_fetch_row($background_resolution_result))
		{
			if ($resolution != 'scalable')
				$extra = '_' . $resolution;
			else
				$extra = '';
			$filename = create_filename($background_name, $category, $filename, $extra);
			print("<tr><td><label><input type=\"checkbox\" name=\"background_toggles[$type|$resolution]\" checked=\"checked\"/>$type - $resolution</label></td><td><input type=\"text\" name=\"backgrounds[$type|$resolution]\" value=\"$filename\" size=\"40\"></td></tr>\n");
		}
		print("</table>");
	}
	else
	{
		print("<table border=\"0\">\n");
		print("<tr><td><strong>X</strong></td><td><strong>Type/Resolution</strong></td><td><strong>Filename</strong></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1024x768]\"></td><td>JPG - 1024x768</td><td><input type=\"text\" name=\"backgrounds[jpg|1024x768]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1280x1024]\"></td><td>JPG - 1280x1024</td><td><input type=\"text\" name=\"backgrounds[jpg|1280x1024]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1400x1050]\"></td><td>JPG - 1400x1050</td><td><input type=\"text\" name=\"backgrounds[jpg|1400x1050]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1600x1200]\"></td><td>JPG - 1600x1200</td><td><input type=\"text\" name=\"backgrounds[jpg|1600x1200]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[jpg|1920x1200]\"></td><td>JPG - 1920x1200</td><td><input type=\"text\" name=\"backgrounds[jpg|1920x1200]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1024x768]\"></td><td>PNG - 1024x768</td><td><input type=\"text\" name=\"backgrounds[png|1024x768]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1280x1024]\"></td><td>PNG - 1280x1024</td><td><input type=\"text\" name=\"backgrounds[png|1280x1024]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1400x1050]\"></td><td>PNG - 1400x1050</td><td><input type=\"text\" name=\"backgrounds[png|1400x1050]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1600x1200]\"></td><td>PNG - 1600x1200</td><td><input type=\"text\" name=\"backgrounds[png|1600x1200]\"></td></tr>\n");
		print("<tr><td><input type=\"checkbox\" name=\"background_toggles[png|1920x1200]\"></td><td>PNG - 1920x1200</td><td><input type=\"text\" name=\"backgrounds[png|1920x1200]\"></td></tr>\n");
		print("</table>\n");
	}
	print("<input type=\"hidden\" name=\"submitID\" value=\"$submitID\">");
	print("<input type=\"submit\" value=\"Add Background\" name=\"add_background\">");
	print("</form>\n");
}
admin_footer();
?>
