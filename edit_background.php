<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";

$redirect = false;
is_logged_in('Edit Background');

if (POST ('save'))
{
	$backgroundID = validate_input_regexp_default ($_POST['backgroundID'], '^[0-9]+$', -1);
	if ($backgroundID == -1)
		art_fatal_error ('Edit Background', 'Save Background', 'Invalid background ID');
	
	$background_result = mysql_query ("SELECT userID FROM background WHERE backgroundID = $backgroundID");
	$background_array = mysql_fetch_array ($background_result);

	if ($_SESSION['userID'] != $background_array['userID'])
		art_fatal_error ('Edit Background', 'Edit Background', 'Go away! You cannot edit other people\'s backgrounds');

	$name = escape_string ($_POST['background_name']);
	$description = escape_string ($_POST['background_description']);
	$version  = validate_input_regexp_default ($_POST['background_version'], '^[0-9a-zA-Z\.]+$', '');
	$parentID  = validate_input_regexp_default ($_POST['background_parentID'], '^[0-9]+$', '0');
	$license = validate_input_array_default($_POST["background_license"], array_keys($license_config_array), '');

	mysql_query ("UPDATE background SET
				name='$name',
				description='$description',
				version='$version',
				license='$license',
				parent=$parentID
			WHERE backgroundID = $backgroundID");
	print (mysql_error());
	$redirect = true;
}

if (POST ('cancel') || $redirect)
{
	$backgroundID = $_POST['backgroundID'];
	$category = $_POST['background_category'];
	header ("Location: http://{$_SERVER['SERVER_NAME']}/backgrounds/$category/$backgroundID");
}


$backgroundID = validate_input_regexp_default ($_GET['backgroundID'], '^[0-9]+$', -1);

if ($backgroundID == -1)
	art_fatal_error ('Edit Background', 'Edit Background', 'Invalid background ID');


$background_result = mysql_query ("SELECT * FROM background WHERE backgroundID = $backgroundID");
$background_array = mysql_fetch_array ($background_result);

extract ($background_array, EXTR_PREFIX_ALL, 'background'); // extract the background data into global variables

if ($_SESSION['userID'] != $background_array['userID'])
	art_fatal_error ('Edit Background', 'Edit Background', 'You cannot edit other people\'s backgrounds');
$variation_array = array ('0' => '&nbsp;');
$background_select_result = mysql_query("SELECT backgroundID, name FROM background WHERE userID = $background_userID AND category = '$background_category'");
while(list($var_backgroundID,$var_background_name) = mysql_fetch_row($background_select_result))
	if ($var_backgroundID != $background_backgroundID)
		$variation_array[$var_backgroundID] = $var_background_name;


art_header ('Edit Background');
$template = new template ('edit_background.html');
$template->add_var ('background_backgroundID');
$template->add_var ('background_name');
$template->add_var ('background_license');
$template->add_var ('background_parent');
$template->add_var ('background_version');
$template->add_var ('background_description');
$template->add_var ('background_category');
$template->add_var ('license_box', create_select_box('background_license', $license_config_array, $background_license));
$template->add_var ('variation_box', create_select_box('background_parentID', $variation_array, $background_parent));
$template->write ();

art_footer ();
?>
