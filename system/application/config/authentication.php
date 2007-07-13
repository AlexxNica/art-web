<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('DEBUG')) define("DEBUG", false);

/**
 *  ACL Constants
 */

if (!defined('EDIT_ARTWORK')) define("EDIT_ARTWORK", 0);
if (!defined('DEL_ARTWORK')) define("DEL_ARTWORK", 1);
if (!defined('EDIT_COLLECTION')) define("EDIT_COLLECTION", 2);
if (!defined('DEL_COLLECTION')) define("DEL_COLLECTION", 3);
if (!defined('EDIT_USER')) define("EDIT_USER", 4);
if (!defined('DEL_USER')) define("DEL_USER", 5);
if (!defined('CHANGE_USER_PERMISSIONS')) define("CHANGE_USER_PERMISSIONS", 6);
if (!defined('MODERATE_ARTWORK')) define("MODERATE_ARTWORK", 7);
if (!defined('ADD_NEWS')) define("ADD_NEWS", 8);
if (!defined('EDIT_NEWS')) define("EDIT_NEWS",9);
if (!defined('DEL_NEWS')) define("DEL_NEWS", 10);
if (!defined('FEATURE_ARTWORK')) define("FEATURE_ARTWORK", 11);
if (!defined('ADD_FAQ')) define("ADD_FAQ", 12);
if (!defined('EDIT_FAQ')) define("EDIT_FAQ", 13);
if (!defined('DEL_FAQ')) define("DEL_FAQ", 14);
if (!defined('EDIT_PAGE')) define("EDIT_PAGE", 15);

$config['permissions'] = array(
								EDIT_ARTWORK 		=> false,
								DEL_ARTWORK			=> false,
								EDIT_COLLECTION		=> false,
								DEL_COLLECTION		=> false,
								EDIT_USER			=> false,
								DEL_USER			=> false,
								CHANGE_USER_PERMISSIONS => false,
								MODERATE_ARTWORK	=> false,
								ADD_NEWS			=> false,
								EDIT_NEWS			=> false,
								DEL_NEWS			=> false,
								FEATURE_ARTWORK		=> false,
								ADD_FAQ				=> false,
								EDIT_FAQ			=> false,
								DEL_FAQ				=> false,
								EDIT_PAGE			=> false
	);

$config['regular'] = array(
							EDIT_ARTWORK 		=> false,
							DEL_ARTWORK			=> false,
							EDIT_COLLECTION		=> false,
							DEL_COLLECTION		=> false,
							EDIT_USER			=> false,
							DEL_USER			=> false,
							CHANGE_USER_PERMISSIONS => false,
							MODERATE_ARTWORK	=> false,
							ADD_NEWS			=> false,
							EDIT_NEWS			=> false,
							DEL_NEWS			=> false,
							FEATURE_ARTWORK		=> false,
							ADD_FAQ				=> false,
							EDIT_FAQ			=> false,
							DEL_FAQ				=> false,
							EDIT_PAGE			=> false
							);
							
$config['moderator'] = array(
							EDIT_ARTWORK 		=> true,
							DEL_ARTWORK			=> true,
							EDIT_COLLECTION		=> true,
							DEL_COLLECTION		=> true,
							EDIT_USER			=> false,
							DEL_USER			=> false,
							CHANGE_USER_PERMISSIONS => false,
							MODERATE_ARTWORK	=> true,
							ADD_NEWS			=> false,
							EDIT_NEWS			=> false,
							DEL_NEWS			=> false,
							FEATURE_ARTWORK		=> true,
							ADD_FAQ				=> false,
							EDIT_FAQ			=> false,
							DEL_FAQ				=> false,
							EDIT_PAGE			=> false
							);
							
$config['admin'] = array(
							EDIT_ARTWORK 		=> true,
							DEL_ARTWORK			=> true,
							EDIT_COLLECTION		=> true,
							DEL_COLLECTION		=> true,
							EDIT_USER			=> true,
							DEL_USER			=> true,
							CHANGE_USER_PERMISSIONS => true,
							MODERATE_ARTWORK	=> true,
							ADD_NEWS			=> true,
							EDIT_NEWS			=> true,
							DEL_NEWS			=> true,
							FEATURE_ARTWORK		=> true,
							ADD_FAQ				=> true,
							EDIT_FAQ			=> true,
							DEL_FAQ				=> true,
							EDIT_PAGE			=> true
							);

/**
 *  Misc Options
 */
$config['login_page'] = '/account/login/'

?>