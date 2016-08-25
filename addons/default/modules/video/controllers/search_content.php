<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Search_content extends Public_Controller {

	
	public function __construct() {
		parent::__construct();
	
		//$this->load->library('youtube');
		//$this->load->library('facebook');
		//$this->load->library('Hashids');
		$this->load->library('Twitter_api');
		$this->load->config('video/config');
		$this->global_twitter = new Twitter_api();
     	$settings_twitter = array();
		$settings_twitter['oauth_access_token'] = Settings::get('oauth_access_token');
		$settings_twitter['oauth_access_token_secret'] = Settings::get('oauth_access_token_secret');
		$settings_twitter['consumer_key'] = Settings::get('consumer_key');
		$settings_twitter['consumer_secret'] = Settings::get('consumer_key_secret');
		$this->global_twitter->initialize($settings_twitter);
		//$this->global_tag = '#SampaikanMaaf';
		$this->global_tag = '#'.Settings::get('crawling-hashtag');
		$this->global_tag_only = Settings::get('crawling-hashtag');
		
		$this->load->model('search_content_m');
		$this->load->model('video_status_m');
		//tempat ganti path ntar di coke di ganti juga ya...
		$this->cli_path = $this->input->is_cli_request()? '/var/www/ramadan/html/' : getcwd(); 
		$this->debug = true;
		
	}

	public function index() {
		
		//$this->search_youtube();
		
		$this->search_hashtag();
		$this->crawlInstagram();
		$this->search_vine();
		$this->check_facebook();
		
		//$this->search_facebook_hashtag();	
		
		
		
	}
	
	/* INSTAGRAM HASHTAG SEARCH */
	private function crawlInstagram(){
	   	$hashtag = $this->global_tag_only;
		$instagram['access_token'] = Settings::get('instagram-token');
	   
	    // get the last id
	    $this->db->order_by('entity_id', 'desc');
	    $this->db->limit(1);
	    $info_insta = $this->db->get_where('video');
	    $entity_id = 0;
	    if($info_insta->num_rows() > 0){
	    	$entity_id = $info_insta->row('max_id');
	    }

	    $get_media_url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?access_token='.$instagram['access_token'];
	    if ($entity_id > 0){
	        $get_media_url .= '&max_tag_id='.$entity_id;
	    }
		$get_media_url .= '&count=400';

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $get_media_url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);
	    curl_close($ch);

	    $media = json_decode($response, true);
		
		if(isset($media['data']))
		{
		    foreach($media['data'] as $insta){
		        //var_dump($insta); die();
				$time_now = date('Y-m-d H:i:s');	 
		        $source_id = $insta['id'];
		        $created_on = strtotime(date('Y-m-d H:i:s', $insta['created_time']));
		        $user_id = $insta['user']['id'];
				$user_photo = $insta['user']['profile_picture'];
				$link_media = $insta['link'];
				$id = explode('_',$source_id);
		       
		        $screen_name = $insta['user']['username'];
		        $this_name =  $this->stripEmojis($insta['user']['full_name']);
		        $text =  $this->stripEmojis($insta['caption']['text']);
		        $post_link	= $insta['link'];
				
				//validate content hashtag
				if(! $this->search_preg_hash($this->global_tag,$text))
				{
					continue;
				}
				//var_dump($insta);
		        if ($insta['type'] == 'video'){
		            $type= 'video';
		            $media_url_video_preview=$insta['images']['standard_resolution']['url'];
		            $media_url_video=$insta['videos']['standard_resolution']['url'];
		        /*} else {
		            $type= 'photo';
		            $media_url=$insta['images']['standard_resolution']['url'];
		            $media_url_https= '';*/
		             
					
			        $data_insta = array(
			        	'description'		=> $text,
			        	//'photo_profile'	=> $user_photo,
			        	'created_on'	=> $created_on,
			        	'created'		=> $time_now,
			        	'max_id'		=> (isset($media['pagination']['next_max_tag_id'])? $media['pagination']['next_max_tag_id']: NULL),
			        	'since_id'		=> $media['pagination']['min_tag_id'],
						'entity_id'		=> $id[0],
			        	'userid'		=> $user_id,
			        	'name'			=> ($this_name)? $this_name :  $screen_name,
						'video'			=> $media_url_video,
						'url'			=> $insta['link'],
						//'video_preview' => $media_url_video_preview,
			        	'via'		=> 'instagram',
						'status'	=>'draft'
			        );
					
					$id_uid_match = $this->match_user_id_sosmed($user_id,'instagram');
			    	if($id_uid_match)
					{
						$data_insta['userid_match'] = $id_uid_match;
					}
					//$pos = strpos($text, '#'.$hashtag); 
					//var_dump($pos);
					//if( $pos){
						$this->search_content_m->insert_search_content($data_insta);
						
						//update image name
						$id = $this->db->insert_id();
						$new_imagename = $this->change_image($id,$media_url_video_preview);
						$new_video_preview = $this->grab_picture_twitter($source_id,$media_url_video_preview,$new_imagename);
						//user photo
						$new_imagename_photo = $this->change_image($id,$user_photo);
						$new_user_photo = $this->grab_picture_twitter($source_id,$user_photo,$new_imagename_photo);
						$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
						//end update image name
						
						/*$get_comment_url = 'https://api.instagram.com/v1/media/'.$source_id.'/comments?access_token='.$instagram['access_token'];
		
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $get_comment_url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$res = curl_exec($ch);
						curl_close($ch);
		
						$comment = json_decode($res, true);
		
						foreach($comment['data'] as $comm){
							$data_comment = array(
								'id_comment'		=> $comm['id'],
								'id_entity'			=> $source_id,
								'comment'			=> $comm['text'],
								'name'				=> $comm['from']['full_name'],
								'username'			=> $comm['from']['username'],
								'profile_picture'	=> $comm['from']['profile_picture'],
								'created_on'		=> $comm['created_time'],
								'created'			=> $time_now,
							);
							$this->search_content_m->insert_search_content_comment($data_comment);
						}*/
					//}
				}
			}	
	    }
		
		if(isset($media['pagination']))
		{
			
			 //update max and since id in video_status
			 $result_status = $this->video_status_m->get_by(array('via'=>'instagram'));
			 if($result_status)
			 {
				 $media['pagination']['next_max_tag_id'] = (isset($media['pagination']['next_max_tag_id'])&& (intval($media['pagination']['next_max_tag_id']) > intval($result_status->since_id))) ? intval($media['pagination']['next_max_tag_id']):intval($result_status->since_id);
				$media['pagination']['min_tag_id'] = (intval($media['pagination']['min_tag_id'])  < intval($result_status->max_id))? intval($media['pagination']['min_tag_id']) :intval($result_status->max_id); 
				 $this->video_status_m->update_by(array('via'=>'instagram'),array('max_id'=>$media['pagination']['next_max_tag_id'],'since_id'=>$media['pagination']['min_tag_id']));
			 }
			 else {
			 	$this->video_status_m->insert(array('max_id'=>((isset($media['pagination']['next_max_tag_id']))?$media['pagination']['next_max_tag_id'] :NULL),'since_id'=>((isset($media['pagination']['min_tag_id']))?$media['pagination']['min_tag_id'] :NULL),'via'=>'instagram'));
			 }
			 //end update status video
		}

	}
		
			
	//PRIVATE FUNCTION//
	/* TWITTER HASHTAG SEARCH */
	private function filter_tag($text='',$filter_text='')
    {
    	return TRUE;
   		$filter_text = explode(',',$filter_text);
		foreach($filter_text as &$data)
		{
			$data = trim($data);
		}
		
		$filter_text = array_filter(array_unique($filter_text));
		
		if(count($filter_text) &&  preg_match('/('.implode('|',$filter_text).')/i',$text)) return FALSE;
		
		return TRUE;
    }
	
	
	private function search_vine()
	{
		$this->load->library('vine/vine');
		
		//check update
		$data_video_status= $this->video_status_m->get_by(array('via'=>'vine'));
		
		 //maximum		
		 $since_id = NULL;
	     $since_id = ($data_video_status)? intval($data_video_status->since_id): $data_video_status;

		 //minimum
		 $max_id = NULL;
		 $max_id = ($data_video_status)? intval($data_video_status->max_id): $data_video_status;
		 
		 //total page
		 $total_page_db =($data_video_status)?  intval($data_video_status->total_page): $data_video_status;;
		if($since_id && $max_id)
		{
			//update status_page
			$data =$this->vine->get_tag($this->global_tag_only,false,100);
			$total_data = intval($data->count)? $data->count: 1;
			$total_page = ceil(intval($total_data)/100);
			if($total_page != $total_page_db)
			{
				//update maximum /newest page
				$since_id = $since_id + ($total_page - $total_page_db);
				//update maximum /oldest page
				$max_id = $max_id + ($total_page - $total_page_db);
				$total_page =$total_page_db;
				$this->video_status_m->update_by(array('via'=>'vine'),array('max_id'=>$max_id,'total_page'=>$total_page,'since_id'=>$since_id ));
				
			}
			
			//check latest_page
			$data_since =$this->vine->get_tag($this->global_tag_only,$since_id ,100);
			if(isset($data_since->records) && count($data_since->records))
			{
				foreach($data_since->records as $record)
				{
					//validate content hashtag
					if(! $this->search_preg_hash($this->global_tag,$record->description))
					{
						continue;
					}
					//var_dump($record);
					$date = new DateTime($record->created);
					$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
					$timestamp = strtotime($record->created);
					$date_str = $date->format('Y-m-d H:i:s');
					$text =  $this->stripEmojis($record->description);
					$dataSave = array(	  'description'=> $text ,
									  'video'=>$record->videoUrl ,
									  'url'=>$record->permalinkUrl,
									  'userid'=>$record->userId,
									  'name'=>$record->username,
									  //'photo_profile'=>$profile_picture,
									  //'video_preview'=>$picture,													 
									  'created_on'=>$timestamp,
									  'created'=>$date_str,
									  'via'=>'vine',
									  'status'=>'draft',
									  'entity_id'=>$record->postId,
									  'max_id'=>$record->postId,
									  'since_id'=>$record->postId);
					$id_uid_match = $this->match_user_id_sosmed($record->userId,'vine');
			    	if($id_uid_match)
					{
						$dataSave['userid_match'] = $id_uid_match;
					}
					$this->search_content_m->insert_search_content($dataSave);
					
					//update image name
					$id = $this->db->insert_id();
					$new_imagename = $this->change_image($id,$record->thumbnailUrl);
					$new_video_preview = $this->grab_picture_twitter($record->userId,$record->thumbnailUrl,$new_imagename);
					//user photo
					$new_imagename_photo = $this->change_image($id,$record->avatarUrl);
					$new_user_photo = $this->grab_picture_twitter($record->userId,$record->avatarUrl,$new_imagename_photo);
					$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
					//end update image name
				}
				if($since_id >1)
				{
					$since_id = $since_id-1;
					$this->video_status_m->update_by(array('via'=>'vine'),array('since_id'=>$since_id));
				}
				
			}
			
			//check oldest page
			$data_max =$this->vine->get_tag($this->global_tag_only,$max_id ,100);
			if(isset($data_max->records) && count($data_max->records))
			{
				foreach($data_max->records as $record)
				{
					//validate content hashtag
					if(! $this->search_preg_hash($this->global_tag,$record->description))
					{
						continue;
					}
					//var_dump($record);
					$date = new DateTime($record->created);
					$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
					$timestamp = strtotime($record->created);
					$date_str = $date->format('Y-m-d H:i:s');
					$text =  $this->stripEmojis($record->description);
					$dataSave = array(	  'description'=> $text ,
									  'video'=>$record->videoUrl ,
									  'url'=>$record->permalinkUrl,
									  'userid'=>$record->userId,
									  'name'=>$record->username,
									  //'photo_profile'=>$profile_picture,
									  //'video_preview'=>$picture,													 
									  'created_on'=>$timestamp,
									  'created'=>$date_str,
									  'via'=>'vine',
									  'status'=>'draft',
									  'entity_id'=>$record->postId,
									  'max_id'=>$record->postId,
									  'since_id'=>$record->postId);
					$id_uid_match = $this->match_user_id_sosmed($record->userId,'vine');
			    	if($id_uid_match)
					{
						$dataSave['userid_match'] = $id_uid_match;
					}
					$this->search_content_m->insert_search_content($dataSave);
					
					//update image name
					$id = $this->db->insert_id();
					$new_imagename = $this->change_image($id,$record->thumbnailUrl);
					$new_video_preview = $this->grab_picture_twitter($record->userId,$record->thumbnailUrl,$new_imagename);
					//user photo
					$new_imagename_photo = $this->change_image($id,$record->avatarUrl);
					$new_user_photo = $this->grab_picture_twitter($record->userId,$record->avatarUrl,$new_imagename_photo);
					$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
					//end update image name
				}
				
				if($max_id <= $total_page_db)
				{
					$max_id =$max_id+1;
					$this->video_status_m->update_by(array('via'=>'vine'),array('max_id'=>$max_id));
				}
			}
			
		}
		else {
			$data =$this->vine->get_tag($this->global_tag_only,false,100);
			//var_dump($data->count);die();
			if(isset($data->records) && count($data->records))
			{
				foreach($data->records as $record)
				{
					//validate content hashtag
					if(! $this->search_preg_hash($this->global_tag,$record->description))
					{
						continue;
					}
					$date = new DateTime($record->created);
					$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
					$timestamp = strtotime($record->created);
					$date_str = $date->format('Y-m-d H:i:s');
					$text =  $this->stripEmojis($record->description);
					$dataSave = array(	  'description'=> $text ,
									  'video'=>$record->videoUrl ,
									  'url'=>$record->permalinkUrl,
									  'userid'=>$record->userId,
									  'name'=>$record->username,
									  //'photo_profile'=>$profile_picture,
									  //'video_preview'=>$picture,													 
									  'created_on'=>$timestamp,
									  'created'=>$date_str,
									  'via'=>'vine',
									  'status'=>'draft',
									  'entity_id'=>$record->postId,
									  'max_id'=>$record->postId,
									  'since_id'=>$record->postId);
					$id_uid_match = $this->match_user_id_sosmed($record->userId,'vine');
			    	if($id_uid_match)
					{
						$dataSave['userid_match'] = $id_uid_match;
					}
					$this->search_content_m->insert_search_content($dataSave);
					
					//update image name
					$id = $this->db->insert_id();
					$new_imagename = $this->change_image($id,$record->thumbnailUrl);
					$new_video_preview = $this->grab_picture_twitter($record->userId,$record->thumbnailUrl,$new_imagename);
					//user photo
					$new_imagename_photo = $this->change_image($id,$record->avatarUrl);
					$new_user_photo = $this->grab_picture_twitter($record->userId,$record->avatarUrl,$new_imagename_photo);
					$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
					//end update image name
					
					// thumbnailUrl = video_preview
					//description = description
					//videoUrl = video
					//permalinkUrl = url
					//userId = userid
					//name = username
					//entity_id = postId
					
					
				}
			}
			
			//total page
			$total_data = intval($data->count)? $data->count: 1;
			$total_page = ceil(intval($total_data)/100);
			$current_max_id = $current_since_id = 1;
			
			if(isset($data->backAnchor) && isset($data->anchor))
			{
				 $result_status = $this->video_status_m->get_by(array('via'=>'vine'));
				 if($result_status)
				 {
					 $this->video_status_m->update_by(array('via'=>'vine'),array('max_id'=>$current_max_id,'total_page'=>$total_page,'since_id'=>$current_since_id));
				 }
				 else {
				 	$this->video_status_m->insert(array('max_id'=>$current_max_id,'since_id'=>$current_since_id,'total_page'=>$total_page,'via'=>'vine'));
				}
			}
		}
		
		
	}
	
	private function search_hashtag()
	{
		$max_count = 100;
		$this->load->model('search_content_m');
		
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
	   // if( $this->input->is_cli_request() )
		//{
			log_message('error', 'Cron search_hashtag method Initialized');
			 $filter_text = null;
   
			//$filter_text = $this->db->get('default_hashtag_filter')->row('filter');
			
			$data_video_status= $this->video_status_m->get_by(array('via'=>'twitter'));
			 //maximum		
			 $since_id = NULL;
		     $since_id = ($data_video_status)? $data_video_status->since_id: $data_video_status;

			 //minimum
			 $max_id = NULL;
			 $max_id = ($data_video_status)?$data_video_status->max_id: $data_video_status;
			 
			if($since_id != NULL && $max_id != NULL)
			{
				//get max id
					$getfield = '?q='.rawurlencode($this->global_tag).'&since_id='.$since_id.'&mode=videos&include_entities=1&count='.$max_count;
					$requestMethod = 'GET';
					$result = $this->global_twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();
				log_message('debug', 'Perform get Twitter By Max ID ');
				$data =  json_decode($result);
				
				 if(isset($data->{'statuses'}))
				 {
				 	
				 	$_max = $data->{'search_metadata'}->{'max_id'} ;
					$_since_id = $data->{'search_metadata'}->{'since_id'} ;
					 $video='';
				 	 foreach($data->{'statuses'} as $data_insert)
					 {
						 
					 	if (!$this->filter_tag($data_insert->text,$filter_text)) continue;
						$picture='';
								$content = $data_insert->text;
								//validate content hashtag
								if(! $this->search_preg_hash($this->global_tag,$content))
								{
									continue;
								}
								if(isset($data_insert->entities->media)&& count($data_insert->entities->media))
								{
								
									$url_image = $data_insert->entities->media[0]->media_url;
									$name_file = basename($url_image);
									$data_ck = explode('.',$name_file);
									$data_ext =end($data_ck);
									$valid_extension = array('jpeg','jpg','png','gif');
									if (in_array($data_ext,$valid_extension))
									{
									
										$picture = $url_image;
											
									}
									
									if(isset($data_insert->entities->media[0]->expanded_url))
									{
										$video =$data_insert->entities->media[0]->expanded_url;
										
										
									}
									
									
								}
								
								if(!preg_match('/\/video\/1$/',$video) && $this->debug)
								{
									continue 1;
								}
							
								//$profile_picture = $this->grab_picture_twitter($data_insert->user->id,$data_insert->user->profile_image_url);
								
								$date = new DateTime($data_insert->created_at);
								$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
								$timestamp = strtotime($data_insert->created_at);
								$date_str = $date->format('Y-m-d H:i:s');
								$data = array(		  'description'=>$content,
													  'video'=>$video ,
													  'url'=>$video,
													  'userid'=>$data_insert->user->id,
													  'screen_name'=>$data_insert->user->screen_name,
													  'name'=>$data_insert->user->name,
													  //'photo_profile'=>$profile_picture,
													  //'video_preview'=>$picture,													 
													  'created_on'=>$timestamp,
													  'created'=>$date_str,
													  'via'=>'twitter',
													  'status'=>'draft',
													  'entity_id'=>$data_insert->id_str,
													  'max_id'=>$_max,
													  'since_id'=>$_since_id);
								$id_uid_match = $this->match_user_id_sosmed($data_insert->user->id,'twitter');
						    	if($id_uid_match)
								{
									$data['userid_match'] = $id_uid_match;
								}
								$this->search_content_m->insert_search_content($data);
								//update image name
								$id = $this->db->insert_id();
								$new_imagename = $this->change_image($id,$picture);
								$new_video_preview = $this->grab_picture_twitter($data_insert->user->id,$picture,$new_imagename);
								//user photo
								$new_imagename_photo = $this->change_image($id,$data_insert->user->profile_image_url);
								$new_user_photo = $this->grab_picture_twitter($data_insert->user->id,$data_insert->user->profile_image_url,$new_imagename_photo);
								$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
								//end update image name
								
								
					 		
					 }
				   	//update max and since id in video_status
					 $result_status = $this->video_status_m->get_by(array('via'=>'twitter'));
					 if($result_status)
					 {
						$_since_id = ($_since_id > intval($result_status->since_id))? $_since_id:intval($result_status->since_id);
						$_max = ($_max  < intval($result_status->max_id))? $_max :intval($result_status->max_id); 
						$this->video_status_m->update_by(array('via'=>'twitter'),array('max_id'=>$_max,'since_id'=>$_since_id));
						
					 }
					 else {
						$this->video_status_m->insert(array('max_id'=>$_max,'since_id'=>$_since_id,'via'=>'twitter'));
					 }
					 //end update status video
				 }
				//get old one
					$getfield = '?q='.rawurlencode($this->global_tag).'&max_id='.$max_id.'&mode=videos&include_entities=1&count='.$max_count;
					//.'&until='.$last_date;
					$requestMethod = 'GET';
					$result = $this->global_twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();
				log_message('error', 'Perform get Twitter By Minimum ID');
				$data1 =  json_decode($result);
				
				 if(isset($data1->{'search_metadata'}->next_results))
				 {
					$getfield =$data1->{'search_metadata'}->next_results;
					$requestMethod = 'GET';
					$result = $this->global_twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();
					log_message('error', 'Perform get Twitter By Minimum ID In Next Results');
					$data2 =  json_decode($result);
					
				 	if (isset($data2->{'statuses'}))
					{
						 $_max_1 = $data2->{'search_metadata'}->{'max_id'} ;
						 $_since_id_1 = $data2->{'search_metadata'}->{'since_id'} ;
						$video= '';
					 	 foreach($data2->{'statuses'} as $data_insert1)
						 {
								if (!$this->filter_tag($data_insert1->text,$filter_text)) continue;
								$picture='';
								$content = $data_insert1->text;
								//validate content hashtag
								if(! $this->search_preg_hash($this->global_tag,$content))
								{
									continue;
								}
								if(isset($data_insert1->entities->media)&& count($data_insert1->entities->media))
								{
								
									$url = $data_insert1->entities->media[0]->media_url;
									$name_file = basename($url);
									$data_ck = explode('.',$name_file);
									$data_ext =end($data_ck);
									$valid_extension = array('jpeg','jpg','png','gif');
									if (in_array($data_ext,$valid_extension))
									{
									
										$picture = $this->grab_picture_twitter($data_insert1->user->id,$url);
											
									}
																		
									if(isset($data_insert1->entities->media[0]->expanded_url))
									{
										$video =$data_insert1->entities->media[0]->expanded_url;
										
										
										
										
									}
								}
								
								if( !preg_match('/\/video\/1$/',$video) && $this->debug)
								{
									continue 1;
								}
								
								//$profile_picture = $this->grab_picture_twitter($data_insert1->user->id,$data_insert1->user->profile_image_url);
								$date = new DateTime($data_insert1->created_at);
								$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
								$timestamp = strtotime($data_insert1->created_at);
								$date_str = $date->format('Y-m-d H:i:s');
								$data = array(		  'description'=>$content,
													  'video'=>$video,
													  'url'=>$video,
													  'userid'=>$data_insert1->user->id,
													  'screen_name'=>$data_insert1->user->screen_name,
													  'name'=>$data_insert1->user->name,
													  //'photo_profile'=>$profile_picture,
													  //'video_preview'=>$picture,
													  'created_on'=>$timestamp,
													  'created'=>$date_str,
													  'via'=>'twitter',
													  'status'=>'draft',
													  'entity_id'=>$data_insert1->id_str,
													  'max_id'=>$_max_1,
													  'since_id'=>$_since_id_1);
								$id_uid_match = $this->match_user_id_sosmed($data_insert1->user->id,'twitter');
						    	if($id_uid_match)
								{
									$data['userid_match'] = $id_uid_match;
								}
								$this->search_content_m->insert_search_content($data);
								
								//update image name
								$id = $this->db->insert_id();
								$new_imagename = $this->change_image($id,$picture);
								$new_video_preview = $this->grab_picture_twitter($data_insert1->user->id,$picture,$new_imagename);
								//user photo
								$new_imagename_photo = $this->change_image($id,$data_insert1->user->profile_image_url);
								$new_user_photo = $this->grab_picture_twitter($data_insert1->user->id,$data_insert1->user->profile_image_url,$new_imagename_photo);
								$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
								//end update image name
						
					 		
						 }
						 
						  //update max and since id in video_status
						 $result_status = $this->video_status_m->get_by(array('via'=>'twitter'));
						 if($result_status)
						 {
							$_since_id_1 = ($_since_id_1 > intval($result_status->since_id))? $_since_id_1:intval($result_status->since_id);
							$_max_1 = ($_max_1  < intval($result_status->max_id))? $_max_1 :intval($result_status->max_id); 
							$this->video_status_m->update_by(array('via'=>'twitter'),array('max_id'=>$_max_1,'since_id'=>$_since_id_1));
								
							 
						 }
						 else {
							 $this->video_status_m->insert(array('max_id'=>$_max_1,'since_id'=>$_since_id_1,'via'=>'twitter'));								
						 	
						 }
						 //end update status video
					}
					
					
				 }
				 //old one condition 2
				 else {
				 		
				 			//get old one
				 			$getfield = '?q='.rawurlencode($this->global_tag).'&max_id='.$max_id.'&mode=videos&include_entities=1&count='.$max_count;
				 			$requestMethod = 'GET';
				 			$result = $this->global_twitter->setGetfield($getfield)
				 		     ->buildOauth($url, $requestMethod)
				 		     ->performRequest();
				 		log_message('error', 'Perform get Twitter By Minimum ID');
				 		$data1 =  json_decode($result);
				 		
				 		 if(isset($data1->{'search_metadata'}->next_results))
				 		 {
				 			$getfield =$data1->{'search_metadata'}->next_results;
				 			$requestMethod = 'GET';
				 			$result = $this->global_twitter->setGetfield($getfield)
				 		     ->buildOauth($url, $requestMethod)
				 		     ->performRequest();
				 			log_message('error', 'Perform get Twitter By Minimum ID In Next Results');
				 			$data2 =  json_decode($result);
				 			
				 		 	if (isset($data2->{'statuses'}))
				 			{
				 				 $_max_1 = $data2->{'search_metadata'}->{'max_id'} ;
				 				 $_since_id_1 = $data2->{'search_metadata'}->{'since_id'} ;
				 				$video = '';
				 			 	 foreach($data2->{'statuses'} as $data_insert1)
				 				 {
				 						if (!$this->filter_tag($data_insert1->text,$filter_text)) continue;
				 						$picture='';
				 						$content = $data_insert1->text;
										//validate content hashtag
										if(! $this->search_preg_hash($this->global_tag,$content))
										{
											continue;
										}
				 						if(isset($data_insert1->entities->media)&& count($data_insert1->entities->media))
				 						{
				 						
				 							$url = $data_insert1->entities->media[0]->media_url;
				 							$name_file = basename($url);
				 							$data_ck = explode('.',$name_file);
				 							$data_ext =end($data_ck);
				 							$valid_extension = array('jpeg','jpg','png','gif');
				 							if (in_array($data_ext,$valid_extension))
				 							{
				 							
				 								$picture = $this->grab_picture_twitter($data_insert1->user->id,$url);
				 									
				 							}
				 																		
											if(isset($data_insert1->entities->media[0]->expanded_url))
											{
												$video =$data_insert1->entities->media[0]->expanded_url;
												
												
												
												
											}
											
				 							
				 						}
										
				 						if(!preg_match('/\/video\/1$/',$video) && $this->debug)
										{
											
											continue 1;
										}
				 						
				 						$date = new DateTime($data_insert1->created_at);
				 						$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
				 						$timestamp = strtotime($data_insert1->created_at);
				 						$date_str = $date->format('Y-m-d H:i:s');
				 						$data = array(		'description'=>$content,
													  'video'=>$video,
													  'url'=>$video,
													  'userid'=>$data_insert1->user->id,
													  'screen_name'=>$data_insert1->user->screen_name,
													  'name'=>$data_insert1->user->name,
													  'photo_profile'=>$profile_picture,
													  //'video_preview'=>$picture,
													  'created_on'=>$timestamp,
													  'created'=>$date_str,
													  'via'=>'twitter',
													  'status'=>'draft',
													  'entity_id'=>$data_insert1->id_str,
													  'max_id'=>$_max_1,
													  'since_id'=>$_since_id_1);
										$id_uid_match = $this->match_user_id_sosmed($data_insert1->user->id,'twitter');
								    	if($id_uid_match)
										{
											$data['userid_match'] = $id_uid_match;
										}
				 						$this->search_content_m->insert_search_content($data);
										//update image name
										$id = $this->db->insert_id();
										$new_imagename = $this->change_image($id,$picture);
										$new_video_preview = $this->grab_picture_twitter($data_insert1->user->id,$picture,$new_imagename);
										//user photo
										$new_imagename_photo = $this->change_image($id,$data_insert1->user->profile_image_url);
										$new_user_photo = $this->grab_picture_twitter($data_insert1->user->id,$data_insert1->user->profile_image_url,$new_imagename_photo);
										$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
										//end update image name
				 			 		
				 				 }
								 
								 //update max and since id in video_status
								 $result_status = $this->video_status_m->get_by(array('via'=>'twitter'));
								 if($result_status)
								 {
									 $_since_id_1 = ($_since_id_1 > intval($result_status->since_id))? $_since_id_1:intval($result_status->since_id);
									 $_max_1 = ($_max_1  < intval($result_status->max_id))? $_max_1 :intval($result_status->max_id);
									 $this->video_status_m->update_by(array('via'=>'twitter'),array('max_id'=>$_max_1,'since_id'=>$_since_id_1));
								 }
								 else {
								 	$this->video_status_m->insert(array('max_id'=>$_max_1,'since_id'=>$_since_id_1,'via'=>'twitter'));
								 }
								 //end update status video
				 			}
				 			
				 			
				 		 }
				 }

				//end get old one
				
			}
			else {
					
					$getfield = '?q='.rawurlencode($this->global_tag).'&mode=videos&include_entities=1&count='.$max_count;
					$requestMethod = 'GET';
					$result = $this->global_twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();
					log_message('error', 'Perform get Twitter For The First Time');
				$data =  json_decode($result);
			 if(isset($data->{'statuses'}))
			 {
			 	
			 	$_max = $data->{'search_metadata'}->{'max_id'} ;
				
			 	 foreach($data->{'statuses'} as $data_insert)
				 {
				 
				 	if (!$this->filter_tag($data_insert->text,$filter_text)) continue;
						$picture='';
						$content = $data_insert->text;
						//validate content hashtag
						if(! $this->search_preg_hash($this->global_tag,$content))
						{
							continue;
						}
						$video = '';
						if(isset($data_insert->entities->media)&& count($data_insert->entities->media))
						{
						
							$url = $data_insert->entities->media[0]->media_url;
							$name_file = basename($url);
							$data_ck = explode('.',$name_file);
							$data_ext =end($data_ck);
							$valid_extension = array('jpeg','jpg','png','gif');
							if (in_array($data_ext,$valid_extension))
							{
							
								$picture = $url;
									
							}
							
							if(isset($data_insert->entities->media[0]->expanded_url))
							{
								$video =$data_insert->entities->media[0]->expanded_url;
								
								
								
								
							}
							
							
						
								
							//var_dump($data_insert->entities);
						}
						
						
						if(!preg_match('/\/video\/1$/',$video) && $this->debug)
						{
							continue 1;
						}
						
						//$profile_picture = $this->grab_picture_twitter($data_insert->user->id,$data_insert->user->profile_image_url);
						$date = new DateTime($data_insert->created_at);
						$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
						$timestamp = strtotime($data_insert->created_at);
						$date_str = $date->format('Y-m-d H:i:s');
						$data = array(		  'description'=>$content,
											  'video'=>$video,
											  'url'=>$video,
											  'userid'=>$data_insert->user->id,
											  'screen_name'=>$data_insert->user->screen_name,											  
											  'name'=>$data_insert->user->name,
											  //'photo_profile'=>$profile_picture,
											  //'video_preview'=>$picture,
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via'=>'twitter',
											  'status'=>'draft',
											  'entity_id'=>$data_insert->id_str,
											  'max_id'=>$_max,
											  'since_id'=>$_max);
						$id_uid_match = $this->match_user_id_sosmed($data_insert->user->id,'twitter');
				    	if($id_uid_match)
						{
							$data['userid_match'] = $id_uid_match;
						}					  
						$this->search_content_m->insert_search_content($data);
						//update image name
						$id = $this->db->insert_id();
						$new_imagename = $this->change_image($id,$picture);
						$new_video_preview = $this->grab_picture_twitter($data_insert->user->id,$picture,$new_imagename);
						//user photo
						$new_imagename_photo = $this->change_image($id,$data_insert->user->profile_image_url);
						$new_user_photo = $this->grab_picture_twitter($data_insert->user->id,$data_insert->user->profile_image_url,$new_imagename_photo);
						$this->search_content_m->update_by(array('id'=>$id),array('video_preview'=>$new_video_preview,'photo_profile'=>$new_user_photo));
						//end update image name
						
					
				 }
				 
				 //update max and since id in video_status
				 $result_status = $this->video_status_m->get_by(array('via'=>'twitter'));
				 if($result_status)
				 {
					 $this->video_status_m->update_by(array('via'=>'twitter'),array('max_id'=>$_max,'since_id'=>$_max));
				 }
				 else {
				 	$this->video_status_m->insert(array('max_id'=>$_max,'since_id'=>$_max,'via'=>'twitter'));
				 }
				 //end update status video
			 }
				
			}
				//exit();	
		//}
		//exit();	
		
	}

	private function grab_picture_twitter($id,$image_url,$new_image_name='')
	{
		$cli_path = $this->cli_path;
		$this->load->config('bubble/config');
		$this->load->helper('file');
		$this->load->helper('directory');
		$map = directory_map($cli_path.$this->config->item('default_path').$id.'/', 1);
		$new_image_url = '';
		//change image_url basename to drafted
		if($new_image_name)
		{
			$new_image_url = $new_image_name;
		}
		else {
			$new_image_url =basename($image_url);
		}
		
		if( !$map || count($map)==0)
		{
			
			if(! is_dir( $cli_path.$this->config->item('default_path').$id))
			{
				$result = mkdir(  $cli_path.$this->config->item('default_path').$id,0755,true);
				
			}

			curl_download_image($image_url, $cli_path.$this->config->item('default_path').$id.'/'.$new_image_url);
			
			return $this->config->item('default_path').$id.'/'.$new_image_url;
			
			//->db->insert('tw_profile_pict',array('tw_id'=>$id,'image_url'=>$this->config->item('default_path').$id.'/'.basename($image_url)));
			
		}
		else if( count($map)  &&   (isset($map[0]) && in_array(basename($image_url),$map)))
		{
			
			/*if(is_file($cli_path.$this->config->item('default_path').$id.'/'.basename($image_url)))
			{
				unlink( $cli_path.$this->config->item('default_path').$id.'/'.basename($image_url));
			}
			curl_download_image($image_url, $cli_path.$this->config->item('default_path').$id.'/'.basename($image_url));*/
			
			return $this->config->item('default_path').$id.'/'.$new_image_url ;
			
		}
		else
		{
			if(! is_dir( $cli_path.$this->config->item('default_path').$id))
			{
				$result = mkdir(  $cli_path.$this->config->item('default_path').$id,0755,true);
				
			}

			curl_download_image($image_url, $cli_path.$this->config->item('default_path').$id.'/'.$new_image_url );
			
			return $this->config->item('default_path').$id.'/'.$new_image_url ;
		}
		
		
	}
	
	
	/* YOUTUBE SEARCH HASHTAG */
	private function search_youtube()
	{
		$date = date("Y-m-d\TH:i:s.uP",now());
		 	//maximum
		
			 $since_id = NULL;
		     $since_id=$this->db->select('MAX(since_id) as since_id')->where(array('via_yt'=>'1'))->get('bubble')->row('since_id');

			 //minimum
			 $max_id = NULL;
			 $max_id = $this->db->select('MIN(max_id) as max_id')->where(array('via_yt'=>'1'))->get('bubble')->row('max_id');
			 
			if($since_id != NULL && $max_id != NULL)
			{   $date_min  = date("Y-m-d\TH:i:s.uP",$max_id); 
				$custom_p = array('publishedAfter'=>$date_min,'publishedBefore'=>$date);
				$videoList = $this->youtube->searchVideosWithParams($this->global_tag,50,$custom_p);
				
				if($videoList)
				{
					$videoListTokenNext =  false;
					$max_limit = 10;
					$inc = 0;
					do
					{
						if($videoListTokenNext)
						{
							$custom_p = array('publishedBefore'=>$date,'publishedAfter'=>$date_min,'pageToken'=>$videoListTokenNext);
							$videoList = $this->youtube->searchVideosWithParams($this->global_tag,50,$custom_p);
						}
						
						if(isset($videoList->items))
						{
							foreach($videoList->items as $itm)
							{
								$data_id = $this->hashids->decrypt($itm->id->videoId);
								$create_time = $itm->snippet->publishedAt;
								$data_time = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$create_time);
								$dest_tz = new DateTimeZone('Asia/Jakarta');
								$data_time->setTimeZone($dest_tz);
								$date_str = $data_time->format('Y-m-d H:i:s');
								$timestamp =strtotime($date_str);
								$obj_max = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$date);
								
								$max_id = $since_id = strtotime($obj_max->format('Y-m-d H:i:s'));
								
								$data = array('content'=>(($this->search_preg_hash($itm->snippet->title))? $itm->snippet->title: $itm->snippet->description),
											  'video'=>'https://youtube.com/watch?v='.$itm->id->videoId,
											  'author_id'=>'0',
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via_yt'=>'1',
											  'status'=>'live',
											  'entity_id'=>$data_id,
											  'max_id'=>$max_id,
											  'since_id'=>$since_id);
								$this->search_content_m->insert_search_content($data);
							}
						}
						
						if(isset($videoList->info->nextPageToken) && !empty($videoList->info->nextPageToken))
						{
							$videoListTokenNext = $videoList->info->nextPageToken;
						}
						else {
							$videoListTokenNext = false;
						}
						
						$inc++;
						if($inc == $max_limit)
						{
							$videoListTokenNext =false;
						}
						
					}while($videoListTokenNext);
				}
			}
			else {
				$custom_p = array('publishedAfter'=>$date);
				$videoList = $this->youtube->searchVideosWithParams($this->global_tag,50,$custom_p);
			
				if(!$videoList)
				{
					$custom_p = array('publishedBefore'=>$date);
					$videoList = $this->youtube->searchVideosWithParams($this->global_tag,50,$custom_p);
					if($videoList)
					{
						foreach($videoList->items as $itm)
						{
							$data_id = $this->hashids->decrypt($itm->id->videoId);
							$create_time = $itm->snippet->publishedAt;
							$data_time = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$create_time);
							$dest_tz = new DateTimeZone('Asia/Jakarta');
							$data_time->setTimeZone($dest_tz);
							$date_str = $data_time->format('Y-m-d H:i:s');
							$timestamp =strtotime($date_str);
							$obj_max = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$date);
							
							$max_id = $since_id = strtotime($obj_max->format('Y-m-d H:i:s'));
							
							
							$content = (($this->search_preg_hash($itm->snippet->title))? $itm->snippet->title: $itm->snippet->description);
							if(!$this->search_preg_hash($content)){
								continue;
							}
							$data = array('content'=>$content,
										  'video'=>'https://youtube.com/watch?v='.$itm->id->videoId,
										  'author_id'=>'0',
										  'created_on'=>$timestamp,
										  'created'=>$date_str,
										  'via_yt'=>'1',
										  'status'=>'live',
										  'entity_id'=>$data_id,
										  'max_id'=>$max_id,
										  'since_id'=>$since_id);
							$this->search_content_m->insert_search_content($data);
							
						}
					}
				}
				else {
					if($videoList)
					{
						$videoListTokenNext =  false;
						$max_limit = 10;
						$inc = 0;
						do
						{
							if($videoListTokenNext)
							{
								$custom_p = array('publishedAfter'=>$date,'pageToken'=>$videoListTokenNext);
								$videoList = $this->youtube->searchVideosWithParams($this->global_tag,50,$custom_p);
							}
							
							if(isset($videoList->items))
							{
								foreach($videoList->items as $itm)
								{
									$data_id = $this->hashids->decrypt($itm->id->videoId);
									$create_time = $itm->snippet->publishedAt;
									$data_time = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$create_time);
									$dest_tz = new DateTimeZone('Asia/Jakarta');
									$data_time->setTimeZone($dest_tz);
									$date_str = $data_time->format('Y-m-d H:i:s');
									$timestamp =strtotime($date_str);
									$obj_max = DateTime::createFromFormat("Y-m-d\TH:i:s.uP",$date);
									
									$max_id = $since_id = strtotime($obj_max->format('Y-m-d H:i:s'));
									
									$content = (($this->search_preg_hash($itm->snippet->title))? $itm->snippet->title: $itm->snippet->description);
									if(!$this->search_preg_hash($content)){
										continue;
									}
									$data = array('content'=>$content,
												  'video'=>'https://youtube.com/watch?v='.$itm->id->videoId,
												  'author_id'=>'0',
												  'created_on'=>$timestamp,
												  'created'=>$date_str,
												  'via_yt'=>'1',
												  'status'=>'live',
												  'entity_id'=>$data_id,
												  'max_id'=>$max_id,
												  'since_id'=>$since_id);
									$this->search_content_m->insert_search_content($data);
								}
							}
							
							if(isset($videoList->info->nextPageToken) && !empty($videoList->info->nextPageToken))
							{
								$videoListTokenNext = $videoList->info->nextPageToken;
							}
							else {
								$videoListTokenNext = false;
							}
							
							$inc++;
							if($inc == $max_limit)
							{
								$videoListTokenNext =false;
							}
							
						}while($videoListTokenNext);
					}
				}
				//insert youtube
			}
	}
	
	private function stripEmojis($text){
	    $clean_text = "";

	    // Match Emoticons
	    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	    $clean_text = preg_replace($regexEmoticons, '', $text);

	    // Match Miscellaneous Symbols and Pictographs
	    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	    $clean_text = preg_replace($regexSymbols, '', $clean_text);

	    // Match Transport And Map Symbols
	    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	    $clean_text = preg_replace($regexTransport, '', $clean_text);

	    return $clean_text;
	}
	
	
	/* FACEBOOK SEARCH HASHTAG */
	private function search_facebook_hashtag()
	{
		$max_limit = 100;
			//maximum
			 $since_id = NULL;
		     $since_id=$this->db->select('MAX(since_id) as since_id')->where(array('via_fb'=>'1'))->get('bubble')->row('since_id');

			 //minimum
			 $max_id = NULL;
			 $max_id = $this->db->select('MIN(max_id) as max_id')->where(array('via_fb'=>'1'))->get('bubble')->row('max_id');
			 
			if($since_id != NULL && $max_id != NULL)
			{
				$now_time = time();
				$data_fb = $this->facebook->api('/search?q='.rawurlencode($this->global_tag).'&type=post&limit='.$max_limit.'&until='.$since_id);
			//var_dump($data_fb);
				if(count($data_fb['data']))
				{
					$fbURLNext =  false;
					$max_limit = 10;
					$inc = 0;
					$since_id=$max_id = time();
					do
					{
						if($fbURLNext)
						{
							$data_url = parse_url($fbURLNext);
							parse_str($data_url['query'], $output);
							$max_id=$since_id = $output['since'];
							$data_fb = $this->facebook->api('/search?'.$data_url['query']);
						}
						
						if(count($data_fb['data']))
						{
							foreach($data_fb['data'] as $itm)
							{
								$data_id = str_replace('_', '',$itm['id'] );
								$content ='';
								$picture ='';
								
								
								switch($itm['type'])
								{
									case 'link' :  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}
									
													break;
									case 'status':  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}	
													break;
									case 'photo' : 
													 if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['object_id']))
													{
														$picture = 'https://graph.facebook.com/'.$itm['object_id'].'/picture';
													}
									
													
													break;
									
								}
								if(empty($content)) continue;
								$date_source = strtotime($itm['created_time']);
								$dtime = new DateTime();
								$dtime->setTimestamp($date_source);
								$localtz = new DateTimeZone("Asia/Jakarta"); 
								$dtime->setTimeZone($localtz);
								$date_str = $dtime->format('Y-m-d H:i:s');
								$timestamp =$date_source;
								
								$data = array('content'=>$content,
											  'video'=>'',
											   'username'=>$itm['from']['id'],
													  'name'=>$itm['from']['name'],
													  'profile_pic'=> 'https://graph.facebook.com/'.$itm['from']['id'].'/picture?type=square',
											  'photo'=>$picture,
											  'author_id'=>'0',
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via_fb'=>'1',
											  'status'=>'live',
											  'entity_id'=>$data_id,
											  'max_id'=>$max_id,
											  'since_id'=>$since_id);
								$this->search_content_m->insert_search_content($data);
							}
						}
						
						if(isset($data_fb['paging']['previous']) && !empty($data_fb['paging']['previous']))
						{
							$fbURLNext = $data_fb['paging']['previous'];
						}
						else {
							$fbURLNext = false;
						}
						
						$inc++;
						if($inc == $max_limit)
						{
							$fbURLNext =false;
						}
						
					}while($fbURLNext);
				}
			}
			else {
				$data_fb = $this->facebook->api('/search?q='.rawurlencode($this->global_tag).'&type=post&limit='.$max_limit);
				
				//insert youtube
				if(count($data_fb['data']))
				{
					$fbURLNext =  false;
					$max_limit = 10;
					$inc = 0;
					$since_id=$max_id = time();
					do
					{
						if($fbURLNext)
						{
							$data_url = parse_url($fbURLNext);
							parse_str($data_url['query'], $output);
							$max_id=$since_id = $output['until'];
							$data_fb = $this->facebook->api('/search?'.$data_url['query']);
						}
						
						if(count($data_fb['data']))
						{
							foreach($data_fb['data'] as $itm)
							{
								$data_id = str_replace('_', '',$itm['id'] );
								$content ='';
								$picture ='';
								
								switch($itm['type'])
								{
									case 'link' :  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}
									
													break;
									case 'status':  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}	
													break;
									case 'photo' : 
													 if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['object_id']))
													{
														$picture = 'https://graph.facebook.com/'.$itm['object_id'].'/picture';
													}
									
													
													break;
									
								}
								if(empty($content)) continue;
								$date_source = strtotime($itm['created_time']);
								$dtime = new DateTime();
								$dtime->setTimestamp($date_source);
								$localtz = new DateTimeZone("Asia/Jakarta"); 
								$dtime->setTimeZone($localtz);
								$date_str = $dtime->format('Y-m-d H:i:s');
								$timestamp =$date_source;
								
								$data = array('content'=>$content,
											  'video'=>'',
											  'username'=>$itm['from']['id'],
													  'name'=>$itm['from']['name'],
													  'profile_pic'=> 'https://graph.facebook.com/'.$itm['from']['id'].'/picture?type=square',
											  'photo'=>$picture,
											  'author_id'=>'0',
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via_fb'=>'1',
											  'status'=>'live',
											  'entity_id'=>$data_id,
											  'max_id'=>$max_id,
											  'since_id'=>$since_id);
								$this->search_content_m->insert_search_content($data);
							}
						}
						
						if(isset($data_fb['paging']['next']) && !empty($data_fb['paging']['next']))
						{
							$fbURLNext = $data_fb['paging']['next'];
						}
						else {
							$fbURLNext = false;
						}
						
						$inc++;
						if($inc == $max_limit)
						{
							$fbURLNext =false;
						}
						
					}while($fbURLNext);
				}
			}
		
	}
	
	//* search hashtag
	private function search_preg_hash($criteria,$string)
	{
		return preg_match("/".$criteria."/i", $string);
	}
	
	//change image name
	private function change_image($id,$image_name)
	{
		$thephotoURLName =  explode('?', basename($image_name));
		$image_name    = array_shift($thephotoURLName);
		$expl = explode('.',$image_name);
		$ext =end($expl);
		unset($expl[count($expl)-1]);		
		$template = implode('.',$expl).'_'.$id.'_drafted.'.$ext;
		return $template;		
		
	}
	//END PRIVATE FUNCTION //


	//---- FUNCTION FOR CRAWLING FB CUSTOM TAG
	/* FACEBOOK SEARCH HASHTAG */
	/*function facebook_hashtag_custom($ch='')
	{
		//var_dump($custom_hashtag); die();
		//$this->global_tag
		if($ch){
			$custom_hashtag = '#'.$ch;
		}else{
			$custom_hashtag = '#sampaikan';
		}
		
		$max_limit = 100;
			//maximum
			 $since_id = NULL;
		     $since_id=$this->db->select('MAX(since_id) as since_id')->where(array('via_fb'=>'1'))->get('bubble')->row('since_id');

			 //minimum
			 $max_id = NULL;
			 $max_id = $this->db->select('MIN(max_id) as max_id')->where(array('via_fb'=>'1'))->get('bubble')->row('max_id');
			 
			if($since_id != NULL && $max_id != NULL)
			{
				$now_time = time();
				$data_fb = $this->facebook->api('/search?q='.rawurlencode($custom_hashtag).'&type=post&limit='.$max_limit.'&until='.$since_id);
				var_dump($data_fb); die();
				if(count($data_fb['data']))
				{
					$fbURLNext =  false;
					$max_limit = 10;
					$inc = 0;
					$since_id=$max_id = time();
					do
					{
						if($fbURLNext)
						{
							$data_url = parse_url($fbURLNext);
							parse_str($data_url['query'], $output);
							$max_id=$since_id = $output['since'];
							$data_fb = $this->facebook->api('/search?'.$data_url['query']);
						}
						
						if(count($data_fb['data']))
						{
							foreach($data_fb['data'] as $itm)
							{
								$data_id = str_replace('_', '',$itm['id'] );
								$content ='';
								$picture ='';
								
								
								switch($itm['type'])
								{
									case 'link' :  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}
									
													break;
									case 'status':  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}	
													break;
									case 'photo' : 
													 if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['object_id']))
													{
														$picture = 'https://graph.facebook.com/'.$itm['object_id'].'/picture';
													}
									
													
													break;
									
								}
								if(empty($content)) continue;
								$date_source = strtotime($itm['created_time']);
								$dtime = new DateTime();
								$dtime->setTimestamp($date_source);
								$localtz = new DateTimeZone("Asia/Jakarta"); 
								$dtime->setTimeZone($localtz);
								$date_str = $dtime->format('Y-m-d H:i:s');
								$timestamp =$date_source;
								
								$data = array('content'=>$content,
											  'video'=>'',
											   'username'=>$itm['from']['id'],
													  'name'=>$itm['from']['name'],
													  'profile_pic'=> 'https://graph.facebook.com/'.$itm['from']['id'].'/picture?type=square',
											  'photo'=>$picture,
											  'author_id'=>'0',
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via_fb'=>'1',
											  'status'=>'live',
											  'entity_id'=>$data_id,
											  'max_id'=>$max_id,
											  'since_id'=>$since_id);
								$this->search_content_m->insert_search_content($data);
							}
						}
						
						if(isset($data_fb['paging']['previous']) && !empty($data_fb['paging']['previous']))
						{
							$fbURLNext = $data_fb['paging']['previous'];
						}
						else {
							$fbURLNext = false;
						}
						
						$inc++;
						if($inc == $max_limit)
						{
							$fbURLNext =false;
						}
						
					}while($fbURLNext);
				}
			}
			else {
				$data_fb = $this->facebook->api('/search?q='.rawurlencode($custom_hashtag).'&type=post&limit='.$max_limit);
				echo 'kosong<br /><br />';
				echo $max_limit.'<br /><br />';
				 
				var_dump($data_fb); die();
				//insert youtube
				if(count($data_fb['data']))
				{
					$fbURLNext =  false;
					$max_limit = 10;
					$inc = 0;
					$since_id=$max_id = time();
					
					do
					{
						if($fbURLNext)
						{
							$data_url = parse_url($fbURLNext);
							parse_str($data_url['query'], $output);
							$max_id=$since_id = $output['until'];
							$data_fb = $this->facebook->api('/search?'.$data_url['query']);
						}
						
						if(count($data_fb['data']))
						{
							foreach($data_fb['data'] as $itm)
							{
								$data_id = str_replace('_', '',$itm['id'] );
								$content ='';
								$picture ='';
								
								switch($itm['type'])
								{
									case 'link' :  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}
									
													break;
									case 'status':  if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['picture']))
													{
														$picture = $itm['picture'];
													}	
													break;
									case 'photo' : 
													 if(isset($itm['message']) && $this->search_preg_hash($itm['message']))
													{
														$content = $itm['message'];
													}
													else if(isset($itm['caption']) && $this->search_preg_hash($itm['caption']))
													{
														$content = $itm['caption'];
													}
													else if(isset($itm['name']) && $this->search_preg_hash($itm['name'])){
														$content = $itm['name'];
													}
													
													if(isset($itm['object_id']))
													{
														$picture = 'https://graph.facebook.com/'.$itm['object_id'].'/picture';
													}
									
													
													break;
									
								}
								if(empty($content)) continue;
								$date_source = strtotime($itm['created_time']);
								$dtime = new DateTime();
								$dtime->setTimestamp($date_source);
								$localtz = new DateTimeZone("Asia/Jakarta"); 
								$dtime->setTimeZone($localtz);
								$date_str = $dtime->format('Y-m-d H:i:s');
								$timestamp =$date_source;
								
								$data = array('content'=>$content,
											  'video'=>'',
											  'username'=>$itm['from']['id'],
											  'name'=>$itm['from']['name'],
											  'profile_pic'=> 'https://graph.facebook.com/'.$itm['from']['id'].'/picture?type=square',
											  'photo'=>$picture,
											  'author_id'=>'0',
											  'created_on'=>$timestamp,
											  'created'=>$date_str,
											  'via_fb'=>'1',
											  'status'=>'live',
											  'entity_id'=>$data_id,
											  'max_id'=>$max_id,
											  'since_id'=>$since_id);
								$this->search_content_m->insert_search_content($data);
							}
						}
						
						if(isset($data_fb['paging']['next']) && !empty($data_fb['paging']['next']))
						{
							$fbURLNext = $data_fb['paging']['next'];
						}
						else {
							$fbURLNext = false;
						}
						
						$inc++;
						if($inc == $max_limit)
						{
							$fbURLNext =false;
						}
						
					}while($fbURLNext);
				}
			}
		
	}*/
	
	private function check_facebook()
	{
		$this->db->where('userid_match IS NULL');
		$data_facebook  =$this->search_content_m->get_many_by(array('via'=>'facebook'));
		if($data_facebook)
		{
			foreach($data_facebook as $data_fb)
			{
				$id_match = $this->match_user_id_sosmed($data_fb->userid,'facebook');
				if($id_match)
				{
					$this->search_content_m->update_by(array('id'=>$data_fb->id),array('userid_match'=>$id_match));
				}
			}
		}
	}
	
	private function match_user_id_sosmed($eid=0,$via='')
	{
		
		$this->load->model('users/profile_m');
		$this->load->helper('array');
		switch($via)
		{
			case 'twitter'		:
							
						  	if(!isset($this->twitter_data))
							{
								$data_uid = $this->profile_m->get_many_by('tw_id IS NOT NULL');
								
								$this->twitter_data = (($data_uid)? array_for_select($data_uid,'tw_id','user_id') : array() );
							}
							
							if (array_key_exists($eid, $this->twitter_data))
							{
								return $this->twitter_data[$eid];
							}
							
						
			
							break;
							
			case 'facebook'		: 
							if(!isset($this->facebook_data))
							{
								$data_uid = $this->profile_m->get_many_by('fb_id IS NOT NULL');
								
								$this->facebook_data = (($data_uid)?array_for_select($data_uid,'fb_id','user_id')  : array() );
							}
							
							if (array_key_exists($eid, $this->facebook_data))
							{
								return $this->facebook_data[$eid];
							}				
			
							break;
							
			case 'instagram'	:
							if(!isset($this->instagram_data))
							{
								$data_uid = $this->profile_m->get_many_by('insta_id IS NOT NULL');
								$this->instagram_data = (($data_uid)?array_for_select($data_uid,'insta_id','user_id')  : array() );
							}
							
							if (array_key_exists($eid, $this->instagram_data))
							{
								return $this->instagram_data[$eid];
							}		
							break;
			
			case 'vine'	:
							if(!isset($this->vine_data))
							{
								$data_uid = $this->profile_m->get_many_by('vine_id IS NOT NULL');
								
								$this->vine_data = (($data_uid)? array_for_select($data_uid,'vine_id','user_id')  : array() );
							}
							
							if (array_key_exists($eid, $this->vine_data))
							{
								return $this->vine_data[$eid];
							}		
							break;
		}
		
		return false;	
	}
	
		
}