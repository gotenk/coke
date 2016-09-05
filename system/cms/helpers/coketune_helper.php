<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_valid_date'))
{
	function is_valid_date($yy, $mm, $dd){
		if ( checkdate($mm, $dd, $yy) ){
			return true;			
		}
		return false;
	}
}

if (!function_exists('is_thirteen_or_more'))
{
	function is_thirteen_or_more($yy, $mm, $dd){
		date_default_timezone_set('Asia/Jakarta');
		$now = new DateTime();
		if ( checkdate($mm, $dd, $yy) ){
			$dob = DateTime::createFromFormat('Y-n-j', "{$yy}-{$mm}-{$dd}");					
			if($dob){
				$diff = $now->diff($dob);
				if($diff->y >= 13){
					return true;
				}			
			}
		}		
		
		return false;
	}
}


if (!function_exists('dob_year'))
{
	function dob_year(){
		date_default_timezone_set('Asia/Jakarta');
		$now = new DateTime();
		$result = array();
		#$thershold = (int) $now->format('Y') - 13;
		$thershold = (int) $now->format('Y') - 40;

		for($i = $thershold; $i<=$now->format('Y'); $i++){			
			$result[$i] = $i;

		}	
		return $result;		
	}
}


if (!function_exists('dob_month'))
{
	function dob_month(){			
		$result = array();
		for($i = 1; $i<=12; $i++){			
			$result[$i] = $i;

		}	
		return $result;		
	}
}

if (!function_exists('dob_day'))
{
	function dob_day(){			
		$result = array();
		for($i = 1; $i<=31; $i++){			
			$result[$i] = $i;

		}	
		return $result;		
	}
}