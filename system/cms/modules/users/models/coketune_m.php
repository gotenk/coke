<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Coketune_m extends MY_Model
{

	public function register_profile($profile_data, $id){
    	$this->db->update('profiles', $profile_data, array('user_id'=>$id));
    	return $this->db->affected_rows() == 1;
    }

    public function check_email_reset($email){
    	$result =  $this->db
	    			->where('active', 1)
		    		->where('email', $email)
		    		->get('users');		    		
		return $result->row();
    }

    public function check_token($token){
    	$result = $this->db
    				->where('active', 1)
		    		->where('forgotten_password_code', $token)
		    		->get('users');
		    		pre($this->db->last_query());
		return $result->row();
    }
}