<?php

require("common.inc.php");
require("art_headers.inc.php");

$contest = '2.14-artwork';

function create_submission_filename($name, $contest, $extra, $ext)
{
	$base = stripslashes ($name);
	$base = ereg_replace('[_|-]', ' ', $base);
	$base = ereg_replace('[^a-zA-Z0-9:space:]', " ", $base);
	$base = ucwords($base);
	$base = str_replace(" ", "", $base);

	return $base . $extra . '.' . $ext;
}

function upload_entry ($unvalidated_item_name, $unvalidated_description)
{
	global $contest;

	$remote_filename = $_FILES['item_filename']['name'];
	$userID = $_SESSION['userID'];

	if ($contest == '')
		return "Invalid category. Please try again";

	if ($unvalidated_item_name == '')
		return "Please enter a name for your submission";

	if ($unvalidated_description == '')
		return "Please enter a description for your submission";

	if ($remote_filename == '')
		return "Please select a file to upload";

	$item_name = mysql_escape_string ($unvalidated_item_name);
	$description = mysql_escape_string ($unvalidated_description);


	/* make sure user is logged in */
	is_logged_in ();

	/* get the uploaded file */
	$file = $_FILES['item_filename'];
	if (!isset ($file))
	{
		art_fatal_error ('Submit Contest Entry', 'Contest Submission', 'Something went wrong, as not all variables are set');
	}
	else
	{
		if ($file['error'] != UPLOAD_ERR_OK)
		{
			$message = array (UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
					UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
					UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded",
					UPLOAD_ERR_NO_FILE    => "No file was uploaded",
					UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
					UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.");
			art_fatal_error ('Submit Screenshot', 'Screenshot Submission', 'There was an error in the file upload: '.$message[$file['error']]);
		}
	}

	/* we have a file! */
	/* get image info */
	list ($width, $height, $type) = getimagesize ($file['tmp_name']);

	if ($type == IMAGETYPE_PNG)
	{
		$extension = 'png';
	}
	elseif ($type == IMAGETYPE_JPEG)
	{
		$extension = 'jpg';
	}
	else
	{
		return "Image must be of type JPG or PNG";
	}


	/* we need to create a unique file name. For this first need a sane name */
	$file_name = create_submission_filename ($unvalidated_item_name, $contest, '', $extension);
	$file_path = 'images/thumbnails/contests/'.$contest.'/'. $file_name;
	$thumb_filename = create_submission_filename ($unvalidated_item_name, $contest, '-Th', $extension);
	$thumb_path = 'images/thumbnails/contests/'.$contest.'/'.$thumb_filename;

	/* check that none of the files already exist. */
	if (file_exists ($file_path) || file_exists ($thumb_path))
	{
		return 'There are already files with the same file name. Please change the name of your theme, so that there is no collision';
	}


	if (!move_uploaded_file ($file['tmp_name'], $file_path))
	{
		art_fatal_error ('Submit Contest Entry', 'Contest Submission', 'An error occured, while saving the uploaded file.');
	}
	chmod ($file_path, 0664);

	copy ($file_path, $thumb_path);
	exec ('convert -scale 96 '.$thumb_path.' '.$thumb_path, $output, $return_var);
	if ($return_var != 0)
	{
		unlink ($file_path);
		unlink ($thumb_path);
		art_fatal_error ('Submit Contest Entry', 'Contest Entry Submission', 'An error occured, while saving the thumbnail.');
	}

	chmod ($thumb_path, 0664); /* Make sure it's readable */

	/* FILES DONE, now insert it into the DB */
	$time = time ();
	$sql  = "INSERT INTO contest (status,name,contest,userID,add_timestamp,description,thumbnail_filename, download_start_timestamp, preview_filename, license) ";
	$sql .= "VALUES ('active','$item_name','$contest','{$_SESSION['userID']}','$time','$description','$thumb_filename','$time','$file_name', '$license')";

	$sql_result = mysql_query ($sql);
	if (!$sql_result)
	{
		unlink ($file_path);
		unlink ($thumb_path);
		art_fatal_error ('Submit Contest Entry', 'Contest Submission', 'Error inserting data into the database: '.mysql_error ().'<br/>Removing the files again.');
	}

	/* Upload was successful! */

	art_header ("Submit Contest Entry");
	create_title ("Contest Submission");
	print ('<p class="info">Thank you for your entry, it will appear in the <a href="/contests/'.$contest.'">listing</a> shortly.</p>');
	art_footer ();
	die ();

}

$item_name = '';
$description = '';


if (array_key_exists('contest', $_POST))
{
	$unvalidated_item_name = stripslashes($_POST['item_name']);
	$unvalidated_description = stripslashes($_POST['description']);
	$error_message = upload_entry($unvalidated_item_name, $unvalidated_description);
	if (!$error_message)
		exit(); /* no error in upload - no need to continue */
}


// OUTPUT /////////////////////////////////////////////////////////////////////

art_header("Submit Contest Entry");
create_title("Contest Submission", "To submit an entry to this competition, please fill in the form below");

/* require login earlier, to prevent problems with file upload */
is_logged_in();
if ($error_message)
	print('<p class="error">'.$error_message.'</p>');
?>

<form method="POST" enctype="multipart/form-data" action="<?php print($_SERVER['PHP_SELF']); ?>">
<table>
<tr>
	<td><label>Contest:</label></td>
	<td><strong><?php print($contest_config_array[$contest]['name']); ?></strong></td>
</tr>
<tr>
	<td><label for="item_name">Entry Name:</label></td>
	<td> <input type="text" id="item_name" name="item_name" size="40" value="<?php print(htmlspecialchars($unvalidated_item_name));?>" /></td>
</tr>
<tr>
	<td><label>Author:</label></td>
	<td><?php print($_SESSION['username']); ?></td>
</tr>
<tr>
	<td><label for="item_filename">Filename:</label></td>
	<td><input type="file" id="item_filename" name="item_filename" size="40" /></td>
</tr>
<tr>
	<td><label>License:</label></td>
	<td>Must be GPL<input type="hidden" name="license" value="GPL"/></td>
</tr>
<tr>
	<td><label for="description">Description:</label></td>
	<td><textarea name="description" id="description" cols="40" rows="5" wrap="true"><?php print($unvalidated_description);?></textarea></td>
</tr>
<tr>
	<td><input type="submit" value="Submit" name="contest"/></td>
	<td></td>
</tr>
</table>
</form>

<?php art_footer(); ?>
