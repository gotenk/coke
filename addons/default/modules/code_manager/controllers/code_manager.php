<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article_manager extends Public_Controller
{
	
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('article_manager_m');
		$this->load->model('search_engine/bitly_cache_m');
		$this->load->library('bitly');
		$this->load->helper('text');		
	}

   public function index()
   {
   		$base_where['status'] = 1;
		$limit = 8;
		switch(Settings::get('article_set_order'))
		{
			case  '0' : $pagination = create_pagination(site_url('event'), $this->article_manager_m->order_by('id','desc',false)->get_many_by($base_where)->num_rows(),$limit,2); 
						$this->db->limit($pagination['limit'], $pagination['offset']);
						
						$articles = $this->article_manager_m->order_by('id','desc',false)->get_many_by($base_where);
						break;
			case  '1' : $pagination = create_pagination(site_url('event'), $this->article_manager_m->order_by('date_custom_int','desc',false)->get_many_by($base_where)->num_rows(),$limit,2 ); 
						$this->db->limit($pagination['limit'], $pagination['offset']);
						
						$articles = $this->article_manager_m->order_by('date_custom_int','desc',false)->get_many_by($base_where);
						break;
			case  '2' : $pagination = create_pagination(site_url('event'), $this->article_manager_m->order_by('order','desc',false)->get_many_by($base_where)->num_rows(),$limit,2 );
						$this->db->limit($pagination['limit'], $pagination['offset']);
						
						$articles = $this->article_manager_m->order_by('order','asc',false)->get_many_by($base_where);		
						break;
		}	
		$this->input->is_ajax_request() and $this->template->set_layout(false);
		Asset::js_inline('var totalPages='.$pagination['total_pages'].' ; ');
		$this->input->is_ajax_request() and $this->template->set_layout(false);
		$this->input->is_ajax_request() and $this->template->set('is_ajax',true);
		$this->template->set('pagination',$pagination)->title('Event')->set('data',$articles)->set_layout('default.html')->build('index');	
   }
   
   public function detail($slug)
	{
		$data = $this->article_manager_m->get_by(array('slug'=>$slug));
		
		$data OR redirect(site_url('event'));
	
		$data_url = site_url();
		if(strpos($data_url, 'localhost')!==false)
		{
				$data_url ='http://sac.maxsol.id'.'/'.$slug;
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
		$twitter_plain_text = 'Ayo ikut serunya Share Coke ';
		$this->template->set('twitter_text',$twitter_plain_text);
		$this->template->set('shorten_url',$data_bitly->url_shorten);
		$this->template->set('share_twitter','https://twitter.com/intent/tweet?text='.rawurlencode($twitter_plain_text.$data_bitly->url_shorten));
		$this->template->set('fb_url',site_url(array('event','detail',$slug)));
		$this->template->set_metadata('og:site_name', 'Ini Sitename','og');
		$this->template->set_metadata('og:title', 'Ini Title','og');
		$this->template->set_metadata('og:description', 'Ini Deskripsi','og');
		$this->template->set_metadata('og:url',site_url(array('event','detail',$slug)),'og');
		$this->template->set_metadata('og:image', base_url($data->picture),'og');
		$this->template->title($data->title);
		$this->template->set('data',$data)->set_layout('default.html')->build('detail');
	}


	
	
}
