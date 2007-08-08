<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['artwork_path'] = './repository/';
$config['thumb_path'] = './repository/thumbs/';

$config['thumb_width'] = 150;
$config['thumb_height'] = 125;

$config['image_lib']['image_library'] = 'ImageMagick';
$config['image_lib']['library_path'] = '/opt/local/bin/';
$config['image_lib']['create_thumb'] = FALSE;
$config['image_lib']['maintain_ratio'] = TRUE;
$config['image_lib']['quality'] = 100;

?>