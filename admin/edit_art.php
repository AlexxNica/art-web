<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

$types = array('theme' => $theme_config_array, 'background' => $background_config_array, 'contest' => $contest_config_array, 'screenshot' => $screenshot_config_array);

$type = FALSE;
$type_name = 'Invalid';
/* extract the type of the artwork */
if (array_key_exists('type', $_REQUEST))
{
	$type = validate_input_array_default($_REQUEST['type'], array_keys($types), FALSE);
	$type_name = ucfirst($type);
	if ($type == 'contest')
		$item_name = 'Contest Item';
	else
		$item_name = $type_name;
}

if ($type !== FALSE)
{
	admin_header("Edit ". $item_name);
	$config_array = $types[$type];
}
else
{
	admin_header("Choose a Type");
}
admin_auth(2);


function input_error($field, $messing = FALSE)
{
	global $error;
	$error = TRUE;
	
	print('<p class="error">'.$field.' was not filed in correctly.</p>');
	if ($messing)
		print('<p class="info">This should under no circumstances happen (except you have been messing with the POST vars.).</p>');
}
array_shift ($resolution_array); /* Remove 'All' from resolution list */
	


/* Four different modes:
 *  - list of categories + id input field.
 *  - chooser for a specific category.
 *  - edit page for the artwork.
 *  - post the changes. 
 * (- show a list of types) */

$action = 'init';
if ($type != FALSE && array_key_exists('ID', $_POST) && array_key_exists('action', $_POST) && $_POST['action'] == 'delete')
{
	$ID = $_POST['ID'];
	$action = 'delete';
}
elseif ($type != FALSE && array_key_exists('ID', $_POST) && array_key_exists('add_resolution', $_POST))
{
	$ID = $_POST['ID'];
	$action = 'add_resolution';
}
elseif ($type != FALSE && array_key_exists('ID', $_POST) && array_key_exists('action', $_POST) && $_POST['action'] == 'write')
{
	$ID = $_POST['ID'];
	$action = 'write';
}
elseif ($type != FALSE && array_key_exists('category', $_REQUEST) && array_key_exists($_REQUEST['category'], $config_array))
{
	$action = 'choose_category';
	$category = $_REQUEST['category'];
}
elseif ($type != FALSE && array_key_exists('ID', $_REQUEST))
{
	$ID = intval($_REQUEST['ID']);
	$action = 'edit';
}

if($action == "init")
{
	if ($type == FALSE)
	{
		/* print a list of all types. */
		print('<ul>');
		foreach ($types as $type => $value)
			print('<li><a href="'. $_SERVER['PHP_SELF'].'?type='.$type.'">Edit a '. ucfirst($type).'</a></li>');
		print('</ul>');
	}
	else
	{
		create_title('Choose a '.$type_name.' Category');
		print('<ul>');
		foreach ($config_array as $category => $value)
			print('<li><a href="'. $_SERVER['self'].'?type='.$type.'&category='.$category.'">'.$value['name'].'</a></li>');
		print('</ul>');
		create_title('Enter ID');
		print('<form action="'.$_SERVER['PHP_SELF'].'" method="get"><p>');
		print('<label for="ID">ID</label>: <input type="text" name="ID" id="ID" />');
		print('<input type="hidden" name="action" value="edit" />');
		print('<input type="hidden" name="type" value="'.$type.'" />');
		print('<input type="submit" value="edit" />');
		print('</p></form>');
	}
}
elseif ($action == 'choose_category')
{
	$sql = "SELECT {$type}ID AS ID, name FROM $type WHERE category=\"$category\" ORDER BY {$type}ID";
	
	$result = mysql_query($sql);
	if ($result && mysql_num_rows($result) > 0)
	{
		create_title('Choose a '. $item_name.' from the Category "'. $config_array[$category]['name'].'".');
		print('<form action="'.$_SERVER['PHP_SELF'].'" method="get">');
		print('<select name="ID" size="24" id="ID">');
		
		while ($row = mysql_fetch_assoc($result))
		{
			print('<option value="'. $row['ID']. '">'. $row['ID']. ': '. html_parse_text($row['name']). '</option>');
		}
		
		print('</select><br /><input type="submit" value="Edit" /><input type="hidden" name="action" value="edit" />');
		print('<input type="hidden" name="type" value="'. $type. '" /></form>');
	}
	else
	{
		if ($result)
			print('<p class="info">No art has been submitted to this category</p>');
		else
			print('<p class="error">SQL query to retrieve a list of artwork from the category "'. $category .'" failed.</p>');
	}
}
elseif ($action == 'edit')
{
	$sql = "SELECT * FROM $type WHERE {$type}ID=$ID";

	$result = mysql_query($sql);
	if ($result && mysql_num_rows($result) == 1)
	{
		$row = mysql_fetch_assoc($result);
		
		print("\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
		print("\t\t<input type=\"hidden\" name=\"originalname\" value=\"".htmlentities($row['name'])."\" />\n");
		print("\t\t<table style=\"border: none;\">\n");
		print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"name\">".$item_name." Name</label>:</strong></td>\n\t\t\t\t<td><input type=\"text\" name=\"name\" id=\"name\" size=\"40\" value=\"".htmlentities($row['name'])."\" /></td>\n\t\t\t</tr>\n");
		print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"category\">".$item_name." Category</label>:</strong></td>\n\t\t\t\t<td>\n\t\t\t\t\t<select name=\"category\" id=\"category\">\n");
		foreach ($config_array as $cat => $value)
		{
			print("\t\t\t\t\t\t<option value=\"".$cat."\"");
			if ($cat == $row['category'])
				print(' selected="true"');
			print(">".$value['name']."</option>\n");
		}
		print("\t\t\t\t\t</select>\n\t\t\t\t</td>\n\t\t\t</tr>\n");
		print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"userID\">UserID</label>:</strong></td>\n\t\t\t\t<td><input id=\"userID\" name=\"userID\" value=\"".$row['userID']."\"></td>\n\t\t\t</tr>\n");
		if ($type == 'theme' || $type == 'background')
		{
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"license\">License</label>:</strong></td>\n\t\t\t\t<td>\n\t\t\t\t");
			print_select_box('license',array_merge($license_config_array, array('' => 'Archived - unknown license')), $row['license']);
			print("\n\t\t\t\t</td>\n\t\t\t</tr>\n");
		}
		if ($type == 'theme' || $type == 'background' || $type == 'contest')
		{
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"version\">Version</label></strong></td>\n\t\t\t\t<td><input type=\"text\" name=\"version\" id=\"version\" value=\"".$row['version']."\" /></td>\n\t\t\t</tr>\n");
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"variation\">Variation of</label> </strong></td>\n\t\t\t\t<td>\n\t\t\t\t\t<select name=\"parentID\" id=\"variation\">\n\t\t\t\t\t\t<option value=\"0\">N/A</option>\n");
			
			$result = mysql_query("SELECT {$type}ID,name,category FROM $type WHERE userID=".$row['userID']." AND parent=0 AND {$type}ID!=$ID ORDER BY category ASC, name ASC");
			$is_in_selection = false;
			while($sub_row = mysql_fetch_assoc($result))
			{
				print("\t\t\t\t\t\t<option value=\"".$sub_row[$type.'ID']."\"");
				if ($sub_row[$type.'ID'] == $row['parent'])
				{
					print(' selected="true"');
					$is_in_selection = true;
				}
				print(">".html_parse_text($sub_row['name'])." (".$sub_row['category'].")</option>\n");
			}
			print("\t\t\t\t\t</select>\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t\t<tr>\n\t\t\t\t<td><br /></td>\n\t\t\t\t<td>(or <label for=\"manualparentID\">set parent manually</label>: <input type=\"text\" id=\"manualparentID\" name=\"manualparentID\" size=\"3\" maxlength=\"6\"");
			if ((!$is_in_selection) and ($row['parent'] != 0)) print(" value=\"".$row['parent']."\"");
			print(" />)</td>\n\t\t\t</tr>\n");

			list($year,$month,$day) = explode("-",$row['release_date']);
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"month\">Release Date</label>:</strong></td>\n");
			print("\t\t\t\t<td>\n\t\t\t\t\t<input type=\"text\" id=\"month\" name=\"month\" value=\"".$month."\" size=\"2\" maxlength=\"2\" />/\n");
			print("\t\t\t\t\t<input type=\"text\" name=\"day\" value=\"".$day."\" size=\"2\" maxlength=\"2\" />/\n");
			print("\t\t\t\t\t<input type=\"text\" name=\"year\" value=\"".$year."\" size=\"4\" maxlength=\"4\" />\n\t\t\t\t</td>\n\t\t\t</tr>\n");
		}
		print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"description\">Description</label>:</strong></td>\n\t\t\t\t<td>\n\t\t\t\t\t<textarea name=\"description\" id=\"description\" cols=\"40\" rows=\"5\" style=\"white-space: pre;\">".htmlentities($row['description'])."</textarea>\n\t\t\t\t</td>\n\t\t\t</tr>\n");
		
		if ($type == 'theme')
		{
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"preview_filename\">Preview Filename</label>:</strong></td>\n\t\t\t\t<td><input type=\"text\" name=\"preview_filename\" id=\"preview_filename\" size=\"40\" value=\"".$row['preview_filename']."\" /></td>\n\t\t\t</tr>\n");
		}
		
		print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"thumbnail_filename\">Thumbnail Filename</label>:</strong></td>\n\t\t\t\t<td><input type=\"text\" name=\"thumbnail_filename\" id=\"thumbnail_filename\" size=\"40\" value=\"".$row['thumbnail_filename']."\" /></td>\n\t\t\t</tr>\n");
		if ($type != 'background')
		{
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"download_filename\">Download Filename</label>:</strong></td><td><input type=\"text\" name=\"download_filename\" id=\"download_filename\" size=\"40\" value=\"".$row['download_filename']."\" /></td>\n\t\t\t</tr>\n");
		}
		else
		{
			$background_resolution_result = mysql_query("SELECT background_resolutionID,resolution,filename,type FROM background_resolution WHERE backgroundID=$ID ORDER BY type ASC, resolution ASC");
			$i = 0;
			while($resolution_row = mysql_fetch_assoc($background_resolution_result)) {
				print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"resolution[".$i."]\">Resolution #".$i."</label>:</strong></td>\n");
				print("\t\t\t\t<td>\n\t\t\t\t");print_select_box("resolution[$i]", array_merge($resolution_array, array('delete' => 'Delete')), $resolution_row['resolution']);
				print("&nbsp;\n\t\t\t\t");print_select_box("resolution_type[$i]", array_combine($background_image_types, $background_image_types), $resolution_row['type']);
				print("&nbsp;\n\t\t\t\t<input type=\"text\" name=\"filename[".$i."]\" size=\"40\" value=\"".$resolution_row['filename']."\" />\n");
				print("\t\t\t\t<input type=\"hidden\" name=\"background_resolutionID[".$i."]\" value=\"".$resolution_row['background_resolutionID']."\" />\n\t\t\t\t</td>\n\t\t\t</tr>\n");
				$i++;
			}
			
			print("\t\t\t<tr>\n\t\t\t\t<td><strong><label for=\"new_res_resolution\">Add resolution</label>:</strong></td>\n\t\t\t\t<td>\n\t\t\t\t");
			print_select_box("new_res_resolution", $resolution_array, '');
			print("&nbsp;\n\t\t\t\t");
			print_select_box("new_res_type", array_combine($background_image_types, $background_image_types), '');
			print("&nbsp;\n\t\t\t\t");
			print("<input type=\"text\" name=\"new_res_file\"/>\n\t\t\t\t<input type=\"submit\" name=\"add_resolution\" value=\"Add Resolution\"/>\n");
			print("\t\t\t\t</td>\n\t\t\t</tr>\n");
		}
		
		print("\t\t\t<tr>\n\t\t\t\t<td>\n\t\t\t\t\t<input type=\"checkbox\" name=\"update_timestamp_toggle\" id=\"update_timestamp_toggle\" /> <label for=\"update_timestamp_toggle\">Update Timestamp</label>\n");
		print("\t\t\t\t\t<input type=\"hidden\" name=\"action\" value=\"write\" />\n");
		print("\t\t\t\t\t<input type=\"hidden\" name=\"type\" value=\"".$type."\" />\n");
		print("\t\t\t\t\t<input type=\"hidden\" name=\"ID\" value=\"".$ID."\" />\n");
		print("\t\t\t\t</td>\n\t\t\t</tr>\n");
		
		print("\t\t\t<tr>\n\t\t\t\t<td><input type=\"submit\" value=\"Update ".$item_name."\" /></td>\n\t\t\t</tr>\n");
		print("\t\t</table>\n");
		print("\t</form>\n");
		
		/* delete form */
		print("\t<hr/>\n");
		print("\t<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n");
		print("\t\t<input type=\"hidden\" name=\"action\" value=\"delete\" />\n");
		print("\t\t<input type=\"hidden\" name=\"type\" value=\"".$type."\" />\n");
		print("\t\t<input type=\"hidden\" name=\"ID\" value=\"".$ID."\" />\n");
		print("\t\t<input type=\"checkbox\" name=\"sure\" id=\"sure\" /> <label for=\"sure\">Check to delete this work.</label><br/>\n");
		print("\t\t<input type=\"submit\" value=\"Delete ".$item_name."\" />\n");
		print("\t</form>");
	}
	else
	{
		if (!$result)
			print("\t<p class=\"error\">SQL query was not successfull</p>\n");
		elseif (mysql_num_rows($result) == 0)
			print("\t<p class=\"info\">No such ".$item_name.".\n");
		else
			print("<p class=\"error\">Something just went really, really wrong. There are ".mysql_num_rows($result)." Items with the same ID!</p>\n");
	}
}
elseif ($action == 'add_resolution')
{
	$error = FALSE;
	if (!ereg('^[0-9]+$', $ID)) input_error('ID', TRUE);
	
	if (!in_array($_POST['new_res_type'], $background_image_types)) input_error('File Type', TRUE);
	$new_res_type = $_POST['new_res_type'];
	
	if (!array_key_exists($_POST['new_res_resolution'], $resolution_array)) input_error('Resolution', TRUE);
	$resolution = $_POST['new_res_resolution'];
	
	$filename = escape_string ($_POST['new_res_file']);
	
	if ($error === FALSE)
	{
		$check_sql = 'SELECT count(background_resolutionID) FROM background_resolution WHERE backgroundID='.$ID.' AND type=\''.$new_res_type.'\' AND resolution=\''.$resolution.'\'';
		$result = mysql_query($check_sql);
		
		if ($result)
		{
			list($count) = mysql_fetch_row($result);
			$name_sql = 'SELECT name FROM background WHERE backgroundID='.$ID;
			$result = mysql_query($name_sql);
			if ($result && mysql_num_rows($result) == 1)
				list($background_name) = mysql_fetch_row ($result);
			else
				$result = FALSE;
		}
			
		if ($result)
		{
			if ($count > 0)
			{
				print('<p class="error">A '.$resolution.', '.$type.' resolution for '.$background_name.' already exists</p>');
			}
			else
			{
				$result = mysql_query ("INSERT INTO background_resolution(backgroundID, type, resolution, filename) VALUES ('$ID', '$new_res_type', '$resolution', '$filename')");
				if ($result)
					print('<p class="info">Added new resolution ('.$resolution.', '.$type.') to background '.$background_name.'</p>');
				else
					print('<p class="error">There was an error adding the new resolution</p>');
			}
		}
		else
		{
			print('<p class="error">Something went wrong ...</p>');
		}
		
		print('<p><a href="/admin/edit_art.php?action=edit&type=background&ID='.$ID.'">Continue editing "'.$background_name.'"</a></p>');
	}
	else
	{
		print('<p class="error">Invalid input ...</p>');
	}
}
elseif ($action == 'write')
{
	$error = FALSE;

	if (!ereg('^[0-9]+$', $ID)) input_error('ID', TRUE);
	$sql  = 'UPDATE '. $type. ' SET ';
	/* check that name is unique?? */
	if ($_POST['name'] != $_POST['originalname'])
	{
		$result = mysql_result(mysql_query("SELECT COUNT(*) FROM $type WHERE name='".escape_string($_POST['name'])."' LIMIT 1"), 0);
	}
	else
	{
		$result = 0;
	}
	if ($result == 0)
	{
		$sql .= 'name="'. escape_string($_POST['name']). '", ';
	}
	else
	{
		input_error('Item name ('.escape_string($_POST['name']).') already used. Name field', FALSE);
	}
	
	if (!array_key_exists($_POST['category'], $config_array)) input_error('Category', TRUE);
	$sql .= "category='". $_POST['category']. "', ";
	if (!ereg('^[0-9]+$', $_POST['userID'])) input_error('userID');
	$sql .= "userID=". $_POST['userID']. ', ';
	if ($type == 'theme' || $type == 'background')
	{
		if ($_POST['license'] != '' && !array_key_exists($_POST['license'], $license_config_array)) input_error('License', TRUE);
		$sql .= "license='".$_POST['license']."', ";
	}
	if ($type == 'theme' || $type == 'contest' || $type == 'background')
	{
		if (!ereg('^[0-9\.]*$', $_POST['version'])) input_error('Version');
		$sql .= "version='". $_POST['version']. "', ";
		if (!ereg('^[0-9]+$', $_POST['parentID'])) 
		{
			input_error('Variation of', TRUE);
		}
		else
		{
			if ($_POST['parentID'] != 0)
			{
				$sql .= 'parent='. $_POST['parentID'].', ';
			}
			else
			{
				if ((!ereg('^[0-9]+$', $_POST['manualparentID'])) and ($_POST['manualparentID']))
				{
					input_error('The field "Manual variation"');
				}
				else
				{
					if ($_POST['manualparentID'] != 0)
					{
						$result = mysql_result(mysql_query("SELECT COUNT(*) FROM $type WHERE parent=0 AND {$type}ID=".$_POST['manualparentID']." LIMIT 1"), 0);
						if (($result != 0) and ($_POST['manualparentID'] != $ID))
						{
							$sql .= 'parent='. $_POST['manualparentID'].', ';
						}
						else
						{
							input_error('The choosen item ('.$_POST['manualparentID'].') does not exist or is a variation itself. The field "Manual variation"');
						}
					}
					else
					{
						$sql .= 'parent=0, ';
					}
				}
			}
		}
		
		if (!ereg('^[0-9]+$', $_POST['day']) || !ereg('^[0-9]+$', $_POST['month']) || !ereg('^[0-9]+$', $_POST['year'])) input_error('Release Date');
		$sql .= 'release_date=\''.$_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'].'\', ';
	}
	
	$sql .= "description='". escape_string($_POST['description']). "', ";
	
	if ($type == 'theme')
		$sql .= "preview_filename='".escape_string($_POST['preview_filename'])."', ";
	
	if ($_POST['update_timestamp_toggle'] == 'on')
		$sql .= 'add_timestamp=UNIX_TIMESTAMP(), ';
	
	if ($type != 'background')
	{
		$sql .= "thumbnail_filename='".escape_string($_POST['thumbnail_filename'])."', ";
		$sql .= "download_filename='".escape_string($_POST['download_filename'])."' ";
	}
	else
	{
		$sql .= "thumbnail_filename='".escape_string($_POST['thumbnail_filename'])."' ";
		
		/* need to update the resolutions */
		$background_resolutionID =$_POST['background_resolutionID'];
		$resolution = $_POST['resolution'];
		$filename = $_POST['filename'];
		$resolution_type = $_POST['resolution_type'];
		escape_gpc_array($background_resolutionID);
		
		foreach ($background_resolutionID as $key => $resolutionID) {
			if ($resolution[$key] != 'delete') {
				if (!array_key_exists($resolution[$key], $resolution_array)) input_error('Resolution', TRUE);
				if (!in_array($resolution_type[$key], $background_image_types)) input_error('Type', TRUE);
				
				if (!$error)
					$result = mysql_query("UPDATE background_resolution SET resolution='{$resolution[$key]}',filename='{$filename[$key]}',type='{$resolution_type[$key]}' WHERE background_resolutionID=$resolutionID");
				else
					$result = TRUE;
			} else {
				if (!$error)
					$result = mysql_query("DELETE FROM background_resolution WHERE background_resolutionID=$resolutionID");
				else
					$result = TRUE;
			}
			if (!$result) {
				print('<p class="error">There was an error updating/deleting a resolution. This should never happen.</p>');
				$error = TRUE;
			}
		}
		
	}
	
	$sql .= "WHERE {$type}ID=$ID ";
	
	if ($error !== TRUE)
	{
		$result = mysql_query($sql);
		
		if ($result)
		{
			print('<p class="info">'.$item_name.' updated.</p>');
			print('<p><ul>');
			print('<li><a href="/admin/">Return to admin panel</a></li>');
			print('<li><a href="'.$_SERVER["PHP_SELF"].'?action=edit&type='.$type.'">Return to edit another '.$item_name.'</a></li>');
			print('<li><a href="'.$_SERVER["PHP_SELF"].'?action=edit&type='.$type.'&ID='.$ID.'">Return to edit this '.$item_name.'</a></li>');
			print('</ul></p>');
		}
		else
			print('<p class="error">Something went wrong :\'(. (SQL executed was: "'.$sql.'")</p>');
	}
	else
	{
		print('Please fix the above errors, and then submit again. <a href="'.$_SERVER["PHP_SELF"].'?action=edit&amp;type='.escape_string($type).'&ID='.escape_string($ID).'">Click here if you want to go back.</a>');
	}

}
elseif ($action == 'delete')
{
	if (array_key_exists('sure', $_POST) && $_POST['sure'])
	{
		/* XXX: should we only mark things as deleted? */
		$ID = validate_input_regexp_error ($ID, '^[0-9]+$');
		$sql = "DELETE FROM $type WHERE {$type}ID=$ID LIMIT 1";
		$result = mysql_query($sql);

		if ($result)
		{
			$result_votes = mysql_query('DELETE FROM vote WHERE artID='.$ID.' AND type=\''.$type."'");
			if ($result_votes)
			{
				$result_comments = mysql_query('DELETE FROM comment WHERE artID='.$ID.' AND type=\''.$type."'");
				if ($result_comments)
				{
					if ($type == 'background')
					{
						$result = mysql_query("DELETE FROM background_resolution WHERE backgroundID=$ID");
						if ($result)
							print("\t<p class=\"info\">Item deleted.</p>\n");
						else
							print("\t<p class=\"error\">Error deleting the resolution (background itself is deleted).</p>\n");
					}
					else
						print("\t<p class=\"info\">Item deleted.</p>\n");
					print("\t<p><ul>\n");
					print("\t\t<li><a href=\"/admin/\">Return to admin panel</a></li>\n");
					print("\t\t<li><a href=\"".$_SERVER["PHP_SELF"]."?action=edit&type=".$type."\">Return to edit another ".$item_name."</a></li>\n");
					print("\t</ul></p>\n");
				}
				else print("\t<p class=\"error\">Error deleting item comments (item itself is deleted).</p>\n");
			}
			else
				print("\t<p class=\"error\">Error deleting item votes (item itself is deleted).</p>\n");
		}
		else
			print("\t<p class=\"error\">Error deleting item.</p>\n");

	}
	else
		print("\t<p class=\"info\">You need to check the checkbox.</p>\n");
}
else
	print("\t<p class=\"error\">You just did the impossible :-).</p>\n");

admin_footer();
?>
