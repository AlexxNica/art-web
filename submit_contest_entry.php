<?php

require("common.inc.php");
require("ago_headers.inc.php");

$mimetypes = array('image/png' => 'png', 'image/jpeg' => 'jpg');

function create_filename($name, $category, $extra, $ext)
{

	$base = ereg_replace("[_|-]", " ", $name);
	$base = ereg_replace('[^a-zA-Z0-9\s]', " ", $base);
	$base = ucwords($base);
	$base = str_replace(" ", "", $base);
	$base = 'Splash-' . $base;
	
	return $base . $extra . '.' . $ext;
}

if (array_key_exists('contest', $_POST))
{
	$item_name = escape_string($_POST['item_name']);
	$contest = escape_string($_POST['contest']);
	$description = escape_string($_POST['description']);
	$userID = $_SESSION['userID'];
	
	assert($contest == '2.12-splash');
	
	ago_header("Updates");
	create_title("Contest Submission");
	
	/* make sure user is logged in */
	is_logged_in();
	
	/* get the uploaded file */
	$file = $_FILES['item_filename'];
	if (!isset($file)) {
		print('<p class="error">Something went wrong, as not all variables are set</p>');
		ago_footer();
		die();
	} else {
		if ($file['error'] != UPLOAD_ERR_OK) {
			$message = array(UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
			                 UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
			                 UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded",
			                 UPLOAD_ERR_NO_FILE    => "No file was uploaded",
			                 UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
			                 UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.");
			print('<p class="error">There was an error in the file upload: '.$message[$file['error']].'</p>');
			ago_footer();
			die();
		}
	}
	
	/* we have a file! */
	/* check the mime type */
	$mimetype = mime_content_type($file['tmp_name']);
	if (!array_key_exists($mimetype, $mimetypes)) {
		print('<p class="error">Sorry, but we only accept PNG and JPEG images.</p>');
		ago_footer();
		die();
	} else {
		$extension = $mimetypes[$mimetype];
	}
	/* check the size??? */
	
	/* finally ... the file itself is OK */
	
	/* we need to create a unique file name. For this first need a sane name */
	$file_name = create_filename($item_name, $contest, '', $extension);
	$file_path = '/ftp/pub/gnome/teams/art.gnome.org/contests/'.$contest.'/'. $file_name;
	$thumb_filename = create_filename($item_name, $contest, '-TH', $extension);
	$thumb_path = 'images/thumbnails/contest/'.$thumb_filename;
	
	/* check that none of the files already exist. */
	if (file_exists($file_path) || file_exists($thumb_path)) {
		print('<p class="error">There are already files with the same file name. Please change the name of your theme, so that there is no collision</p>');
		ago_footer();
		die();
	}
	
	/* create thumbnail image */
	if ($extension == "jpg") {
		$image = imagecreatefromjpeg($file['tmp_name']);
	} else {
		$image = imagecreatefrompng($file['tmp_name']);
	}
	
	list($width, $height) = getimagesize($file['tmp_name']); /* why can't this use $image? */
	
	$maxheight = 80; $maxwidth = 96; /* XXX: just random values :) */
	$ratio=$width/$height;
	
	if ($ratio > ($maxwidth/$maxheight)) {
		$newwidth  = $maxwidth;
		$newheight = round($maxwidth/$ratio);
	} else {
		$newheight = $maxheight;
		$newwidth  = round($maxheight*$ratio);
	}
	
	$thumb_image = ImageCreateTrueColor($newwidth,$newheight);
	imagecopyresized($thumb_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	if (!imagejpeg($thumb_image, $thumb_path, 70)) {
		print('<p class="error">An error occured, while saving the thumbnail.</p>');
		ago_footer();
		die();
	}
	chmod($thumb_path, 0666); /* XXX: power to everyone ... easier during development */
	
	if (!move_uploaded_file($file['tmp_name'], $file_path)) {
		print('<p class="error">An error occured, while saving the splash screen. Will try to delete the already saved thumbnail file.</p>');
		
		unlink($thumb_path);
		
		ago_footer();
		die();
	}
	chmod($file_path, 0666);
	
	/* FILES DONE, now insert it into the DB */
	$sql  = "INSERT INTO contest (status,name,contest,license,userID,parent,add_timestamp,release_date,version,description,thumbnail_filename,small_thumbnail_filename, download_start_timestamp, download_filename) ";
	$sql .= "VALUES ('uploaded','$item_name','$contest','gnu-gpl','{$_SESSION['userID']}',0,".time().",now(),'','$description','','$thumb_filename',".time().",'$file_name')";
	
	$sql_result = mysql_query($sql);
	if(!$sql_result) {
		print('<p class="error">Error inserting data into the database: '.mysql_error().'<br/>Removing the files again.</p>');
		unlink($file_path);
		unlink($thumb_path);
		ago_footer();
		die();
	}
	
	print('<p class="info">Thank you for your entry, it will appear in the <a href="/contests/2.12-splash">listing</a> shortly.</p>');
	ago_footer();
	die();
}


// OUTPUT /////////////////////////////////////////////////////////////////////

ago_header("Updates");
create_title("Contest Submission", "To submit an entry to this competition, please fill in the form below");

/* require login earlier, to prevent problems with file upload */
is_logged_in();

?>

<form method="POST"  enctype="multipart/form-data" action="<?php print($_SERVER['PHP_SELF']); ?>">
<table>
<tr><td><label>Contest:</label></td><td><strong>GNOME 2.12 Splash Screen Contest</strong><input type="hidden" name="contest" value="2.12-splash"/></td></tr>
<tr><td><label for="item_name">Entry Name:</label></td><td> <input type="text" id="item_name" name="item_name" size="40"/></td></tr>
<tr><td><label>Author:</label></td><td><?php print($_SESSION['username']); ?></td></tr>
<tr><td><label for="item_filename">Filename:</label></td><td> <input type="file" id="item_filename" name="item_filename" size="40"/></td></tr>
<tr><td><label>License:</label></td><td>Must be GPL<input type="hidden" name="license" value="GPL"/></td></tr>
<tr><td><label for="description">Description:</label></td><td><textarea name="description" id="description" cols=\"40\" rows=\"5\" wrap></textarea></td></tr>
<tr><td><input type="submit" value="Submit"/></td><td></td></tr>
</table>
</form>

<?php ago_footer(); ?>
