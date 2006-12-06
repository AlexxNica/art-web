<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";

$redirect = false;
is_logged_in('Edit Theme');

if (POST ('save'))
{
	$themeID = validate_input_regexp_default ($_POST['themeID'], '^[0-9]+$', -1);
	if ($themeID == -1)
		art_fatal_error ('Edit Theme', 'Save Theme', 'Invalid theme ID');
	
	$theme_result = mysql_query ("SELECT userID FROM theme WHERE themeID = $themeID");
	$theme_array = mysql_fetch_array ($theme_result);

	if ($_SESSION['userID'] != $theme_array['userID'])
		art_fatal_error ('Edit Theme', 'Edit Theme', 'Go away! You cannot edit other people\'s themes');

	$name = escape_string ($_POST['theme_name']);
	$description = escape_string ($_POST['theme_description']);
	$version  = validate_input_regexp_default ($_POST['theme_version'], '^[0-9a-zA-Z\.]+$', '');
	$parentID  = validate_input_regexp_default ($_POST['theme_parentID'], '^[0-9]+$', '0');
	$license = validate_input_array_default($_POST["theme_license"], array_keys($license_config_array), '');

	mysql_query ("UPDATE theme SET
				name='$name',
				description='$description',
				version='$version',
				license='$license',
				parent=$parentID
			WHERE themeID = $themeID");
	print (mysql_error());
	$redirect = true;
}

if (POST ('cancel') || $redirect)
{
	$themeID = $_POST['themeID'];
	$category = $_POST['theme_category'];
	header ("Location: http://{$_SERVER['SERVER_NAME']}/themes/$category/$themeID");
}


$themeID = validate_input_regexp_default ($_GET['themeID'], '^[0-9]+$', -1);

if ($themeID == -1)
	art_fatal_error ('Edit Theme', 'Edit Theme', 'Invalid theme ID');


$theme_result = mysql_query ("SELECT * FROM theme WHERE themeID = $themeID");
$theme_array = mysql_fetch_array ($theme_result);

extract ($theme_array, EXTR_PREFIX_ALL, 'theme'); // extract the theme data into global variables

if ($_SESSION['userID'] != $theme_array['userID'])
	art_fatal_error ('Edit Theme', 'Edit Theme', 'You cannot edit other people\'s themes');
$variation_array = array ('0' => '&nbsp;');
$theme_select_result = mysql_query("SELECT themeID, name FROM theme WHERE userID = $theme_userID AND category = '$theme_category'");
while(list($var_themeID,$var_theme_name) = mysql_fetch_row($theme_select_result))
	if ($var_themeID != $theme_themeID)
		$variation_array[$var_themeID] = $var_theme_name;


art_header ('Edit Theme');
$template = new template ('edit_theme.html');
$template->add_var ('theme_themeID');
$template->add_var ('theme_name');
$template->add_var ('theme_license');
$template->add_var ('theme_parent');
$template->add_var ('theme_version');
$template->add_var ('theme_description');
$template->add_var ('theme_category');
$template->add_var ('license_box', create_select_box('theme_license', $license_config_array, $theme_license, 43));
$template->add_var ('variation_box', create_select_box('theme_parentID', $variation_array, $theme_parent, 44));
$template->write ();

art_footer ();
?>
