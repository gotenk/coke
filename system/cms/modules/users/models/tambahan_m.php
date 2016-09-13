<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author		MaxCMS Dev Team
 * @package		MaxCMS\Core\Modules\Users\Models
 */
class Tambahan_m extends MY_Model
{

	public function get_profile($id){
		$query = $this->db	
					->select('gender, phone, dob_date_format, ')			
					->where('users.id', $id)	
					->join('profiles', 'profiles.user_id=users.id')
					->get_where('users');

		return $query->row();
	}

	public function is_user($id){

	}


}