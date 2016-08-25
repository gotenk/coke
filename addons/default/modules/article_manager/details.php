<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Article_manager extends Module
{
	public $version = '1.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Article Manager',
				'id' => 'Artikel Manajer',
			),
			'description' => array(
				'en' => 'Manage Your Simple Article',
				'id' => 'Manajemen Artikel sederhana mu',
			),
			'frontend' => true,
			'backend'  => true,
			'skip_xss' => true,
			'menu'	  => '',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live'
			),
	         'sections'=>array('article_manager' => array(
										   'name' 	=> 'article_manager:list_title',
										   'uri' 	=> ADMIN_URL.'/article_manager',
												   'shortcuts' => array(
														'add' => array(
															'name' 	=> 'article_manager:create',
															'uri' 	=> ADMIN_URL.'/article_manager/create',
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
		$this->lang->load('article_manager/article_manager');
		$menu[lang('article_manager:main_menu')] = ADMIN_URL.'/article_manager';
		
		add_admin_menu_place(lang('article_manager:main_menu'), 2);
	}
	public function install()
	{
		$this->db->delete('settings', array('module' =>'article_manager')); 
		$this->install_tables(array(
			'article_manager' => array(
				'id' => array('type' => 'INT','constraint' => 11, 'primary' => true,'auto_increment' => true),
				'title' => array('type' => 'VARCHAR','constraint'=>512,'null'=>false),
				'slug' => array('type' => 'VARCHAR','constraint'=>512,'null'=>false),
				'content' => array('type' => 'TEXT','null'=>false),
                'status' =>array('type'=>'TINYINT','constraint'=>1,'default'=>1),
                'order' =>array('type'=>'INT','constraint'=>4,'default'=>0),
              	'created_at' =>array('type'=>'DATE','null'=>true),
				'date_custom'=>array('type'=>'DATETIME','null'=>true),
				'created_on'=>array('type'=>'INT','constraint'=>11, 'null'=>true),
				'picture' =>array('type'=>'TEXT','null' => true),
				'picture_thumb' =>array('type'=>'TEXT','null'=>true),
			),
			
			));
	  
	  
	  $settings = array(
			array(
				'slug' => 'article_set_order',
				'title' => 'Order Newest Article By',
				'description' => 'Order Newest Article Position By',
				'type' => 'radio',
				'default' => '0',
				'value' => '0',
				'options' => '0=Latest Id|1=Date Modified|2=Order Position',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'article_manager',
				'order' => 3,
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