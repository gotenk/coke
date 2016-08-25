<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manages image selection and insertion for WYSIWYG editors
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\WYSIWYG\Controllers
 */
class Video extends Public_Controller {


	public function __construct() {
		parent::__construct();
        $this->template->set_layout('home.html');
		//$this->template->set_layout('photo.html');
		//$this->lang->load('product');
        $this->load->helper('text');
        $this->load->helper('youtube_detail');
        $this->load->model('bubble_m');
	}

	public function index() {
		$limit = Settings::get('records_per_page'); 
		$via = $this->input->post('via');

		$base_where = array();
		if($via){
			$base_where['via'] = $via;
		}

		$this->db->limit($limit, 0);
		$data = $this->bubble_m->get_list_bubble_front($base_where);
		
		$this->input->is_ajax_request() and $this->template->set_layout(false);

		/*
		if( $this->input->is_ajax_request() ){
			Asset::js_inline(
				"$('#list-cerita').imagesLoaded( function() {
				  $('#list-cerita').masonry({
					columnWidth: 10,
				 	itemSelector: '.item'
				   });
				   $('#list-cerita').data('masonry').layout();
					$('#wrapper-list').mCustomScrollbar({
						advanced:{ updateOnContentResize: true },
					});
				});"
			);
		}
		*/

		//$this->template->set_layout('home.html');
		$this->template
			->set('data', $data)
			->build('index');		
	}

	function load_more(){
		$start = $this->input->post('start');
		$via = $this->input->post('via');

		$base_where = array();
		if($via){
			$base_where['via'] = $via;
		}

		$limit = Settings::get('records_per_page'); 
		$offset = ($limit * $start) ;

		$this->db->limit($limit, $offset);
		$data = $this->bubble_m->get_list_bubble_front($base_where);
		//var_dump($this->db->last_query()); die();
		$ret = array();
		if($data->num_rows() > 0){
			foreach ($data->result() as $key => $value) {
				$is_yt = false;
				$link_img = '';

				if($value->photo!=''){ 
					if($value->author_id){
						$link_img = '<img src="'.base_url().$value->photo.'" width="100%" >'; //base_url().$value->photo;
					}else{
						$link_img = '<img src="'.$value->photo.'" width="100%" >'; //$value->photo;
						if($value->via_tw){
							$link_img = '<img src="'.base_url().$value->photo.'" width="100%" >';
						}
					}
				}

				$pp = '';
				$username = '';
				if($value->video!=''){ 
					$is_yt = true;
					$arr_video = explode('watch?v=', $value->video);
					$link_img = '<img src="https://img.youtube.com/vi/'.$arr_video['1'].'/0.jpg" width="100%" >'; //"http://img.youtube.com/vi/".$arr_video['1']."/0.jpg";
					
					$yt_detail = get_video_author($arr_video['1']); 
					$pp = $yt_detail['propic'];
					$username = character_limiter($yt_detail['author'], 15); 
				}
				$tamp_cerita = word_limiter($value->content, 15);
				$cerita = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<span>#\2</span>', $tamp_cerita); 

				if($value->via_fb){
					$pp = $value->profile_pic; //"https://graph.facebook.com/" .$value->username. "/picture?type=square";
					$username = character_limiter($value->name, 15);
				}
				if($value->via_tw){
					$username = '@'.character_limiter($value->username, 15);
					$pp = base_url().$value->profile_pic;
				}
				if($value->via_yt){
					//$username = character_limiter($value->name, 20);
				}

				if($pp==''){
					$pp = base_url('addons/default/themes/ramadan/img/pict-sample-thumbs.jpg');
				}

				$ret[] = '<div class="item kotak-cerita" data-id="'.$value->id.'" ini-yt="'.$is_yt.'" ini-big="no">
					<div class="thumbs"><img src="'.$pp.'" width="100%" /></div>
					<div class="username">
						<a href="javascript:void(0);">'.$username.'</a>
						<span>'.date('d M Y', strtotime($value->created)).'</span>
					</div>
					<div class="clear"></div>
					<p class="text">'.$cerita.'</p>
					<div class="img-video">
						'.$link_img.'
					</div>
					<div class="mask"></div>
				</div>';
			}
		}
		/*
		$ret[0] = '<div class="item kotak-cerita">
				<div class="thumbs"><img src="'.base_url().'addons/default/themes/ramadan/img/pict-sample-thumbs.jpg" width="100%" /></div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt, sed do eiusmod
				tempor incididunt ut labore et </p>
				<img src="'.base_url().'addons/default/themes/ramadan/img/pict-sample-cerita-3.png" width="100%" />
			</div>'; 
		*/

		echo json_encode($ret);
		
	}

	function detail_bubble(){
		$id = $this->input->post('id');
		$is_big = $this->input->post('is_big');
		
		$this->db->select('video.*, profiles.display_name');
		$this->db->join('profiles', 'profiles.user_id=video.author_id', 'left');
		$data = $this->db->get_where('video', array('video.id'=>$id));
		
		$ret = array();
		//echo json_encode($ret); die();
		if($data->num_rows() > 0 ){
			$value = $data->result();
			$value = $value[0];
			$tamp_img_path = '';

			if($value->photo!=''){ 
				if($value->author_id){
					//$ret['pv'] = '<img src="'.base_url().$value->photo.'" '.$max_img.'="100%" />';
					$tamp_img_path = base_url().$value->photo;
				}else{
					//$ret['pv'] = '<img src="'.$value->photo.'" '.$max_img.'="100%" />';
					$tamp_img_path = $value->photo;
					if($value->via_tw){
						//$ret['pv'] = '<img src="'.base_url().$value->photo.'" '.$max_img.'="100%" />';
						$tamp_img_path = base_url().$value->photo;
					}
				}

				$info_img = getimagesize($tamp_img_path); 
				$img_width = intval($info_img[0]);
				$img_height = intval($info_img[1]);

				$max_img = '';
				if($img_width > $img_height){
					$max_img = 'landscape-img';
				}
				$ret['pv'] = '<img src="'.$tamp_img_path.'"  class="'.$max_img.'"/>';
			}		

			$pp = '';
			$username = '';
			if($value->via_fb){
				$pp = $value->profile_pic; //"https://graph.facebook.com/" . $value->username . "/picture?type=square";
				$username = character_limiter($value->name, 20);
			}
			if($value->via_tw){
				$username = '@'.character_limiter($value->username, 20);
				$pp = base_url().$value->profile_pic;
			}
			if($value->via_yt){
				$username = character_limiter($value->name, 20);
			}

			if($pp==''){
				$pp = base_url('addons/default/themes/ramadan/img/pict-sample-thumbs.jpg');
			}

			if($value->video!=''){ 
				$arr_video = explode('watch?v=', $value->video);
				if($is_big=='yes'){
					$ret['pv'] = "<img src='https://img.youtube.com/vi/".$arr_video[1]."/0.jpg' width='100%' />";
				}else{
					$ret['pv'] = '<iframe id="ytplayer" type="text/html" width="100%" height="357px" src="https://www.youtube.com/embed/'.$arr_video[1].'?autoplay=0&origin='.site_url().'" frameborder="0"/>';
				}
				$ret['vivid'] = $arr_video[1];
			}

			$cerita = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<span>#\2</span>', $value->content); 

			$ret['cerita'] = $cerita;
			$ret['pp'] = $value->profile_pic!=''? '<img src="'.$pp.'" width="100%" />':'<img src="'.base_url().'addons/default/themes/ramadan/img/pict-sample-thumbs.jpg" width="100%" />';
			$ret['username'] = $username;
			$ret['nama'] = date('d M Y', strtotime($value->created)); //$value->name!=''? $value->name:'nama user';
			//$ret .= $value->content.'<br />br />'.$detail;
			//$ret = $detail;
		}
		
		echo json_encode($ret);
	}

	/*
	public function konek_fb(){
		$this->session->set_flashdata('via_sos', 'fb');
		redirect(site_url('bubble/posting_cerita'));
	}
	public function konek_tw(){
		$this->session->set_flashdata('via_sos', 'tw');	
		redirect(site_url('bubble/posting_cerita'));
	}
	*/

	public function posting_cerita(){
		//var_dump($this->session->all_userdata());
		//var_dump($this->current_user);
		if(!$this->current_user){
			//redirect(site_url());
			Asset::js_inline('IS_LOGGED_IN = false;');
			if($this->session->userdata('register_status')=='tw'){
				$this->session->unset_userdata('dob_failed');
				//$this->session->set_userdata('dob_failed', 1);
				Asset::js_inline('$(document).ready(function(){ $("#birth-form").show(); })');
			}else{
				redirect(site_url());
			}
			//var_dump($this->session->all_userdata()); die();
		}
		if($this->current_user){
			if($this->session->userdata('konek_by')=='fb'){
				$info_user = $this->db->get_where('profiles', array('user_id'=>$this->current_user->id));
				if($info_user->num_rows() > 0){
					$dob = $info_user->row('dob_date_format');
					$cek_dob = $this->cek_dob($dob);
				}
			}

			//redirect(site_url());
			if($this->session->userdata('dob_failed')){
				Asset::js_inline('IS_LOGGED_IN = false;');
				Asset::js_inline('$(document).ready(function(){ $("#birth-form").show(); })');
				$this->template->set('user_id', $this->session->userdata('user_id'));
			}else{
				Asset::js_inline('IS_LOGGED_IN = true;');
			}
		}

		$via_fb = $via_tw = 0;
		if($this->session->userdata('konek_by')=='tw'){
			$this->template->set('konek_twitter', 'tw');
			$via_tw = 1;
		}
		if($this->session->userdata('konek_by')=='fb'){
			$via_fb = 1;
		}
		
		$this->load->library('Recaptcha');
		$this->recaptcha->recaptcha_get_html();
		//var_dump($this->session->all_userdata());
		$sub_folder = 0;
			if($this->session->userdata('user_id')){ $sub_folder = $this->current_user->id; }
		$username = $this->session->userdata('username');
		
		if(count($_POST))
		{
			//var_dump($_POST); die();
			$cerita = $this->input->post('cerita');
			$video = $this->input->post('video');


			$this->load->library('form_validation');
			$validation_rules = array(
				'title' => array(
					'field' => 'cerita',
					'label' => 'cerita',
					'rules' => 'trim|xss_clean|required'
				),
			);
			
			$this->load->library(array('random_string'));
			$this->load->library('fileupload');
			//$this->load->library('exams_image_manipulation',array('sub_folder'=>$this->current_user->id));
			$this->load->library('exams_image_manipulation',array('sub_folder'=>$sub_folder));
			if($this->input->post('xhr') == '0' && !$this->input->post('filename'))
			{
				//$data_image = $this->exams_image_manipulation->upload_image('','','',$this->input->post('xhr') );
				$data_image = array();				
				echo json_encode($data_image);
				return;
			}
			
			$this->form_validation->set_rules($validation_rules);
			//$data = $this->bahasa_m->get_by(array('created_by'=>$this->current_user->id,'status'=>1));
			//$data = $this->bubble_m->get_by(array('created_by'=>1,'status'=>1));
			$data = false;
			if($data)
			{
				echo json_encode(array('success'=>'false','slug'=>'only_one','msg'=>'upload photo hanya sekali saja'));
				return;
			}

			if($this->input->post('xhr') == '2'){
				$dataPost = array();
				
				if($via_tw){
					$tw_id = isset($_SESSION['access_token']['user_id']) ? $_SESSION['access_token']['user_id']:0;
					$screen_name = isset($_SESSION['access_token']['screen_name']) ? $_SESSION['access_token']['screen_name']:$this->session->userdata('username');
					if($image_url = $this->session->userdata('image_url')){
						$dataPost['profile_pic'] = '/uploads/default/files/twitter_image/'.$tw_id.'/'.basename($image_url);
					}else{
						$dataPost['profile_pic'] = '/addons/default/themes/ramadan/img/pict-sample-thumbs.jpg';
					}	
					$dataPost['username'] = $screen_name;
				}
				if($via_fb){
					$fb_id = $this->session->userdata('fb_id');
					$dataPost['profile_pic'] = 'https://graph.facebook.com/'.$fb_id .'/picture?type=square';
					$dataPost['username'] = $fb_id;
				}

				$dataPost['content'] 		= $cerita;
				$dataPost['photo'] 			= '';
				$dataPost['thumb_photo'] 	= '';
				$dataPost['video'] 			= '';
				$dataPost['created'] 		= date('Y-m-d H:i:s');
				$dataPost['created_on'] 	= strtotime(date('Y-m-d H:i:s'));
				$dataPost['via_fb'] 		= $via_fb;
				$dataPost['via_tw'] 		= $via_tw;
				$dataPost['status'] 		= 'draft' ;
				$dataPost['show_first'] 	= 0;
				$dataPost['name'] 			= $this->session->userdata('username');
				$dataPost['author_id'] 		= $this->current_user->id;				

				if ($id = $this->bubble_m->insert_data($dataPost)){
					if($video!='' || $video){
						$postVideo = array();
						$postVideo['content'] 		= $cerita;
						$postVideo['photo'] 		= '';
						$postVideo['thumb_photo'] 	= '';
						$postVideo['video'] 		= $video;
						$postVideo['created'] 		= date('Y-m-d H:i:s');
						$postVideo['created_on'] 	= strtotime(date('Y-m-d H:i:s'));
						$postVideo['via_fb'] 		= 0;
						$postVideo['via_tw'] 		= 0;
						$postVideo['via_yt'] 		= 1;
						$postVideo['status'] 		= 'draft' ;
						$postVideo['show_first'] 	= 0;
						$postVideo['name'] 			= $this->session->userdata('username');
						$postVideo['author_id'] 	= $this->current_user->id;

						$this->bubble_m->insert_data($postVideo);
					}

					$this->post_tweet($cerita);
					echo json_encode(array('status'=>true,'popup'=>'#berhasil', 'realpath'=>'', 'video'=>$video, 'konek_by'=>$this->session->userdata('konek_by') ));
					return; 
				}
				else{
					echo json_encode(array('status'=>false,'popup'=>'#gagal'));
					return; 
				}

			}			

			if($this->form_validation->run()  && $this->exams_image_manipulation->check_allowed_image('uploadphoto', ($this->input->post('xhr')? true:false )))
			{				
				
				$data_image =  $this->exams_image_manipulation->upload_image('','','',(($this->input->post('xhr')=='0')? '0':'1' ));
				
				if(isset($data_image['msg']))
				{
					echo json_encode($data_image);
					return;
				}
				//var_dump($data_image); die();
				$dataPost = array();

				if($via_tw){
					$tw_id = isset($_SESSION['access_token']['user_id']) ? $_SESSION['access_token']['user_id']:0;
					$screen_name = isset($_SESSION['access_token']['screen_name']) ? $_SESSION['access_token']['screen_name']:$this->session->userdata('username');
					if($image_url = $this->session->userdata('image_url')){
						$dataPost['profile_pic'] = '/uploads/default/files/twitter_image/'.$tw_id.'/'.basename($image_url);
					}else{
						$dataPost['profile_pic'] = '/addons/default/themes/ramadan/img/pict-sample-thumbs.jpg';
					}	
					$dataPost['username'] = $screen_name;
				}
				if($via_fb){
					$fb_id = $this->session->userdata('fb_id');
					$dataPost['profile_pic'] = 'https://graph.facebook.com/'.$fb_id .'/picture?type=square';
					$dataPost['username'] = $fb_id;
				}

				$dataPost['content'] 		= $cerita;
				$dataPost['photo'] 			= $data_image['file_path']; //$data_image['file_path_original'];
				$dataPost['thumb_photo'] 	= $data_image['file_path_thumb'];
				$dataPost['video'] 			= '';
				$dataPost['created'] 		= date('Y-m-d H:i:s');
				$dataPost['created_on'] 	= strtotime(date('Y-m-d H:i:s'));
				$dataPost['via_fb'] 		= $via_fb;
				$dataPost['via_tw'] 		= $via_tw;
				$dataPost['status'] 		= 'draft' ;
				$dataPost['show_first'] 	= 0; 
				$dataPost['name'] 			= $this->session->userdata('username');
				$dataPost['author_id'] 	= $this->current_user->id;
				
				/*
				$data_slug =  url_title($this->input->post('title').$this->current_user->id.date('Y-m-d H:i:s'), 'dash', true);
				if(strlen($data_slug)>254)
				{
					$data_slug =substr($data_slug, 0,254);
				}
				*/
				//$dataPost['slug'] =$data_slug;
				//$dataPost['created_by'] 	=$this->current_user->id;
				/*
				echo json_encode(
					array(
						'status'	=> true,
						'popup'		=> '#berhasil', 
						'realpath'	=> base_url($dataPost['photo']), 
						'photo'		=> $dataPost['photo'],
						'thumb'		=> $dataPost['thumb_photo']
					)
				);
				return;
				*/
				
				if ($id = $this->bubble_m->insert_data($dataPost))
				{
					//Events::trigger('master_question_created', $id);

					if($video!='' || $video){
						$postVideo = array();
						$postVideo['content'] 		= $cerita;
						$postVideo['photo'] 		= '';
						$postVideo['thumb_photo'] 	= '';
						$postVideo['video'] 		= $video;
						$postVideo['created'] 		= date('Y-m-d H:i:s');
						$postVideo['created_on'] 	= strtotime(date('Y-m-d H:i:s'));
						$postVideo['via_fb'] 		= 0;
						$postVideo['via_tw'] 		= 0;
						$postVideo['status'] 		= 'draft';
						$postVideo['via_yt'] 		= 1;
						$postVideo['show_first'] 	= 0;
						$postVideo['name'] 			= $this->session->userdata('username');
						$postVideo['author_id'] 	= $this->current_user->id;

						$this->bubble_m->insert_data($postVideo);
					}

					$this->post_tweet_pic($cerita, $dataPost['photo']);
					echo json_encode(array('status'=>true,'popup'=>'#berhasil', 'realpath'=>base_url($dataPost['photo']), 'video'=>$video, 'konek_by'=>$this->session->userdata('konek_by') ));return; 
				}
				else
				{
					echo json_encode(array('status'=>false,'popup'=>'#gagal'));return; 
				}
				
			}
			else {
				echo json_encode(array('status'=>false,'popup'=>'#gagal-bwh'));return; 
			}
		}
		else{

			/*
			if($vote_id = $this->input->get('vote_id'))
			{
				$this->session->set_flashdata('vote_id',$vote_id);
				echo '<script>window.location.href="'.site_url('bubble').'";</script>';
				die();
			}
			*/
			
			Asset::js('theme::Modernizr.js');
			Asset::js_inline('Modernizr.load({test: Modernizr.touch,yep : "'.base_url(Asset::get_filepath_js('theme::hammer.js')).'"});');
			//Asset::js('theme::jquery.tinylimiter.js');
			Asset::js('theme::SimpleAjaxUploader.js');
			Asset::css('theme::jquery.cropbox.css');
			Asset::js('theme::hammer.js');
			Asset::js('theme::jquery.mousewheel.js');			
			Asset::js('theme::jquery.cropbox.js');
			Asset::js('module::bubble.js');
			//Asset::js_inline('IS_LOGGED_IN = true;');

			$this->template->set_layout('submission.html');
			$this->template
				->title('Form Submision')
				->build('form');
		}
	}
	
	function cek_recaptha(){
		$this->load->library('Recaptcha');
		$remoteip = $_SERVER["REMOTE_ADDR"];
		$challenge = $this->input->post('chalenge'); 
		$response = $this->input->post('answer'); 
		
		$resp = $this->recaptcha->recaptcha_check_answer($remoteip, $challenge, $response, $extra_params = array());
		echo $resp;
	}

	function post_tweet($cerita){
		$access_token = $_SESSION['access_token'];
		$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'), $access_token['oauth_token'],$access_token['oauth_token_secret']));
		$this->twitter->post('statuses/update',array('status'=>$cerita));
		//echo json_encode(array('status'=>1));
	}

	function post_tweet_pic($cerita, $img_share){
		//$im = file_get_contents($img_share);
		$img = getcwd().$img_share;
		//$im = fopen($img_path,"r");
        //$imdata = base64_encode($im); 

		$fd = fopen ($img, 'rb');
		$size=filesize ($img);
		$image = fread ($fd, $size);
		fclose ($fd);
		//$imdata = base64_encode($cont);   

		//$media = array();
		//$media[] = $imdata;
		$access_token = $_SESSION['access_token'];
		$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'), $access_token['oauth_token'],$access_token['oauth_token_secret']));
		
		$params = array(
	      'media[]' => $image, 
	      'status'  => $cerita
	    );
		$this->twitter->post('statuses/update_with_media', $params, true );
	}
	

	public function kirim_cerita(){
		
		$this->load->library('Recaptcha');
		$remoteip = $_SERVER["REMOTE_ADDR"];
		$challenge = $this->input->post('recaptcha_challenge_field'); 
		$response = $this->input->post('recaptcha_response_field'); 
		
		$resp = $this->recaptcha->recaptcha_check_answer($remoteip, $challenge, $response, $extra_params = array());
		
		$cerita = $this->input->post('cerita');
		$photo = $this->input->post('photo_cerita'); 
		$thumb_photo = $this->input->post('thumb_photo_cerita'); 
		$link_youtube = $this->input->post('link_youtube');
		$agreement = $this->input->post('agreement');

		//redirect(site_url());
	}

	public function lagu(){		
		$data = '';	
		$this->input->is_ajax_request() and $this->template->set_layout(false);
		
		$this->session->set_userdata('dari_lagu', 'ya');
		
		$this->template->set_layout('lagu-ramadhan.html');
		$this->template
			->set('data', $data)
			//->append_js('theme::mediaelement-and-player.min.js')
			//->append_css('theme::mediaelementplayer.css')
			->title('Lagu Ramadhan')
			->build('nidji');
	}

	public function download_lagu(){
		$file = ADDONPATH.'themes/ramadan/media/echo-hereweare.mp4';
		header ("Content-type: octet/stream");
		header ("Content-disposition: attachment; filename=coke-laguramadhan-nidji.mp4;");
		header("Content-Length: ".filesize($file));
		readfile($file);
		exit;
		/*
		$url='http://www.youtube.com/watch?v=HkMNOlYcpHg';
							
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);				
		$html .= curl_exec($curl);
		curl_close ($curl);

		echo $html;
		*/
	}

	public function tnc(){
		$this->template->set_layout('lagu-ramadhan.html');
		$this->template
			->set('is_tnc_page',true)
			->title('Persyaratan Penggunaan')
			->build('tnc');
	}

	public function policy(){
		$this->template->set_layout('lagu-ramadhan.html');
		$this->template
			->set('is_tnc_page',true)
			->title('Kebijakan Privasi')
			->build('policy');
	}

	public function tvc_video(){
		echo $this->load->view('bubble/tnv_popup');
	}

	function delete_permanent_fb($start=0, $end=0){
		if($start){
			$this->db->where('created >=', $start);
		}
		if($end){
			$this->db->where('created <=', $end);
		}
		$this->db->delete('video', array('via_fb'=>1));
	}
	function delete_permanent_tw($start=0, $end=0){
		if($start){	
			$this->db->where('created >=', $start);
		}
		if($end){
			$this->db->where('created <=', $end);
		}
		$this->db->delete('video', array('via_tw'=>1));
	}
	function delete_permanent_yt($start=0, $end=0){
		if($start){
			$this->db->where('created >=', $start);
		}
		if($end){
			$this->db->where('created <=', $end);
		}
		$this->db->delete('video', array('via_yt'=>1));
	}
	
	/*function _check_youtube_exists($vid='')
	{
		$this->load->library('youtube');
		$data = $this->youtube->getVideoInfo($vid);
		if(!$data)
		{
			$this->form_validation->set_message('_check_youtube_exists','Video Tidak Di Ketemukan');
		}
		
		return true;
	}*/

	public function cek_dob($dob='')
	{
		$arr_dob = explode('/', $dob);
		$day = isset($arr_dob['1']) ? $arr_dob['1']:0;
		$month = isset($arr_dob['0']) ? $arr_dob['0']:0;
		$year = isset($arr_dob['2']) ? $arr_dob['2']:0;
		if( checkdate (intval($month), intval($day), intval($year) ))
		{
			$now      = new DateTime();
			$birthday = new DateTime($year.'-'.$month.'-'.$day.' 00:00:00');
			$interval = $now->diff($birthday);
			$current_year = $interval->format('%y');
			$current_month = $interval->format('%m');
			$current_day = $interval->format('%d');
			//if( intval($current_year) > intval(Settings::get('max_age')) )
			if(intval($current_year) > intval(12) )
			{
				//var_dump('masuk1');die();
				$this->session->unset_userdata('dob_failed');
				return true;
			}
			//else if(intval($current_year) == intval(Settings::get('max_age')))
			else if(intval($current_year) == intval(12) )
			{
				if($current_day>0 || $current_month>0)
				{
					//var_dump('masuk2');die();
					$this->session->unset_userdata('dob_failed');
					return true;
				}
				else {
					
					$this->session->set_userdata('dob_failed','true');
					return false;
				}
			}
			else {
				$this->session->set_userdata('dob_failed','true');
				return false;
				
			}			
			
		}
		else {
			//$this->form_validation->set_message('_dob', 'Umur tidak mencukupi lang');
			$this->session->set_userdata('dob_failed','true');
			return false;
		}		
	}

	/*
	function curl_lagi(){
		$url = base_url().'/addons/default/themes/ramadan/media/echo-hereweare.mp4';
		$path = base_url().'uploads/file.mp4';
		$fp = fopen($path, 'w');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		$data = curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		$file = $data;
		header ("Content-type: octet/stream");
		header ("Content-disposition: attachment; filename=coke-laguramadhan-nidji.mp4;");
		header("Content-Length: ".filesize($file));
		readfile($file);
		exit;
	}

	public function curl_download($Url=0){
	 	$url = base_url().'/addons/default/themes/ramadan/media/echo-hereweare.mp4';

	    // is cURL installed yet?
	    if (!function_exists('curl_init')){
	        die('Sorry cURL is not installed!');
	    }
	 
	    // OK cool - then let's create a new cURL resource handle
	    $ch = curl_init();
	 
	    // Now set some options (most are optional)
	 
	    // Set URL to download
	    curl_setopt($ch, CURLOPT_URL, $Url);
	 
	    // Set a referer
	    curl_setopt($ch, CURLOPT_REFERER, site_url());
	 	 
	    // Include header in result? (0 = yes, 1 = no)
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	 
	    // Should cURL return or print out the data? (true = return, false = print)
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
	    // Timeout in seconds
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	 
	    // Download the given URL, and return output
	    $output = curl_exec($ch);
	 
	    // Close the cURL resource, and free system resources
	    curl_close($ch);
	 	var_dump($output);
	    //return $output;
	}

	function with_curl()
	{
		$url = base_url().'addons/default/themes/ramadan/media/echo-hereweare.mp4';
		$path = base_url().'uploads/file.mp4';
		//$path = getcwd().'uploads/file.mp4';

		$ch = curl_init($url);
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
		curl_exec( $ch );
		$headers = curl_getinfo( $ch );
		curl_close( $ch );

		//return $headers;

		if ($headers['http_code'] === 200 and $headers['download_content_length'] < 1024*1024) {
			if (download($url, $path)){
				echo 'Download complete!'; 
			}
		}
		//$url = 'http://path/to/remote/file.jpg';
		//$path = 'uploads/file.jpg';

		//$headers = getHeaders($url);

		//var_dump($headers);
		//if ($headers['http_code'] === 200 and $headers['download_content_length'] < 1024*1024) {
		if ($headers['http_code'] === 200) {
		  if ($this->download($url, $path)){
		    echo 'Download complete!'; 
		  }
		}
		//echo 'sini';

	}

	function download($url, $path)
	{
	  # open file to write
	  $fp = fopen ($path, 'w+');
	  # start curl
	  $ch = curl_init();
	  curl_setopt( $ch, CURLOPT_URL, $url );
	  # set return transfer to false
	  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
	  curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
	  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	  # increase timeout to download big file
	  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
	  # write data to local file
	  curl_setopt( $ch, CURLOPT_FILE, $fp );
	  # execute curl
	  curl_exec( $ch );
	  # close curl
	  curl_close( $ch );
	  # close local file
	  fclose( $fp );

	  if (filesize($path) > 0) return true;
	}
	*/
		
}