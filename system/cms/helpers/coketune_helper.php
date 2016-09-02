<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_valid_date'))
{
	function is_valid_date($date = null){
		date_default_timezone_set('Asia/Jakarta');
		return DateTime::createFromFormat('Y-m-d', $date);
	}
}

if (!function_exists('is_thirteen_or_more'))
{
	function is_thirteen_or_more($date = null){
		date_default_timezone_set('Asia/Jakarta');
		$now = new DateTime();
		
		if($birth = is_valid_date($date)){
			$diff = $now->diff($birth);
			if($diff->y >= 13){
				return true;
			}			
		}
		return false;
	}
}