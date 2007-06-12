<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('DEBUG')) define("DEBUG", false);

/**
 *  ACL Constants
 */

if (!defined('ADD_ARTWORK')) define("ADD_ARTWORK", 0);
if (!defined('EDIT_ARTWORK')) define("EDIT_ARTWORK", 1);
if (!defined('DEL_ARTWORK')) define("DEL_ARTWORK", 2);
if (!defined('COMMENT_ARTWORK')) define("COMMENT_ARTWORK", 3);
if (!defined('CREATE_COLLECTION')) define("CREATE_COLLECTION", 4);
if (!defined('EDIT_COLLECTION')) define("EDIT_COLLECTION",5);
if (!defined('DEL_COLLECTION')) define("DEL_COLLECTION", 6);
if (!defined('ADMIN_MODERATE_ARTWORK')) define("ADMIN_MODERATE_ARTWORK", 7);

$config['permissions'] = array(
								ADD_ARTWORK			=> false,
								EDIT_ARTWORK 		=> false,
								DEL_ARTWORK			=> false,
								COMMENT_ARTWORK		=> false,
								CREATE_COLLECTION 	=> false,
								EDIT_COLLECTION		=> false,
								DEL_COLLECTION		=> false,
								ADMIN_MODERATE_ARTWORK	=> false
	);

$config['regular'] = array(
							ADD_ARTWORK			=> true,
							EDIT_ARTWORK 		=> true,
							DEL_ARTWORK			=> true,
							COMMENT_ARTWORK		=> true,
							CREATE_COLLECTION 	=> false,
							EDIT_COLLECTION		=> false,
							DEL_COLLECTION		=> true,
							ADMIN_MODERATE_ARTWORK	=> false
							);

/**
 *  Misc Options
 */
$config['login_page'] = '/account/login/'

?>