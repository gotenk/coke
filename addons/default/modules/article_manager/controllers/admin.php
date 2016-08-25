<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	protected $section = 'article_manager';
	
	protected $validation_rules = array(
		array(
			'field' => 'title',
			'label' => 'lang:article_manager:title',
			'rules' => 'xss_clean|trim|required|max_length[255]|callback__check_title'
		),
		array(
			'field' => 'id',
			'rules' => 'xss_clean|trim|numeric'
		),
		array(
			'field' => 'status',
			'rules' => 'xss_clean|trim|numeric'
		),
		array(
			'field' => 'content',
			'label' => 'lang:article_manager:description',
			'rules' => 'trim'
		),
		array(
			'field' => 'created_on',
			'label' => 'lang:blog:date_label',
			'rules' => 'xss_clean|trim|required'
		),
		array(
			'field' => 'created_on_hour',
			'label' => 'lang:article_manager:created_hour',
			'rules' => 'xss_clean|trim|numeric|required'
		),
		array(
			'field' => 'created_on_minute',
			'label' => 'lang:article_manager:created_minute',
			'rules' => 'xss_clean|trim|numeric|required'
		),
		'userfile'=>array(
			'field' => 'userfile',
			'label' => 'lang:article_manager:image_upload',
			'rules' => 'xss_clean|required'	
		)
	);
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct();
		/*$MY_STRING= 'fdsafasf afds<br />
fsf<br />
sf<br />
sf<br />
sfs<br />
fs<br />
f<br />
fs<br />
fs<br />
fsdfsf
<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>
fsdfsfsdf<br />
fsfsfsffsf';
var_dump(explode('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>',xss_clean_without_style($MY_STRING)));die();*/
		// Load the required classes
		$this->load->model('article_manager_m');
		$this->lang->load('article_manager');
		$this->load->helper(array('inflector','text'));
		
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		
		// Date ranges for select boxes
		$this->template
			->set('hours', array_combine($hours = range(0, 23), $hours))
				->set('minutes', array_combine($minutes = range(0, 59), $minutes))
				->append_css('module::article_manager.css');
		
	}

	public function index()
	{
		$base_where = array('active' => -1);

		// ---------------------------
		// Twitter Filters
		// ---------------------------

		// Determine active param
		$base_where['status'] = $this->input->post('f_module',true) ? (int)$this->input->post('f_active',true) : $base_where['active'];

		// Keyphrase param
		if($this->input->post('f_keywords'))
		{
			$this->db->like('title',$this->input->post('f_keywords',true))->or_like('content',$this->input->post('f_keywords',true));
		}
		
		// Create pagination links
		switch(Settings::get('article_set_order'))
		{
			case  '0' : $pagination = create_pagination(ADMIN_URL.'/article_manager/index', $this->article_manager_m->order_by('id','desc',false)->get_many_by($base_where)->num_rows(),300); 
						$this->db->limit($pagination['limit'], $pagination['offset']);
						if($this->input->post('f_keywords',true))
						{
							$this->db->like('title',$this->input->post('f_keywords',true))->or_like('content',$this->input->post('f_keywords',true));
						}
						$articles = $this->article_manager_m->order_by('id','desc',false)->get_many_by($base_where);
						break;
			case  '1' : $pagination = create_pagination(ADMIN_URL.'/article_manager/index', $this->article_manager_m->order_by('date_custom_int','desc',false)->get_many_by($base_where)->num_rows(),300); 
						$this->db->limit($pagination['limit'], $pagination['offset']);
						if($this->input->post('f_keywords',true))
						{
							$this->db->like('title',$this->input->post('f_keywords',true))->or_like('content',$this->input->post('f_keywords',true));
						}
						$articles = $this->article_manager_m->order_by('date_custom_int','desc',false)->get_many_by($base_where);
						break;
			case  '2' : $pagination = create_pagination(ADMIN_URL.'/article_manager/index', $this->article_manager_m->order_by('order','desc',false)->get_many_by($base_where)->num_rows(),300);
						$this->db->limit($pagination['limit'], $pagination['offset']);
						if($this->input->post('f_keywords',true))
						{
							$this->db->like('title',$this->input->post('f_keywords',true))->or_like('content',$this->input->post('f_keywords',true));
						}
						$articles = $this->article_manager_m->order_by('order','asc',false)->get_many_by($base_where);		
						break;
		}	

		
		// Unset the layout if we have an ajax request
		if ($this->input->is_ajax_request())
		{
			$this->template->set_layout(false);
		}
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('articles', $articles)
			->set_partial('filters', 'admin/article/partials/filters')
			->append_js('admin/filter.js')
			->append_css('module::index.css')
			//->append_js('module::admin.js')
			->append_js('module::index.js');
		
		$this->input->is_ajax_request() ? $this->template->build('admin/article/tables/articles') : $this->template->build('admin/article/index');
	}


	/*public function update_status($id)
	{
		if ($this->input->post('update'))
		{
			$data = $this->article_manager_m->get_data_current_id($id);
			if ($data)
			{
				$new_data = (($data->status) == 0 ? 1 : 0 );
				$this->db->where('id',$id);
				$this->db->update('default_hashtag',array('status'=>$new_data));
				echo json_encode(array('status'=>'1','str'=> (( $new_data == 1)? lang('article_manager:active') :lang('article_manager:inactive') ) ));
			}
			else {
				echo json_encode(array('status'=>'0'));
			}
		}
		else {
			echo json_encode(array('status'=>'0'));
		}
	}*/
	
	public function change_status()
	{
		if($id = $this->input->post('id',true))
		{
			$data = $this->article_manager_m->get_by(array('id'=>$id));
			if ($data)
			{
				$new_data = (($data->status) == 0 ? 1 : 0 );
				$this->article_manager_m->update_by(array('id'=>$id),array('status'=>$new_data));
				$this->session->set_flashdata('success', sprintf(lang('article_manager:publish_success'), $data->title));
				
			}
			else {
				$this->session->set_flashdata('error',lang('article_manager:publish_error_group'));
			}
			
		}
		
		redirect(ADMIN_URL.'/article_manager');
		
	}
	
	private function status_alias($id)
	{
		$alias = 'draft';
		switch($id)
		{
			case '1' : $alias = ''; break;
			case '0' : $alias = 'draft'; break;
			default : $alias = 'draft';
		}
		
		return $alias;
	}
	public function create()
	{
		$article = new stdClass;
		if ($this->input->post('created_on',true))
		{
			$created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on',true), $this->input->post('created_on_hour',true), $this->input->post('created_on_minute',true)));
		}
		else
		{
			$created_on = now();
		}
		
		if(isset($_FILES['userfile']['size']) && $_FILES['userfile']['size'] )
		{
			unset($this->validation_rules['userfile']);
		}
		
		$this->form_validation->set_rules($this->validation_rules);
		// Validate the data
		if ($this->form_validation->run())
		{
			foreach ($this->validation_rules as $rule)
			{	
				if($rule['field'] !='content')
				{
					$article->{$rule['field']} = $this->input->post($rule['field'],true);
				}
				else {
					$article->{$rule['field']} = $this->input->post($rule['field']);
				}
			}
			$article->slug = slugify($article->title);
			unset($article->created_on_hour);
			unset($article->created_on_minute);
			unset($article->created_on);
			unset($article->userfile);
			unset($article->id);
			$article->content = sanitize_html($article->content);
			$article->date_custom = date('Y-m-d H:i:s',$created_on);
			$article->created_at = date('Y-m-d H:i:s');
			$article->date_custom_int = $created_on;
			
			if ($id = $this->article_manager_m->insert((array)$article))
			{
				
				// Fire an event. A new blog category has been created.
				Events::trigger('article_created', $id);
				$image_status = '_'.$id.( ( $this->status_alias( $this->input->post('status',true) ) ) ? '_'.$this->status_alias($this->input->post('status',true)) : '' ) ;
				$image_status_thumb = '_'.$id.'_thumb_'.(($this->status_alias($this->input->post('status',true) ) )? '_'.$this->status_alias($this->input->post('status',true)) : '' ) ;
				$arr_filename = explode('.', $_FILES['userfile']['name']);
				$new_filename = $arr_filename['0'].$image_status.'.'.end($arr_filename);
				$new_filename_thumb = $arr_filename['0'].$image_status_thumb.'.'.end($arr_filename);
				
				$upload = $this->do_upload($new_filename,$new_filename_thumb);
				if(! isset($upload))
				{
					$this->article_manager->delete_by(array('id'=>$id));
					$this->session->set_flashdata('error',$upload['error'] );
					
				}
				else {
					$this->article_manager_m->update_by(array('id'=>$id), array('picture'=>$upload['full_path'],'picture_thumb'=>$upload['full_path_thumb']) );
					$this->session->set_flashdata('success', sprintf(lang('article_manager:add_success'), $this->input->post('title',true)));
				}
				
			}
			else
			{
				$this->session->set_flashdata('error', lang('article_manager:add_error'));
			}

			($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/article_manager') : redirect(ADMIN_URL.'/article_manager/edit/'.$id);	
		}

		$article = new stdClass();

		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			if($rule['field'] == 'content')
			{
				
				$article->{$rule['field']} = sanitize_html($this->input->post($rule['field']));
			}
			else {
				$article->{$rule['field']} = $this->input->post($rule['field'],true);
			}
			
		}
		$article->created_on = now();
		$this->template
			->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
			->title($this->module_details['name'], lang('article_manager:create_title'))
			->set('article', $article)
			->set('mode', 'create')
			->append_js('module::article_form.js')
			->build('admin/article/form');
	}
	
	public function edit($id)
	{
			// Get the category
		$article = $this->article_manager_m->get($id);
		
		// ID specified?
		$article or redirect(ADMIN_URL.'/article_manager/index');
		
		if(isset($_FILES['userfile']['size']) && $_FILES['userfile']['size'] || !empty($article->picture) )
		{
			unset($this->validation_rules['userfile']);
		}
	
		if ($this->input->post('created_on',true))
		{
			$created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on',true), $this->input->post('created_on_hour',true), $this->input->post('created_on_minute',true)));
		}
		else
		{
			$created_on = $article->date_custom_int;
		}
		$this->form_validation->set_rules($this->validation_rules);
		// Validate the data
		if ($this->form_validation->run())
		{
			foreach ($this->validation_rules as $rule)
			{
				if($rule['field'] !='content')
				{
					$article->{$rule['field']} = $this->input->post($rule['field'],true);
				}
				else {
					$article->{$rule['field']} = $this->input->post($rule['field']);
				}
				
			}
			$article->slug = slugify($article->title);
			unset($article->created_on_hour);
			unset($article->created_on_minute);
			unset($article->created_on);
			unset($article->userfile);
			unset($article->id);
			$article->content = sanitize_html($article->content);
			$article->date_custom = date('Y-m-d H:i:s',$created_on);
			$article->date_custom_int = $created_on;
			if ($this->article_manager_m->update_by(array('id'=>$id),(array)$article))
			{
				
				// Fire an event. A new blog category has been created.
				Events::trigger('article_created', $id);
				if(isset($_FILES['userfile']['size']) && $_FILES['userfile']['size'])
				{	
					$image_status = '_'.$id.( ( $this->status_alias( $this->input->post('status') ) ) ? '_'.$this->status_alias($this->input->post('status')) : '' ) ;
					$image_status_thumb = '_'.$id.'_thumb_'.(($this->status_alias($this->input->post('status',true) ) )? '_'.$this->status_alias($this->input->post('status',true)) : '' ) ;
					$arr_filename = explode('.', $_FILES['userfile']['name']);
					$new_filename = $arr_filename['0'].$image_status.'.'.end($arr_filename);
					$new_filename_thumb = $arr_filename['0'].$image_status_thumb.'.'.end($arr_filename);
					
					$upload = $this->do_upload($new_filename,$new_filename_thumb);
					if(! isset($upload))
					{
						$this->article_manager->delete_by(array('id'=>$id));
						$this->session->set_flashdata('error',$upload['error'] );
						
					}
					else {
						$this->article_manager_m->update_by(array('id'=>$id), array('picture'=>$upload['full_path'],'picture_thumb'=>$upload['full_path_thumb']) );
						$this->session->set_flashdata('success', sprintf(lang('article_manager:edit_success'), $this->input->post('title',true)));
					}
					
					
				}
				else {
					$this->session->set_flashdata('success', sprintf(lang('article_manager:edit_success'), $this->input->post('title',true)));
				}
				
			}
			else
			{
				$this->session->set_flashdata('error', lang('article_manager:add_error'));
			}

			($this->input->post('btnAction') == 'save_exit') ? redirect(ADMIN_URL.'/article_manager') : redirect(ADMIN_URL.'/article_manager/edit/'.$id);	
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			if($this->input->post($rule['field']) && ($rule['field'] == 'content') )
			{
				$article->{$rule['field']} = sanitize_html($this->input->post($rule['field']));
				
				//$article->{$rule['field']} = xss_clean_without_style($this->input->post($rule['field']));
				
			}
			else if($this->input->post($rule['field']) ){
				$article->{$rule['field']} = $this->input->post($rule['field'],true);
			}
			
		}
		
		$article->created_on = $created_on;
		
		$this->template
			->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
			->title($this->module_details['name'], lang('article_manager:create_title'))
			->set('article', $article)
			->set('mode', 'edit')
			->append_js('module::article_form.js')
				->build('admin/article/form');
	}
	
	public function delete()
	{
		if($id = $this->input->post('id',true))
		{
			$id_array =  array($id) ;
	
			// Delete multiple
			if (!empty($id_array))
			{
				$deleted = 0;
				$to_delete = 0;
				$deleted_ids = array();
				$delete_name = array();
				foreach ($id_array as $id)
				{
					$data = $this->article_manager_m->get_by(array('id'=>$id));
					if ($this->article_manager_m->delete($id))
					{
						$deleted++;
						$deleted_ids[] = $id;
						$delete_name[]=$data->title;
					}
					else
					{
						$this->session->set_flashdata('error', sprintf(lang('article_manager:delete_error'), $id));
					}
					$to_delete++;
				}
	
				if ($deleted > 0)
				{
					$this->session->set_flashdata('success', sprintf(lang('article_manager:delete_success'),implode(',',$delete_name), $to_delete));
				}
	
			}
			else
			{
				$this->session->set_flashdata('error', lang('article_manager:delete_error_group'));
			}
		}
		
		redirect(ADMIN_URL.'/article_manager/index');
	}
	
	/**
	 * Callback method that checks the title of the category
	 *
	 * @param string $title The title to check
	 *
	 * @return bool
	 */
	public function _check_title($title = '')
	{
		if ($this->article_manager_m->check_title($title, $this->input->post('id',true)))
		{
			$this->form_validation->set_message('_check_title', sprintf(lang('article_manager:already_exist_error_group'), $title));

			return false;
		}

		return true;
	}

	/**
	 * Callback method that checks the slug of the category
	 *
	 * @param string $slug The slug to check
	 *
	 * @return bool
	 */
	public function _check_slug($slug = '')
	{
		if ($this->group_article_m->check_slug($slug, $this->input->post('id',true)))
		{
			$this->form_validation->set_message('_check_slug', sprintf(lang('article_manager:already_exist_slug_error_group'), $slug));

			return false;
		}

		return true;
	}
	
	public function _url_article_validate($val)
	{
		if(!$this->get_article_vidid($val))
		{
			$this->form_validation->set_message('_url_article_validate',lang('article_manager:video_not_valid'));
			
			return false;
		}
		else {
			return true;
		}
	}
	
	public function ajax_update_order(){
		
			if($this->input->post('status') != '-1' ){
				$cond['status'] = $this->input->post('status');
				$data_article = $this->article_manager_m->get_many_by($cond);
			}
			else {
				$data_article = $this->article_manager_m->get_all();
			}
			
			$data_order = explode(',',$this->input->post('order',true));
		 	$data_order =array_flip($data_order);
			
			foreach($data_article as $vals ){
				if(isset($data_order[$vals->id]))
				{
					$this->article_manager_m->update_by(array('id'=>$vals->id),array('order'=>$data_order[$vals->id]));
				}
			}
		
		
	}
	
	public function ajax_update_default(){
		/*if( ($video_id = $this->input->post('video_id')) && ($group_id = $this->input->post('group_id')) )
		{
			$this->group_article_m->update_by(array('id'=>$group_id),array('default_show'=>$this->input->post('video_id')));
			
		}*/
	}
	
	private function get_article_vidid (&$url) {
	    $vidid = false;
	    $valid_schemes = array ('http', 'https');
	    $valid_hosts = array ('www.article.com', 'article.com','youtu.be');
	    $valid_paths = array ('/watch');
	
	    $bits = parse_url ($url);
	    if (! is_array ($bits)) {
	        return false;
	    }
		//add share article
		if (in_array ($bits['host'], $valid_hosts) && ($bits['host'] == 'youtu.be') &&  array_key_exists ('path', $bits) ) {
			$url = 'https://'.'www.article.com/'.'watch?v='. basename($bits['path']);
	        return basename($bits['path']);
	    }
	    if (! (array_key_exists ('scheme', $bits)
	            and array_key_exists ('host', $bits)
	            and array_key_exists ('path', $bits)
	            and array_key_exists ('query', $bits))) {
	        return false;
	    }
	    if (! in_array ($bits['scheme'], $valid_schemes)) {
	        return false;
	    }
	    if (! in_array ($bits['host'], $valid_hosts)) {
	        return false;
	    }
	    if (! in_array ($bits['path'], $valid_paths)) {
	        return false;
	    }
	    $querypairs = explode ('&', $bits['query']);
	    if (count ($querypairs) < 1) {
	        return false;
	    }
	    foreach ($querypairs as $querypair) {
	        list ($key, $value) = explode ('=', $querypair);
	        if ($key == 'v') {
	            if (preg_match ('/^[a-zA-Z0-9\-_]+$/', $value)) {
	                # Set the return value
	                $vidid = $value;
	            }
	        }
	    }
	
	    return $vidid;
	}
	
	
	private function do_upload($new_file_name,$new_file_name_thumb)
	{
		$config_file['upload_path'] = getcwd().'/uploads/default/files/article';
		$config_file['allowed_types'] = 'gif|jpg|png|jpeg';		
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
		if ( ! $this->upload->do_upload('userfile'))
		{
			$error = array('error' => $this->upload->display_errors());
			return $error;
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = $this->upload->data();		
			
			$this->do_upload_thumb($data['file_path'],$data['full_path'],$new_file_name_thumb);
			
			return array('full_path'=>'/uploads/default/files/article/'.$data['file_name'],'full_path_thumb'=>'/uploads/default/files/article/'.$new_file_name_thumb );
			//$this->load->view('upload_success', $data);
		}
	}

	private function do_upload_thumb($path,$full_path,$new_file_name)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = $full_path;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 304;
		$config['height'] = 216;
		$config['new_image'] = $path.$new_file_name;
		$this->load->library('image_lib');
		$this->image_lib->initialize($config);
		
		if ( ! $this->image_lib->resize())
		{
		    echo $this->image_lib->display_errors();die();
		}
	}
	
	/*public function export()
	{
		$this->load->library('PHPExcel/PHPExcel');
		$type = 'csv';
		//Process The Data
		
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("")
										 ->setLastModifiedBy("Indomie Igalogi")
										 ->setTitle("Indomie Igalogi")
										 ->setSubject("Indomie Igalogi")
										 ->setDescription("Indomie Igalogi")
										 ->setKeywords("Indomie Igalogi")
										 ->setCategory("Indomie Igalogi");
			
			
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			//
			$column_header = array('No.','Twitter ID','Screen Name','TEXT','Date Created','Status');
			foreach($column_header as $idxs => $header_text)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($idxs,1, $header_text);
			}
			//$data = $this->db->where('status',1)->get('hashtag_manager')->result();
			$data = $this->db->get('hashtag_manager')->result();
			foreach($data as $_index => $value_twitter)
			{
	
				$idx =1;
				
				$value_twitters = unserialize($value_twitter->data);
				
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($_index+2), $_index+1);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($_index+2),  '="'.$value_twitter->id.'"');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($_index+2), $value_twitters->user->screen_name);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($_index+2), $value_twitters->text);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($_index+2), date("d M Y",strtotime($value_twitter->created_at)));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($_index+2), ($value_twitter->status==0)? 'Deactive': 'Active');
			}
		
			
		
		switch($type)
		{
			case 'csv' : 
							
							// Redirect output to a client’s web browser (Excel5)
							header("Content-type: text/csv");
							header("Content-Disposition: attachment; filename=file-".date('d-M-y').".csv");
							header('Cache-Control: max-age=0');
							$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
							$objWriter->save('php://output');
							exit; 
			
							break;
			case 'text' : 
							
							// Redirect output to a client’s web browser (Excel5)
							header("Content-type: text/plain");
							header("Content-Disposition: attachment; filename=file.txt");
							header('Cache-Control: max-age=0');
							$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
							$objWriter->save('php://output');
							exit; 
			
							break;
			
			case 'xls' :
						
							
							// Redirect output to a client’s web browser (Excel5)
							header('Content-Type: application/vnd.ms-excel');
							header('Content-Disposition: attachment;filename="01simple.xls"');
							header('Cache-Control: max-age=0');
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
							$objWriter->save('php://output');
							exit;
				 break;
			
			case 'xml' : 
							// Redirect output to a client’s web browser (Excel5)
							header('Content-Type: text/xml');
							header('Content-Disposition: attachment;filename="01simple.xml"');
							header('Cache-Control: max-age=0');
							$objWriter = new PHPExcel_Writer_XML($objPHPExcel);
							$objWriter->setGroup('campaign');
							$objWriter->save('php://output');
							
			
						break;
			
			
			case 'pdf' : 
							$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
							//$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
							//$rendererLibrary = 'tcPDF5.9';
							//$rendererLibrary = 'mpdf';
							//$rendererLibrary = 'domPDF0.6.0beta3';
							$rendererLibraryPath = APPPATH .'libraries/MPDF';
							
							//var_dump($rendererLibraryPath);
							if (!PHPExcel_Settings::setPdfRenderer(
									$rendererName,
									$rendererLibraryPath
							)) {
								die(
									'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
									'<br />' .
									'at the top of this script as appropriate for your directory structure'
								);
							}
							
							// Redirect output to a client’s web browser (PDF)
							header('Content-Type: application/pdf');
							header('Content-Disposition: attachment;filename="01simple.pdf"');
							header('Cache-Control: max-age=0');
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
							$objWriter->save('php://output');
							exit;
			
						break;
			
			case 'html' : 
							// Redirect output to a client’s web browser (Excel5)
							header("Content-type: text/html");
							header("Content-Disposition: attachment; filename=file.html");
							header('Cache-Control: max-age=0');
							$objWriter = new PHPExcel_Writer_HTML($objPHPExcel);
							$objWriter->save('php://output');
							exit; 
						break;
		}
	}*/

	
}