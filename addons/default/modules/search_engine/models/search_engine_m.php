<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class Search_engine_m extends MY_Model
{
	protected $_table = 'search_engine_data';	
	
	//--------------------------- FRONT END
	public function insert_data($data){
		$this->db->insert('search_engine_data', $data);
		return $this->db->insert_id();
	}

	function get_list_bubble_front($params = array()){
		if(!empty($params['via'])){
			/*if($params['via']=='fb'){
				$this->db->where('search_engine_data.via', 1);
			}*/			
			//if($params['via']=='tw'){
				$this->db->where('search_engine_data.via', $params['via']);
			//}			
			/*if($params['via']=='yt'){
				$this->db->where('search_engine_data.via_yt', 1);
			}*/			
		}
		$this->db->select('search_engine_data.*, profiles.display_name');
		$this->db->join('profiles', 'profiles.user_id=search_engine_data.author_id', 'left');
		//$this->db->order_by('search_engine_data.show_first', 'desc');
		$this->db->order_by('search_engine_data.id', 'desc');
		return $this->db->get_where('search_engine_data', array('search_engine_data.status'=>'live'));		
	}

	//--------------------------- BACK END
	function get_list_bubble($params = array()){
		if(!empty($params['status'])){
			$this->db->where('search_engine_data.status', $params['status']);
		}else{
			$this->db->where('search_engine_data.status !=', 'deleted');
		}


		if(!empty($params['via'])){
			$this->db->where('search_engine_data.via', $params['via']);
		}
		
		if(!empty($params['source'])){
			if($params['source']=='submmision'){
				$this->db->where('search_engine_data.author_id !=', 0);
			}
			if($params['source']=='crawl'){
				$this->db->where('search_engine_data.author_id', 0);
			}
		}
		if(!empty($params['keywords'])){
			$this->db->like('search_engine_data.description', $params['keywords']);
		}
		if(!empty($params['start_date'])){
			$this->db->where('search_engine_data.created >=', $params['start_date']);
		}
		if(!empty($params['end_date'])){
			$this->db->where('search_engine_data.created <=', $params['end_date']);
		}

		$this->db->select('search_engine_data.*, profiles.display_name');
		$this->db->join('profiles', 'profiles.user_id=search_engine_data.author_id', 'left');
		$this->db->order_by('search_engine_data.id', 'desc');
		return $this->db->get_where('search_engine_data');
	}

	function cek_top(){
		return $this->db->get_where('search_engine_data', array('status'=>'live'))->num_rows();
	}


	public function get_data($created_on ='')
	{
	   $this->db
			->select('carousel.*')
			->select('users.username, profiles.display_name')
			//->join('carousel_detail', 'carousel.id = carousel_detail.id_carousel', 'left')
			->join('profiles', 'profiles.user_id = carousel.author_id', 'left')
			->join('users', 'carousel.author_id = users.id', 'left')
			->order_by('created_on', (! empty($created_on)? : 'DESC'));

		return $this->db->get($this->_table)->result();
	}	

	function get_cerita_by($id=0){
		$this->db->select('search_engine_data.*, profiles.display_name');
		$this->db->join('profiles', 'profiles.user_id=search_engine_data.author_id', 'left');
		return $this->db->get_where('search_engine_data', array('search_engine_data.id'=>$id));
	}

	function get_list_fb_user($params = array()) {
		$this->db->where('profiles.fb_id !=', '');
		
		if(!empty($params['keywords'])){
			$this->db->like('profiles.display_name', $params['keywords']);
			$this->db->or_like('profiles.fb_id', $params['keywords']);
		}
		if(!empty($params['start_date'])){
			$this->db->where('profiles.updated_on >=', strtotime($params['start_date']));
		}
		if(!empty($params['end_date'])){
			$this->db->where('profiles.updated_on <=', strtotime($params['end_date']));
		}

		$this->db->select('profiles.*, (SELECT COUNT(default_search_engine_data.id) FROM default_video WHERE default_search_engine_data.userid=default_profiles.fb_id) AS jumlah_video');
		$this->db->order_by('profiles.id', 'desc');
		return $this->db->get_where('profiles');
	}
	
	//--------------------------- BACK END
	function get_list_fb_video($params = array()){
		$this->db->where('search_engine_data.userid', $params['userid']);
		if(!empty($params['status'])){
			$this->db->where('search_engine_data.status', $params['status']);
		}else{
			$this->db->where('search_engine_data.status !=', 'deleted');
		}
		if(!empty($params['favorite'])){
			$this->db->like('search_engine_data.favorite', $params['favorite']);
		}
		if(!empty($params['keywords'])){
			$this->db->like('search_engine_data.name', $params['keywords']);
		}
		if(!empty($params['start_date'])){
			$this->db->where('search_engine_data.created_on >=', strtotime($params['start_date']));
		}
		if(!empty($params['end_date'])){
			$this->db->where('search_engine_data.created_on <=', strtotime($params['end_date']));
		}

		$this->db->select('search_engine_data.*');
		$this->db->order_by('search_engine_data.id', 'desc');
		//$this->db->order_by('search_engine_data.show_first', 'desc');
		return $this->db->get_where('search_engine_data');
	}
}