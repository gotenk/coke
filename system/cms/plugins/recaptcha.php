<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User Plugin
 *
 * Run checks on a users status
 *
 * @author  MaxCMS Dev Team
 * @package MaxCMS\Core\Plugins
 */
class Plugin_Recaptcha extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Recaptcha',
	);
	
	
	public function get_recaptcha()
	{
		$this->load->library('recaptcha');
		$ajax = $this->attribute('is_ajax',false);
		$is_checkbox = $this->attribute('is_checkbox',false);
		
		if($is_checkbox)
		{
			return $this->recaptcha->recaptcha_get_checkbox_html();
		}
		else {
			return $this->recaptcha->recaptcha_get_html( null,false,'custom',$ajax);
		}
		
	}

}