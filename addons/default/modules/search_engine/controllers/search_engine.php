<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manages image selection and insertion for WYSIWYG editors
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\WYSIWYG\Controllers
 */
class Search_engine extends Public_Controller {


	public function __construct() {
		parent::__construct();
		
        $this->template->set_layout('default.html');
		$this->load->model('search_engine_m');
		$this->load->model('word_used_m');
		$this->load->model('list_word_m');
		//$this->template->set_layout('photo.html');
		//$this->lang->load('product');
        $this->load->helper('text');
	}

	public function index() {
		
		if($data_name = $this->input->post('search_field',true))
		{
			$this->template->set('search_field',$data_name);
			$this->db->like('screen_name',$data_name);
		}
		$total = $this->search_engine_m->count_by(array('status'=>'live'));
	
		$data_pagination  = create_pagination(site_url(array('galeri')),$total, 8, 2);
		if($data_name = $this->input->post('search_field',true))
		{
			$this->db->like('screen_name',$data_name);
		}
		$this->db->limit($data_pagination['limit'], $data_pagination['offset']);	
		$data = $this->search_engine_m->get_many_by(array('status'=>'live'));
		$this->input->is_ajax_request() and $this->template->set_layout(false);
		Asset::js_inline('var totalPages='.$data_pagination['total_pages'].' ; ');
		$this->input->is_ajax_request() and $this->template->set_layout(false);
		$this->input->is_ajax_request() and $this->template->set('is_ajax',true);
		$this->template
			->append_js('theme::masonry.pkgd.min.js')
			->append_js('theme::imageloader.js')
			->append_js('theme::lib/greensock/TweenMax.min.js')
			->append_js('theme::ScrollMagic.min.js')
			->append_js('theme::plugins/animation.gsap.min.js')
			->append_js('theme::jquery.autocomplete.min.js')
			->append_css('theme::autocomplete.css')
			->append_js('theme::plugins/debug.addIndicators.min.js')
			->append_js('theme::jquery.ba-throttle-debounce.min.js')
			->append_js('theme::galeri.js')
			->set('data', $data)
			->set('pagination',$data_pagination)
			->title('Galeri')
			->build('index');		
	}

	private function resize_image($new_file_name)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = $new_file_name;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 500;
		$config['height'] = 500;
		//$config['new_image'] = $path.$new_file_name;
		$this->load->library('image_lib');
		$this->image_lib->initialize($config);
		
		if ( ! $this->image_lib->resize())
		{
		    echo $this->image_lib->display_errors();die();
		}
	}
	
	public function insert_word()
	{
		if($data_name=$this->input->post('word',true))
		{
			if(strlen($data_name)>30)
			{
				$data_name = substr($data_name, 0,30);
			}
			$data_found = $this->list_word_m->get_by(array('slug'=>slugify($data_name),'status'=>'live'));
			
			if($data_found)
			{
				//generate image if not exists
				//default path save file
				$save_file = getcwd().'/uploads/default/files/sac/';
				if(! is_dir($save_file))
				{
					mkdir($save_file,0775,true);
				}
				$filename = $save_file.strtolower($data_found->name).'_'.$data_found->id.'.png';
				if(! is_file($filename ))
				{
					$this->generate_image($filename,$data_found->name);
					$this->resize_image($filename);
					
				}
				//update count list
				$count_word = $this->word_used_m->get_by(array('list_name_id'=>$data_found->id));
				if($count_word)
				{
					$new_count = intval($count_word->count_list_name) +1;
					$this->word_used_m->update_by(array('id'=>$count_word->id),array('count_list_name'=>$new_count));
				}
				else {
					$this->word_used_m->insert(array('count_list_name'=>1,'list_name_id'=>$data_found->id,'created'=>date('Y-m-d H:i:s') ));
				}
				//set session to success or error page
				$this->session->set_flashdata('success_page',$data_found->id);
				$this->session->set_flashdata('sample_page',$data_found->id);
				redirect('yay');
				
			}
			else {
				if($id = $this->list_word_m->insert(array('name'=>$data_name,'slug'=>slugify($data_name),'status'=>'draft','created'=>date('Y-m-d H:i:s'),'from_user'=>1 )))
				{
					$count_word = $this->word_used_m->get_by(array('list_name_id'=>$id));
					if($count_word)
					{
						$new_count = intval($count_word->count_list_name) +1;
						$this->word_used_m->update_by(array('id'=>$count_word->id),array('count_list_name'=>$new_count));
					}
					else {
						$this->word_used_m->insert(array('count_list_name'=>1,'list_name_id'=>$id,'created'=>date('Y-m-d H:i:s') ));
					}
					
					//set session to success or error page
					$this->session->set_flashdata('error_page',true);
					redirect('oops');
				}
				else {
					redirect('');
				}
			}
		}
		else {
			redirect('');
		}
	}
	
	public function auto_suggest()
	{
		if($name = $this->input->post('query',true))
		{	
			$this->db->like('screen_name',$name);
			$this->db->limit(5);
			$this->db->order_by('id','desc');
			$this->db->group_by('screen_name');
			$data =$this->search_engine_m->get_many_by(array('status'=>'live'));
			$data_names =array();
				if($data)
				{
					$data_names =array_for_select($data,'screen_name');
					
					
					
				}
			
				if($data_name = $this->input->post('query',true))
				{
					
					$this->db->like('screen_name',$data_name);
				}
				$total = $this->search_engine_m->count_by(array('status'=>'live'));
			
				$data_pagination  = create_pagination(site_url(array('galeri')),$total, 8, 3);
				if($data_name = $this->input->post('query',true))
				{
					$this->db->like('screen_name',$data_name);
				}
				$this->db->limit($data_pagination['limit'], $data_pagination['offset']);	
				$data = $this->search_engine_m->get_many_by(array('status'=>'live'));
				$this->input->is_ajax_request() and $this->template->set_layout(false);
				$this->input->is_ajax_request() and $this->template->set_layout(false);
				$this->input->is_ajax_request() and $this->template->set('is_ajax',true);
				$data_text = $this->template->build('index',array('data'=>$data),true);		
				$total_pages = $data_pagination['total_pages'];
				echo json_encode(array('suggestions'=>$data_names,'data_content'=>$data_text,'total_pages'=>$total_pages));
		
		}
		else {
				$total = $this->search_engine_m->count_by(array('status'=>'live'));			
				$data_pagination  = create_pagination(site_url(array('galeri')),$total, 8, 3);
				$this->db->limit($data_pagination['limit'], $data_pagination['offset']);	
				$data = $this->search_engine_m->get_many_by(array('status'=>'live'));
				$this->input->is_ajax_request() and $this->template->set_layout(false);
				$this->input->is_ajax_request() and $this->template->set_layout(false);
				$this->input->is_ajax_request() and $this->template->set('is_ajax',true);
				$data_text = $this->template->build('index',array('data'=>$data),true);		
				$total_pages = $data_pagination['total_pages'];
				echo json_encode(array('suggestions'=>array(),'data_content'=>$data_text,'total_pages'=>$total_pages));
			
		}
	}
	public function test()
	{
		$this->list_word_m->get_random_word();
		//$this->list_word_m->get_range_word(15);
	}
	private function generate_image($filename,$text)
	{
		
		$this->load->library('generate_image',array('font_path'=>getcwd().'/'.$this->module_details['path'].'/font/You2013.ttf',
	 								'image_path'=>getcwd().'/'.$this->module_details['path'].'/img/sac_template.png',
									'font_size' =>130,
									'minimum_margin_left_right'	=>100,
									'offset_y' => 150,
									'box_height' =>300,
									'box_width'=>700,
									'angle' => 0,
									'text_color'=>array(255,255,255),
									'opacity'=>1.0,
									'quality'=>99
										));
		
		$this->generate_image->set_text($text);
		return $this->generate_image->generate($filename,false);
			
	}	
	
		
}