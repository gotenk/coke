<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User controller for the users module (frontend)
 *
 * @author		 Phil Sturgeon
 * @author		MaxCMS Dev Team
 * @package		MaxCMS\Core\Modules\Users\Controllers
 */
class Users extends Public_Controller
{
	/**
	 * Constructor method
	 *
	 * @return \Users
	 */

	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->load->model(array('user_m', 'profile_m'));
		$this->load->helper(array('user', 'coketune'));
		$this->lang->load('user');
		$this->load->library('form_validation');

		$this->social_media_list =array('vine','instagram','twitter','facebook');
	}

	/**
	 * Show the current user's profile
	 */
	public function index()
	{
		redirect('');

	}
	public 	function instagram_callback(){
			$this->load->library('instagram/instagram');
			$this->instagram->setApiCallback(site_url(uri_string()));

			if($code = $this->input->get('code'))
			{
				$data = $this->instagram->getOAuthToken($code);
				if(!$data)
				{
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					exit();
				}


				//var_dump($id_twitter);
				$data_check = $this->profile_m->get_profile(array('insta_id'=> $data->user->id));
				//var_dump($data);
				//var_dump($_SESSION);die();
				if($data_check)
				{
					if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='instagram') )
					{
						echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
						exit();
					}

					//save last current data
					$dob = explode('/',$this->session->userdata('dob'));
					$parent_email=$this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
					$connect_with =$this->session->userdata('connect_with');

					$this->session->set_userdata('data_current',array('social_media'=>$connect_with,'day'=>$dob[0],'month'=>$dob[1],'year'=>$dob[2],'parent_email'=>$parent_email ));
					$this->session->set_userdata('message_register',lang('user:already_register'));
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					exit();
				}
				else {



					if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='instagram') )
					{
						echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
						exit();
					}
					$dob = $this->session->userdata('dob');
					$dob_int = date_create_from_format('j/n/Y',$dob)->getTimestamp();
					$this->session->set_userdata('message_register',lang('user:success_register'));
					//register



					$profile_data =array();
					$profile_data['display_name'] =(($data->user->full_name)? $data->user->full_name: $data->user->username);
					$profile_data['insta_id'] = $data->user->id;
					$profile_data['dob'] = $dob_int;
					$profile_data['dob_date_format'] = $dob;
					$email='';
					$username = $data->user->username;
					//generate pub, private key
					$password=rand(895,9542324).microtime().rand(0,5443434);
					$username = $this->generate_username($username);
					//split name
					$this->split_fullname((($data->user->full_name)? $data->user->full_name: $data->user->username),$profile_data);
					$parent_email = $this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
					$my_extra = array(	'insta_id'	=>$data->user->id,
										'dob'	=>$dob_int,
										'dob_date_format'=> $dob,
									);

					$id = $this->ion_auth->register($username, $password, $email,$parent_email, null, $profile_data,false,$my_extra);
					if($parent_email)
					{
						//send email confirmation
						$this->send_email_confirmation($parent_email,$id);
					}
					$this->check_id_entity_match_by($id,$data->user->id,'instagram');
					//unset
					$this->session->unset_userdata('parent_email');
					$this->session->unset_userdata('connect_with');
					$this->session->unset_userdata('dob');
					$this->ion_auth->activate($id, false);

				}


				if(isset($redir) && $redir)
				{

					echo '<script>window.location.href="'.site_url(rawurldecode($redir)).'";</script>';
					//redirect(site_url(rawurldecode($redir)));
				}
				else
				{
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					//redirect();
				}
			}
			else {
				redirect($this->instagram->getLoginUrl());
			}





	}

	public function check_parent_email()
	{
		$day = $this->input->post('day',true);
		$month = $this->input->post('month',true);
		$year = $this->input->post('year',true);


		if($this->func_check_parent_email($month,$day,$year))
		{
			echo json_encode(array('status'=>1));
		}
		else {
			echo json_encode(array('status'=>0));
		}
	}

	function _parent_email_check()
	{
		$month = $this->input->post('month');
		$day = $this->input->post('day');
		$year = $this->input->post('year');
		if($this->func_check_parent_email($month,$day,$year))
		{
			return true;
		}
		else {
			$this->form_validation->set_message('_parent_email_check',lang('user:parent_email_check'));
			return false;
		}

	}



	private function func_check_parent_email($month,$day,$year)
	{
		if( checkdate (intval($month), intval($day), intval($year) ))
		{
			$now      = new DateTime();
			$birthday = new DateTime($year.'-'.$month.'-'.$day.' 00:00:00');
			$interval = $now->diff($birthday);
			$current_year = $interval->format('%y');
			$current_month = $interval->format('%m');
			$current_day = $interval->format('%d');
			//if( intval($current_year) > intval(Settings::get('max_age')) )
			if(intval($current_year) > intval(12) && intval($current_year)  < intval(21) )
			{
				//var_dump('masuk1');die();
				return true;
			}
			//else if(intval($current_year) == intval(Settings::get('max_age')))
			else if(intval($current_year) <= intval(12) )
			{
				return false;
			}
			else {

				return false;

			}


		}
		else {
			return false;
		}
	}


	//facebook callback
	public function fb_connect()
	{
		$this->session->unset_userdata('connect_with');
		$this->session->set_userdata('connect_with', 'facebook');
		$redir = site_url();

		if(!$this->facebook->getUser())
		{
			$fb_connect_url = uri_string();
			// var_dump($fb_connect_url); die();

			$data_url = $this->facebook->getLoginUrl(array('redirect_uri'=>site_url(uri_string()),
												// 'scope'=>array('email')));
												'scope'=>array('email, public_profile, publish_actions, user_photos')));

		 	echo '<script>window.location.href="'.$data_url.'";</script>';
			// redirect($data_url);
			return;

		}
		else {
			$me =array();
			try
			{
				// $me = $this->facebook->api('/'.$this->facebook->getUser());
				$me = $this->facebook->api('/me?fields=id,name,email,first_name,last_name');
			}
			catch(FacebookApiException $e)
			{
				// echo '<html><head><META HTTP-EQUIV="REFRESH" CONTENT="5;URL='.site_url('fb-connect').'?'.(($this->input->get())?http_build_query($this->input->get()):'').'"><title>Sedang Di Arahkan Ulang</title></head><body>tunggu sebentar</body></html>';
				redirect(site_url());
				return;
			}
			$this->facebook->setExtendedAccessToken();

			// var_dump($me);
			// die();
			$data_user = $this->profile_m->get_profile(array('fb_id'=>$me['id']));
			if ($data_user)
			{
				//force login
				if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='facebook') )
				{
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					exit();
				}

				//save last current data
				// $dob = explode('/',$this->session->userdata('dob'));
				// $parent_email=$this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
				// $connect_with =$this->session->userdata('connect_with');
				//
				// $this->session->set_userdata('data_current',array('social_media'=>$connect_with,'day'=>$dob[0],'month'=>$dob[1],'year'=>$dob[2],'parent_email'=>$parent_email ));
				// $this->session->set_userdata('message_register',lang('user:already_register'));
				// echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
				// exit();

				$redir = site_url('profile-page');
			}
			else {

				if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='facebook') )
				{
					// echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					// exit();
					redirect(site_url('syarat-dan-ketentuan'));
				}

				$this->session->set_userdata('me', $me);

				/*
				$dob = $this->session->userdata('dob');
				$dob_int = date_create_from_format('j/n/Y',$dob)->getTimestamp();
				$this->session->set_userdata('message_register',lang('user:success_register'));
				//register
				$profile_data =array();
				$profile_data['display_name'] = $me['name'];
				$profile_data['fb_id'] = $me['id'];
				$profile_data['dob'] = $dob_int;
				$profile_data['dob_date_format'] = $dob;
				$email='';
				$email = (isset($me['email']))? $me['email']:$email;
				if(!empty($email))
				{
					//email already exists
					if($this->ion_auth->email_check($email))
					{
						$email ='';
					}
				}
				$username ='';
				$profile_data = array_merge($profile_data);
				$password=rand(895,9542324).microtime().rand(0,5443434);
				$username = $this->generate_username($me['name']);
				//split name
				$this->split_fullname($me['name'],$profile_data);
				$parent_email = $this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
				$my_extra = array(		'fb_id'	=>$me['id'],
										'dob'	=>$dob_int,
										'dob_date_format'=> $dob,
									);

				$id = $this->ion_auth->register($username, $password, $email,$parent_email, null, $profile_data,false,$my_extra);
				if($parent_email)
				{
					//send email confirmation
					$this->send_email_confirmation($parent_email,$id);
				}
				$this->check_id_entity_match_by($id,$me['id'],'facebook');

				//unset
				$this->session->unset_userdata('parent_email');
				$this->session->unset_userdata('connect_with');
				$this->session->unset_userdata('dob');

				$this->ion_auth->activate($id, false);
				//$current_register = $this->session->userdata('register_status');
				*/
			}

			/*
			if(isset($redir) && $redir)
			{

				echo '<script>window.location.href="'.site_url(rawurldecode($redir)).'";</script>';
				//redirect(site_url(rawurldecode($redir)));
			}
			else
			{
				echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
				//redirect();
			}
			*/

		}
	}


	public function twitter_callback()
	{
   		/*if($this->input->get('reset') == 'true')
		{
			$this->session->unset_userdata('twitter_id');
			$this->session->unset_userdata('image_url');
			$this->session->unset_userdata('screen_name');
			$this->session->unset_userdata('tw_name');
			$this->ion_auth->logout();
			$build_query=array();

			if($this->current_user)
			{
				$this->session->unset_userdata('access_token');
				$this->session->unset_userdata('status_twitter');
				$this->ion_auth->logout();
				echo '<script>window.location.href="'.site_url(uri_string()).'?'.http_build_query($build_query).'";</script>';
				//redirect(uri_string());
				return;
			}
			else {
				$this->session->unset_userdata('access_token');
				$this->session->unset_userdata('status_twitter');
			   echo '<script>window.location.href="'.site_url(uri_string()).'?'.http_build_query($build_query).'";</script>';
				//redirect(uri_string());
				return;
			}

		}*/

		$this->session->unset_userdata('connect_with');
		$this->session->set_userdata('connect_with', 'twitter');

   		$settings_twitter = array();
		$settings_twitter['oauth_access_token'] = Settings::get('oauth_access_token');
		$settings_twitter['oauth_access_token_secret'] = Settings::get('oauth_access_token_secret');
		$settings_twitter['consumer_key'] = Settings::get('consumer_key');
		$settings_twitter['consumer_secret'] = Settings::get('consumer_key_secret');

		if(isset($_REQUEST['oauth_verifier']))
		{
			$redirecting = $this->input->get('redirect');
			/* If the oauth_token is old redirect to the connect page. */

			if (isset($_REQUEST['oauth_token']) && isset( $_SESSION['oauth_token']) && ($_REQUEST['oauth_token'] !== $_REQUEST['oauth_token']) ) {
				unset($_SESSION['oauth_token']);
				//redirect('juara-voice/tw-connect?reset=true');
			}

			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'), (isset($_SESSION['oauth_token'])? $_SESSION['oauth_token']: FALSE), (isset($_SESSION['oauth_token_secret']))?$_SESSION['oauth_token_secret'] :false ));

			/* Request access tokens from twitter */
			$access_token = $this->twitter->getAccessToken($_REQUEST['oauth_verifier']);

			if(! isset($access_token['oauth_token']) && !isset($access_token['oauth_token_secret']) )
			{
				//var_dump($access_token);die();
				redirect('tw-connect?reset=true&redirect='.rawurlencode($redirecting));
			}
			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$_SESSION['access_token'] = $access_token;

			/* Remove no longer needed request tokens */
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);

			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if (200 == $this->twitter->http_code) {
			  /* The user has been verified and the access tokens can be saved for future use */
			  $_SESSION['status_twitter'] = 'verified';
			  //$_SESSION['register_status']='tw';
			} else {
				//var_dump($this->twitter->http_code);die();
			  redirect('tw-connect?reset=true&redirect='.rawurlencode($redirecting));
			}
		}

		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {

				$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'),null,null));
				$_redir = $this->input->get('redirect');
				$twitter_callback = uri_string().'?redirect='.rawurlencode($_redir);
				$request_token = $this->twitter->getRequestToken(site_url($twitter_callback));
				$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			/* If last connection failed don't display authorization link. */
			switch ($this->twitter->http_code) {
			  case 200:
			    	/* Build authorize URL and redirect user to Twitter. */
			    	$url = $this->twitter->getAuthorizeURL($token);
			    header('Location: ' . $url);
				return;
			    break;
			    default:
			    /* Show notification if something went wrong. */
			    echo 'Could not connect to Twitter. Refresh the page or try again later.';
			}
		}
		else {

			//$this->session->set_userdata('register_status','tw');
			$access_token = $this->session->userdata('access_token');
			$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'), $access_token['oauth_token'],$access_token['oauth_token_secret']));

			//$data = $this->twitter->get('account/verify_credentials');
			/*$rate_limit= $this->twitter->get('application/rate_limit_status');
			var_dump($rate_limit);
			var_dump($_SESSION);die();
			if(!isset($data->id))
			{
				var_dump($rate_limit);die();
			}*/

			$id_twitter = isset($_SESSION['access_token']['user_id'])? $_SESSION['access_token']['user_id'] :false;
			//s
			//var_dump($id_twitter);
			$data = $this->profile_m->get_profile(array('tw_id'=>$id_twitter,'tw_tokens'=>serialize($access_token)));
			//var_dump($data);
			//var_dump($_SESSION);die();
			if($data && isset($data->user_id))
			{
				if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='twitter') )
				{
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					exit();
				}

				//save last current data
				$dob = explode('/',$this->session->userdata('dob'));
				$parent_email=$this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
				$connect_with =$this->session->userdata('connect_with');

				$this->session->set_userdata('data_current',array('social_media'=>$connect_with,'day'=>$dob[0],'month'=>$dob[1],'year'=>$dob[2],'parent_email'=>$parent_email ));
				$this->session->set_userdata('message_register',lang('user:already_register'));
				echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
				exit();
			}
			else {



				if( (!$this->session->userdata('dob')) || ($this->session->userdata('connect_with') !='twitter') )
				{
					echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
					exit();
				}
				$dob = $this->session->userdata('dob');
				$dob_int = date_create_from_format('j/n/Y',$dob)->getTimestamp();
				$this->session->set_userdata('message_register',lang('user:success_register'));
				//register
				$data = $this->twitter->get('account/verify_credentials');
				$profile_data =array();
				$profile_data['display_name'] =$data->name;
				$profile_data['tw_id'] = $data->id;
				$profile_data['dob'] = $dob_int;
				$profile_data['dob_date_format'] = $dob;
				$profile_data['tw_tokens']=serialize($access_token);
				$profile_data['tw_screen_name'] = $data->screen_name;
				$email='';
				$username =$data->screen_name;
				//generate pub, private key
				$password=rand(895,9542324).microtime().rand(0,5443434);
				$username = $this->generate_username($username);
				//split name
				$this->split_fullname($data->name,$profile_data);
				if(! is_dir(  getcwd().$this->config->item('default_path').$data->id))
				{
					$result = mkdir(  getcwd().$this->config->item('default_path').$data->id,0755,true);

				}
				$parent_email = $this->session->userdata('parent_email')?$this->session->userdata('parent_email') : null;
				$my_extra = array(	'tw_id'	=>$data->id,
									'dob'	=>$dob_int,
									'dob_date_format'=> $dob,
									'tw_screen_name'=>$data->screen_name,
									'tw_tokens'	=>serialize($access_token),

								);

				$id = $this->ion_auth->register($username, $password, $email,$parent_email, null, $profile_data,false,$my_extra);
				if($parent_email)
				{
					//send email confirmation
					$this->send_email_confirmation($parent_email,$id);
				}
				$this->check_id_entity_match_by($id,$data->id,'twitter');
				//unset
				$this->session->unset_userdata('parent_email');
				$this->session->unset_userdata('connect_with');
				$this->session->unset_userdata('dob');
				$this->ion_auth->activate($id, false);

			}


			if(isset($redir) && $redir)
			{

				echo '<script>window.location.href="'.site_url(rawurldecode($redir)).'";</script>';
				//redirect(site_url(rawurldecode($redir)));
			}
			else
			{
				echo '<script>window.location.href="'.site_url('persyaratan-dan-ketentuan').'";</script>';
				//redirect();
			}




		}


		}




	/**
	 * Method to log the user out of the system
	 */
	public function logout()
	{
		// allow third party devs to do things right before the user leaves
		Events::trigger('pre_user_logout');

		$this->ion_auth->logout();

		//unset($this->session->userdata);
		$this->session->sess_destroy();

		if ($this->input->is_ajax_request())
		{
			exit(json_encode(array('status' => true, 'message' => lang('user:logged_out'))));
		}
		else
		{
			$this->session->set_flashdata('success', lang('user:logged_out'));
			redirect('');
		}
	}



	public function _recaptcha_check()
	{
		$this->load->library('recaptcha');
		$response = $this->recaptcha->recaptcha_check_answer_checkbox($this->input->post('g-recaptcha-response'));


		if(!$this->recaptcha->is_valid){

			$this->form_validation->set_message('_recaptcha_check',lang('user:recaptcha'));
			return false;
		}else{
			return true;
		}

	}

	public function _tnc($val)
	{
		if(empty($val))
		{
			$this->form_validation->set_message('_tnc',lang('user:tnc'));

			return false;
		}

		return true;
	}

	function encode5t($str){
	  	for($i=0; $i<1;$i++){
	    	$str=strtr(base64_encode($str), '+/=', '-__'); //apply base64 first and then reverse the string
	  	}
	  	return $str;
	}
	function decode5t($str){
	  	for($i=0; $i<1;$i++){
	    	$str=base64_decode(strtr($str, '-__', '+/=')); //apply base64 first and then reverse the string}
	  	}
	  	return $str;
	}

	/*public function register_user(){

		$this->load->helper('home/home');

		if ($this->current_user)
		{
			$this->session->set_flashdata('notice', lang('user:already_logged_in'));
			redirect();
		}
		if($this->session->userdata('dob_false'))
		{
			echo json_encode(array('url'=>'', 'status'=>false, 'message'=>'Maaf, kamu belum memenuhi syarat untuk mendaftar', 'aksi'=>'home' ));
			return;
		}
		// Validation rules
		$validation = array(
			array(
				'field' => 'password',
				'label' => lang('global:password'),
				'rules' => (($this->session->userdata('connect_with')!='fb'&& $this->session->userdata('connect_with')!='tw')? 'callback__password_complexcity|':'').'required|min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']'
			),
			array(
				'field' => 'email',
				'label' => lang('global:email'),
				'rules' => 'required|max_length[60]|valid_email|callback__email_check',
			),
			array(
				'field' => 'first_name',
				'label' => 'Fisrt Name',
				'rules' => 'trim|max_length[100]|required',
			),
			array(
				'field' => 'last_name',
				'label' => 'Last Name',
				'rules' => 'trim|max_length[100]|required',
			),
			array(
				'field' => 'recaptcha_response_field',
				'label' => 'recaptcha',
				'rules' => 'trim|xss_clean|required|callback_recaptcha_response_field'
				//'rules' => 'trim|xss_clean|required'
			),
	'parent_email'=>array(
			'field' => 'parent_email',
			'label' => lang('global:email'),
			'rules' => 'required|max_length[60]|valid_email|callback__same_email',
			),
			array(
				'field' => 'agree',
				'label' => 'Syarat & Ketentuan',
				'rules' => 'trim|required',
			),
		);

		if(!$this->input->post('parent_email'))
		{
			unset($validation['parent_email']);
		}

		// Set the validation rules
		$this->form_validation->set_rules($validation);

		$user = new stdClass();

		// Set default values as empty or POST values
		foreach ($validation as $rule)
		{
			$user->{$rule['field']} = $this->input->post($rule['field']) ? escape_tags($this->input->post($rule['field'])) : null;
		}

		if ($_POST)
		{

			//$complete_test = $this->session->userdata('complete_tes');
			//if($complete_test==NULL){

				//$this->session->unset_userdata(array('firstlogin', 'connect_with', 'me'));
			//
			//	if(!$this->input->is_ajax_request())
			//	{
			//		redirect();
			//	}
			//	else
			//	{
			//		$this->session->set_userdata('email', $this->input->post('email'));
			//		$this->session->set_userdata('nama', $this->input->post('nama'));
			//		$this->session->set_userdata('tgl', $this->input->post('tgl'));
			//		$this->session->set_userdata('bln', $this->input->post('bln'));
			//		$this->session->set_userdata('thn', $this->input->post('thn'));
			//		//var_dump($_POST); die();
			//		echo json_encode(array('url'=>site_url(''), 'status'=>false, 'message'=>'Your are doesn\'t complete personal test', 'aksi'=>'personal' ));
			//		return;
			//	}
			//}


			$email_asli = $this->input->post('email');
			if ($this->form_validation->run())
			{
				$password = escape_tags($this->input->post('password'));
				$email = $this->input->post('email');
				$email_parts = explode('@', $email);
				$username = $email_parts[0];

				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$display_name = $first_name.' '.$last_name;
				$username = $first_name;

				$profile_data = $extra = array();
				$profile_data['display_name'] = $display_name;
				$profile_data['first_name'] = $first_name;
				$profile_data['last_name'] = $last_name;

				//-- CEK DOB
				$tgl = $this->input->post('tgl');
				$bln = $this->input->post('bln');
				$thn = $this->input->post('thn');
				if( $thn && $bln && $tgl ){
					if($cek_dob = $this->_cek_dob($thn, $bln, $tgl)){
						$extra['dob'] = $thn.'-'.$bln.'-'.$tgl;
					}else{
						$this->session->set_userdata('dob_false', '1');
						echo json_encode(array('url'=>'', 'status'=>false, 'message'=>'Maaf, kamu belum memenuhi syarat untuk mendaftar', 'aksi'=>'home' ));
						return;
					}
				}else{
					echo json_encode(array('url'=>'', 'status'=>false, 'message'=>'Tanggal lahir tidak valid'));
					return;
				}

				//-- IF BY FACEBOOK
				if($this->session->userdata('connect_with')=='fb'){
					$data_sosmed = $this->session->userdata('me');
					$me = $this->facebook->api('/'.$this->facebook->getUser());
					//$extra['fb_id'] = $data_sosmed['id'];
					$extra['fb_id'] = $me['id'];
					$extra['photo_profile'] = $this->session->userdata('image_url');
				}

				//-- IF BY TWITTER
				if($this->session->userdata('connect_with')=='tw'){
					$data_sosmed = $this->session->userdata('me');
					$extra['tw_id'] = $this->session->userdata('twitter_id');
					$extra['tw_screenname'] = $this->session->userdata('screen_name');
					$extra['tw_access_token'] = serialize($_SESSION['access_token']);
					$extra['tw_oauth_token'] = '';
					$extra['photo_profile'] = $this->session->userdata('image_url');

				}

				//$id = $this->ion_auth->register($username, $password, $email, null, $profile_data);
				$id = $this->ion_auth->register($username, $password, $email_asli, $email, null, $profile_data);



				if ($this->input->post('parent_email')) {
					$data_email = array();
					$data_email['to'] = $this->input->post('parent_email');
					$data_email['parent_email'] = $this->input->post('parent_email');
					$data_email['name'] = $display_name;

					$hash_id = $this->encode5t($id);

					$data_email['link'] = site_url()."confirm_parent/".$hash_id;
					// var_dump($data_email);
					$this->send_email($data_email);

				}


				// Try to create the user
				if ($id > 0)
				{
					//-- UPDATE PROFILE DATA
					$this->db->update('profiles', $extra, array('user_id'=>$id));

					//-- UNSET ALL SESSION TERKAIT
					$this->session->unset_userdata('firstlogin');
					$this->session->unset_userdata('me');

					$created = date('Y-m-d H:i:s');
					$created_on = strtotime($created);

					$this->ion_auth->activate($id, false);
					// if ($this->input->post('parent_email')=="") {
					// 	$this->ion_auth->force_login($id);
					// }
					// else{
					// 	$this->db->update('users', array('active' => '0', ), 'id = '.$id);
					// }

					$this->ion_auth->force_login($id);

					if(!$this->input->is_ajax_request())
					{
						redirect();
					}
					else
					{
						echo json_encode(array('url'=>site_url('profile-page'), 'status'=>true));
						return;
					}
				}


			}
			else{
				// Return the validation error
				if(!$this->input->is_ajax_request())
				{
					$this->template->error_string = $this->form_validation->error_string();
					redirect();
				}
				else
				{
					echo json_encode(array('url'=>'', 'status'=>false, 'message'=>$this->form_validation->error_string() ));
					return;
				}
			}
		}
	}*/


	public function register_term_user()
	{
		//init day month year
		$this->month_locale = array('Januari',
								 'Februari',
								 'Maret',
								 'April',
								 'Mei',
								 'Juni',
								 'Juli',
								 'Agustus',
								 'September',
								 'Oktober',
								 'November',
								 'Desember'
								);
		$day_temp = array_values(range(1,31,1));
		$month_temp = array_values(range(1,12,1));
		$this->day_list =  array('hari'=>'hari') + array_combine ( $day_temp , $day_temp );
		$this->month_list = array('bulan'=>'bulan') + array_combine ( $month_temp , $this->month_locale ) ;
		$year_list = range(1910,date('Y'),1);
		$val_test = array_values ( $year_list);
		rsort($val_test);
		$year_list = array_combine ( $val_test , $val_test );
		$this->year_list =   array('tahun'=>'tahun')+$year_list ;

		//set validation rules
		$this->rules_validation = array(
			array(
				'field' => 'social_media',
				'label' => 'Sosial Media',
				'rules' => 'required|xss_clean|callback__valid_social_media'
			),
			array(
				'field' => 'dob',
				'label' => 'Tanggal Lahir',
				'rules' => 'callback__dob',
			),
			array(
				'field' => 'g-recaptcha-response',
				'label' => lang('user:recaptcha'),
				'rules' => 'callback__recaptcha_check',
			),
			array(
				'field' => 'tnc',
				'label' => lang('user:tnc'),
				'rules' => 'callback__tnc',
			),
		);

		$this->vine_rules_validation =array(
			array(
				'field' => 'vine_username',
				'label' => lang('user:vine_username'),
				'rules' => 'required|max_length[60]|callback__vine_login'
			),
			array(
				'field' => 'vine_password',
				'label' => lang('user:vine_password'),
				'rules' => 'required|max_length[60]'
			),
		);

		$this->email_parent_validation =array(
			array(
				'field' => 'parent_email',
				'label' => lang('user:parent_email'),
				'rules' => 'required|max_length[60]|valid_email|callback__same_email|callback__parent_email_check'
				)

		);

		$check_dob = $this->session->userdata('dob_failed');

		//check double
		$data_current = $this->session->userdata('data_current');
		if($this->func_check_parent_email($this->input->post('month'),$this->input->post('day'),$this->input->post('year'))||isset($data_current['parent_email']))
	    {
			$this->rules_validation = array_merge($this->rules_validation,$this->email_parent_validation );
	    }

		if($this->input->post('social_media') =='vine'){
			$this->rules_validation = array_merge($this->rules_validation,$this->vine_rules_validation);
		}

		$this->form_validation->set_rules($this->rules_validation);
		$register_field = new stdClass();
		//format-date = day-month-year //
		if($this->form_validation->run() !== false && !$check_dob)
		{
			$parent_email = $this->input->post('parent_email')?$this->input->post('parent_email'): null;
			$this->session->set_userdata('parent_email',$parent_email);
			$this->session->set_userdata('connect_with',$this->input->post('social_media'));
			$this->session->set_userdata('dob',$this->input->post('day').'/'.$this->input->post('month').'/'.$this->input->post('year'));

			switch($this->input->post('social_media'))
			{

				case 'twitter':

						echo '<script>window.location.href="'.site_url('tw-connect').'";</script>';


				break;

				case 'facebook':

						echo '<script>window.location.href="'.site_url('fb-connect').'";</script>';

				break;

				case 'instagram':

						echo '<script>window.location.href="'.site_url('instagram-connect').'";</script>';

				break;

				case 'vine' :

				$data = new stdClass;
				$this->vine_data = $GLOBALS['vine_data'];

				if(isset($this->vine_data['user_id']))
				{
					$data  = $this->profile_m->get_profile(array('vine_id'=>$this->vine_data['user_id']) );
				}


			//var_dump($data);
			//var_dump($_SESSION);die();
			if($data && isset($data->user_id))
			{
				$this->session->set_userdata('message_register',lang('user:already_register'));
				redirect('persyaratan-dan-ketentuan');
			}
			else {


				$dob =$this->input->post('day').'/'.$this->input->post('month').'/'.$this->input->post('year');
				$dob_int = date_create_from_format('j/n/Y',$dob)->getTimestamp();
				$this->session->set_userdata('message_register',lang('user:success_register'));
				//register
				$profile_data =array();
				$profile_data['display_name'] =$this->vine_data['username'];
				$profile_data['vine_id'] = $this->vine_data['user_id'];
				$profile_data['dob'] = $dob_int;
				$profile_data['dob_date_format'] = $dob;
				$email='';
				$username=$this->vine_data['username'];
				//generate pub, private key
				$password=rand(895,9542324).microtime().rand(0,5443434);
				$username = $this->generate_username($username);
				//split name
				$this->split_fullname($this->vine_data['username'],$profile_data);

				$parent_email = $this->input->post('parent_email')?$this->input->post('parent_email'): null;
				$my_extra = array(	'vine_id'	=>$this->vine_data['user_id'],
									'dob'	=>$dob_int,
									'dob_date_format'=> $dob,

								);

				$id = $this->ion_auth->register($username, $password, $email,$parent_email, null, $profile_data,false,$my_extra);
				if($parent_email)
				{
					//send email confirmation
					$this->send_email_confirmation($parent_email,$id);
				}
				$this->check_id_entity_match_by($id,$this->vine_data['user_id'],'vine');
				//unset
				$this->session->unset_userdata('parent_email');
				$this->session->unset_userdata('connect_with');
				$this->session->unset_userdata('dob');
				$this->ion_auth->activate($id, false);
				redirect('persyaratan-dan-ketentuan');
			  }
				break;

			}
			exit();

		}
		else {
			//var_dump(validation_errors());die();
			//,array('sosmed'=>$connect_with,'day'=>$dob[0],'month'=>$dob[1],'year'=>$dob[2],'parent_email'=>$parent_email )
			$data_current = $this->session->userdata('data_current');

			$this->session->unset_userdata('data_current');
			foreach($this->rules_validation as $field)
			{
				if($field['field'] == 'dob')
				{

					if($data_current)
					{
						$register_field->day =  $data_current['day'];
							$register_field->month = $data_current['month'];
							$register_field->year = $data_current['year'];
							$register_field->dob =  (  $data_current['day'].'/'.  $data_current['month'].'/'.  $data_current['year']  );
					}
					else {
							$register_field->day =  $this->input->post('day');
							$register_field->month =  $this->input->post('month');
							$register_field->year = $this->input->post('year') ;
							$register_field->dob =  ( ( $this->input->post('day') && $this->input->post('month') && $this->input->post('year') ) ? $this->input->post('day').'/'.$this->input->post('month').'/'.$this->input->post('year'): '0/0/0' );
					}



				}
				else {
					if($data_current && isset($data_current[$field['field']]))
					{
						$register_field->{$field['field']} =$data_current[$field['field']];
					}
					else {
						$register_field->{$field['field']} = $this->input->post($field['field']);
					}

				}
			}

		}

		if($message_register = $this->session->userdata('message_register'))
		{
			$this->session->unset_userdata('message_register');
			$this->template->set('message_register',$message_register);
			Asset::js_inline('$(\'document\').ready(function(){ $(\'#message-register\').fadeIn(\'slow\'); });');

		}
		$this->template->set('day_list',form_dropdown('day',$this->day_list,$register_field->day,'id="day" class="reg fancy-select day"'));
		$this->template->set('month_list',form_dropdown('month',$this->month_list,$register_field->month,'id="month" class="fancy-select reg month"'));
		$this->template->set('year_list',form_dropdown('year',$this->year_list,$register_field->year,'id="year" class="fancy-select reg year"') );

		$this->template->append_css('theme::fancySelect.css');
		$this->template->append_js('theme::fancySelect.js');
		$this->template->append_js('theme::dateSelectBoxes.js');
		$this->template->append_js('theme::register.js');
		$this->template->build('register_term',$register_field);
	}



	//reset password
	/**
	 * Reset a user's password
	 *
	 * @param bool $code
	 */
	public function email_parent_confirmation($code = null)
	{
		//check user is valid
		$user		= $this->ion_auth_model->profile($code, true); //pass the code to profile

	    if (!is_object($user))
	    {
			$this->session->set_userdata('message',lang('user:check_code_confirmation'));
			redirect('');
			return;
	    }


		// code is supplied in url so lets try confirm update user
		if ($code && $this->input->post())
		{
			// verify reset_code against code stored in db
			$answer_validation = array(
				array(
				'field' => 'answer',
				'label' => lang('global:password'),
				'rules' => 'xss_clean|required|callback__check_code_confirmation['.$code.']|callback__check_answer_valid'
				),

			);

		     $user		= $this->ion_auth_model->profile($code, true); //pass the code to profile
			$this->form_validation->set_rules($answer_validation);

			if($this->form_validation->run() !==false)
			{

				$this->db->where('forgotten_password_code', $code);

				if ($this->db->count_all_results('users') > 0)
				{

					$data = array(
						'forgotten_password_code'	=> '0',
						'active'			=> ($this->input->post('answer')=='no')? 1 : 0,
						);

					$this->db->update('users', $data, array('forgotten_password_code' => $code));

					$this->session->set_flashdata('message','Email Persetujuan Orang Tua status menjadi '.(($this->input->post('answer')=='no')? 'ditolak' : 'diterima'));
					redirect('');
				}
				else {
					$this->session->set_flashdata('message','Terjadi Kesalahan, silahkan coba beberapa saat lagi');
				}


			}else {

			}
		}
		$this->template->append_js('theme::email_parent_confirmation.js');
		$this->template->set_layout('default.html');
		$this->template->build('parent_confirmation',array('code'=>$code));

	}

	function _check_answer_valid($value)
	{
		$answers = array('yes','no');
		if(in_array($value, $answers))
		{
			return true;
		}
		else {
			$this->form_validation->set_message('_check_answer_valid',lang('user:check_answer_valid'));
			return false;
		}
	}
	function _check_code_confirmation($value,$code)
	{

		$user		= $this->ion_auth_model->profile($code, true);

		if(is_object($user))
		{
			return true;
		}
		else {
			$this->form_validation->set_message('_check_code_confirmation',lang('user:check_code_confirmation'));
			return false;
		}
	}


	/**
	 * Callback method used during login
	 *
	 * @param str $email The Email address
	 *
	 * @return bool
	 */
	public function _check_login($email)
	{
		$remember = false;
		if ($this->input->post('remember') == 1)
		{
			$remember = true;
		}

		if ($this->ion_auth->login($email, $this->input->post('password'), $remember))
		{
			return true;
		}

		Events::trigger('login_failed', $email);
		error_log('Login failed for user '.$email);

		$this->form_validation->set_message('_check_login', $this->ion_auth->errors());
		return false;
	}

	/**
	 * Username check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $username The username to check.
	 *
	 * @return bool
	 */
	public function _username_check($username)
	{
		if ($this->ion_auth->username_check($username))
		{
			$this->form_validation->set_message('_username_check', lang('user:error_username'));
			return false;
		}

		return true;
	}

	/**
	 * Email check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $email The email to check.
	 *
	 * @return bool
	 */
	public function _email_check($email)
	{
		if ($this->ion_auth->email_check($email))
		{
			$this->form_validation->set_message('_email_check', lang('user:error_email'));
			return false;
		}else{
			$check_email = $this->_email_block($email);
			return $check_email;
		}

		return true;
	}

	public function _email_block($email)
	{
		if($email){
			$ret = true;
			$arr_email = explode('@', $email);
			if(count($arr_email) > 0){
				$arr_email_host =  explode('.', $arr_email[1]);
				if(count($arr_email_host) > 0){
					if($arr_email_host[0]=='gmail'){
						$nama_email = str_replace('.', '', $arr_email[0]);
						$valid_email = $nama_email.'@'.$arr_email[1];
						$cek_email = $this->db->get_where('users', array('email'=>$email));
							if($cek_email->num_rows() > 0){
								$ret = false;
								return $ret;
							}
						$cek_valid_email = $this->db->get_where('users', array('valid_email'=>$valid_email));
							if($cek_valid_email->num_rows() > 0){
								$ret = false;
								return $ret;
							}
						$ret = $valid_email;
					}
				}
			}


			if($ret){
				//--------- FOR BLOCK AN DOMAIN
				//build list of not allowed providers as lowercase
				$NotAllowedClients = array("guerrillamail", "mailinator","getairmail","fakeinbox","yopmail","10minutemail","temp-mail","mailcatch","mintemail","easytrashmail","ssl.trashmail.net","trashmail","meltmail","tempemail","sharklasers","fakemailgenerator","armyspy","cuvox","dayrep","einrot","jetable","disposableinbox","mytrashmail","tempmailer","inbox","spamgourmet","incognitomail","fakebox","tempsky","maildrop");

				preg_match_all('/\@(.*?)\./', $email, $clientarr);
				$client = strtolower($clientarr[1][0]);
				if($client=='ssl'){
					$cek = explode('@', $email);
					$client = $cek[1];
				}

				if(!in_array($client,$NotAllowedClients)){
				    //DO NOTHING
				    //var_dump($client); die();
				}else{
				    //NOT ALLOWED
				    $ret = false;
				}
				//---------
			}

			return $ret;
		}else{
			$ret = false;
			return $ret;
		}
	}


	public function _dob()
	{
		$day = $this->input->post('day');
		$month = $this->input->post('month');
		$year = $this->input->post('year');

		if($this->session->userdata('dob_failed'))
		{
				Asset::js_inline('$(document).ready(function(){

										$(\'#error-register\').fadeIn(\'slow\');
									});');
				$this->form_validation->set_message('_dob','');
				return false;
		}
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
				return true;
			}
			//else if(intval($current_year) == intval(Settings::get('max_age')))
			else if(intval($current_year) == intval(12) )
			{
				if($current_day>0 || $current_month>0)
				{
					//var_dump('masuk2');die();
					return true;
				}
				else {

					$this->session->set_userdata('dob_failed','true');
				//notif message success
				Asset::js_inline('$(document).ready(function(){

										$(\'#error-register\').fadeIn(\'slow\');
								   });');
				$this->form_validation->set_message('_dob','');
				return false;
				}
			}
			else {
				$this->session->set_userdata('dob_failed','true');
				//notif message success
				Asset::js_inline('$(document).ready(function(){

										$(\'#error-register\').fadeIn(\'slow\');
								   });');
				$this->form_validation->set_message('_dob','');
				return false;

			}


		}
		else {
				$this->form_validation->set_message('_dob',lang('user:date'));
			return false;
		}

		//var_dump('masuk3');die();

	}

	function send_email($data = array()) {
		$send_email = Events::trigger('email', array(
			'name' 	=> $data['name'],
			'to' 	=> $data['to'],
			'from_name' 	=> 'Coca-Cola Indonesia',
			'slug' 	=> 'parent-email',
			'parent_email'	=> $data['parent_email'],
			'link'		=> $data['link']
		), 'array');

	    return false;
	}

	function akun_aktif(){
		$hash_id = $this->input->post('id');
		$id = $this->decode5t($hash_id);
		$active_akun = $this->db->update('users', array('active' => '0', ), 'id = '.$id);
		if ($active_akun) {
			redirect(site_url());
		}
	}

	function _password_complexcity($pass,$user_id){
			$this->form_validation->set_message('_password_complexcity','Password minimal 8 karakter terdiri minimal 1 huruf, 1 angka dan 1 karakter spesial.');
			preg_match('/[^a-zA-Z0-9]+/ism', $pass,$matches);
			preg_match('/[0-9]+/ism', $pass,$matches2);
			//var_dump($matches);
			if(!empty($matches[0][0]) && isset($matches2[0][0]) && ($matches2[0][0]!='') ) {
				//compare with old password
				if($user_id!=0)
				{
					//echo $user_id;
					$user_info = $this->user_m->get(array('id' => $user_id));
					if($this->method == 'edit' && $this->input->post('old_password'))
					{
						if(!$this->_check_old_password($this->input->post('old_password'),$user_id))
						{
							return false;
						}
					}

					if($user_info)
					{
						$hashed_new_pass = $this->ion_auth_model->hash_password($pass ,$user_info->salt?$user_info->salt:'');
						$data_tst = $this->history_password_m->get_by(array('password_new'=>$hashed_new_pass,'user_id'=>$user_id));

						if($data_tst || ( $hashed_new_pass == $user_info->password))
						{
							$this->form_validation->set_message('_password_complexcity','Password tidak boleh sama dengan password lama.');
							return false;
						}
						else {
							$this->history_password_m->insert(array('user_id'=>$user_id,
																	'password_new'=>$hashed_new_pass,
																	'password_old'=>$user_info->password,
																	'salt'=>$user_info->salt,
																	'message'=>'password edited',
																	'created_on'=>now()
																	));
							return true;
						}

					}
				}
				return true;
			}
			return false;
		}

		function _check_old_password($pass,$user_id)
		{
			$user_info = $this->user_m->get(array('id' => $user_id));
			if($user_info)
			{
				$hashed_new_pass = $this->ion_auth_model->hash_password($this->input->post('old_password') ,$user_info->salt?$user_info->salt:'');
				if($user_info->password !=  $hashed_new_pass)
				{
					$this->form_validation->set_message('_check_old_password','Password Lama tidak cocok');
					return false;
				}
				else {
					return true;
				}


			}
		}

		function _same_email()
		{
			$this->form_validation->set_message('_same_email','Email Orang tua tidak valid');
			if($this->input->post('email') == $this->input->post('parent_email'))
			{
				return false;
			}

			return true;
		}


		function _valid_social_media($social_media)
		{
			if(in_array($social_media,$this->social_media_list))
			{
				return true;
			}
			else {
				$this->form_validation->set_message('_valid_social_media','Sosial Media Tidak Valid');
				return false;
			}
		}

		function _vine_login()
		{
			$this->load->library('vine/vine');
			if( $this->session->userdata('register_type') !='vine'){

				$this->vine->login($this->input->post('vine_username'),$this->input->post('vine_password'));
				$obj = $this->vine->get_vine_session();
				if(isset($obj->success) &&  ($obj->success== true) )
				{
					$GLOBALS['vine_data']= array('username'=>$obj->data->username,'user_id'=>$obj->data->userId);


					return true;
				}
				else {

				$this->form_validation->set_message('_vine_login','Tidak dapat Login Vine, karena "'.$obj->error.'"');
				return false;
				}

			}
		}


		public function vine_test_login()
		{
			$this->load->library('vine/vine');
			$result = $this->vine->login('rustama.1211@gmail.com','Samsung1211');
			$obj = $this->vine->get_vine_session();
			//$this->vine_data =
			//var_dump($result);
		}

	private function generate_username($name)
	{

		$this->load->helper('url');
		$username = url_title($name, '-', true);

		// do they have a long first name + last name combo?
		if (strlen($username) > 19)
		{

			if (strlen($username) > 19)
			{
				// even their last name is over 20 characters, snip it!
				$username = substr($username, 0, 20);
			}
		}


		// Usernames absolutely need to be unique, so let's keep
		// trying until we get a unique one
		$i = 1;

		$username_base = $username;

		while ($this->db->where('username', $username)
			->count_all_results('users') > 0)
		{
			// make sure that we don't go over our 20 char username even with a 2 digit integer added
			$username = substr($username_base, 0, 18).$i;

			++$i;
		}

		return $username;


	}

	private function split_fullname($name,&$profile_data)
	{
		$data_name = explode(' ',$name);
		if(count($data_name)> 1)
		{
			$profile_data['first_name'] =$data_name[0];
			unset($data_name[0]);
			$profile_data['last_name'] = implode(' ',$data_name);
		}
		else {
			$profile_data['first_name'] = $name;
		}
	}

	private function send_email_confirmation($parent_email,$id)
	{
		if ( $this->ion_auth_model->forgotten_password_id($id) )   //changed
		{
			$user = $this->ion_auth->get_user($id);
		// Add in some extra details
			$data['subject']	= Settings::get('site_name') . ' - Email Konfirmasi Orang Tua';
			$data['slug'] 		= 'parent-email';
			$data['to'] 		= $parent_email;
			$data['from'] 		= Settings::get('server_email');
			$data['from_name']	= Settings::get('site_name');
			$data['reply-to']	= Settings::get('contact_email');
			$data['link']		= site_url(array('konfirmasi-orang-tua',$user->forgotten_password_code));
			$data['name']		= $user->display_name;

			// send the email using the template event found in system/cms/templates/
			$results = Events::trigger('email', $data, 'array');
			foreach ($results as $result)
			{
				if ( ! $result)
				{
					return false;
				}
			}

			return true;
		}
		else {
			return false;
		}
	}

	private function check_id_entity_match_by($id,$sosid,$social_media)
	{
		$this->load->model('video/search_content_m');
		$social_media_data = $this->search_content_m->get_by(array('via'=>$social_media,'userid'=>$sosid));
		if($social_media_data)
		{
			$this->search_content_m->update_by(array('via'=>$social_media,'userid'=>$sosid),array('userid_match'=>$id));
		}
	}







/*-----------------------------------------------------------COKE TUNE-----------------------------------------------------------*/	
	private $SESS_DOB = 'SESS_DOB';	
	private $register_validation_array = array(
		array(
			'field' => 'email',
			'label' => 'Alamat E-Mail',
			'rules' => 'required|max_length[60]|valid_email|callback__email_check|callback__string_email_tambahan|xss_clean',
		),
		array(
			'field' => 'display_name',
			'label' => 'Display name',
			'rules' => 'trim|required|min_length[2]|callback__string_angka_spasi|xss_clean',
		),			
		array(
			'field' => 'phone',
			'label' => 'Nomor HP',
			'rules' => 'trim|required|numeric|is_natural|min_length[10]|max_length[15]|xss_clean',
		),
		array(
			'field' => 'alamat',
			'label' => 'Alamat',
			'rules' => 'trim|min_length[5]|max_length[300]|xss_clean',
		),					
		array(
			'field' => 'term',
			'label' => 'Agreement',
			'rules' => 'required',
		),			
	);


	public function register(){
		$this->_already_logged_in();

		// social media
		if('s'){

		}else{

		}
		$this->form_validation->set_rules($this->register_validation_array);
	}

	public function dob(){
		$this->_already_logged_in();
	}

	public function forget_password(){
		$this->_already_logged_in();
		
	}

	public function reset_password(){
		
	}

	public function profile(){
		$this->_restricted_area();
	}



	private function _submit_code(){
		$this->_restricted_area();		
	}

	private function _already_logged_in(){
		if ($this->current_user) {
			$this->session->set_flashdata('flash_message', lang('user:already_logged_in'));
			redirect();
		}
	}

	private function _restricted_area(){
		if(!$this->current_user){
			redirect();
		}
	}


/*-----------------------------------------------------------END COKE TUNE-----------------------------------------------------------*/	

}
