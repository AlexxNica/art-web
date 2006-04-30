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
}

if ($type !== FALSE)
{
	admin_header("Edit ". $type_name);
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
		create_title('Choose a Category');
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
		create_title('Choose a '. $type_name.' from the Category "'. $config_array[$category]['name'].'".');
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
		
		print('<form action="'. $_SERVER["PHP_SELF"]. '" method="post">');
		print('<table border="0">');
		print('<tr><td><strong><label for="name">'. $type_name. ' Name</label>:</strong></td><td><input type="text" name="name" id="name" size="40" value="'. htmlentities($row['name']). '" /></td></tr>');
		print('<tr><td><strong><label for="category">'. $type_name.' Category</label>:</strong></td><td><select name="category" id="category">');
		foreach ($config_array as $cat => $value)
		{
			print('<option value="'. $cat. '"');
			if ($cat == $row['category'])
				print(' selected="true"');
			print('>'. $value['name']. "</option>\n");
		}
		
		print('<tr><td><strong><label for="userID">UserID</label>:</strong></td><td><input id="userID" name="userID" value="'. $row['userID']. '"</td></tr>');
		if ($type == 'theme' || $type == 'background')
		{
			print('<tr><td><strong><label for="license">License</label>:</strong></td><td>');
			print_select_box('license',array_merge($license_config_array, array('' => 'Archived - unknown license')), $row['license']);
			print('</td></tr>');
		}
		if ($type == 'theme' || $type == 'background' || $type == 'contest')
		{
			print('<tr><td><strong><label for="version">Version</label></strong></td><td><input type="text" name="version" id="version" value="'. $row['version']. '" /></td></tr>');
			print('<tr><td><strong><label for="variation">Variation of</label> </strong></td><td><select name="parentID" id="variation"><option value="0">N/A</option>');
			
			$result = mysql_query("SELECT themeID,name,category FROM theme WHERE userID=$userID AND parent=0 ORDER BY category");
			while($sub_row = mysql_fetch_assoc($result))
			{
				print('<option value="'. $sub_row['themeID']. '"');
				if ($sub_row['themeID'] == $row['parent'])
					print(' selected="true"');
				print('>'. html_parse_text($sub_row['theme_name']).' ('.$sub_row['category']. ')</option>');
			}
			print('</select></td></tr>');

			list($year,$month,$day) = explode("-",$row['release_date']);
			print('<tr><td><strong><label for="month">Release Date</label>:</strong></td>');
			print('<td><input type="text" id="month" name="month" value="'. $month. '" size="2" maxlength="2" />/');
			print('<input type="text" name="day" value="'. $day. '" size="2" maxlength="2" />/');
			print('<input type="text" name="year" value="'. $year. '" size="4" maxlength="4" /></td></tr>');
		}
		print('<tr><td><strong><label for="description">Description</label>:</strong></td><td><textarea name="description" id="description" cols="40" rows="5" wrap>'. htmlentities($row['description']). '</textarea></td></tr>');
		
		if ($type == 'theme')
		{
			print('<tr><td><strong><label for="preview_filename">Preview Filename</label>:</strong></td><td><input type="text" name="preview_filename" id="preview_filename" size="40" value="'. $row['preview_filename']. '" /></td></tr>');
		}
		
		print('<tr><td><strong><label for="thumbnail_filename">Thumbnail Filename</label>:</strong></td><td><input type="text" name="thumbnail_filename" id="thumbnail_filename" size="40" value="'. $row['thumbnail_filename']. '" /></td></tr>');
		if ($type != 'background')
		{
			print('<tr><td><strong><label for="download_filename">Download Filename</label>:</strong></td><td><input type="text" name="download_filename" id="download_filename" size="40" value="'. $row['download_filename']. '" /></td></tr>');
		}
		else
		{
			$background_resolution_result = mysql_query("SELECT background_resolutionID,resolution,filename,type FROM background_resolution WHERE backgroundID=$ID");
			$i = 0;
			while($resolution_row = mysql_fetch_assoc($background_resolution_result)) {
				print('<tr><td><strong><label for="resolution['.$i.']">Resolution #'.$i.'</label>:</strong></td>');
				print('<td>');print_select_box("resolution[$i]", array_merge($resolution_array, array('delete' => 'Delete')), $resolution_row['resolution']);
				print('&nbsp;');print_select_box("resolution_type[$i]", array_combine($background_image_types, $background_image_types), $resolution_row['type']);
				print('&nbsp;<input type="text" name="filename['.$i.']" size="40" value="'.$resolution_row['filename'].'" />');
				print('<input type="hidden" name="background_resolutionID['.$i.']" value="'.$resolution_row['background_resolutionID']."\" /></td></tr>\n");
				$i++;
			}
			
			print('<tr><td><strong><label for="new_res_resolution">Add resolution</lable>:</strong></td><td>');
			print_select_box("new_res_resolution", $resolution_array, '');
			print('&nbsp;');
			print_select_box("new_res_type", array_combine($background_image_types, $background_image_types), '');
			print('&nbsp;');
			print('<input type="text" name="new_res_file"/><input type="submit" name="add_resolution" value="Add Resolution" />');
			print('</td></tr>');
		}
		
		print('<tr><td><input type="checkbox" name="update_timestamp_toggle" id="update_timestamp_toggle" /><label for="update_timestamp_toggle">Update Timestamp</label>');
		print('<input type="hidden" name="action" value="write" />');
		print('<input type="hidden" name="type" value="'. $type. '" />');
		print('<input type="hidden" name="ID" value="'.$ID.'" />');
		print('</td></tr>');
		
		print('<tr><td><input type="submit" value="Update '. $type_name. '" /></td></tr>');
		print('</table>');
		print('</form>');
		
		/* delete form */
		print('<hr/>');
		print('<form action="'. $_SERVER["PHP_SELF"]. '" method="post">');
		print('<input type="hidden" name="action" value="delete" />');
		print('<input type="hidden" name="type" value="'. $type. '" />');
		print('<input type="hidden" name="ID" value="'.$ID.'" />');
		print('<input type="checkbox" name="sure" id="sure" /><label for="sure">Check to delete this work.</label><br/>');
		print('<input type="submit" value="Delete '. $type_name. '" />');
		print('</table>');
		print('</form>');
	}
	else
	{
		if (!$result)
			print('<p class="error">SQL query was not successfull</p>');
		elseif (mysql_num_rows($result) == 0)
			print('<p class="info">No such '.$type_name.'.');
		else
			print('<p class="error">Something just went really, really wrong. There are '.mysql_num_rows($result).' Items with the same ID!</p>');
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
	$sql .= 'name="'. escape_string($_POST['name']). '", ';
	
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
		if (!ereg('^[0-9]+$', $_POST['parentID'])) input_error('Variation of', TRUE);
		$sql .= 'parent='. $_POST['parentID'].', ';
		
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
			print('<p class="info">'.$type_name.' updated.</p>');
		else
			print('<p class="error">Something went wrong :\'(. (SQL executed was: "'.$sql.'")</p>');
	}
	else
	{
		print('Please fix the above errors, and then submit again.');
	}

}
elseif ($action == 'delete')
{
	if (array_key_exists('sure', $_POST) && $_POST['sure'])
	{
		/* XXX: should we only mark things as deleted? */
		$ID = validate_input_regexp_error ($ID, '^[0-9]+$');
		$sql = 'DELETE FROM '. $type. " WHERE {$type}ID=$ID";
		$result = mysql_query($sql);
		
		if ($type == 'background' && $result)
		{
			$result = mysql_query('DELETE FROM background_resolution WHERE backgroundID='. $ID);
			if ($result)
				print('<p class="info">Item deleted.</p>');
			else
				print('<p class="error">Error deleting the resolution (background itself is deleted).</p>');
		}
		else
		{
			if ($result)
				print('<p class="info">Item deleted.</p>');
			else
				print('<p class="error">Error deleting the item.</p>');
		}
	}
	else
	{
		print('<p class="info">You need to check the checkbox.</p>');
	}
}
else
	print('<p class="error">You just did the impossible :-).</p>');

admin_footer();
?>
