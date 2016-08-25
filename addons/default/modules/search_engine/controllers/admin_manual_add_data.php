<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Controllers
 */
class Admin_manual_add_data extends Admin_Controller {
	
	protected $section = 'custom_data';

	protected $validation_rules = array(
		'name' => array(
			'field' => 'name',
			'label' => 'lang:search_engine:name_label',
			'rules' => 'required|trim|alpha|xss_clean'
		),
		'image_upload' => array(			
			'field' => 'image_upload',
			'label' => 'lang:search_engine:image_upload_label',
			'rules' => 'required|xss_clean'
		),
		'location' => array(			
			'field' => 'location',
			'label' => 'lang:search_engine:location_label',
			'rules' => 'xss_clean'
		),
		'status' => array(			
			'field' => 'status',
			'label' => 'lang:search_engine:status_label',
			'rules' => 'required|xss_clean'
		),
	);
    
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model(array('search_engine_m'));
		$this->lang->load(array('search_engine'));
        $this->load->library('image_lib');
		$this->load->library('facebook');
		$this->load->model('search_engine/bitly_cache_m');
		$this->load->library(array('form_validation'));
		
	}

	public function index() {		
		
		$base_where = array();
		//$base_where['status'] = 'all';
		
		if ($this->input->post('f_status')) {			
			$base_where['status'] = $this->input->post('f_status');
		}

		
				
		$total_rows = $this->search_engine_m->get_list_bubble($base_where)->num_rows();
		$pagination = create_pagination(ADMIN_URL.'/search_engine/index', $total_rows); 

		$this->db->limit($pagination['limit'], $pagination['offset']);	
		$data = $this->search_engine_m->get_list_bubble($base_where)->result();
		
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		$this->template
			->title($this->module_details['name'])
			->append_js('admin/filter.js')
			->set_partial('filters', 'admin/partials/filters')
			->set('pagination', $pagination)
			->set('total_rows', $total_rows)
			->set('data', $data);

			$this->input->is_ajax_request()
			? $this->template->build('admin/tables/search_engine')
			: $this->template->build('admin/index');
		
	}

	function action(){
		$btnAction = $this->input->post('btnAction');
		$id = $this->input->post('cerita_id');
		$this->load->model('search_engine/bitly_cache_m');
		
		//--- CEK TOP ITEM
		/*if($btnAction=='top'){
			$max_top = Settings::get('max_top');
			$data_top = $this->search_engine_m->cek_top();	
			$cek_top = $data_top + intval(count($id));
			if($cek_top > $max_top ){
				$this->session->set_flashdata('error', 'Error : Data TOP to many (max '.$max_top.')');			
				redirect(ADMIN_URL.'/search_engine');
			}
		}*/

		//-- THE ACTION
		if(count($id) > 0){
			foreach ($id as $key => $value) {
				/*if($btnAction=='top'){
					$this->db->update('search_engine_data', array('id'=>$value));
				}

				if($btnAction=='untop'){
					$this->db->update('search_engine_data', array('id'=>$value));
				}*/

				if($btnAction=='live'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$value));
					//tambahan untuk ngebitly
					/*$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
					
					if(strpos($data_url, 'localhost')!==false)
					{
						$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
					}
					
					$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
					
					
					if(!$data_bitly )
					{
						$this->load->library('bitly');
						$data_bitlys = $this->bitly->shorten($data_url);
						$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					}*/
					
					//end tambahan ngebitly
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$value.'_drafted';
						if ($info_bubble->row('via') != "facebook") {
							$photo_path = $info_bubble->row('photo_profile');
							if($photo_path){
								$arr_photo = explode('.', $photo_path);
								$arr_photo_path = explode($drafted, $arr_photo['0']);
								$new_photo_path = $arr_photo_path['0'].'.'.$arr_photo['1'];
	
								$old_name = getcwd().$photo_path;
								$new_name = getcwd().$new_photo_path;
								rename($old_name, $new_name);
							}
						}
						$thumb_path = $info_bubble->row('picture');
						if($thumb_path){		
							$arr_thumb = explode('.', $thumb_path);					
							$arr_thumb_path = explode($drafted, $arr_thumb['0']);
							$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}
						
						$data_update = array();
						$data_update['status'] = $btnAction;
						if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
						if($new_thumb_path){ $data_update['picture'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$value));
							
					}else{
						$this->db->update('search_engine_data', array('status'=>$btnAction), array('id'=>$value));
					}
					$this->db->update('search_engine_data', array('status'=>$btnAction), array('id'=>$value));
				}

				if($btnAction=='draft'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$value, 'status'=>'live'));
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$value.'_drafted';

						$photo_path = $info_bubble->row('photo_profile');
						if($photo_path){
							$arr_photo = explode('.', $photo_path);
							$new_photo_path = $arr_photo['0'].$drafted.'.'.$arr_photo['1'];

							$old_name = getcwd().$photo_path;
							$new_name = getcwd().$new_photo_path;
							rename($old_name, $new_name);
						}
						
						$thumb_path = $info_bubble->row('picture');
						if($thumb_path){
							$arr_thumb = explode('.', $thumb_path);
							$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}

						$data_update = array();
						$data_update['status'] = $btnAction;
						if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
						if($new_thumb_path){ $data_update['picture'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$value));
					}else{
						$this->db->update('search_engine_data', array('status'=>$btnAction), array('id'=>$value));
					}
				}
			}
			if($btnAction=='top'){
				$this->session->set_flashdata('success', 'Success : update data TOP');
			}else{

				$this->session->set_flashdata('success', 'Success : update status data');
			}							
		}
		else{
			$this->session->set_flashdata('error', 'Error : No data selected');	
		}	
		redirect(ADMIN_URL.'/search_engine');
	}

	public function create($id_db=0) {
		
		//$post 	= new stdClass();
		$data_create = new stdClass;
		$rules 	= $this->validation_fb;

		$this->form_validation->set_rules($rules);
		$data_db = $this->search_engine_m->get_cerita_by($id_db)->row();
		$created_on = now();
        $id= 0;
		if (($id_db == 0) && (empty($_FILES['video_preview']['name']))) {
			$this->form_validation->set_rules('video_preview', 'Video Preview', 'required');
		}
		//var_dump($this->form_validation->run());var_dump(validation_errors())die();
		
		if ($this->form_validation->run()) {
			$data_inputan = array();
			$data_inputan['entity_id'] = "";
			if ($this->input->post('id') == "") {
				$str = $this->input->post('video_url');
				preg_match_all('!\d+!', $str, $matches);
				foreach($matches as $item) {
					foreach($item as $itm) {
						if ($itm > 100000) {
							if ($itm != $this->input->post('userid')) {
								if ($data_inputan['entity_id'] == "") {
									$data_inputan['entity_id'] = $itm;
								}
							}
						}
					}
				}
				$cek = $this->db->get_where('search_engine_data', array('entity_id'=>$data_inputan['entity_id']));
			
				if($cek->num_rows() ==  0 ) {
			
					$token = $this->db->get_where('settings', array('slug'=>'fb-token'));
					$fb_token = $token->row('value');
					$this->facebook->setAccessToken($fb_token)->setExtendedAccessToken();
					$new_token = $this->facebook->getAccessToken();
					$this->db->update('settings', array('value'=>$new_token), array('slug'=>'fb-token'));
					
					$data_fb = $this->facebook->api('/'.$this->input->post('userid'));
					
					$str = $data_fb['link'];
					preg_match_all('!\d+!', $str, $matches);
					foreach($matches as $item) {
						foreach($item as $itm) {
							$fb_id_app = $itm;
						}
					}
					
					$data_inputan['name'] = $data_fb['name'];
					$data_inputan['userid'] = $fb_id_app;
					$data_inputan['description'] = $this->input->post('desc');
					$data_inputan['photo_profile'] = 'https://graph.facebook.com/'.$data_fb['id'].'/picture?type=square';
					$data_inputan['search_engine_data'] = $this->input->post('video_url');
					$data_inputan['created_on'] = strtotime(date('Y-m-d H:i:s'));
					$data_inputan['created'] = date('Y-m-d H:i:s');
					$data_inputan['via'] = "facebook";
					$data_inputan['status'] = $this->input->post('status');
					$data_inputan['favorite'] = $this->input->post('favorite');
					//pas create entitiy id di set di atas ya.. ?
					//$data_inputan['entity_id'] = $this->input->post('entity_id');
					if ($id_db == 0) {
						if ($id = $this->search_engine_m->insert_data($data_inputan)) {
							if ($data_inputan['status'] == "draft") {
								$drafted = '_'.$id.'_drafted';
								$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
								$new_filename_video = $arr_filename_video['0'].$drafted.'.'.$arr_filename_video['1'];;
								
								$upload = $this->do_upload($new_filename_video);
								
								$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
								$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
							} else {
								//tambahan untuk ngebitly
								$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id));
								$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
								
								if(strpos($data_url, 'localhost')!==false)
								{
									$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
								}
								
								$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
								
								
								if(!$data_bitly )
								{
									$this->load->library('bitly');
									$data_bitlys = $this->bitly->shorten($data_url);
									$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
								}
								
								//end tambahan ngebitly
								
								//tambahan bitly favorite
								if($data_inputan['favorite'] =='ya')
								{
									$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
								
									if(strpos($data_url, 'localhost')!==false)
									{
										$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
									}
									
									$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
					
					
									if(!$data_bitly )
									{
										$this->load->library('bitly');
										$data_bitlys = $this->bitly->shorten($data_url);
										$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
									}
								}
								//end tambahan bitly favorite
								$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
								$new_filename_video = $arr_filename_video['0'].'.'.$arr_filename_video['1'];;
								
								$upload = $this->do_upload($new_filename_video);
								
								$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
								$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
							}
						}
					} else {
						if ($data_inputan['status'] == "draft") {
							$status_curr = $this->db->get_where('search_engine_data', array('id'=>$id_db, 'status'=>'live'));
							if($status_curr->num_rows() >  0 ) {
								if ($_FILES['video_preview']['name'] == "") {
									$thumb_path = $status_curr->row('video_preview');
									if($thumb_path){
										$drafted = '_'.$id_db.'_drafted';
										$arr_thumb = explode('.', $thumb_path);
										$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
										$old_name = getcwd().$thumb_path;
										$new_name = getcwd().$new_thumb_path;
										rename($old_name, $new_name);
									}
								} else {
									$thumb_path = $_FILES['video_preview']['name'];
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);
									$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
			
								if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
			
								$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
							} else {
								$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
							}
						} else {
							$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_db));
							//tambahan untuk ngebitly
							$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
							
							if(strpos($data_url, 'localhost')!==false)
							{
								$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
							}
							
							$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
						
							
							if(!$data_bitly )
							{
								$this->load->library('bitly');
								$data_bitlys = $this->bitly->shorten($data_url);
								$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
							}
							
							//end tambahan ngebitly
							
							//tambahan bitly favorite
							if($data_inputan['favorite'] =='ya')
							{
								$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
							
								if(strpos($data_url, 'localhost')!==false)
								{
									$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
								}
								
								$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
				
				
								if(!$data_bitly )
								{
									$this->load->library('bitly');
									$data_bitlys = $this->bitly->shorten($data_url);
									$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
								}
							}
							//end tambahan bitly favorite
							$thumb_path = $data_db->video_preview;
							if($thumb_path){
								if ($_FILES['video_preview']['name'] == "") {
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);					
									$arr_thumb_path = explode($drafted, $arr_thumb['0']);
									$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];
				
									$old_name = getcwd().$thumb_path;
									$new_name = getcwd().$new_thumb_path;
									rename($old_name, $new_name);
								} else {
									$new_thumb_path = $_FILES['video_preview']['name'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
							}
			
							if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
							
							$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
						}
						$id = $id_db;
					}
					($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/search_engine') : redirect(ADMIN_URL.'/search_engine/create/'.$id);
				} else {
					$this->session->set_flashdata('error', "Video yang anda isikan sudah ada dalam database.");
					redirect(ADMIN_URL.'/search_engine/create/');
				}
			} else {
				$id_db = $this->input->post('id');
				$token = $this->db->get_where('settings', array('slug'=>'fb-token'));
				$fb_token = $token->row('value');
				$this->facebook->setAccessToken($fb_token)->setExtendedAccessToken();
				$new_token = $this->facebook->getAccessToken();
				$this->db->update('settings', array('value'=>$new_token), array('slug'=>'fb-token'));
				
				$data_fb = $this->facebook->api('/'.$this->input->post('userid'));
				
				$str = $data_fb['link'];
				preg_match_all('!\d+!', $str, $matches);
				foreach($matches as $item) {
					foreach($item as $itm) {
						$fb_id_app = $itm;
					}
				}
					
					
				$data_inputan['name'] = $data_fb['name'];
				$data_inputan['userid'] = $fb_id_app;
				$data_inputan['description'] = $this->input->post('desc');
				$data_inputan['photo_profile'] = 'https://graph.facebook.com/'.$data_fb['id'].'/picture?type=square';
				$data_inputan['search_engine_data'] = $this->input->post('video_url');
				$data_inputan['created_on'] = strtotime(date('Y-m-d H:i:s'));
				$data_inputan['created'] = date('Y-m-d H:i:s');
				$data_inputan['via'] = "facebook";
				$data_inputan['status'] = $this->input->post('status');
				$data_inputan['favorite'] = $this->input->post('favorite');
				$data_inputan['entity_id'] = $this->input->post('entity_id');
				if ($id_db == 0) {
					if ($id = $this->search_engine_m->insert_data($data_inputan)) {
						if ($data_inputan['status'] == "draft") {
							$drafted = '_'.$id.'_drafted';
							$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
							$new_filename_video = $arr_filename_video['0'].$drafted.'.'.$arr_filename_video['1'];;
							
							$upload = $this->do_upload($new_filename_video);
								
							$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
							$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
						} else {
							//tambahan untuk ngebitly
							$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id));
							$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
							
							if(strpos($data_url, 'localhost')!==false)
							{
								$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
							}
							
							$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
							
							
							if(!$data_bitly )
							{
								$this->load->library('bitly');
								$data_bitlys = $this->bitly->shorten($data_url);
								$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
							}
							
							//end tambahan ngebitly
							
							//tambahan bitly favorite
							if($data_inputan['favorite'] =='ya')
							{
								$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
							
								if(strpos($data_url, 'localhost')!==false)
								{
									$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
								}
								
								$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
				
				
								if(!$data_bitly )
								{
									$this->load->library('bitly');
									$data_bitlys = $this->bitly->shorten($data_url);
									$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
								}
							}
							//end tambahan bitly favorite
							$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
							$new_filename_video = $arr_filename_video['0'].'.'.$arr_filename_video['1'];;
								
							$upload = $this->do_upload($new_filename_video);
								
							$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
							$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
						}
					}
				} else {
					if ($data_inputan['status'] == "draft") {
						$status_curr = $this->db->get_where('search_engine_data', array('id'=>$id_db, 'status'=>'live'));
						if($status_curr->num_rows() >  0 ) {
							if ($_FILES['video_preview']['name'] == "") {
								$thumb_path = $status_curr->row('video_preview');
								if($thumb_path){
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);
									$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
			
									$old_name = getcwd().$thumb_path;
									$new_name = getcwd().$new_thumb_path;
									rename($old_name, $new_name);
								}
							} else {
								$thumb_path = $_FILES['video_preview']['name'];
								$drafted = '_'.$id_db.'_drafted';
								$arr_thumb = explode('.', $thumb_path);
								$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
			
								if ($upload = $this->do_upload($new_thumb_path)) {
									@unlink(getcwd().$data_db->video_preview);
								}
								$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
							}
			
							if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
			
							$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
						} else {
							$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
						}
					} else {
						//tambahan untuk ngebitly
						$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_db));
						$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
						
						if(strpos($data_url, 'localhost')!==false)
						{
							$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
						}
						
						$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
						
						
						if(!$data_bitly )
						{
							$this->load->library('bitly');
							$data_bitlys = $this->bitly->shorten($data_url);
							$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
						}
						
						//end tambahan ngebitly
						
						//tambahan bitly favorite
						if($data_inputan['favorite'] =='ya')
						{
							$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
						
							if(strpos($data_url, 'localhost')!==false)
							{
								$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
							}
							
							$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
			
			
							if(!$data_bitly )
							{
								$this->load->library('bitly');
								$data_bitlys = $this->bitly->shorten($data_url);
								$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
							}
						}
						//end tambahan bitly favorite
						$thumb_path = $data_db->video_preview;
						if($thumb_path){
							if ($_FILES['video_preview']['name'] == "") {
								$drafted = '_'.$id_db.'_drafted';
								$arr_thumb = explode('.', $thumb_path);					
								$arr_thumb_path = explode($drafted, $arr_thumb['0']);
								$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];
				
									$old_name = getcwd().$thumb_path;
									$new_name = getcwd().$new_thumb_path;
									rename($old_name, $new_name);
								} else {
									$new_thumb_path = $_FILES['video_preview']['name'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
							}
			
							if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
							
							$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
						}
						$id = $id_db;
					}
					($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/search_engine') : redirect(ADMIN_URL.'/search_engine/create/'.$id);
			}
		}
		else
		{
			
			foreach ($this->validation_rules as $key => $field) {
				
				$data_create->$field['field'] = set_value($field['field']);
			}
				
			$data_create->created_on = $created_on;
		}

       	$this->template
			->title($this->module_details['name'], lang('product:create_title'))
			->set('data_create', $data_db)
			->set('aksi', 'create')
            ->build('admin/create');
	}
	
	public function graph($id=0) {
		$token = $this->db->get_where('settings', array('slug'=>'fb-token'));
		$fb_token = $token->row('value');
		$this->facebook->setAccessToken($fb_token)->setExtendedAccessToken();
		$new_token = $this->facebook->getAccessToken();
		$this->db->update('settings', array('value'=>$new_token), array('slug'=>'fb-token'));
		
		$data_fb = $this->facebook->api('/'.$id);
		$hastag = $this->db->get_where('settings', array('slug'=>'crawling-hashtag'));
       	$this->template
			->title($this->module_details['name'], lang('product:create_title'))
			->set('data_fb', $data_fb)
			->set('aksi', 'create')
			->set('hastag', $hastag->row('value'))
            ->build('admin/graph');
	}

	public function edit($id = 0) {
		
		$id or redirect(ADMIN_URL.'/search_engine');

		$dago_gallery = $this->search_engine_m->get_cerita_by($id)->result();
		$dago_gallery = $dago_gallery[0];
		//$image_old = $dago_gallery->photo;
		//$image_old_thumb = $dago_gallery->thumb_photo;
		//var_dump($dago_gallery->name); 
		if(! ($dago_gallery)) {
			
			redirect(ADMIN_URL.'/search_engine');
		}

		if ($this->input->post('created_on')) {
			
			$created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on'), $this->input->post('created_on_hour'), $this->input->post('created_on_minute')));
		
		}else {
				
			$created_on = $dago_gallery->created_on;
		}

		
		$hash = $this->input->post('preview_hash');

		if ($this->input->post('status') == 'draft' and $this->input->post('preview_hash') == '') {
			
			//$hash = $this->_preview_hash();
		}
		
		$data_upload = array();		
		$rules 	= array_merge($this->validation_rules);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) { 
			
			$id_cerita = $this->input->post('id_cerita');
			
			if($id_cerita){
				//var_dump($this->input->post('status')); die();
				$status = $this->input->post('status');
				$favorite = $this->input->post('favorite');
				$description = trim($this->input->post('desc'));
				if($status=='live'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_cerita));
					//tambahan untuk ngebitly
					$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
					
					if(strpos($data_url, 'localhost')!==false)
					{
						$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
					}
					
					$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
					
					
					if(!$data_bitly )
					{
						$this->load->library('bitly');
						$data_bitlys = $this->bitly->shorten($data_url);
						$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					}
					
					//end tambahan ngebitly
					
					//tambahan bitly favorite
					if($favorite =='ya')
					{
						$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
					
						if(strpos($data_url, 'localhost')!==false)
						{
							$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
						}
						
						$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
						
						
						if(!$data_bitly )
						{
							$this->load->library('bitly');
							$data_bitlys = $this->bitly->shorten($data_url);
							$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
						}
					}
					//end tambahan bitly favorite
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$id_cerita.'_drafted';
						if ($info_bubble->row('via') == 'twitter') {
							$photo_path = $info_bubble->row('photo_profile');
							if($photo_path){
								$arr_photo = explode('.', $photo_path);
								$arr_photo_path = explode($drafted, $arr_photo['0']);
								$new_photo_path = $arr_photo_path['0'].'.'.$arr_photo['1'];
	
								$old_name = getcwd().$photo_path;
								$new_name = getcwd().$new_photo_path;
								rename($old_name, $new_name);
							}
						}
						
						$thumb_path = $info_bubble->row('video_preview');
						if($thumb_path){		
							$arr_thumb = explode('.', $thumb_path);					
							$arr_thumb_path = explode($drafted, $arr_thumb['0']);
							$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}
						
						$data_update = array();
						$data_update['favorite'] = $favorite;
						$data_update['status'] = $status;
						$data_update['description'] = $description;
						if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
						if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$id_cerita));							
					}else{
						$this->db->update('search_engine_data', array('status'=>$status), array('id'=>$id_cerita));
					}
				}

				if($status=='draft'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_cerita, 'status'=>'live'));
					$this->db->update('search_engine_data', array('status'=>$status, 'description'=>$description), array('id'=>$id_cerita));
					//tambahan untuk ngebitly
					$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
					
					if(strpos($data_url, 'localhost')!==false)
					{
						$data_url ='https://ramadan.coca-cola.id/galeri/1/'.$info_bubble->row('id');
					}
					
					$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
					
					
					if(!$data_bitly )
					{
						$this->load->library('bitly');
						$data_bitlys = $this->bitly->shorten($data_url);
						$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					}
					
					//end tambahan ngebitly
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$id_cerita.'_drafted';
						if ($info_bubble->row('via') == 'twitter') {
							$photo_path = $info_bubble->row('photo_profile');
							if($photo_path){
								$arr_photo = explode('.', $photo_path);
								$new_photo_path = $arr_photo['0'].$drafted.'.'.$arr_photo['1'];
	
								$old_name = getcwd().$photo_path;
								$new_name = getcwd().$new_photo_path;
								rename($old_name, $new_name);
							}
						}
						
						$thumb_path = $info_bubble->row('video_preview');
						if($thumb_path){
							$arr_thumb = explode('.', $thumb_path);
							$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}

						$data_update = array();
						$data_update['favorite'] = $favorite;
						$data_update['status'] = $status;
						$data_update['description'] = $description;
						if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
						if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$id_cerita));
					}else{
						$this->db->update('search_engine_data', array('status'=>$status), array('id'=>$id_cerita));
					}
				}
				//$this->db->update('bubble', array('status'=>$this->input->post('status')), array('id'=>$id_cerita));
			}

			// Redirect back to the form or main page
			($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/search_engine') : redirect(ADMIN_URL.'/search_engine/edit/'.$id);
		}

		foreach ($this->validation_rules as $key => $field)
		{
			if (isset($_POST[$field['field']]))
			{
				$dago_gallery->$field['field'] = set_value($field['field']);
			}
		}

		//if($_POST){ var_dump($_POST); var_dump($dago_gallery);  die();}
		$dago_gallery->created_on = $created_on;
		// die();
        
		
		//var_dump($dago_gallery->name); 
		$this->template
			->title($this->module_details['name'], sprintf(lang('photo:edit_title'), $dago_gallery->name))
			->set('data_create', $dago_gallery)
			->set('aksi', 'Edit')
			->build('admin/form');
	}

	function do_upload($new_file_name)
	{
		$config_file['upload_path'] = getcwd().'/uploads/default/files/facebook_image';
		$config_file['allowed_types'] = 'gif|jpg|png';		
        $config_file['overwrite'] = TRUE; //overwrite user avatar
		$config_file['file_name'] = $new_file_name;
		if(! is_dir($config_file['upload_path'])) {
			$result = mkdir($config_file['upload_path'],0755,true);
        } 
		//$config['max_size']	= '100';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		//$this->load->library('upload');

		//$new = new CI_Upload($config_file);
		//var_dump($new); die();
		$this->load->library('upload', $config_file);

		//if ( ! $this->upload->do_upload('userfile'))
		if ( ! $this->upload->do_upload('video_preview'))
		{
			$error = array('error' => $this->upload->display_errors());
			return $error;
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			return $data;
			//$this->load->view('upload_success', $data);
		}
	}

	function do_upload_thumb($new_file_name)
	{
		$config['upload_path'] = getcwd().'/uploads/default/photo/thumb';
		$config['allowed_types'] = 'gif|jpg|png';		
        $config['overwrite'] = TRUE; //overwrite user avatar
		$config['file_name'] = $new_file_name;
		$config['width']  = '136';
		$config['height']  = '133';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('thumbfile'))
		{
			$error = array('error' => $this->upload->display_errors());
			//var_dump($error); die();
			return $error;
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			//var_dump($data); die();
			return $data;
			//$this->load->view('upload_success', $data);
		}
	}

	function delete(){
		if($id = $this->input->post('id'))
		{
			if($id!=0){
				$info_photo = $this->db->get_where('search_engine_data', array('id'=>$id));
				$photo_name = $info_photo->row('name');
				$del = $this->db->update('search_engine_data', array('status'=>'deleted'), array('id'=>$id));
				if($info_photo->num_rows() >  0 ){
					$new_photo_path = $new_thumb_path = '';
					$drafted = '_'.$id.'_drafted';
					if ($info_photo->row('via') == 'twitter') {
						$photo_path = $info_photo->row('photo_profile');
						if($photo_path){
							$arr_photo = explode('.', $photo_path);
							$new_photo_path = $arr_photo['0'].$drafted.'.'.$arr_photo['1'];
		
							$old_name = getcwd().$photo_path;
							$new_name = getcwd().$new_photo_path;
							rename($old_name, $new_name);
						}
					}
					$thumb_path = $info_photo->row('video_preview');
					if($thumb_path){
						$arr_thumb = explode('.', $thumb_path);
						$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
	
						$old_name = getcwd().$thumb_path;
						$new_name = getcwd().$new_thumb_path;
						rename($old_name, $new_name);
					}
	
					$data_update = array();
					if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
					if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }
					
					$this->db->update('search_engine_data', $data_update, array('id'=>$id));
				}
	
				
				
				/*if($info_photo->row('via_tw')==1 && $info_photo->row('photo'))
				{
					if(is_file(getcwd().$info_photo->row('photo')))
					{
						@unlink(getcwd().$info_photo->row('photo'));
					}
					
				}*/
					
				if($del){
					$this->session->set_flashdata('success', 'Video '.$photo_name.' deleted');
				}else{
					$this->session->set_flashdata('error', 'Video '.$photo_name.' can"t delete');
				}
				redirect(site_url(ADMIN_URL.'/search_engine'));
			}
		}
		else {
			redirect(site_url(ADMIN_URL.'/search_engine'));
		}
		
		
	}

	function export_fb_crawling($status=0, $sorted=0, $keyword=0){
		$this->load->library('excel');
    	           
            
            $this->excel->getActiveSheet()->setTitle('Data FB Crawling');
            
            $nama_file = 'data_crawling_fb_';
			
			$this->db->limit(1000);
			$this->db->order_by('id', 'asc');
			$data = $this->db->get_where('search_engine_data', array('via_fb'=>1, 'was_import'=>0, 'author_id'=>0, 'status !='=>'deleted'));
			if($data->num_rows <=0){
				$this->session->set_flashdata('error', 'Data crawling FB habis');
				redirect(site_url(ADMIN_URL.'/search_engine'));
				die();
			}
			//var_dump($this->db->last_query());
			//var_dump($data->result_array()); die();

            $count = 2;
            $this->excel->getActiveSheet()->setCellValue('A1', 'photo');   
            $this->excel->getActiveSheet()->setCellValue('B1', 'thumb_photo');
            $this->excel->getActiveSheet()->setCellValue('C1', 'search_engine_data');
            $this->excel->getActiveSheet()->setCellValue('D1', 'author_id');
            $this->excel->getActiveSheet()->setCellValue('E1', 'created_on');
            $this->excel->getActiveSheet()->setCellValue('F1', 'created');
            $this->excel->getActiveSheet()->setCellValue('G1', 'via_fb');
            $this->excel->getActiveSheet()->setCellValue('H1', 'via_tw');
            $this->excel->getActiveSheet()->setCellValue('I1', 'via_yt');
            $this->excel->getActiveSheet()->setCellValue('J1', 'show_first');
            $this->excel->getActiveSheet()->setCellValue('K1', 'status');
            $this->excel->getActiveSheet()->setCellValue('L1', 'max_id');
            $this->excel->getActiveSheet()->setCellValue('M1', 'since_id');
            $this->excel->getActiveSheet()->setCellValue('N1', 'profile_pic');
            $this->excel->getActiveSheet()->setCellValue('O1', 'username');
            $this->excel->getActiveSheet()->setCellValue('P1', 'name');
            $this->excel->getActiveSheet()->setCellValue('Q1', 'ID');
            $this->excel->getActiveSheet()->setCellValue('R1', 'entity_id');
            $this->excel->getActiveSheet()->setCellValue('S1', 'content');
            
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('M1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('N1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('O1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('P1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('R1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('S1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);
            
           
            
            $no=1;
            foreach($data->result() as $dt){
            	$this->db->update('search_engine_data', array('was_import'=>1, 'date_import'=>date('Y-m-d')), array('id'=>$dt->id));
            	//var_dump($this->db->last_query()); die();
            	
                $this->excel->getActiveSheet()->setCellValueExplicit('A'.$count, $dt->photo, PHPExcel_Cell_DataType::TYPE_STRING); 
                $this->excel->getActiveSheet()->setCellValueExplicit('B'.$count, $dt->thumb_photo, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C'.$count, $dt->video, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('D'.$count, $dt->author_id, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('E'.$count, $dt->created_on, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('F'.$count, $dt->created, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('G'.$count, $dt->via_fb, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('H'.$count, $dt->via_tw, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('I'.$count, $dt->via_yt, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('J'.$count, $dt->show_first, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('K'.$count, $dt->status, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('L'.$count, $dt->max_id, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('M'.$count, $dt->since_id, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('N'.$count, $dt->profile_pic, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('O'.$count, $dt->username, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('P'.$count, $dt->name, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('Q'.$count, $dt->id, PHPExcel_Cell_DataType::TYPE_STRING);   
                $this->excel->getActiveSheet()->setCellValueExplicit('R'.$count, $dt->entity_id, PHPExcel_Cell_DataType::TYPE_STRING);   
                $this->excel->getActiveSheet()->setCellValueExplicit('S'.$count, $dt->content, PHPExcel_Cell_DataType::TYPE_STRING);   
                $count++; 
                $no++;
            }
            
            foreach(range('A','S') as $columnID) {
                $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             
            $filename = $nama_file.'_'.date('d-m-Y').'.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            $objWriter->save('php://output');
       
	}
	
	function import_fb_crawling(){
		$this->load->model('search_engine/search_content_m');
	
		$config['upload_path'] = getcwd().'/uploads/default/excel_fb';
	    $config['allowed_types'] = 'xls';
	                
	    $this->load->library('upload', $config);
	    if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg_excel', 'Insert failed. Please check your file, only .xls file allowed.');
			var_dump($this->upload->display_errors());
		}else{
			$data = array('error' => false);
			$upload_data = $this->upload->data();
			
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			
			$file =  $upload_data['full_path'];
			$this->excel_reader->read($file);
			error_reporting(E_ALL ^ E_NOTICE);
			//var_dump('aaa');
			// Sheet 1
			$data = $this->excel_reader->sheets[0] ;
			var_dump($data['numRows']); 
			//$dataexcel = Array();
			for ($i = 2; $i <= intval($data['numRows']); $i++) {	
				//if($data['cells'][$i][18] == '') var_dump('asd'); break;
			   			
				$dataexcel = array('content'		=> $data['cells'][$i][19],
							  'search_engine_data'		=> $data['cells'][$i][3],
							  'username'	=> $data['cells'][$i][15],
							  'name'		=> $data['cells'][$i][16],
							  'profile_pic'	=> $data['cells'][$i][14],
							  'photo'		=> htmlspecialchars($data['cells'][$i][1]),
							  'author_id'	=> $data['cells'][$i][4],
							  'created_on'	=> $data['cells'][$i][5],
							  'created'		=> $data['cells'][$i][6],
							  'via_fb'		=> $data['cells'][$i][7],
							  'status'		=> $data['cells'][$i][11],
							  'entity_id'	=> $data['cells'][$i][18],
							  'max_id'		=> $data['cells'][$i][12],
							  'since_id'	=> $data['cells'][$i][13] );
							  
				$this->search_content_m->insert_search_content($dataexcel);
				
			}
			//var_dump($this->db->last_query()); 
			//die();
			redirect(site_url(ADMIN_URL.'/search_engine'));
			
		}    
	
	}
	
	function export_kandidat_detail($kandidat=0, $date_from=0, $date_end=0){
		//$status = $this->input->post('status');
		//$sorted = $this->input->post('sorted');
		//$keyword = $this->input->post('kandidat_name');
        //if($status){
			$info_kandidat = $this->db->get_where('photo', array('id'=>$kandidat));
			$nama = str_replace(" ", "-", $info_kandidat->row('kid_name'));
        	$nama_file = 'data_kandidat_'.$nama;
            
            if($date_from){
				$arr_date = explode('-', $date_from);
				$new_date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0];
				$start = strtotime($new_date);
				$this->db->where('photo_detail.created_on >=', $start);
			}
			if($date_end){
				$arr_date = explode('-', $date_end);
				$new_date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' 23:59:59';
				$end = strtotime($new_date);
				$this->db->where('photo_detail.created_on <=', $end);
			}
			
			$this->db->select('photo_detail.*, profiles.user_id as user_id_profiles, users.email, users.username');
			$this->db->join('profiles', 'profiles.fb_id=photo_detail.user_id', 'left');
			$this->db->join('users', 'users.id=profiles.user_id', 'left');
			$this->db->order_by('photo_detail.id', 'DESC');
			$data = $this->db->get_where('photo_detail', array('photo_detail.id_photo'=>$kandidat));
			//var_dump($data->result_array());
			//var_dump($this->db->last_query()); die();
			
			$this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Data Kandidat '.$info_kandidat->row('kid_name') );
            
            $count = 2;
            $this->excel->getActiveSheet()->setCellValue('A1', 'NO');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Email');   
            $this->excel->getActiveSheet()->setCellValue('C1', 'Username');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Facebook ID');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Date');
            
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
            
            $no=1;
            foreach($data->result() as $dt){
                $this->excel->getActiveSheet()->setCellValue('A'.$count, $no);   
                $this->excel->getActiveSheet()->setCellValueExplicit('B'.$count, $dt->email, PHPExcel_Cell_DataType::TYPE_STRING); 
                $this->excel->getActiveSheet()->setCellValue('C'.$count, $dt->username, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('D'.$count, $dt->user_id, PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValue('E'.$count, $dt->created, PHPExcel_Cell_DataType::TYPE_STRING);
                $count++; 
                $no++;
            }
            
            foreach(range('A','H') as $columnID) {
                $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            /*
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            //$this->excel->getActiveSheet()->mergeCells('A1:D1');
            */
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             
            $filename = $nama_file.'_'.date('d-m-Y').'xls'; 
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            $objWriter->save('php://output');
        /*
		}
        
        $this->template
            ->append_js('module::jquery.ui.datepicker.js')
			->append_js('module::export.js')
			->title('Export Secret Code')
			//->set('data_cabang', $data_cabang)
            ->build('admin/export_page');
		*/
	}
	
	public function facebook_user() {
		$this->template->active_section = "Facebook";
		$base_where = array();
		if ($this->input->post('f_keywords')) {
			$base_where['keywords'] = $this->input->post('f_keywords');
		}
		if ($this->input->post('f_date_start')) {
			$arr_date = explode('-', $this->input->post('f_date_start'));
			$date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' 00:00:00';
			$base_where['start_date'] = $date;
		}
		if ($this->input->post('f_date_end')) {
			$arr_date2 = explode('-', $this->input->post('f_date_end'));
			$date2 = $arr_date2[2].'-'.$arr_date2[1].'-'.$arr_date2[0].' 23:59:59';
			$base_where['end_date'] = $date2;
		}
		
		$total_rows = $this->search_engine_m->get_list_fb_user($base_where)->num_rows();
		$pagination = create_pagination(ADMIN_URL.'/search_engine/index', $total_rows); 

		$this->db->limit($pagination['limit'], $pagination['offset']);	
		$data = $this->search_engine_m->get_list_fb_user($base_where)->result();
		
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		/*$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('total_rows', $total_rows)
			->set('data', $data)
            ->build('admin/facebook_user');*/
			
		$this->template
			->title($this->module_details['name'])
			->append_js('admin/filter.js')
			->set_partial('filters', 'admin/partials/filters_fb_user')
			->set('pagination', $pagination)
			->set('total_rows', $total_rows)
			->set('data', $data);
			
			$this->input->is_ajax_request()
			? $this->template->build('admin/tables/fb_user')
			: $this->template->build('admin/facebook_user');
	}
	
	public function facebook_video($fb_id = 0) {		
		
		$this->template->active_section = "Facebook";
		
		$base_where = array();
		$base_where['userid'] = $fb_id;
		//$base_where['status'] = 'all';
		
		if ($this->input->post('f_status')) {			
			$base_where['status'] = $this->input->post('f_status');
		}

		if ($this->input->post('f_favorite')) {			
			$base_where['favorite'] = $this->input->post('f_favorite');
		}

		/*if ($this->input->post('f_top')) {
			$base_where['top'] = $this->input->post('f_top');
		}
		if ($this->input->post('f_source')) {
			$base_where['source'] = $this->input->post('f_source');
		}*/
		if ($this->input->post('f_keywords')) {
			$base_where['keywords'] = $this->input->post('f_keywords');
		}
		if ($this->input->post('f_date_start')) {
			$arr_date = explode('-', $this->input->post('f_date_start'));
			$date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' 00:00:00';
			$base_where['start_date'] = $date;
		}
		if ($this->input->post('f_date_end')) {
			$arr_date2 = explode('-', $this->input->post('f_date_end'));
			$date2 = $arr_date2[2].'-'.$arr_date2[1].'-'.$arr_date2[0].' 23:59:59';
			$base_where['end_date'] = $date2;
		}
		//var_dump($base_where);
		//echo '<br />br />';*/
				
		$total_rows = $this->search_engine_m->get_list_fb_video($base_where)->num_rows();
		$pagination = create_pagination(ADMIN_URL.'/search_engine/index', $total_rows, 25, 5);
		//var_dump($this->db->last_query());

		$this->db->limit($pagination['limit'], $pagination['offset']);	
		$data = $this->search_engine_m->get_list_fb_video($base_where)->result();
		//var_dump($this->db->last_query());
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		$this->template
			->title($this->module_details['name'])
			->append_js('admin/filter.js')
			->set_partial('filters', 'admin/partials/filters_fb_video')
			->set('pagination', $pagination)
			->set('total_rows', $total_rows)
			->set('data', $data)
			->set('fb_id', $fb_id);
			
			$this->input->is_ajax_request()
			? $this->template->build('admin/tables/fb_video')
			: $this->template->build('admin/facebook_video');
			
	}

	public function fb_create($user_id=0,$id_db=0) {
		
		//$post 	= new stdClass();
		$data_create = new stdClass;
		$rules 	= array_merge($this->validation_fb);

		$this->form_validation->set_rules($rules);
		$data_db = $this->search_engine_m->get_cerita_by($id_db)->row();
		$created_on = now();
        $id= 0;
		if (($id_db == 0) && (empty($_FILES['video_preview']['name']))) {
			$this->form_validation->set_rules('video_preview', 'Video Preview', 'required');
		}
		
		if ($this->form_validation->run()) {
			$data_inputan = array();
			$data_inputan['entity_id'] = "";
			if ($this->input->post('id') == "") {
				$str = $this->input->post('video_url');
				preg_match_all('!\d+!', $str, $matches);
				foreach($matches as $item) {
					foreach($item as $itm) {
						if ($itm > 100000) {
							if ($itm != $user_id) {
								if ($data_inputan['entity_id'] == "") {
									$data_inputan['entity_id'] = $itm;
								}
							}
						}
					}
				}
				$cek = $this->db->get_where('search_engine_data', array('entity_id'=>$data_inputan['entity_id']));
				
				if($cek->num_rows() ==  0 ) {
					$token = $this->db->get_where('settings', array('slug'=>'fb-token'));
					$fb_token = $token->row('value');
					$this->facebook->setAccessToken($fb_token)->setExtendedAccessToken();
					$new_token = $this->facebook->getAccessToken();
					$this->db->update('settings', array('value'=>$new_token), array('slug'=>'fb-token'));
					
					$data_fb = $this->facebook->api('/'.$this->input->post('userid'));
					
					$str = $data_fb['link'];
					preg_match_all('!\d+!', $str, $matches);
					foreach($matches as $item) {
						foreach($item as $itm) {
							$fb_id_app = $itm;
						}
					}
					
					$data_inputan['name'] = $data_fb['name'];
					$data_inputan['userid'] = $fb_id_app;
					$data_inputan['description'] = $this->input->post('desc');
					$data_inputan['photo_profile'] = 'https://graph.facebook.com/'.$data_fb['id'].'/picture?type=square';
					$data_inputan['search_engine_data'] = $this->input->post('video_url');
					//$data_inputan['video_preview'] = $upload['upload_data']['file_name'];
					$data_inputan['created_on'] = strtotime(date('Y-m-d H:i:s'));
					$data_inputan['created'] = date('Y-m-d H:i:s');
					$data_inputan['via'] = "facebook";
					$data_inputan['status'] = $this->input->post('status');
					$data_inputan['favorite'] = $this->input->post('favorite');
					//entity id pas create ndak usah di insert jadi null dia soalnya
					//$data_inputan['entity_id'] = $this->input->post('entity_id');
					
					if ($id_db == 0) {
						if ($id = $this->search_engine_m->insert_data($data_inputan)) {
							if ($data_inputan['status'] == "draft") {
								$drafted = '_'.$id.'_drafted';
								$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
								$new_filename_video = $arr_filename_video['0'].$drafted.'.'.$arr_filename_video['1'];;
								
								$upload = $this->do_upload($new_filename_video);
								
								$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
								$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
							} else {
								$arr_filename_video = explode('.', $_FILES['video_preview']['name']);
								$new_filename_video = $arr_filename_video['0'].'.'.$arr_filename_video['1'];;
								
								$upload = $this->do_upload($new_filename_video);
								
								$this->db->update('search_engine_data', array('video_preview'=>'/uploads/default/files/facebook_image/'.$new_filename_video), array('id'=>$id));
								$this->session->set_flashdata('success', sprintf($this->lang->line('video:post_add_success'), $data_inputan['name']));
							}
						}
					} else {
						if ($data_inputan['status'] == "draft") {
							$status_curr = $this->db->get_where('search_engine_data', array('id'=>$id_db, 'status'=>'live'));
							if($status_curr->num_rows() >  0 ) {
								if ($_FILES['video_preview']['name'] == "") {
									$thumb_path = $status_curr->row('video_preview');
									if($thumb_path){
										$drafted = '_'.$id_db.'_drafted';
										$arr_thumb = explode('.', $thumb_path);
										$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
										$old_name = getcwd().$thumb_path;
										$new_name = getcwd().$new_thumb_path;
										rename($old_name, $new_name);
									}
								} else {
									$thumb_path = $_FILES['video_preview']['name'];
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);
									$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
			
								if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
			
								$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
							} else {
								
								if ($_FILES['video_preview']['name'] !== "") {
									$thumb_path = $_FILES['video_preview']['name'];
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);
									$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
			
								if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
								
								$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
							}
						} else {
							$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_db));
							//tambahan untuk ngebitly
							$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
							
							if(strpos($data_url, 'localhost')!==false)
							{
								$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
							}
							
							$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
							
						
							if(!$data_bitly )
							{
								$this->load->library('bitly');
								$data_bitlys = $this->bitly->shorten($data_url);
								$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
							}
							
							//end tambahan ngebitly
							
							//tambahan bitly favorite
							if($data_inputan['favorite'] =='ya')
							{
								$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
							
								if(strpos($data_url, 'localhost')!==false)
								{
									$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
								}
								
								$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
				
				
								if(!$data_bitly )
								{
									$this->load->library('bitly');
									$data_bitlys = $this->bitly->shorten($data_url);
									$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
								}
							}
							//end tambahan bitly favorite
							$thumb_path = $data_db->video_preview;
							if($thumb_path){
								if ($_FILES['video_preview']['name'] == "") {
									$drafted = '_'.$id_db.'_drafted';
									$arr_thumb = explode('.', $thumb_path);					
									$arr_thumb_path = explode($drafted, $arr_thumb['0']);
									$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];
				
									$old_name = getcwd().$thumb_path;
									$new_name = getcwd().$new_thumb_path;
									rename($old_name, $new_name);
								} else {
									$new_thumb_path = $_FILES['video_preview']['name'];
				
									if ($upload = $this->do_upload($new_thumb_path)) {
										@unlink(getcwd().$data_db->video_preview);
									}
									$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
								}
							}
			
							if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
							
							$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
						}
						$id = $id_db;
					}
					($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/search_engine/facebook_search_engine/'.$user_id) : redirect(ADMIN_URL.'/search_engine/fb_create/'.$user_id.'/'.$id);
				} else {
					$this->session->set_flashdata('error', "Video yang anda isikan sudah ada dalam database.");
					redirect(ADMIN_URL.'/search_engine/fb_create/'.$user_id);
				}
			} else {
				$id_db = $this->input->post('id');
					$token = $this->db->get_where('settings', array('slug'=>'fb-token'));
					$fb_token = $token->row('value');
					$this->facebook->setAccessToken($fb_token)->setExtendedAccessToken();
					$new_token = $this->facebook->getAccessToken();
					$this->db->update('settings', array('value'=>$new_token), array('slug'=>'fb-token'));
					
					$data_fb = $this->facebook->api('/'.$this->input->post('userid'));
					
					$str = $data_fb['link'];
					preg_match_all('!\d+!', $str, $matches);
					foreach($matches as $item) {
						foreach($item as $itm) {
							$fb_id_app = $itm;
						}
					}
					
					$data_inputan['name'] = $data_fb['name'];
					$data_inputan['userid'] = $fb_id_app;
					$data_inputan['description'] = $this->input->post('desc');
					$data_inputan['photo_profile'] = 'https://graph.facebook.com/'.$data_fb['id'].'/picture?type=square';
					$data_inputan['search_engine_data'] = $this->input->post('video_url');
					$data_inputan['created_on'] = strtotime(date('Y-m-d H:i:s'));
					$data_inputan['created'] = date('Y-m-d H:i:s');
					$data_inputan['via'] = "facebook";
					$data_inputan['status'] = $this->input->post('status');
					$data_inputan['favorite'] = $this->input->post('favorite');
					$data_inputan['entity_id'] = $this->input->post('entity_id');
					
				if ($data_inputan['status'] == "draft") {
					$status_curr = $this->db->get_where('search_engine_data', array('id'=>$id_db, 'status'=>'live'));
					if($status_curr->num_rows() >  0 ) {
						if ($_FILES['video_preview']['name'] == "") {
							$thumb_path = $status_curr->row('video_preview');
							if($thumb_path){
								$drafted = '_'.$id_db.'_drafted';
								$arr_thumb = explode('.', $thumb_path);
								$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
								$old_name = getcwd().$thumb_path;
								$new_name = getcwd().$new_thumb_path;
								rename($old_name, $new_name);
							}
						} else {
							$thumb_path = $_FILES['video_preview']['name'];
							$drafted = '_'.$id_db.'_drafted';
							$arr_thumb = explode('.', $thumb_path);
							$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
							if ($upload = $this->do_upload($new_thumb_path)) {
								@unlink(getcwd().$data_db->video_preview);
							}
							$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
						}
			
						if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
			
						$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
					} else {
						if ($_FILES['video_preview']['name'] !== "") {
							$thumb_path = $_FILES['video_preview']['name'];
							$drafted = '_'.$id_db.'_drafted';
							$arr_thumb = explode('.', $thumb_path);
							$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];
				
							if ($upload = $this->do_upload($new_thumb_path)) {
								@unlink(getcwd().$data_db->video_preview);
							}
							$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
						}
			
						if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
								
						$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
					}
				} else {
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$id_db));
					//tambahan untuk ngebitly
					$data_url = site_url(array('galeri','index',1,$info_bubble->row('id')));
					
					if(strpos($data_url, 'localhost')!==false)
					{
						$data_url ='https://ramadan.coca-cola.co.id/galeri/index/1/'.$info_bubble->row('id');
					}
					
					$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
					
					
					if(!$data_bitly )
					{
						$this->load->library('bitly');
						$data_bitlys = $this->bitly->shorten($data_url);
						$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					}
					
					//end tambahan ngebitly
					
					//tambahan bitly favorite
					if($data_inputan['favorite'] =='ya')
					{
						$data_url = site_url(array('favorite')).'#'.$info_bubble->row('id');
					
						if(strpos($data_url, 'localhost')!==false)
						{
							$data_url ='https://ramadan.coca-cola.co.id/favorite#'.$info_bubble->row('id');
						}
						
						$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
		
		
						if(!$data_bitly )
						{
							$this->load->library('bitly');
							$data_bitlys = $this->bitly->shorten($data_url);
							$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
						}
					}
					//end tambahan bitly favorite
					$thumb_path = $data_db->video_preview;
					if($thumb_path){
						if ($_FILES['video_preview']['name'] == "") {
							$drafted = '_'.$id_db.'_drafted';
							$arr_thumb = explode('.', $thumb_path);					
							$arr_thumb_path = explode($drafted, $arr_thumb['0']);
							$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];
				
							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						} else {
							$new_thumb_path = $_FILES['video_preview']['name'];
				
							if ($upload = $this->do_upload($new_thumb_path)) {
								@unlink(getcwd().$data_db->video_preview);
							}
							$new_thumb_path = '/uploads/default/files/facebook_image/'.$new_thumb_path;
						}
					}
			
					if($new_thumb_path){ $data_inputan['video_preview'] = $new_thumb_path; }
							
					$this->db->update('search_engine_data', $data_inputan, array('id'=>$id_db));
				}
				$id = $id_db;
				$this->session->set_flashdata('success', "Data video sudah berhasil diupdate.");
				($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/search_engine/facebook_search_engine/'.$user_id) : redirect(ADMIN_URL.'/search_engine/fb_create/'.$user_id.'/'.$id);
			}
		}
		else
		{
				
			//$data_create = new stdClass;
			
			foreach ($this->validation_rules as $key => $field) {
				
				$data_create->$field['field'] = set_value($field['field']);
			}
			
			$data_create->created_on = $created_on;
		}

		//$data_pic = $this->db->get_where('photo_detail', array('id_photo'=>$id, 'status !='=>'deleted'));
       	$this->template
			->title($this->module_details['name'], lang('product:create_title'))
			->set('data_create', $data_db)
			->set('aksi', 'create')
			->set('user_id', $user_id)
			->set('id_db', $id_db)
            ->build('admin/fb_create');
	}
	
	function fb_delete($id=0){
		if($id!=0){
			$info_photo = $this->db->get_where('search_engine_data', array('id'=>$id));
			$photo_name = $info_photo->row('name');
			$del = $this->db->update('search_engine_data', array('status'=>'deleted'), array('id'=>$id));
			if($info_photo->num_rows() >  0 ){
				$new_photo_path = $new_thumb_path = '';
				$drafted = '_'.$id.'_drafted';
				if ($info_photo->row('via') == 'twitter') {
					$photo_path = $info_photo->row('photo_profile');
					if($photo_path){
						$arr_photo = explode('.', $photo_path);
						$new_photo_path = $arr_photo['0'].$drafted.'.'.$arr_photo['1'];
	
						$old_name = getcwd().$photo_path;
						$new_name = getcwd().$new_photo_path;
						rename($old_name, $new_name);
					}
				}
				$thumb_path = $info_photo->row('video_preview');
				if($thumb_path){
					$arr_thumb = explode('.', $thumb_path);
					$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];

					$old_name = getcwd().$thumb_path;
					$new_name = getcwd().$new_thumb_path;
					rename($old_name, $new_name);
				}

				$data_update = array();
				if($new_photo_path){ $data_update['photo_profile'] = $new_photo_path; }
				if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }
				
				$this->db->update('search_engine_data', $data_update, array('id'=>$id));
			}

				
			if($del){
				$this->session->set_flashdata('success', 'Video '.$photo_name.' deleted');
			}else{
				$this->session->set_flashdata('error', 'Video '.$photo_name.' can"t delete');
			}
			redirect(site_url(ADMIN_URL.'/search_engine/facebook_search_engine/'.$info_photo->row('userid')));
		}
		
	}
	
	function fb_action(){
		$btnAction = $this->input->post('btnAction');
		$id = $this->input->post('cerita_id');
		
		//-- THE ACTION
		if(count($id) > 0){
			foreach ($id as $key => $value) {
				if($btnAction=='live'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$value));
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$value.'_drafted';
						
						$thumb_path = $info_bubble->row('video_preview');
						if($thumb_path){		
							$arr_thumb = explode('.', $thumb_path);					
							$arr_thumb_path = explode($drafted, $arr_thumb['0']);
							$new_thumb_path = $arr_thumb_path['0'].'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}
						
						$data_update = array();
						$data_update['status'] = $btnAction;
						if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$value));
							
					}else{
						$this->db->update('search_engine_data', array('status'=>$btnAction), array('id'=>$value));
					}
				}

				if($btnAction=='draft'){
					$info_bubble = $this->db->get_where('search_engine_data', array('id'=>$value, 'status'=>'live'));
					if($info_bubble->num_rows() >  0 ){
						$new_photo_path = $new_thumb_path = '';
						$drafted = '_'.$value.'_drafted';
						
						$thumb_path = $info_bubble->row('video_preview');
						if($thumb_path){
							$arr_thumb = explode('.', $thumb_path);
							$new_thumb_path = $arr_thumb['0'].$drafted.'.'.$arr_thumb['1'];

							$old_name = getcwd().$thumb_path;
							$new_name = getcwd().$new_thumb_path;
							rename($old_name, $new_name);
						}

						$data_update = array();
						$data_update['status'] = $btnAction;
						if($new_thumb_path){ $data_update['video_preview'] = $new_thumb_path; }

						$this->db->update('search_engine_data', $data_update, array('id'=>$value));
					}else{
						$this->db->update('search_engine_data', array('status'=>$btnAction), array('id'=>$value));
					}
				}
			}
			$this->session->set_flashdata('success', 'Success : update status data');
		}
		else{
			$this->session->set_flashdata('error', 'Error : No data selected');	
		}	
		redirect(ADMIN_URL.'/search_engine/facebook_search_engine/'.$this->input->post('fb_id'));
	}
	
	public function clear_video_crawl() {
		$data_update = array();
		$data_update['max_id'] = NULL;
		$data_update['since_id'] = NULL;
		$this->db->query("UPDATE default_video_status SET max_id = NULL, since_id = NULL, total_page = NULL");
		$this->db->query("TRUNCATE default_video");
		$this->db->query("TRUNCATE default_video_approve");
	}
}
