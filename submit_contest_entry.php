<?php

require("common.inc.php");
require("art_headers.inc.php");

$contest = '2.12-splash';

function create_submission_filename($name, $category, $extra, $ext)
{

	$base = ereg_replace("[_|-]", " ", $name);
	$base = ereg_replace('[^a-zA-Z0-9\s]', " ", $base);
	$base = ucwords($base);
	$base = str_replace(" ", "", $base);
	$base = 'Splash-' . $base;
	
	return $base . $extra . '.' . $ext;
}

function upload_entry($unvalidated_item_name, $unvalidated_description)
{
	global $contest;

	$remote_filename = $_FILES['item_filename']['name'];
	$userID = $_SESSION['userID'];

	if ($unvalidated_item_name == '')
		return "Please enter a name for your submission";

	if ($unvalidated_description == '')
		return "Please enter a description for your submission";

	if ($remote_filename == '')
		return "Please select a file to upload";

	$item_name = mysql_escape_string($unvalidated_item_name);
	$description = mysql_escape_string($unvalidated_description);


	/* make sure user is logged in */
	is_logged_in();

	/* get the uploaded file */
	$file = $_FILES['item_filename'];
	if (!isset($file))
	{
		art_fatal_error('Submit Contest Entry', 'Contest Submission', 'Something went wrong, as not all variables are set');
	}
	else
	{
		if ($file['error'] != UPLOAD_ERR_OK)
		{
			$message = array(UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
					 UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
					 UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded",
					 UPLOAD_ERR_NO_FILE    => "No file was uploaded",
					 UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
					 UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.");
			art_fatal_error('Submit Contest Entry', 'Contest Submission', 'There was an error in the file upload: '.$message[$file['error']]);
		}
	}
	
	/* we have a file! */
	/* get image info */
	list($width, $height, $type) = getimagesize($file['tmp_name']);

	if (($width > 640) or ($height > 480))
		return "Image size must not exceed 640 pixels wide or 480 pixels high";

	if ($type == IMAGETYPE_PNG)
	{
		$extension = 'png';
		$image = imagecreatefrompng($file['tmp_name']);
	}
	elseif ($type == IMAGETYPE_JPEG)
	{
		$extension = 'jpg';
		$image = imagecreatefromjpeg($file['tmp_name']);
	}
	else
	{
		return "Image must be of type JPG or PNG";
	}


	/* we need to create a unique file name. For this first need a sane name */
	$file_name = create_submission_filename($item_name, $contest, '', $extension);
	$file_path = '/ftp/pub/gnome/teams/art.gnome.org/contests/'.$contest.'/'. $file_name;
	$thumb_filename = create_submission_filename($item_name, $contest, '-Th', $extension);
	$thumb_path = 'images/thumbnails/contests/'.$contest.'/'.$thumb_filename;

	/* check that none of the files already exist. */
	if (file_exists($file_path) || file_exists($thumb_path))
	{
		return 'There are already files with the same file name. Please change the name of your theme, so that there is no collision';
	}


	$maxheight = 80; $maxwidth = 96; /* XXX: just random values :) */
	$ratio=$width/$height;

	if ($ratio > ($maxwidth/$maxheight))
	{
		$newwidth  = $maxwidth;
		$newheight = round($maxwidth/$ratio);
	}
	else
	{
		$newheight = $maxheight;
		$newwidth  = round($maxheight*$ratio);
	}

	$thumb_image = ImageCreateTrueColor($newwidth,$newheight);
	imagecopyresampled($thumb_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	if (!imagejpeg($thumb_image, $thumb_path, 70))
		art_fatal_error('Submit Contest Entry', 'Contest Submission', 'An error occured, while saving the thumbnail.');

	chmod($thumb_path, 0664); /* Make sure it's readable */
	
	if (!move_uploaded_file($file['tmp_name'], $file_path))
	{
		unlink($thumb_path);
		art_fatal_error('Submit Contest Entry', 'Contest Submission', 'An error occured, while saving the uploaded file. Will try to delete the already saved thumbnail file.');
	}
	chmod($file_path, 0664);

	/* FILES DONE, now insert it into the DB */
	$sql  = "INSERT INTO contest (status,name,contest,license,userID,parent,add_timestamp,release_date,version,description,thumbnail_filename,small_thumbnail_filename, download_start_timestamp, download_filename) ";
	$sql .= "VALUES ('uploaded','$item_name','$contest','gnu-gpl','{$_SESSION['userID']}',0,".time().",now(),'','$description','','$thumb_filename',".time().",'$file_name')";
	
	$sql_result = mysql_query($sql);
	if(!$sql_result)
	{
		unlink($file_path);
		unlink($thumb_path);
		art_fatal_error('Submit Contest Entry', 'Contest Submission', 'Error inserting data into the database: '.mysql_error().'<br/>Removing the files again.');
	}

	/* Upload was successful! */

	art_header("Submit Contest Entry");
	create_title("Contest Submission");
	print('<p class="info">Thank you for your entry, it will appear in the <a href="/contests/'.$contest.'">listing</a> shortly.</p>');
	art_footer();
	die();

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
