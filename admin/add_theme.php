<?php

require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a Theme");
admin_auth(2);

$submitID = validate_input_regexp_default ($_POST["submitID"], "^[0-9]+$", "0");

if($_POST["add_theme"])
{
	$userID = validate_input_regexp_default ($_POST["userID"], "^[0-9]+$", "0");
	$parentID = validate_input_regexp_default ($_POST["parentID"], "^[0-9]+$", "0");
	$month = validate_input_regexp_default ($_POST["month"], "^[0-9]+$", "0");
	$day = validate_input_regexp_default ($_POST["day"], "^[0-9]+$", "0");
	$year = validate_input_regexp_default ($_POST["year"], "^[0-9]+$", "0");
	$theme_name = escape_string($_POST["theme_name"]);
	$category = validate_input_array_default($_POST["category"], array_keys($theme_config_array), "");
	$description = escape_string($_POST["description"]);
	$thumbnail_filename = escape_string($_POST["thumbnail_filename"]);
	$small_thumbnail_filename = escape_string($_POST["small_thumbnail_filename"]);
	$download_filename = escape_string($_POST["download_filename"]);
	$license = escape_string($_POST["license"]);

	if($theme_name && $userID && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename && $download_filename )
	{
		$date = $year . "-" . $month . "-" . $day;
		$timestamp = time();
		$theme_insert_query  = "INSERT INTO theme(themeID,status,theme_name,category,license,userID,parent,add_timestamp,release_date,version,description,thumbnail_filename,small_thumbnail_filename, download_start_timestamp, download_filename) ";
		$theme_insert_query .= "VALUES('','active','$theme_name','$category','$license','$userID','$parentID','$timestamp','$date','$version','$description','$thumbnail_filename','$small_thumbnail_filename', UNIX_TIMESTAMP(), '$download_filename')";
		$theme_insert_result = mysql_query($theme_insert_query);
		$themeID = mysql_insert_id();
		if($theme_insert_result)
		{
			print("Successed added theme to the database.<br/>");
			if($submitID)
			{
				$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='added' WHERE themeID='$submitID'");
				print("Successfully marked submitted theme as added in incoming themes list.");
			}
			print("<p><a href=\"/admin/show_submitted_themes.php\">Return</a> to incoming themes list.</p>");
		}
		else
		{
			print("<p>There were database errors adding theme into database.</p>");
			print("<tt>".mysql_error()."</tt>");
		}
	}
	else
	{
		print("Error, all of the form fields are not filled in.");
	}
}
else
{
	$category_array = array(
	"gdm_greeter" => "gdm_greeter",
	"gtk" => "gtk",
	"gtk2" => "gtk2",
	"icon" => "icon",
	"metacity" => "metacity",
	"sounds" => "sounds",
	"splash_screens" => "splash_screens",
	"other" => "other",
	"gtk_engines" => "gtk_engines",
	"desktop" => "desktop"
	);
	$date = date("m/d/Y");

	if (array_key_exists("submitID", $_POST))
	{
		$theme_select_result = mysql_query("SELECT theme_name,userID,category,license,theme_description,version,depends,parentID FROM incoming_theme WHERE themeID=$submitID");
		list($theme_name,$userID,$theme_category,$license,$description,$version,$depends,$parentID) = mysql_fetch_row($theme_select_result);
	}

	list($month,$day,$year) = explode("/",$date);
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\" value=\"$theme_name\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td>");
	create_select_box("category",$category_array,$theme_category);
	print("</td></tr>\n");
	print("<tr><td><b>UserID:</b></td><td><input type=\"text\" name=\"userID\" size=\"40\" value=\"$userID\"></td></tr>\n");
	print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
	print("<tr><td><b>License</b></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
	print("<tr><td><b>Version:</b></td><td><input type=\"text\" name=\"version\" size=\"40\" value=\"$version\"></td></tr>\n");
	print("<tr><td><b>Depends:</b></td><td><input type=\"text\" name=\"depends\" size=\"40\" value=\"$depends\"></td></tr>\n");

	print("<tr><td><b>Variation of</b></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>");

	$background_select_result = mysql_query("SELECT themeID,theme_name,category FROM theme WHERE userID=$userID ORDER BY category");
	while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($background_select_result))
	{
		if ($var_themeID == $parentID)
			$selected = "selected=\"true\"";
		else
			$selected = "";
		print("<option $selected value=\"$var_themeID\">$var_theme_name ($var_category)</option>");
	}
	print("</td></tr>");

	print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");

	print("<tr><td><b>Thumbnail Filename:</b></td><td>");
	if (isset($theme_category)) file_chooser("thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/$theme_category/");
	else print("<input type=\"text\" name=\"thumbnail_filename\" size=\"40\">");
	print("</td></tr>\n");

	print("<tr><td><b>Small Thumbnail Filename:</b></td><td>");
	if (isset($theme_category)) file_chooser("small_thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/$theme_category/");
	else print("<input type=\"text\" name=\"small_thumbnail_filename\" size=\"40\">");
	print("</td></tr>\n");

	print("<tr><td><b>Download Filename:</b></td><td>");
	if (isset($theme_category)) file_chooser("download_filename", "$sys_theme_dir/$theme_category/"); 
	else print("<input type=\"text\" name=\"download_filename\" size=\"40\">");
	print("</td></tr>\n");

	print("</table>\n");

	print("<input type=\"hidden\" name=\"submitID\" value=\"$submitID\">\n");
	print("<input type=\"submit\" value=\"Add Theme\" name=\"add_theme\" />\n");
	print("</form>\n");
}
admin_footer();
?>
