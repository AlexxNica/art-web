<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a Background");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if($add_background)
{
	if($background_name && $background_author && $month && $day && $year && $background_description && $thumbnail_filename && (count($background_toggles)>0) )
	{
		$date = $year . "-" . $month . "-" . $day;
		$timestamp = time();
		if($background_type == "new")
		{
			$parentID = "0";
		}
		$background_insert_query  = "INSERT INTO background(backgroundID,status,background_name,parent,category,author,author_email,add_timestamp,release_date,background_description,thumbnail_filename,screenshot_filename,screenshot_description, download_start_timestamp) ";
		$background_insert_query .= "VALUES('','active','$background_name','$parentID','$category','$background_author','$author_email','$timestamp','$date','$background_description','$thumbnail_filename','$screenshot_filename','$screenshot_description', UNIX_TIMESTAMP())";
		//print($background_insert_query);
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
			print("Successfully added background to the database.\n<p>\nClick <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to add another.");
			if ($submitID)
			{
				$incoming_background_update_result = mysql_query("UPDATE incoming_background SET status='added' WHERE backgroundID='$mark_background'");
				print("<hr />Successfully marked background as added.<p><a href=\"" . $_SERVER["PHP_SELF"] . "\">Click here</a> to return to incoming backgrounds list.");
			}
		}
		else
		{	
			print("There were database errors adding background into database.  Contact Alex.");
		}
	}
	else
  	{
		print("Error, all of the form fields are not filled in.");
	}
}
else
{
	$date = date("m/d/Y");
	list($month,$day,$year) = explode("/",$date);
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td><select name=\"category\" value=\"$category\"><option value=\"gnome\">GNOME<option value=\"other\">Other</select></td></tr>\n");
	print("<tr><td><b>Background Author:</b></td><td><input type=\"text\" name=\"background_author\" size=\"40\" value=\"$author\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\" value=\"$author_email\"></td></tr>\n");
	print("<tr><td><b>Background Type</b></td><td><input type=\"radio\" name=\"background_type\" value=\"new\" checked>New Background<br><input type=\"radio\" name=\"background_type\" value=\"variation\">Variation of:\n<select name=\"parentID\">");
	$background_select_result = mysql_query("SELECT backgroundID,background_name FROM background");
	while(list($backID,$back_name)=mysql_fetch_row($background_select_result))
	{
		print("<option value=\"$backID\">$back_name\n");
	}
	print("</select></td></tr>\n");
	print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
	print("<tr><td><b>Background Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap>$background_description</textarea></td></tr>\n");
	print("<tr><td><b>Thumbnail Filename:</b></td><td>");file_chooser("thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/backgrounds");print("</td></tr>\n");
	print("<tr><td><b>Screenshot Filename:</b></td><td><input type=\"text\" name=\"screenshot_filename\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Screenshot Description:</b></td><td><textarea name=\"screenshot_description\" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>\n");
	print("</table>\n<p>\n");
	
	print("<table border=\"0\">\n");
	print("<tr><td><b>X</b></td><td><b>Type/Resolution</b></td><td><b>Filename</b></td></tr>\n");
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
	
	print("<input type=\"hidden\" name=\"submitID\" value=\"$backgroundID\">");
	print("<input type=\"submit\" value=\"Add Background\" name=\"add_background\">");
	print("</form>\n");
}
admin_footer();
?>
