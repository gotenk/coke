<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Addons Module
 *
 * @author MaxCMS Dev Team
 * @package MaxCMS\Core\Modules\Modules
 */
class Module_Addons extends Module
{
	public $version = '2.0.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Add-ons',
				'id' => 'Pengaya',
				
			),
			'description' => array(
				'en' => 'Allows admins to see a list of currently installed modules.',
				'id' => 'Memperlihatkan kepada admin daftar modul yang terinstall.',
			),
			'frontend' => false,
			'backend' => true,
			'menu' => false,

			'sections' => array(
				'modules' => array(
					'name' => 'addons:modules',
					'uri' => ADMIN_URL.'/addons/modules',
				),
				'themes' => array(
					'name' => 'global:themes',
					'uri' => ADMIN_URL.'/addons/themes',
				),
				'admin_themes' => array(
					'name' => 'addons:admin_themes',
					'uri' => ADMIN_URL.'/addons/admin-themes',
				),
				'plugins' => array(
					'name' => 'global:plugins',
					'uri' => ADMIN_URL.'/addons/plugins',
				),
				'field_types' => array(
					'name' => 'global:field_types',
					'uri' => ADMIN_URL.'/addons/field-types',
				),
			),
		);
	
		// Add upload options to various modules
		if ( ! class_exists('Module_import') and Settings::get('addons_upload'))
		{
			$info['sections']['modules']['shortcuts'] = array(
				array(
					'name' => 'global:upload',
					'uri' => ADMIN_URL.'/addons/modules/upload',
					'class' => 'add',
				),
			);

			$info['sections']['themes']['shortcuts'] = array(
				array(
					'name' => 'global:upload',
					'uri' => ADMIN_URL.'/addons/themes/upload',
					'class' => 'add modal',
				),
			);

			$info['sections']['admin_themes']['shortcuts'] = array(
				array(
					'name' => 'global:upload',
					'uri' => ADMIN_URL.'/addons/themes/upload',
					'class' => 'add',
				),
			);
		}

		return $info;
	}

	public function admin_menu(&$menu)
	{
		$menu['lang:cp:nav_addons'] = array(
			'lang:cp:nav_modules'			=> ADMIN_URL.'/addons',
			'lang:global:themes'			=> ADMIN_URL.'/addons/themes',
			'lang:global:plugins'			=> ADMIN_URL.'/addons/plugins',
			'lang:global:field_types'		=> ADMIN_URL.'/addons/field-types'
		);

		add_admin_menu_place('lang:cp:nav_addons', 6);
	}

	public function install()
	{
		$this->dbforge->drop_table('theme_options');

		$tables = array(
			'theme_options' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 30),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100),
				'description' => array('type' => 'TEXT', 'constraint' => 100),
				'type' => array('type' => 'set', 'constraint' => array('text', 'textarea', 'password', 'select', 'select-multiple', 'radio', 'checkbox', 'colour-picker')),
				'default' => array('type' => 'VARCHAR', 'constraint' => 255),
				'value' => array('type' => 'VARCHAR', 'constraint' => 255),
				'options' => array('type' => 'TEXT'),
				'is_required' => array('type' => 'INT', 'constraint' => 1),
				'theme' => array('type' => 'VARCHAR', 'constraint' => 50),
			),
		);

		if ( ! $this->install_tables($tables)) {
			return false;
		}

		// Install settings
		$settings = array(
			array(
				'slug' => 'addons_upload',
				'title' => 'Addons Upload Permissions',
				'description' => 'Keeps mere admins from uploading addons by default',
				'type' => 'text',
				'default' => '0',
				'value' => '0',
				'options' => '',
				'is_required' => 1,
				'is_gui' => 0,
				'module' => '',
				'order' => 0,
			),
			array(
				'slug' => 'default_theme',
				'title' => 'Default Theme',
				'description' => 'Select the theme you want users to see by default.',
				'type' => '',
				'default' => 'default',
				'value' => 'default',
				'options' => 'func:get_themes',
				'is_required' => 1,
				'is_gui' => 0,
				'module' => '',
				'order' => 0,
			),
			array(
				'slug' => 'admin_theme',
				'title' => 'Control Panel Theme',
				'description' => 'Select the theme for the control panel.',
				'type' => '',
				'default' => '',
				'value' => 'maxcms',
				'options' => 'func:get_themes',
				'is_required' => 1,
				'is_gui' => 0,
				'module' => '',
				'order' => 0,
			),
		);

		foreach ($settings as $setting)
		{
			if ( ! $this->db->insert('settings', $setting))
			{
				return false;
			}
		}

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
