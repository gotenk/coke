<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Code_manager extends Module
{
	public $version = '1.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Code Manager',
				'id' => 'Kode Manajer',
			),
			'description' => array(
				'en' => 'Manage Your Code List',
				'id' => 'Manajemen Daftar Kode',
			),
			'frontend' => true,
			'backend'  => true,
			'skip_xss' => true,
			'menu'	  => '',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live'
			),
       'sections'=>array('code_manager' => array(
				   'name' 	=> 'code_manager:list_title',
				   'uri' 	=> ADMIN_URL.'/code_manager',
						   'shortcuts' => array(
								'add' => array(
									'name' 	=> 'code_manager:create',
									'uri' 	=> ADMIN_URL.'/code_manager/create',
									'class' => 'add'

								)
							),
					),
				)
		);

		return $info;
	}
	public function admin_menu(&$menu)
	{
		$this->lang->load('code_manager/code_manager');
		$menu[lang('code_manager:main_menu')] = ADMIN_URL.'/code_manager';

		add_admin_menu_place(lang('code_manager:main_menu'), 2);
	}
	public function install()
	{
		$this->db->delete('settings', array('module' =>'code_manager'));

		$this->install_tables(
			array(
				'tune_code_manager' => array(
					'id' 					=> array('type'=>'INT', 'constraint' => 11, 'primary' => true, 'auto_increment' => true),
					'merchant' 		=> array('type'=>'VARCHAR', 'constraint'=>512, 'null'=>false),
					'no_transaksi'=> array('type'=>'VARCHAR', 'constraint'=>512, 'null'=>false),
					'kode' 				=> array('type'=>'VARCHAR', 'constraint'=>512, 'null'=>false),
					'date_used' 	=> array('type'=>'DATETIME', 'null'=>true),
	        'status' 			=> array('type'=>'TINYINT', 'constraint'=>1, 'default'=>0),
	        'created_at' 	=> array('type'=>'DATE', 'null'=>true),
					'created_on'	=> array('type'=>'INT', 'constraint'=>11, 'null'=>true),
	        'active' 			=> array('type'=>'TINYINT', 'constraint'=>1, 'default'=>1),
				),
			)
		);


	  $settings = array(
			array(
				'slug' 			=> 'code_set_order',
				'title' 		=> 'Order Newest Article By',
				'description' => 'Order Newest Article Position By',
				'type' 			=> 'radio',
				'default' 	=> '0',
				'value' 		=> '0',
				'options' 	=> '0=Latest Id|1=Date Modified|2=Order Position',
				'is_required' => 0,
				'is_gui' 		=> 1,
				'module' 		=> 'code_manager',
				'order' 		=> 3,
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
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
