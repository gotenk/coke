<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_video_author') ) {
	function get_video_author($video_id = '', $yt_user_id ='') {
		$xml = simplexml_load_file(sprintf('https://gdata.youtube.com/feeds/api/videos/'.$video_id.'?v=2&alt=rss&orderby=published&prettyprint=true'));
		//echo $xml->author;
		$ret['author'] = $xml->author;
		$ret['propic']  = 'https://i2.ytimg.com/i/'+$yt_user_id+'/1.jpg';
		return $ret;
	}
}