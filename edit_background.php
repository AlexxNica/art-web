<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";

$redirect = false;
is_logged_in('Edit Background');

if (POST ('save'))
{
  /* make sure all data are clean before SQL query */
	$backgroundID = validate_input_regexp_default ($_POST['backgroundID'], '^[0-9]+$', -1);
	$background_category = escape_string($_POST['background_category']);
	$name = escape_string($_POST['background_name']);
	$description = escape_string($_POST['background_description']);
	$version  = validate_input_regexp_default ($_POST['background_version'], '^[0-9a-zA-Z\.]+$', '');
	$parentID  = validate_input_regexp_default ($_POST['background_parentID'], '^[0-9]+$', '0');
	$license = validate_input_array_default($_POST["background_license"], array_keys($license_config_array), '');

  /* we need a valid background id! */
	if ($backgroundID == -1)
		art_fatal_error ('Edit Background', 'Save Background', 'Invalid background ID');

  /* check this user owns the background */
	$background_result = mysql_query ("SELECT userID FROM background WHERE backgroundID = '$backgroundID' AND category = '$background_category' LIMIT 1");
	$background_array = mysql_fetch_array ($background_result);
	if ($_SESSION['userID'] != $background_array['userID'])
		art_fatal_error ('Edit Background', 'Edit Background', 'Go away! You cannot edit other people\'s backgrounds');

  /* Update database... */
	mysql_query ("UPDATE background SET
				name='$name',
				description='$description',
				version='$version',
				license='$license',
				parent=$parentID
			WHERE backgroundID = $backgroundID LIMIT 1");
	print (mysql_error());
	$redirect = true;
}

if (POST ('preview'))
{
  /* get post data and make sure there are no magic quotes as we are not adding to sql */
	$backgroundID = validate_input_regexp_default ($_POST['backgroundID'], '^[0-9]+$', -1);
	$background_name = strip_string ($_POST['background_name']);
	$background_license = strip_string ($_POST['background_license']);
	$background_parentID = validate_input_regexp_default ($_POST['background_parentID'], '^[0-9]+$', false);
	$background_version  = validate_input_regexp_default ($_POST['background_version'], '^[0-9a-zA-Z\.]+$', '');	
	$background_description = strip_string($_POST['background_description']);
	$background_parent = validate_input_regexp_default ($_POST['background_parent'], '^[0-9]+$', 0);

  /* We have to use escape_string() because of SQL query below */
	$background_category = escape_string($_POST['background_category']);

  /* we need a valid background id! */
	if ($backgroundID == -1)
		art_fatal_error ('Edit Background (Preview)', 'Edit Background - Preview', 'Invalid background ID');

  /* check this user owns the background */
	$background_result = mysql_query ("SELECT userID FROM background WHERE backgroundID = '$backgroundID' AND category = '$background_category' LIMIT 1");
	$background_array = mysql_fetch_array ($background_result);
	if ($_SESSION['userID'] != $background_array['userID'])
		art_fatal_error ('Edit Background', 'Edit Background', 'Go away! You cannot edit other people\'s backgrounds');

	$variation_array = array ('0' => '&nbsp;');
	$background_select_result = mysql_query("SELECT backgroundID, name FROM background WHERE userID = '" . $background_array['userID'] . "' AND category = '$background_category' LIMIT 1");
	while(list($var_backgroundID,$var_background_name) = mysql_fetch_row($background_select_result))
		if ($var_backgroundID != $background_backgroundID)
			$variation_array[$var_backgroundID] = $var_background_name;

	art_header ('Edit Background');
	$template = new template ('edit_background_preview.html');
	$template->add_var ('background_backgroundID', $backgroundID);
	$template->add_var ('background_name', htmlspecialchars($background_name));
	$template->add_var ('background_license', $background_license);
	$template->add_var ('background_parent', $background_parentID);
	$template->add_var ('background_version', $background_version);
	$template->add_var ('background_description', htmlspecialchars($background_description));
	$template->add_var ('background_description_preview', html_parse_text($background_description));
	$template->add_var ('background_category', $background_category);
	$template->add_var ('license_box', create_select_box('background_license', $license_config_array, $background_license, 43));
	$template->add_var ('variation_box', create_select_box('background_parentID', $variation_array, $background_parentID, 44));
	$template->write ();
	art_footer ();
	die();
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
$template->add_var ('background_description_preview');
$template->add_var ('background_category');
$template->add_var ('license_box', create_select_box('background_license', $license_config_array, $background_license, 43));
$template->add_var ('variation_box', create_select_box('background_parentID', $variation_array, $background_parent, 44));
$template->write ();

art_footer ();
?>
