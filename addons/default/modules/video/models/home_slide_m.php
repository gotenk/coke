<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class Home_slide_m extends MY_Model
{
	protected $_table = 'default_home_slide';

	public function get_all($created_on ='') {
	   $this->db
			->select('home_slide.*')
			->select('users.username, profiles.display_name')
			->join('profiles', 'profiles.user_id = home_slide.author_id', 'left')
			->join('users', 'home_slide.author_id = users.id', 'left')
			->order_by('home_slide.created_on', (! empty($created_on)? : 'DESC'));

		return $this->db->get($this->_table)->result();
	}

	public function get_num_slide($params = array()) {
		$this->db->select('home_slide.*');
		return $this->db->get_where('home_slide', array('status'=>1))->num_rows();
	}

	public function get_many_by($params = array()) {
		
	}
}