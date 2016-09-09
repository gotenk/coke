<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_valid_date'))
{
	function is_valid_date($yy, $mm, $dd){
		if ( checkdate( ((int) $mm), ((int) $dd), ((int) $yy) ) ){
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

if (!function_exists('profile_date_format'))
{
	function profile_date_format($strdatetime){		
		$datetime = new DateTime($strdatetime);		
		return $datetime->format('j M');		
	}
}

if (!function_exists('profile_gender_format'))
{
	function profile_gender_format($str){
		$data['m'] = 'Male';
		$data['f'] = 'Female';		

		return isset($data[$str]) ? $data[$str] : $str;
	}
}

if (!function_exists('profile_get_umur'))
{
	function profile_get_umur($str){		
		$now = new DateTime();
		$dob = DateTime::createFromFormat('Y-n-j', $str);
		if($dob){			
			$diff = $now->diff($dob);
			return $diff->y;
		}
	}
}


if(!function_exists('confidential'))
{
	function confidential($no_trans){
        $string = '340'.$no_trans.'#37F0PJ0T';
        $hasil = dechex(crc32($string));
        $length = strlen($hasil);

        if ($length < 8) {
            $kurang = 8 - $length;
            $tambahan = '';

            for ($i = 0; $i < $kurang; $i++) {
                $tambahan .= '0';
            }

            $hasil = $tambahan.$hasil;
        }

        return strtoupper($hasil);
    }
}