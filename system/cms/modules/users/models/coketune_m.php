<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Coketune_m extends MY_Model
{

	public function register_profile($profile_data, $id){
    	$this->db->update('profiles', $profile_data, array('user_id'=>$id));
    	return $this->db->affected_rows() == 1;
    }

    public function check_email_reset($email){
    	$result =  $this->db
                    ->select('id, salt')
	    			->where('active', 1)
                    ->where('group_id', 2)
		    		->where('email', $email)
		    		->get('users')->row();        
        return $result;
    }

    public function create_token($result = array()){
        if($result){
            $token = substr(hash('sha256', $result->id.$result->salt.time()), 0, 40);
            $data_update['forgotten_password_code'] = $token; 
            $update = $this->usr_update($result->id, $data_update);
            if($update){
                return $token;
            }            
        }
        return false;
    }

    public function usr_update($id, $data){
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return $this->db->affected_rows() == 1;   
    }

    public function check_token($token){
    	$result = $this->db
                    ->select('id, salt') 
    				->where('active', 1)
		    		->where('forgotten_password_code', $token)
		    		->get('users');		    		
		return $result->row();
    }


    public function data_pemenang($offset, $limit){
        return $this->db->get('pemenang', $limit, $offset)->result();
    }

    public function count_by(){
        $this->db->from('pemenang');
        return $this->db->count_all_results();
    }

    public function is_berikutnya($offset, $limit){
        if($offset == 0){
            $newoffset = $limit;
        }else{
            $newoffset = $offset + $limit;
        }
        $q  = $this->db
                    ->select('pemenang_id')
                    ->get('pemenang', $limit, $newoffset);
        return ($q->row()) ? $newoffset : 0;
    }
 
    public function search_pemenang(){
        $total = 50;        
        $limit = 10;

        $range = $this->_create_range(0, $limit, $total, array());

        pre($range);

    }
 
    private function _create_range($offset, $limit, $total, $result){ 
        $next = $offset + $limit;

        if($offset == 0){
            $result[] = array(0, ($next-1));
            if($next < $total){
                $result = $this->_create_range($next, $limit, $total, $result);
            }
        }else{
            $result[] = array($next, ( ($next+$limit) - 1) );
            if($next < $total){
                $result = $this->_create_range($next, $limit, $total, $result);
            }
        }

        return $result;
    }

    public function code_user($user_id, $offset='', $limit=''){
        $str = "SELECT * FROM
                (
                    SELECT unique_code as kode_unik, transaction_code, date_created as tanggal
                    FROM default_alfamart_code 
                    WHERE user_id = {$user_id}
                    UNION ALL
                    SELECT code as kode_unik, '' as transaction_code, date_used as tanggal
                    FROM default_indomaret_code
                    WHERE is_used = 1
                    AND user_id = {$user_id}
                ) sub
                ORDER BY tanggal DESC ";
        if($offset && $limit){
            $str .= "LIMIT {$offset}, {$limit}";
        }
        $result = $this->db->query($str);
        return $result->row();
    }

    public function count_code_user($user_id){
        $str = "SELECT COUNT(*) AS total FROM
                (
                    SELECT unique_code as kode_unik
                    FROM default_alfamart_code 
                    WHERE user_id = {$user_id}
                    UNION ALL
                    SELECT code as kode_unik
                    FROM default_indomaret_code
                    WHERE is_used = 1
                    AND user_id = {$user_id}
                ) sub";
        $query = $this->db->query($str)->row();
        return $query->total;        
    }
}