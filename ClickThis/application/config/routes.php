<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/**
 * Api Routings
 */
$route["auth"] = "api/auth";
$route["authenticate"] = "api/authenticate";
$route["access_token"] = "api/access_token";
$route["request_token"] = "api/request_token";
$route["authenrized"] = "api/authenticated";
$route["token/regenerate"] = "api/token_regenerate";
$route["token"] = "api/token";
$route["token/set"] = "api/set_token";
$route["v1/(:any)"] = "api/$1";
$route["keypair"] = "api/keypair";

/**
 * ClickThis Routes
 */

$route["logout"] = "api/logout";
$route["verify/login"] = "api/login";
$route["home"] = "frontend";
$route["onetimepasswordtest/php"] = "api/topt";
$route["login/mobile"] = "mobile/login";
$route["register/mobile"] = "mobile/register";
$route["login/desktop"] = "login/clickthis";
$route["register/desktop"] = "login/clickthis/register";
$route["login/mobile"] = "mobile/login";
$route["login/tablet"] = "tablet/login";
$route["termsofservice"] = "desktop/termsofservice";
$route["privacy"] = "desktop/privacy";
$route["register"] = "login/clickthis/register";
$route["verify/register"] = "api/register";
$route["resend/email/(:num)"] = "api/resendemail/$1";
$route["reset/password"] = "api/resetpasswordemail";
$route["reset/password/change/(:any)"] = "api/resetpasswordform/$1";
$route["reset/password/endpoint/(:any)"] = "api/resetpassword/$1";
$route["reset/password/resend/email/(:any)"] = "api/resetpasswordresendemail/$1";


/**
 * Frontend Mapping
 */
$route["series/(:num)"] = "frontend/desktop/series/$1";
$route["admin"] = "frontend/desktop/admin";
$route["mobile/(:any)"] = "frontend/mobile/$1";
$route["desktop/(:any)"] = "frontend/desktop/$1";
$route["mobile"] = "frontend/mobile/";
$route["desktop"] = "frontend/desktop/";


/**
 * Standard CodeIgniter routings
 */
$route['default_controller'] = "frontend";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */