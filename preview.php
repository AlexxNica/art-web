<?php
# Test if $image path is valid - add parent directory

include ('config.inc.php');
include ('templates.inc.php');

$image = $_GET['image'];

$image = '/images/'.$image;
if (!File_Exists('.'.$image)) $image = false;
if (StrPos($image, '../')) $image = false;
list ($image_width, $image_height, $image_type, $image_attr) = getimagesize ('.'.$image);


$template = new template ('preview.html');
$template->add_var ('image-width', $image_width);
if ($image)
	$template->add_var ('image','<img id="preview-image" onclick="scaleImg()" src="'.$image.'" alt="Preview image for this art." /><br />'.Chr(13).'<script type="text/javascript">setImgWidth();</script>'.Chr(13));
else
	$template->add_var ('image','<div id="preview-broken">Image location is not valid or you tried to manipulate image location. Sorry.</div>'.Chr(13));

$template->write();
?>
