<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pia Legong Product module
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Blog
 */
class Module_Search_engine extends Module
{
	public $version = '1.0';

	public function info()
	{
		$info = array(
		
			'name' => array(				
				'en' => 'Search Engine',
				'id' => 'Mesin Pencarian'
				
			),
			
			'description' => array(				
				'en' => 'Management Search Engine',
				'id' => 'Pengaturan Mesin Pencarian'
				
			),
			
			'frontend' => true,
			'backend'  => true,
			'skip_xss' => false,
			'menu' => '',

			'roles' => array(
				
				'put_live', 'edit_live', 'delete_live'
			),

			'sections' => array(
				'searched_data' => array(
					'name' => 'search_engine:searched_data',
					'uri' => ADMIN_URL.'/search_engine',
					/*'shortcuts' => array(
						array(
							'name' => 'search_engine:export_data',
							'uri' => ADMIN_URL.'/search_engine/export',
							'class' => 'add',
						)
					),*/
				),
				'list_words' => array(
					'name' => 'search_engine:black_list_word',
					'uri' => ADMIN_URL.'/search_engine/black_list_words',
				),
				'master_list_name' => array(
					'name' => 'search_engine:master_list_name',
					'uri' => ADMIN_URL.'/search_engine/master_list_name',
					'shortcuts' => array(
						array(
							'name' => 'search_engine:create_master_list_name',
							'uri' => ADMIN_URL.'/search_engine/master_list_name/create',
							'class' => 'add',
						)
					),
				),
				'custom_data' => array(
					'name' => 'search_engine:custom_data',
					'uri' => ADMIN_URL.'/search_engine/custom_data',
					'shortcuts' => array(
						array(
							'name' => 'search_engine:create_custom_data',
							'uri' => ADMIN_URL.'/search_engine/custom_data/create',
							'class' => 'add',
						)
					),
				),
			),
		);
		
		return $info;
	}

	public function install()
	{
		// Ad the rest of the blog fields the normal way.
        $this->dbforge->drop_table('search_engine');
		$this->dbforge->drop_table('search_engine_list_name');
		$this->dbforge->drop_table('search_engine_list_name_used');
        
        $search_engine_fields = array(
				'id' 		 		=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'name' 				=> array('type' => 'TEXT', 'null' => true),
				'userid' 			=> array('type' => 'TEXT', 'null' => true),
				'screen_name' 		=> array('type' => 'VARCHAR','constraint' => 255, 'null' => true),
				'url'				=> array('type' => 'TEXT', 'null' => true),
				'description' 		=> array('type' => 'TEXT', 'null' => true),
				'location' 			=> array('type' => 'TEXT', 'null' => true),
				'lat'				=> array('type' => 'VARCHAR','constraint' => 255, 'null' => true),
				'lng'				=> array('type' => 'VARCHAR','constraint' => 255, 'null' => true),
				'is_found_location' => array('type' => 'TINYINT','constraint'=>1,'default'=>0),
				'photo_profile'		=> array('type' => 'TEXT', 'null' => true),
				'picture' 			=> array('type' => 'TEXT', 'null' => true),
				'author_id'  		=> array('type' => 'INT', 'constraint' => 11, 'default' => NULL,'null'=>true),
				'created_on' 		=> array('type' => 'INT', 'constraint' => 11),
				'max_id'			=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL,'null'=>true),
				'since_id'			=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL,'null'=>true),
				'entity_id'			=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'unique'=>true, 'default' => NULL,'null'=>true),
				'created' 	 		=> array('type' => 'DATETIME'),
				'via' 				=> array('type' => 'VARCHAR', 'constraint' => 50, 'default' => NULL,'null'=>true),
				'status' 	 		=> array('type' => 'ENUM', 'constraint' => array('draft', 'live', 'deleted'), 'default' => 'live'),
				'favorite' 	 		=> array('type' => 'ENUM', 'constraint' => array('ya', 'tidak'), 'default' => 'tidak')
		);
		
		
		 $search_engine_list_name = array(
				'id' 		 	=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'name' 			=> array('type' => 'VARCHAR','constraint'=>255, 'null' => false),
				'slug' 			=> array('type' => 'VARCHAR','constraint'=>255, 'null' => false),
				'created' 	 	=> array('type' => 'DATETIME'),
				'status' 	 	=> array('type' => 'ENUM', 'constraint' => array('draft', 'live', 'deleted','black_listed'), 'default' => 'live'),
			);
		
		$search_engine_list_name_used = array(
				'id' 		 			=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'count_list_name' 		=> array('type' => 'INT',	'constraint'=>11, 'null' => false),
				'list_name_id' 			=>  array('type' => 'INT',	'constraint'=>11, 'null' => false),
				'created' 	 			=> array('type' => 'DATETIME'),
		);
			
		$search_engine_status = array(
				'id' 		 	=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'via' 			=> array('type' => 'VARCHAR', 'constraint' => 50, 'default' => NULL),
				'max_id' 		=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL),
				'since_id' 	 	=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL),
				'total_page'	=> array('type' => 'INT', 'null' => true),
			);
				
        return $this->install_tables(        	
        	array(        		
        		'search_engine_data' 			=> $search_engine_fields,
				'search_engine_list_name' 		=> $search_engine_list_name,
				'search_engine_list_name_used' 	=> $search_engine_list_name_used,
				'search_engine_status'			=> $search_engine_status,				
			)
		);
	}
	
	public function admin_menu(&$menu)
	{
		$this->lang->load('search_engine/search_engine');
		$menu[lang('search_engine:main_menu')] = array(
				lang('search_engine:searched_data') => ADMIN_URL.'/search_engine',
				lang('search_engine:black_list_word') => ADMIN_URL.'/search_engine/black_list_word',
				lang('search_engine:master_list_name') => ADMIN_URL.'/search_engine/master_list_name',
				lang('search_engine:custom_data')=> ADMIN_URL.'/search_engine/custom_data'
		);
		
		add_admin_menu_place(lang('search_engine:main_menu'), 1);
	}

	public function uninstall()
	{
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
