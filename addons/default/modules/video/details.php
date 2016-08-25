<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pia Legong Product module
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Blog
 */
class Module_Video extends Module
{
	public $version = '1.0';

	public function info()
	{
		$info = array(
		
			'name' => array(				
				'en' => 'Video',
				'id' => 'Video'
				
			),
			
			'description' => array(				
				'en' => 'Management Video',
				'id' => 'Pengaturan Video'
				
			),
			
			'frontend' => true,
			'backend'  => true,
			'skip_xss' => false,
			'menu' => '',

			'roles' => array(
				
				'put_live', 'edit_live', 'delete_live'
			),

			'sections' => array(
				'Video' => array(
					'name' => 'List',
					'uri' => ADMIN_URL.'/video',
					'shortcuts' => array(
						array(
							'name' => 'video:create_video',
							'uri' => ADMIN_URL.'/video/create',
							'class' => 'add',
						)
					),
				),
				'Facebook' => array(
					'name' => 'Facebook User',
					'uri' => ADMIN_URL.'/video/facebook_user',
					'shortcuts' => array(
						array(
							'name' => 'video:create_video',
							'uri' => ADMIN_URL.'/video/create',
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
        $this->dbforge->drop_table('video');
		$this->dbforge->drop_table('video_approve');
		$this->dbforge->drop_table('video_status');
        
        $video_fields = array(
				'id' 		 	=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'name' 			=> array('type' => 'TEXT', 'null' => true),
				'userid' 		=> array('type' => 'TEXT', 'null' => true),
				'screen_name' 	=> array('type' => 'VARCHAR','constraint' => 255, 'null' => true),
				'url'			=> array('type' => 'TEXT', 'null' => true),
				'description' 	=> array('type' => 'TEXT', 'null' => true),
				'photo_profile' => array('type' => 'TEXT', 'null' => true),
				'video' 		=> array('type' => 'TEXT', 'null' => true),
				'video_preview' => array('type' => 'TEXT', 'null' => true),
				'author_id'  	=> array('type' => 'INT', 'constraint' => 11, 'default' => NULL,'null'=>true),
				'userid_match'  => array('type' => 'INT', 'constraint' => 11, 'default' => NULL,'null'=>true),
				'created_on' 	=> array('type' => 'INT', 'constraint' => 11),
				'max_id'		=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL,'null'=>true),
				'since_id'		=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL,'null'=>true),
				'entity_id'		=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL,'null'=>true),
				'created' 	 	=> array('type' => 'DATETIME'),
				'via' 			=> array('type' => 'VARCHAR', 'constraint' => 50, 'default' => NULL,'null'=>true),
				'status' 	 	=> array('type' => 'ENUM', 'constraint' => array('draft', 'live', 'deleted'), 'default' => 'live'),
				'favorite' 	 	=> array('type' => 'ENUM', 'constraint' => array('ya', 'tidak'), 'default' => 'tidak')
		);
		
		
		 $video_approve = array(
				'id' 		 	=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'id_video' 		=> array('type' => 'INT', 'null' => false),
				'order_id' 		=> array('type' => 'INT', 'null' => true),
				'created' 	 	=> array('type' => 'DATETIME'),
				'status' 	 	=> array('type' => 'ENUM', 'constraint' => array('draft', 'live', 'deleted'), 'default' => 'live'),
			);
			
		$video_status = array(
				'id' 		 	=> array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'via' 			=> array('type' => 'VARCHAR', 'constraint' => 50, 'default' => NULL),
				'max_id' 		=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL),
				'since_id' 	 	=> array('type' => 'DECIMAL', 'constraint' => array(30,0), 'default' => NULL),
				'total_page'	=> array('type' => 'INT', 'null' => true),
			);
				
        return $this->install_tables(        	
        	array(        		
        		'video' 		=> $video_fields,
				'video_approve' => $video_approve,
				'video_status' 	=> $video_status
			)
		);
	}
	
	public function admin_menu(&$menu)
	{
		$this->lang->load('video/video');
		$menu[lang('video:main_menu')] = array(
				'Videos' => ADMIN_URL.'/video',
				'Facebook User' => ADMIN_URL.'/video/facebook_user'
		);
		
		add_admin_menu_place(lang('video:main_menu'), 1);
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
