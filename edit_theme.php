<?php

include "mysql.inc.php";
include "common.inc.php";
include "art_headers.inc.php";

$redirect = false;
is_logged_in('Edit Theme');

if (POST ('save'))
{
  /* make sure all data are clean before SQL query */
	$name = escape_string($_POST['theme_name']);
	$description = escape_string($_POST['theme_description']);
	$version  = validate_input_regexp_default ($_POST['theme_version'], '^[0-9a-zA-Z\.]+$', '');
	$parentID  = validate_input_regexp_default ($_POST['theme_parentID'], '^[0-9]+$', 0);
	$license = validate_input_array_default($_POST["theme_license"], array_keys($license_config_array), '');
	$themeID = validate_input_regexp_default ($_POST['themeID'], '^[0-9]+$', -1);

  /* we need a valid theme id! */
	if ($themeID == -1)
		art_fatal_error ('Edit Theme', 'Save Theme', 'Invalid theme ID');

  /* Read theme owner's ID from db */
	$theme_category = mysql_real_escape_string($_POST['theme_category']);
	$theme_user_result = mysql_query("SELECT userID FROM theme WHERE themeID = '$themeID' AND category = '$theme_category' LIMIT 1");
	if ($theme_user_result)
	{
		$theme_data = mysql_fetch_row($theme_user_result);
		$theme_userID = $theme_data[0];
	}
	else art_fatal_error ('Edit Theme (Preview)', 'Edit Background - Preview', 'Unable to read userID from database.');

  /* check this user owns the background */
	if ($_SESSION['userID'] != $theme_userID)
		art_fatal_error ('Edit Theme', 'Edit Theme', 'You cannot edit other people\'s themes');

  /* Update database... */
	mysql_query ("UPDATE theme SET
				name='$name',
				description='$description',
				version='$version',
				license='$license',
				parent=$parentID
			WHERE themeID = $themeID LIMIT 1");
	print (mysql_error());
	$redirect = true;
}

if (POST ('preview'))
{
  /* get post data and make sure there are no magic quotes as we are not adding to sql */
	$themeID = validate_input_regexp_default ($_POST['themeID'], '^[0-9]+$', -1);
	$theme_name = strip_string($_POST['theme_name']);
	$theme_license = strip_string($_POST['theme_license']);
	$theme_parentID = validate_input_regexp_default ($_POST['theme_parentID'], '^[0-9]+$', false);
	$theme_version = strip_string($_POST['theme_version']);
	$theme_description = strip_string($_POST['theme_description']);
	$theme_parent = validate_input_regexp_default ($_POST['theme_parent'], '^[0-9]+$', 0);

  /* We have to use escape_string() because of SQL query below */
	$theme_category = escape_string($_POST['theme_category']);

  /* we need a valid theme id! */
	if ($themeID == -1)
		art_fatal_error ('Edit Theme', 'Edit Theme', 'Invalid theme ID');

  /* Read theme owner's ID from db */
	$theme_user_result = mysql_query("SELECT userID FROM theme WHERE themeID = '$themeID' AND category = '$theme_category' LIMIT 1");
	if ($theme_user_result)
	{
		$theme_data = mysql_fetch_row($theme_user_result);
		$theme_userID = $theme_data[0];
	}
	else art_fatal_error ('Edit Theme (Preview)', 'Edit Background - Preview', 'Unable to read userID from database.');

  /* check this user owns the theme */
	if ($_SESSION['userID'] != $theme_userID)
		art_fatal_error ('Edit Theme', 'Edit Theme', 'You cannot edit other people\'s themes');

	$variation_array = array ('0' => '&nbsp;');
	$theme_select_result = mysql_query("SELECT themeID, name FROM theme WHERE userID = '$theme_userID' AND category = '$theme_category' LIMIT 1");
	while(list($var_themeID,$var_theme_name) = mysql_fetch_row($theme_select_result))
		if ($var_themeID != $theme_themeID)
			$variation_array[$var_themeID] = $var_theme_name;

	art_header ('Edit Theme (Preview)');
	$template = new template ('edit_theme_preview.html');
	$template->add_var ('theme_themeID', $themeID);
	$template->add_var ('theme_name', htmlspecialchars($theme_name));
	$template->add_var ('theme_license', $theme_license);
	$template->add_var ('theme_parent', $theme_parentID);
	$template->add_var ('theme_version', htmlspecialchars($theme_version));
	$template->add_var ('theme_description', htmlspecialchars($theme_description));
	$template->add_var ('theme_description_preview', html_parse_text($theme_description));
	$template->add_var ('theme_category', $theme_category);
	$template->add_var ('license_box', create_select_box('theme_license', $license_config_array, $theme_license, 43));
	$template->add_var ('variation_box', create_select_box('theme_parentID', $variation_array, $theme_parent, 44));
	$template->write ();

	art_footer ();
	die();
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


$theme_result = mysql_query ("SELECT * FROM theme WHERE themeID = $themeID LIMIT 1");
$theme_array = mysql_fetch_array ($theme_result);

extract ($theme_array, EXTR_PREFIX_ALL, 'theme'); // extract the theme data into global variables

if ($_SESSION['userID'] != $theme_array['userID'])
	art_fatal_error ('Edit Theme', 'Edit Theme', 'You cannot edit other people\'s themes');

$variation_array = array ('0' => '&nbsp;');
$theme_select_result = mysql_query("SELECT themeID, name FROM theme WHERE userID = $theme_userID AND category = '$theme_category' LIMIT 1");
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
