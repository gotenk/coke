<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Controllers
 */
class Admin_master_list_words extends Admin_Controller {
	
	protected $section = 'master_list_name';

	protected $validation_rules = array(
		'status' => array(
			'field' => 'status',
			'label' => 'lang:search_engine:status_label',
			'rules' => 'required|trim|xss_clean'
		),
		'word' => array(			
			'field' => 'name',
			'label' => 'lang:search_engine:word_label',
			'rules' => 'required|xss_clean'
		),
	);
    

	public function __construct() {
		
		parent::__construct();

		$this->load->model(array('list_word_m'));
		$this->lang->load(array('search_engine'));
        $this->load->library('image_lib');
		$this->load->library('facebook');
		$this->load->library(array('form_validation'));
		$this->load->helper('inflector');
		
		$this->template->set('master_status',array('live'=>lang('search_engine:live_label'),'draft'=>lang('search_engine:draft_label'),'deleted'=>lang('search_engine:delete_label'),'black_listed'=>lang('search_engine:black_listed_label')));
		
	}

	public function index() {		
		
		$base_where = array();
		//$base_where['status'] = 'all';
		
		if ($this->input->post('f_status')) {			
			$base_where['status'] = $this->input->post('f_status');
		}
		if ($this->input->post('f_keywords')) {
			$this->list_word_m->like('name',$this->input->post('f_keywords'));
			//$base_where['name'] = $this->input->post('f_keywords');
		}
			
		$total_rows = $this->list_word_m->count_by($base_where);
		$pagination = create_pagination(ADMIN_URL.'/search_engine/master_list_name/index', $total_rows,null,5); 
		
		if ($this->input->post('f_keywords')) {
			$this->list_word_m->like('name',$this->input->post('f_keywords'));
			//$base_where['name'] = $this->input->post('f_keywords');
		}
		$this->db->limit($pagination['limit'], $pagination['offset']);	
		$data = $this->list_word_m->get_many_by($base_where);
		
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		$this->template
			->title($this->module_details['name'])
			->append_js('admin/filter.js')
			->set_partial('filters', 'admin/master_list_name/partials/filters')
			->set('pagination', $pagination)
			->set('total_rows', $total_rows)
			->set('data', $data);

			$this->input->is_ajax_request()
			? $this->template->build('admin/master_list_name/tables/master_list_name')
			: $this->template->build('admin/master_list_name/index');
		
	}

	function action(){
		$btnAction = $this->input->post('btnAction');
		$id = $this->input->post('id');
		$this->load->model('search_engine/bitly_cache_m');
		
		//--- CEK TOP ITEM
		/*if($btnAction=='top'){
			$max_top = Settings::get('max_top');
			$data_top = $this->list_word_m->cek_top();	
			$cek_top = $data_top + intval(count($id));
			if($cek_top > $max_top ){
				$this->session->set_flashdata('error', 'Error : Data TOP to many (max '.$max_top.')');			
				redirect(ADMIN_URL.'/search_engine');
			}
		}*/

		//-- THE ACTION
		if(count($id) > 0){
			$data_to_update =array();
			foreach ($id as $key => $value) {
				/*if($btnAction=='top'){
					$this->db->update('search_engine_data', array('id'=>$value));
				}

				if($btnAction=='untop'){
					$this->db->update('search_engine_data', array('id'=>$value));
				}*/
				$info_bubble = $this->list_word_m->get_by(array('id'=>$value));
				$data_to_update[] = $info_bubble->name;
				if($btnAction=='live'){
					
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
					
					$this->list_word_m->update_by(array('id'=>$value), array('status'=>$btnAction));
				}

				if($btnAction=='draft'){
					$this->list_word_m->update_by(array('id'=>$value), array('status'=>$btnAction));
				}
				
				
			}
			if(count($data_to_update))
			{
				$this->session->set_flashdata('success',sprintf(lang('search_engine_success_change_status'),implode(',',$data_to_update),lang('search_engine:'.$btnAction.'_label') ));
			}
			else {
				$this->session->set_flashdata('error',lang('search_engine_change_status_error') );
			}
			
			
		}
		else{			
			$this->session->set_flashdata('error', 'Error : No data selected');	
		}
		
		redirect(ADMIN_URL.'/search_engine/master_list_name');
	}

	public function create() {		
		$this->validation_rules['word']['rules'] .='|callback__check_name';
		$this->form_validation->set_rules($this->validation_rules);
		$data = new stdClass;
		
		foreach($this->validation_rules as $item)
		{
			$data->{$item['field']} = $this->input->post($item['field']);
			
		}		
		
		
		if($this->form_validation->run())
		{
			$data->created = date('Y-m-d H:i:s');
			$data->slug = slugify($data->name);
			
			if($id = $this->list_word_m->insert((array)$data))
			{
				$this->session->set_flashdata('success',sprintf( lang('search_engine:success_create_master_list_word_message'),$data->name ) )  ; 
				redirect( (($this->input->post('btnAction') =='save_exit') ? ADMIN_URL.'/search_engine/master_list_name' : ADMIN_URL.'/search_engine/master_list_name/edit/'.$id) );
			}
			else {
				$this->template->set('messages',array('error'=>lang('search_engine:failed_create_master_list_word_message')));
			}			
		
			
		}
		
		$this->template->set('data',$data);
		$this->template->build('admin/master_list_name/form');
	}
	

	public function edit($id = 0) {
		
		$id or redirect(ADMIN_URL.'/search_engine/master_list_name');
		
		$data = $this->list_word_m->get_by(array('id'=>$id));
		
		$data or redirect(ADMIN_URL.'/search_engine/master_list_name');
		
		$this->validation_rules['word']['rules'] .='|callback__check_name['.$id.']';
		
		$this->form_validation->set_rules($this->validation_rules);
		
		foreach($this->validation_rules as $item)
		{
			$data->{$item['field']} = $this->input->post($item['field'])?  $this->input->post($item['field']):$data->{$item['field']} ;		
			
		}
		
		if($this->form_validation->run())
		{
			unset($data->created);
			$new_id = $data->id;
			unset($data->id);
			$data->slug = slugify($data->name);
			
			$success = $this->list_word_m->update_by(array('id'=>$new_id),(array)$data);
			if($success)
			{
				$this->session->set_flashdata('success',sprintf( lang('search_engine:success_edit_master_list_word_message'),$data->name ) )  ; 
				redirect( (($this->input->post('btnAction') =='save_exit') ? ADMIN_URL.'/search_engine/master_list_name' : ADMIN_URL.'/search_engine/master_list_name/edit/'.$id) );
			}
			else {
				$this->template->set('messages',array('error'=>lang('search_engine:failed_edit_master_list_word_message')));
			}		
		}
		
		$this->template->set('data',$data);
		$this->template->build('admin/master_list_name/form');
	}


	function delete(){
		if($id = $this->input->post('id'))
		{
			if($id!=0){
				$info_photo = $this->db->get_where('search_engine_data', array('id'=>$id));
				$photo_name = $info_photo->row('name');
				$del = $this->list_word_m->update_by( array('id'=>$id),array('status'=>'deleted'));
				
					
				if($del){
					$this->session->set_flashdata('success', 'Video '.$photo_name.' deleted');
				}else{
					$this->session->set_flashdata('error', 'Video '.$photo_name.' can"t delete');
				}
				redirect(site_url(ADMIN_URL.'/search_engine/master_list_name'));
			}
		}
		else {
			redirect(site_url(ADMIN_URL.'/search_engine/master_list_name'));
		}
		
		
	}

	public function _check_name($name,$id = null)
	{		
		$this->form_validation->set_message('_check_name', lang('search_engine:word_already_exists'));
		return $this->list_word_m->check_exists('name', $name, $id);
	}
}
