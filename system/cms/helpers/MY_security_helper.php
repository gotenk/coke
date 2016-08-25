<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MaxCMS Array Helpers
 * 
 * This overrides Codeigniter's helpers/array_helper.php file.
 *
 * @author      MaxCMS Dev Team
 * @copyright   Copyright (c) 2012, MaxCMS LLC
 * @package		MaxCMS\Core\Helpers
 */


if ( ! function_exists('xss_clean_without_style'))
{
	/**
	 * Merge an array or an object into another object
	 *
	 * @param object $object The object to act as host for the merge.
	 * @param object|array $array The object or the array to merge.
	 */
	function xss_clean_without_style($str, $is_image=false)
	{
		$CI =& get_instance();
		return $CI->security->xss_clean($str, $is_image,true);
	}

}

