<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Switch_admin_theme extends CI_Migration {

	public function up()
	{
		$this->db
			->set('default', 'maxcms')
			->set('value', 'maxcms')
			->where('slug', 'admin_theme')
			->update('settings');
	}

	public function down()
	{
		$this->db
			->set('default', 'admin_theme')
			->set('value', 'admin_theme')
			->where('slug', 'admin_theme')
			->update('settings');
	}
}