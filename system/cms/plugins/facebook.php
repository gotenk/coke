<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Integration Plugin
 *
 * Attaches a Google Analytics tracking piece of code.
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Plugins
 */
class Plugin_Facebook extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Facebook',
	);
	public $description = array(
		'en' => 'Facebook Plugin',
		'id' => 'Facebook Plugin'
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @todo fill the  array with details about this plugin, then uncomment the return value.
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'analytics' => array(// the name of the method you are documenting
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Facebook Plugin'
				),
				'single' => true,// will it work as a single tag?
				'double' => true,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(),
			),// end analytics method

		);
	
		return $info;
	}

	/**
	 * Partial
	 *
	 * Loads Google Analytic
	 *
	 * Usage:
	 *
	 *     {{ facebook:fb_javascript }}
	 *
	 * @return string The analytics partial view.
	 */
	public function fb_javascript()
	{
		$html = $this->template->load_view('fragments/fb_script', array('extra' =>'null'), false);
		$view = $this->parser->parse_string($html, array('extra' =>'null'), true, false);
		return $view;
	}
	
	public function fb_javascript_mobile()
	{
		$html = $this->template->load_view('fragments/fb_script_mobile', array('extra' =>'null'), false);
		$view = $this->parser->parse_string($html, array('extra' =>'null'), true, false);
		return $view;
	}

	

}