<?php defined('BASEPATH') OR exit('No direct script access allowed.');
/**
 * CodeIgniter Admin URL!
 *
 * This is an extension on CI_Extensions that provides awesome error messages
 * with full backtraces and a view of the line with the error.  It is based
 * on Kohana v3 Error Handling.
 *
 * @package		CodeIgniter
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @license		Apache License v2.0
 * @version		1.0
 */

/**
 * Load Exceptions
 *
 * Simply loads the Exception class
 */
function load_admin_url()
{  
	// Due to a weird bug I have to get the absolute paths here -- Dan
	$my_site_ref = 'default';
	
	if(! defined('ADMIN_URL'))
	{
		require_once BASEPATH.'database/DB'.EXT;
	
		$admin_url = DB()
			->select($my_site_ref.'_settings.*')
			->where('slug', 'admin_url')
			->get($my_site_ref.'_settings')->row();
			
		if(!$admin_url)
		{
			define('ADMIN_URL','admin');
		}
		else {
			define('ADMIN_URL',($admin_url->value)? $admin_url->value: $admin_url->default );
		}
		
		$CFG =& load_class('Config', 'core');
		$CFG->_assign_to_config(array('csrf_protection'=>true));	
		
	}
}