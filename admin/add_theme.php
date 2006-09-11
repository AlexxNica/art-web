<?php

require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");
require('config.inc.php');

admin_header("Add a Theme");
admin_auth(2);

$submitID = validate_input_regexp_default ($_POST["submitID"], "^[0-9]+$", "0");

if($_POST["add_theme"])
{
	$userID = validate_input_regexp_default ($_POST["userID"], "^[0-9]+$", "0");

	// Parent hack...
	$parentID = validate_input_regexp_default ($_POST["parentID"], "^[0-9]+$", "0");
	if ($parentID == 0)
		$parentID = validate_input_regexp_default ($_POST["manualparentID"], "^[0-9]+$", "0");	// is set or returns 0

	$month = validate_input_regexp_default ($_POST["month"], "^[0-9]+$", "0");
	$day = validate_input_regexp_default ($_POST["day"], "^[0-9]+$", "0");
	$year = validate_input_regexp_default ($_POST["year"], "^[0-9]+$", "0");
	$theme_name = escape_string($_POST["theme_name"]);
	$category = validate_input_array_default($_POST["category"], array_keys($theme_config_array), "");
	if ($category == '')
		ago_fatal_error ('Invalid category');
	$description = escape_string($_POST["description"]);
	$download_filename = escape_string($_POST["download_filename"]);
	$uploaded_thumbnail = $_FILES['thumbnail_filename']['tmp_name'];
	$uploaded_preview = $_FILES['preview_filename']['tmp_name'];
	$license = escape_string($_POST["license"]);

	if($theme_name && $userID && $month && $day && $year && $description && $uploaded_thumbnail && $uploaded_preview && $download_filename )
	{
		$thumbnail_filename = "../images/thumbnails/$category/" . create_filename ($theme_name, $category, $_FILES['thumbnail_filename']['name'], '-Th');
		$preview_filename = "../images/thumbnails/$category/" . create_filename ($theme_name, $category, $_FILES['preview_filename']['name'], '-Shot');

		if (file_exists ($thumbnail_filename) || file_exists ($preview_filename))
		{
			print ("\t<p class=\"error\">One or more of the screenshot files already exist!</p>\n");
		}
		else
		{
			if (move_uploaded_file($uploaded_thumbnail, $thumbnail_filename)
				&& move_uploaded_file($uploaded_preview, $preview_filename))
			{
				/* Make sure someone other than the httpd user can access these ... */
				chmod ($thumbnail_filename, 0664);
				chmod ($preview_filename, 0664);

				/* Now we've uploaded the files, we don't need the path information in the database */
				$preview_filename = basename ($preview_filename);
				$thumbnail_filename = basename ($thumbnail_filename);

				$date = $year . "-" . $month . "-" . $day;
				$timestamp = time();
				$theme_insert_query  = "
					INSERT INTO theme(status,name,category,license,userID,parent,add_timestamp,release_date,version,description,thumbnail_filename,preview_filename, download_start_timestamp, download_filename)
					VALUES('active','$theme_name','$category','$license','$userID','$parentID','$timestamp','$date','$version','$description','$thumbnail_filename','$preview_filename', UNIX_TIMESTAMP(), '$download_filename')";
				$theme_insert_result = mysql_query($theme_insert_query);
				$themeID = mysql_insert_id();
				if($theme_insert_result)
				{
					print("\t<p class=\"info\">Successed added theme to the database.</p>\n");
					if($submitID)
					{
						$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='added' WHERE themeID='$submitID'");
						print("\t<p class=\"info\">Successfully marked submitted theme as added in incoming themes list.</p>\n");
					}
					print("\t<p><a href=\"/admin/show_submitted_themes.php\">Return</a> to incoming themes list.</p>\n");
					art_footer ();
					exit ();
				}
				else
				{
					print("\t<p class=\"error\">There were database errors adding theme into database.</p>\n\t<hr/>\n");
					print("\t<tt>".mysql_error()."</tt>\n\t<hr/>\n");
				}
			}
			else
				print ("\t<p class=\"error\">There was an error uploading the screenshots</p>\n\t<hr/>\n");
		}
	}
	else
	{
		print("\t<p class=\"error\">Error, all of the form fields are not filled in.</p>\n");
	}
}

$date = date("m/d/Y");

if ($submitID = POST ('submitID'))
{
	$theme_select_result = mysql_query("SELECT name,userID,category,license,description,version,depends,parentID FROM incoming_theme WHERE themeID=$submitID");
	list($theme_name,$userID,$theme_category,$license,$description,$version,$depends,$parentID) = mysql_fetch_row($theme_select_result);
}
else
	art_fatal_error ('Add Theme', 'Add Theme',"No theme selected to add!");

list($month,$day,$year) = explode("/",$date);

?>

	<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post"  enctype="multipart/form-data" >
		<table border="0">
			<tr>
				<td><strong>Theme Name:</strong></td>
				<td><input type="text" name="theme_name" size="40" value="<?php echo $theme_name; ?>"></td>
			</tr>
			<tr>
				<td><strong>Category</strong></td>
				<td>
					<input type="hidden" name="category" value="<?php echo $theme_category; ?>" />
					<?php echo $theme_config_array[$theme_category]['name'].' ('.$theme_category; ?>)
				</td>
			</tr>
			<tr>
				<td><strong>UserID:</strong></td>
				<td><input type="text" name="userID" size="40" value="<?php echo $userID; ?>"></td>
			</tr>
			<tr>
				<td><strong>Release Date:</strong></td>
				<td>
					<input type="text" name="month" value="<?php echo $month; ?>" size="2" maxlenght="2">/
					<input type="text" name="day" value="<?php echo $day; ?>" size="2" maxlenght="2">/
					<input type="text" name="year" value="<?php echo $year; ?>" size="4" maxlenght="4">
				</td>
			</tr>
			<tr>
				<td><strong>License</strong></td>
				<td><?php print_select_box("license",$license_config_array, $license); ?></td>
			</tr>
			<tr>
				<td><strong>Version:</strong></td>
				<td><input type="text" name="version" size="40" value="<?php echo $version; ?>"></td>
			</tr>
			<tr>
				<td><strong>Depends:</strong></td>
				<td><input type="text" name="depends" size="40" value="<?php echo $depends; ?>"></td>
			</tr>

			<tr>
				<td><strong>Variation of</strong></td>
				<td>
					<select name="parentID">
						<option value="0">N/A</option>
<?php
	$background_select_result = mysql_query("SELECT themeID,name,category FROM theme WHERE userID=$userID ORDER BY category ASC, name ASC");
	while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($background_select_result))
	{
		if ($var_themeID == $parentID)
			$selected = 'selected="true"';
		else
			$selected = '';
		print("\t\t\t\t\t\t<option $selected value=\"$var_themeID\">$var_theme_name ($var_category)</option>\n");
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td><br /></td>
				<td>
					(or <label for=\"manualparentID\">set parent manually</label>: 
					<input type=\"text\" id=\"manualparentID\" name=\"manualparentID\" size=\"3\" maxlength=\"6\" />)
				</td>
			<tr>
				<td><strong>Description:</strong></td>
				<td><textarea name="description" cols="40" rows="5" wrap><?php echo $description; ?></textarea></td>
			</tr>
			<tr>
				<td><strong>Large Preview Filename:</strong></td>
				<td><input type="file" name="preview_filename" /></td>
			</tr>
			<tr>
				<td><strong>Small Thumbnail Filename:</strong></td>
				<td><input type="file" name="thumbnail_filename" /></td>
			</tr>
			<tr>
				<td><strong>Download Filename:</strong></td>
				<td>
<?php
	if (isset($theme_category))
		file_chooser("download_filename", "$sys_ftp_dir/themes/$theme_category/");
	else
		print ("\t\t\t\t\t<p class=\"error\">Error: No theme category set!</p>\n");
?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="submitID" value="<?php echo $submitID;?>">
		<input type="submit" value="Add Theme" name="add_theme" />
	</form>
<?php
admin_footer();
?>
