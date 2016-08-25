<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{

	public function __construct()
	{
		parent::__construct();

		// No page is mentioned and we are not using pages as default
		//  (eg blog on homepage)
		if ( ! $this->uri->segment(1) and $this->router->default_controller != 'home')
		{

			redirect('');
		}
		$this->load->config('config');
		$this->template->set_layout('default.html');

	}

    // --------------------------------------------------------------------------

	/**
	 * Catch all requests to this page in one mega-function.
	 *
	 * @param string $method The method to call.
	 */
	public function _remap($method)
	{
		// This page has been routed to with pages/view/whatever
		if ($this->uri->rsegment(1, '').'/'.$method == 'home/view')
		{
			$url_segments = $this->uri->total_rsegments() > 0 ? array_slice($this->uri->rsegment_array(), 2) : null;
		}

		// not routed, so use the actual URI segments
		else
		{
			if (($url_segments = $this->uri->uri_string()) === 'favicon.ico')
			{
				$favicon = Asset::get_filepath_img('theme::favicon.ico');

				if (file_exists(FCPATH.$favicon) && is_file(FCPATH.$favicon))
				{
					header('Content-type: image/x-icon');
					readfile(FCPATH.$favicon);
				}
				else
				{
					set_status_header(404);
				}

				exit;
			}

			$url_segments = $this->uri->total_segments() > 0 ? $this->uri->segment_array() : null;
		}



		if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='yay'))
		{
			if($my_id =$this->session->flashdata('success_page'))
			{
				$this->load->model('search_engine/bitly_cache_m');
				$this->load->model('search_engine/list_word_m');
				$this->load->library('bitly');
				$this->session->set_flashdata('sample_page',$my_id);
				/*Asset::css('theme::jquery.mCustomScrollbar.css');
				Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
				Asset::js('theme::jquery.mCustomScrollbar.min.js');*/
				$data_url = site_url();
				if(strpos($data_url, 'localhost')!==false)
				{
						$data_url ='http://sac.maxsol.id';
				}
				$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
				if(!$data_bitly )
				{
					$this->load->library('bitly');
					$data_bitlys = $this->bitly->shorten($data_url);
					$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					$data_bitly = new stdClass;
					$data_bitly->url_shorten = $data_bitlys;
				}
				$data_found = $this->list_word_m->get_by(array('id'=>$my_id,'status'=>'live'));
				//set data facebook share
				$save_file = '/uploads/default/files/sac/';
				$filename = $save_file.strtolower($data_found->name).'_'.$data_found->id.'.png';
				$this->template->set('share_facebook',htmlentities(json_encode(array('caption'=>"ini share facebook" ,'picture'=> base_url($filename),'name'=>"ini share facebook",'description'=>'ini deskripsi facebook: '.$data_bitly->url_shorten.' #ShareACoke','link'=>site_url() ),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8' ));
				//set data twitter share
				$this->template->set('share_twitter','https://twitter.com/intent/tweet?text='.rawurlencode('ini twitter '.$data_bitly->url_shorten.''));
				$this->template->set('fb_url',site_url(array('share',$data_found->slug)));
				$this->load->model('search_engine/list_word_m');
				$this->template->set('data_cocacola',$this->list_word_m->get_range_word($my_id));
				$this->template->set_layout('message.html');
				$this->template->title('Yay');
				$this->template->set('status','success');
				$this->template->build('notif_sac');
			return;
			}
			else {
				redirect('');return;
			}

		}

		if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='oops'))
		{
			if($this->session->flashdata('error_page'))
			{
			/*Asset::css('theme::jquery.mCustomScrollbar.css');
			Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
			Asset::js('theme::jquery.mCustomScrollbar.min.js');*/
			$this->load->model('search_engine/list_word_m');
			$this->template->set('data_cocacola',$this->list_word_m->get_random_word());
			$this->template->set_layout('message.html');
			$this->template->title('Ops');
			$this->template->set('status','error');
			$this->template->build('notif_sac');
			return;
			}
			else {
				redirect('');return;
			}
		}

		if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='sample'))
		{
			/*Asset::css('theme::jquery.mCustomScrollbar.css');
			Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
			Asset::js('theme::jquery.mCustomScrollbar.min.js');*/
			if($this->session->flashdata('sample_page'))
			{
				$this->template->title('Sample');
				$this->template->build('sample');
				return;
			}
			else {
				redirect('');return;
			}
		}

		if(count($url_segments) && isset($url_segments[1]) && isset($url_segments[2]) && ($url_segments[1] =='share')  )
		{
			$this->load->model('search_engine/bitly_cache_m');
				$this->load->model('search_engine/list_word_m');
				$this->load->library('bitly');
			$slug = $url_segments[2];
			$data_found = $this->list_word_m->get_by(array('slug'=>$slug,'status'=>'live'));
			if($data_found)
			{
				//set data facebook share
				$save_file = '/uploads/default/files/sac/';
				$filename = $save_file.strtolower($data_found->name).'_'.$data_found->id.'.png';
				$data_url = site_url();
				if(strpos($data_url, 'localhost')!==false)
				{
						$data_url ='http://sac.maxsol.id'.'/'.$slug;
				}
				if((! is_file(getcwd().$filename )))
				{
					redirect('');
					return;
				}
				$data_bitly =$this->bitly_cache_m->get_by(array('url'=>$data_url));
				if(!$data_bitly )
				{
					$this->load->library('bitly');
					$data_bitlys = $this->bitly->shorten($data_url);
					$this->bitly_cache_m->insert(array('url'=>$data_url,'url_shorten'=>$data_bitlys));
					$data_bitly = new stdClass;
					$data_bitly->url_shorten = $data_bitlys;
				}
				$this->template->set('share_facebook',htmlentities(json_encode(array('caption'=>"ini share facebook" ,'picture'=> base_url($filename),'name'=>"ini share facebook",'description'=>'ini deskripsi facebook: '.$data_bitly->url_shorten.' #ShareACoke','link'=>site_url() ),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8' ));
				//set data twitter share
				$this->template->set('share_twitter','https://twitter.com/intent/tweet?text='.rawurlencode('ini twitter '.$data_bitly->url_shorten.''));
				$twitter_plain_text = 'Ayo ikut serunya Share Coke ';
				$this->template->set('twitter_text',$twitter_plain_text);
				$this->template->set('shorten_url',$data_bitly->url_shorten);
				$this->template->set('share_twitter','https://twitter.com/intent/tweet?text='.rawurlencode($twitter_plain_text.$data_bitly->url_shorten));
				$this->template->set('fb_url',site_url(array('event','detail',$slug)));
				$this->template->set_metadata('og:site_name', 'Ini Sitename','og');
				$this->template->set_metadata('og:title', 'Ini Title','og');
				$this->template->set_metadata('og:description', 'Ini Deskripsi','og');
				$this->template->set_metadata('og:url',site_url(array('share',$slug)),'og');
				$this->template->set_metadata('og:image', base_url($filename),'og');
				$this->template->title($data_found->name);
				$this->template->set('data',$data_found)->set_layout('share.html')->build('sample');
				return;
			}
			else {
				redirect('');
			}

		}




	 	if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='kebijakan-privasi'))
		{
			/*Asset::css('theme::jquery.mCustomScrollbar.css');
			Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
			Asset::js('theme::jquery.mCustomScrollbar.min.js');*/

			$this->template->title('Kebijakan Privasi');
			//$this->template->set('image_background','bg-how-to.jpg');
			$this->template->build('kebijakan_privasi');
			return;
		}

		if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='persyaratan-penggunaan'))
		{
			/*Asset::css('theme::jquery.mCustomScrollbar.css');
			Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
			Asset::js('theme::jquery.mCustomScrollbar.min.js');*/

			$this->template->title('Persyaratan Penggunaan');
			//$this->template->set('image_background','bg-how-to.jpg');
			$this->template->build('persyaratan_penggunaan');
			return;
		}

		if(count($url_segments) && isset($url_segments[1]) && ($url_segments[1] =='syarat-dan-ketentuan'))
		{
			/*Asset::css('theme::jquery.mCustomScrollbar.css');
			Asset::js('theme::jquery.mCustomScrollbar.concat.min.js');
			Asset::js('theme::jquery.mCustomScrollbar.min.js');*/

			$this->template->title('Syarat Dan Ketentuan');
			//$this->template->set('image_background','bg-how-to.jpg');
			$this->template->build('syarat_dan_ketentuan');
			return;
		}

		$this->_home($url_segments);


	}

    // --------------------------------------------------------------------------

	/**
	 * Page method
	 *
	 * @param array $url_segments The URL segments.
	 */
	public function _home($url_segments)
	{

		if($code = $this->session->flashdata('code'))
		{
			$this->template->set('reset_code',site_url(array('reset-pass',$code) ) );
			Asset::js_inline('var RESET_CODE=true;');
		}

		$is_home = false;
		$is_404 = false;
		if($url_segments == null) $is_home = true;
		// If page is missing or not live (and the user does not have permission) show 404
		if ( $url_segments != NULL && is_array($url_segments) && isset($url_segments[1]) && !(method_exists($this,$url_segments[1] )))
		{
			// Load the '404' page. If the actual 404 page is missing (oh the irony) bitch and quit to prevent an infinite loop.
			/*if ( ! file_exists(getcwd().'/'.APPPATH.'themes/'.$this->theme->slug.'/views/layouts/404.html') )
			{
				if(!file_exists(getcwd().'/'.ADDONPATH.'themes/'.$this->theme->slug.'/views/layouts/404.html'))
				{
					show_error('The page you are trying to view does not exist and it also appears as if the 404 page has been deleted.');
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}*/
			$is_404 = true;
		}


		// If this is a homepage, do not show the slug in the URL
		if ($is_home  && is_array($url_segments) && isset($url_segments[1]) && ($url_segments[1] == strtolower(get_class($this)) ))
		{
			redirect('', 'location', 301);
		}

		// If the page is missing, set the 404 status header
		if ($is_404)
		{
			$this->output->set_status_header(404);
		}


		// ---------------------------------
		// Metadata
		// ---------------------------------

		// First we need to figure out our metadata. If we have meta for our page,
		// that overrides the meta from the page layout.
		/*$meta_title = ($page->meta_title ? $page->meta_title : $page->layout->meta_title);
		$meta_description = ($page->meta_description ? $page->meta_description : $page->layout->meta_description);
		$meta_keywords = '';
		if ($page->meta_keywords or $page->layout->meta_keywords)
		{
			$meta_keywords = $page->meta_keywords ?
								Keywords::get_string($page->meta_keywords) :
								Keywords::get_string($page->layout->meta_keywords);
		}

		$meta_robots = $page->meta_robots_no_index ? 'noindex' : 'index';
		$meta_robots .= $page->meta_robots_no_follow ? ',nofollow' : ',follow';
		// They will be parsed later, when they are set for the template library.

		// Not got a meta title? Use slogan for homepage or the normal page title for other pages
		if ( ! $meta_title)
		{
			$meta_title = $page->is_home ? $this->settings->site_slogan : $page->title;
		}

		// Set the title, keywords, description, and breadcrumbs.
		$this->template->title($this->parser->parse_string($meta_title, $page, true))
			->set_metadata('keywords', $this->parser->parse_string($meta_keywords, $page, true))
			->set_metadata('robots', $meta_robots)
			->set_metadata('description', $this->parser->parse_string($meta_description, $page, true))
			->set_breadcrumb($page->title);*/

		// Parse the CSS so we can use tags like {{ asset:inline_css }}
		// #foo {color: red} {{ /asset:inline_css }}
		// to output css via the {{ asset:render_inline_css }} tag. This is most useful for JS
		//$css = $this->parser->parse_string($page->layout->css.$page->css, $this, true);

		// there may not be any css (for sure after parsing Lex tags)
		/*if ($css)
		{
			$this->template->append_metadata('
				<style type="text/css">
					'.$css.'
				</style>', 'late_header');
		}

		$js = $this->parser->parse_string($page->layout->js.$page->js, $this, true);

		// Add our page and page layout JS
		if ($js)
		{
			$this->template->append_metadata('
				<script type="text/javascript">
					'.$js.'
				</script>');
		}*/

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
		$this->template->set('day_list',form_dropdown('day',$this->day_list,null,'id="day" class="fancy-select day"'));
		$this->template->set('month_list',form_dropdown('month',$this->month_list,null,'id="month" class="fancy-select month"'));
		$this->template->set('year_list',form_dropdown('year',$this->year_list,null,'id="year" class="fancy-select year"'));
		/*
		form_hidden('dob',$profiles->dob);
        form_hidden('d0ntf1llth1s1n',' '); */

		/*if($this->session->userdata('is_register') == true )
		{

			//check login by social media
			if($this->session->userdata('register_status') == 'tw')
			{
				$this->template->set('register_status','Twitter');
				$access_token = $this->session->userdata('access_token');
				$this->load->library('twitter',array(Settings::get('consumer_key'), Settings::get('consumer_key_secret'), $access_token['oauth_token'],$access_token['oauth_token_secret']));
				//check user has registered
				//$rate_limit =  $this->twitter->get('1/account/rate_limit_status');
				if(!$this->session->userdata('id') && !$this->session->userdata('image_url') && !$this->session->userdata('screen_name') && !$this->session->userdata('tw_name'))
				{
					$data = $this->twitter->get('account/verify_credentials');
				}
				else {
					$data = new stdClass;

					$data->id = $this->session->userdata('id');
					$data->profile_image_url = $this->session->userdata('image_url');
					$data->screen_name = $this->session->userdata('screen_name');
					$data->name = $this->session->userdata('tw_name');
				}

				//$this->session->unset_userdata('register_status');

				if(isset( $data->id))
				{
					$this->session->set_userdata('id', $data->id);
					$this->session->set_userdata('image_url',$data->profile_image_url);
					$this->session->set_userdata('screen_name',$data->screen_name);
					$this->session->set_userdata('tw_name',$data->name);


						$this->template->set('type','tw');
						$this->template->set('name',$data->name);
						$this->template->set('tw_name',$data->name);
						$this->template->set('screen_name',$data->screen_name);
						$this->template->set('image_url',$data->profile_image_url);
						$this->template->set('sos_med_id',$data->id);
						//var_dump($data);
						$is_failed = $this->session->userdata('dob_failed');

						if($is_failed)
						{
							Asset::js_inline('$(document).ready(function(){
							$(\'#reject-data-popup\').show();
							});');
						}
						else {

							Asset::js_inline('$(document).ready(function(){
							$(\'#data-diri-popup\').fadeIn();
							});');
						}

				}
			}
			else if($this->session->userdata('register_status')=='fb')
			{
					$this->template->set('register_status','Facebook');

						//end facebook enabled
					try
					{
						$me = $this->facebook->api('/'.$this->facebook->getUser());
					}
					catch(FacebookApiException $e)
					{
						echo '<html><head><META HTTP-EQUIV="REFRESH"
		CONTENT="5;URL='.site_url('fb-connect').'?'.(($this->input->get())?http_build_query($this->input->get()):'').'"><title>Sedang Di Arahkan Ulang</title></head><body>tunggu sebentar</body></html>';
						//redirect();
						return;
					}

					$email='';
					$email = (isset($me['email']))? $me['email']:$email;
					$this->session->set_userdata('type','fb');
					$this->template->set('email',$email);
					$this->template->set('name',$me['name']);
					$this->template->set('sos_med_id',$me['id']);
					$this->template->set('image_url','https://graph.facebook.com/'.$me['id'].'/picture?type=square');

					//var_dump($data);
					$is_failed = $this->session->userdata('dob_failed');

					if($is_failed)
					{
						Asset::js_inline('$(document).ready(function(){
						$(\'#reject-data-popup\').show();
						});');
					}
					else {

						Asset::js_inline('$(document).ready(function(){
						$(\'#data-diri-popup\').fadeIn();
						});');
					}


			}
			else if( $this->session->userdata('register_status')=='email')
			{
				 $this->template->set('type','email');

			}





		}*/

		$this->template->set('is_home',true);
		// We are going to pre-build this data so we have the data
		// available to the template plugin (since we are pre-parsing our views).
		$template = $this->template->build_template_data();
		// Parse our view file. The view file is nothing
		// more than an echo of $page->layout->body and the
		// comments after it (if the page has comments).
		$html = $this->template->load_view('home/home', array('extra' =>'null'), false);
		$view = $this->parser->parse_string($html, array('extra' =>'null','is_home'=>true), true, false);

		if ($is_404)
		{
			redirect('');
			log_message('error', 'Page Missing: '.$this->uri->uri_string());
			$html = $this->template->load_view('layouts/404.html', array('extra' =>'null'), false);
			$view = $this->parser->parse_string($html, array('extra' =>'null','is_home'=>true), true, false);
			// things behave a little differently when called by MX from MY_Exceptions' show_404()
			exit($this->template->build($view, array('extra' =>'null'), false, false, true, $template));
		}

		$this->template
					->append_js('theme::masonry.pkgd.min.js')
					->append_js('theme::imageloader.js')
					// ->append_js('theme::home.js')
					->append_js('theme::jquery.tinylimiter.js')
					->append_js('theme::calculate-size.js')
					->build($view, array('extra' => 'null'), false, false, true, $template);
	}

    // --------------------------------------------------------------------------

	public function privacy_policy()
	{
		$this->template->set_layout('privacypolicy.html');
		$this->template->title('Kebijakan');
		$this->template->build('home/home');
	}

}
