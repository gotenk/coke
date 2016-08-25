<?php

require 'curl/curl.php';

class Vine {

	private static $base_url = "https://api.vineapp.com";
	private static $referer = "api.vineapp.com";
	private static $vine_session = null;
	private static $vine_id = null;
	private static $data_vine = null;
	public function __construct(){
		
	}
	
	public static function login($username, $password) {
		$success = false;
		$url = self::$base_url . "/users/authenticate";
		$curl = new Curl;
		$response = json_decode($curl->post($url, array('username'=>$username, 'password'=>$password)));
		self::$data_vine = $response;
		if(isset($response->success) and $response->success) {
			self::$vine_session = $response->data->key;
			self::$vine_id = $response->data->key;
			$success = true;
		}
		return $success;	
	}
	
	public function get_vine_session()
	{
		return self::$data_vine;
	}

	public static function get_tag($tag,$page_id=false,$size=100) {
		$encoded_tag = urlencode($tag);
		$url = self::$base_url . "/timelines/tags/$encoded_tag"."?size=".$size.(($page_id)? '&page='.$page_id:"" );
		$payload = null;
		$curl = new Curl;
		if(self::$vine_session) {
			$curl->headers['vine-session-id'] = self::$vine_session;
		}
		$response = json_decode($curl->get($url));
		if(isset($response->success) and $response->success) {
			$payload = $response->data;
		}
		return $payload;
	}
	
	public static function get_popular($tag) {
		$encoded_tag = urlencode($tag);
		$url = self::$base_url . "/timelines/tags/$encoded_tag";
		$payload = null;
		$curl = new Curl;
		if(self::$vine_session) {
			$curl->headers['vine-session-id'] = self::$vine_session;
		}
		$response = json_decode($curl->get($url));
		if(isset($response->success) and $response->success) {
			$payload = $response->data->records;
		}
		
		return $payload;
	}
	
	public static function  get_user($uid) {
		
	}
	
	public static function get_user_timeline($uid)
	{
		
	}
	
	public static function get_single_post($uid)
	{
		
	}
	
	public static function get_single_vine_post($postid)
	{
		
	}
	
	public static function get_notification($uid)
	{
		
	}
	
	/*not implemented */
	/* must use session */
	public static function like_post()
	{
		
	}
	
	public static function follower()
	{
		
	}

}

?>
