<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Blog Plugin
 *
 * Create lists of posts
 * 
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\Blog\Plugins
 */
class Plugin_video extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Video Plugin.',
	);
	public $description = array(
		'en' => 'Video Plugin.',
	);

	 
	public function _self_doc()
	{
		$info = array(
		);
	
		return $info;
	}
	
	public function recaptcha()
	{
		$this->load->library('recaptcha');
		return $this->recaptcha->recaptcha_get_html();
	}

	public function get_display_name(){
		return $this->session->userdata('username');
	}
	
}