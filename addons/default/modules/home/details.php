<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Home extends Module
{
	public $version = '2.2.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Home',
				'id' => 'Rumah',
			),
			'description' => array(
				'en' => 'Add custom Home with any content you want.',
				'id' => 'Menambahkan home kostum dengan isi yg anda suka',
			),
			'frontend' => true,
			'backend'  => false,
			'skip_xss' => false,
			'menu'	  => 'content',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live',
                'create_types', 'edit_types', 'delete_types'
			),

		);

		return $info;
	}

	public function install()
	{
	    return true;
	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}