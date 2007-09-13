<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you se t a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.
|
*/

$route['default_controller'] = "main";
$route['scaffolding_trigger'] = "scaffolding";

/**
 * Admin pages routes
 */
$route['admin'] = "main";
	
	// User section
	$route['admin/users/([0-9]+)'] = "admin/users/index/$1";
	
//$route['admin/([a-z\-_0-9\/]+)'] = "admin/$1";
		

/**
 * Browser gallery
 */
$route['browse/user'] = "browse/user";
$route['browse/user/([a-z0-9]+)'] = "browse/user/$1";
$route['browse/user/([a-z0-9]+)/([a-z\-_0-9\/]+)'] = "browse/user/$1";
$route['browse'] = "browse/index";
$route['browse/([a-z\-_0-9\/]+)'] = "browse/index/$1";

$route['backgrounds'] = "browse/index/backgrounds/";
$route['themes'] = "browse/index/themes/";
$route['screenshots'] = "browse/index/screenshots/";
$route['backgrounds/([a-z\-_0-9\/]+)'] = "browse/index/backgrounds/$1";
$route['themes/([a-z\-_0-9\/]+)'] = "browse/index/themes/$1";
$route['screenshot/([a-z\-_0-9\/]+)'] = "browse/index/screenshots/$1";

?>
