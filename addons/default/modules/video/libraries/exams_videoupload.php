<?php defined("BASEPATH") or exit('No Direct Script Access Allowed');

class Exams_videoupload
{
	
	private $image_data = array();
	private $save_path = '/uploads/default/user_files';
	private $sub_folder = '';
	private $save_path_not_login = false;
	function __construct($config=array())
	{
		$this->ci =& get_instance();
		$this->ci->load->library('exams_manager/random_string',array('lowercase'=>array('count'=>3),
								 									 'uppercase'=>array('count'=>4),
																	  'numeric'=>array('count'=>3)));
		$this->ci->load->config('exams_manager/exams_manager');
		$this->initialize($config);
	}
	
	function initialize($config)
	{
	
		//image data harus di sort 
		if(isset($config['image_data']))
		{
			$this->image_data = $config['image_data'];
		}
		
		if(isset($config['sub_folder']))
		{
			$this->sub_folder = '/'.$config['sub_folder'].'/';
		}
		else {
			$this->sub_folder = '/';
		}
		
		
		//check_folder
		$this->new_save_path =  $this->save_path.$this->sub_folder;
		if(!is_dir(getcwd().$this->new_save_path))
		{		
			mkdir(getcwd().$this->new_save_path,0775,true);
		}
	}
	public function get_current_path()
	{
		return $this->new_save_path;
	}
	
	
	public function check_allowed_video($filename,$xhr = true)
	{
		if(!$xhr) return true;
		if(isset($_FILES[$filename]) )
		{
			$allowed_image = array('mp4', 'avi', 'mov', '3gp','flv','webm');
			$mimes =& get_mimes();
			$collection_mimes =array();
			foreach($allowed_image as $itm_allow)
			{
				if (is_array($mimes[$itm_allow]) )
				{
				 	$collection_mimes = array_merge($collection_mimes,$mimes[$itm_allow]);
				}
				else {
					$collection_mimes[] = $mimes[$itm_allow];
				}
				
			}
			if(in_array($_FILES['file']['type'],$collection_mimes))
			{
				return true;
				
			
			}
			else {
				return false;
			}
			
		}
		else
		{
			return false;
		}
	}
	public function upload_video($new_filename='',$new_filename_original='',$new_filename_thumb='',$support_xhr='1'){
		$allowed_image = array('mp4', 'avi', 'mov', '3gp','flv','webm');
			
			$image_paths = $this->new_save_path.$this->ci->config->item('default_path_save_video');
		
			if(!is_dir(getcwd().$image_paths))
			{
				mkdir(getcwd().$image_paths,0775,true);
			}
			$uploader = new FileUpload('uploadvideo');
			$uploader->sizeLimit = 1024*5120;
			$video_file = $this->ci->random_string->generate();
			
		    
			$uploader->newFileName = $video_file.'.'.$uploader->getExtension();
			$result = $uploader->handleUpload(getcwd().$image_paths,$allowed_image); 
			if(! $result)
			{
				return array('success' => false,'msg' => $uploader->getErrorMsg());
			}
			else {
				
				
				return array('realpath'=>$image_paths.'/'.$video_file.'.'.$uploader->getExtension(),'success' => true);
			}
			
			
		
	}

	
}
