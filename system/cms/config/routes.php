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
| There are two reserved routes:
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

$route['default_controller']                = 'users/home';
$route['404_override']                      = 'pages';

if(ADMIN_URL !='admin')
{
	$route['admin(/:any)?'] ='404';
}

$route[ADMIN_URL.'(/)?'] = 'admin/index';
$route[ADMIN_URL.'/(login|logout|remove_installer_directory|index)']			    = 'admin/$1';
$route[ADMIN_URL.'/help/([a-zA-Z0-9_-]+)']       = 'admin/help/$1';
$route[ADMIN_URL.'/([a-zA-Z0-9_-]+)/(:any)']	    = '$1/admin/$2';
$route[ADMIN_URL.'/([a-zA-Z0-9_-]+)']            = '$1/admin/index';

//$route['konfirmasi-orang-tua(/:any)?']	= 'users/email_parent_confirmation$1';
// $route['search-content']          = 'search_engine/search_content';
// $route['galeri/autocomplete']			= 'search_engine/auto_suggest';
// $route['galeri(/:any)?']					= 'search_engine/index$1';
// $route['event/detail(/:any)?']		= 'article_manager/detail$1';
// $route['event(/:any)?']						= 'article_manager/index$1';
// $route['insert-word']							= 'search_engine/insert_word';
$route['fb-connect']            = 'users/fb_connect';
$route['tw-connect']	          = 'users/tw_connect';
$route['register']	          = 'users/register';
$route['login']	          = 'users/login';
$route['dob']	          = 'users/dob';
$route['dob-failed']	          = 'users/dob_failed';
$route['profile']	          = 'users/profile';
//$route['vine-connect']					= 'users/vine_login';
//$route['instagram-connect']				= 'users/instagram_callback';
//$route['check-parent-email']			= 'users/check_parent_email';


//$route['persyaratan-dan-ketentuan']      = 'users/register_term_user';
//$route['konfirmasi-orangtua(/:any)?']    = 'users/parent_email_confirmation$1';
/* End of file routes.php */
