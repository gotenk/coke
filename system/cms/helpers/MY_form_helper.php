<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MaxCMS Form Helpers
 * 
 * This overrides Codeigniter's helpers/array_helper.php file.
 *
 * @author      MaxCMS Dev Team
 * @copyright   Copyright (c) 2012, MaxCMS LLC
 * @package		MaxCMS\Core\Helpers
 */


 if ( ! function_exists('cmc_form_open'))
{
	/**
	 * Form Declaration
	 *
	 * Creates the opening portion of the form.
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function cmc_form_open($id_form ='', $action = '', $attributes = '', $hidden = array())
	{
		$CI =& get_instance();

		if ($attributes === '')
		{
			$attributes = 'method="post"';
		}

		// If an action is not a full URL then turn it into one
		if ($action && strpos($action, '://') === FALSE)
		{
			$action = $CI->config->site_url($action);
		}
		elseif ( ! $action)
		{
			// If no action is provided then set to the current url
			$action = $CI->config->site_url($CI->uri->uri_string());
		}

		$form = '<form action="'.$action.'"'._attributes_to_string($attributes, TRUE).">\n";
	
		// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
		if ($CI->config->item('csrf_multi_form') === TRUE && ! (strpos($action, $CI->config->base_url()) === FALSE OR strpos($form, 'method="get"')))
		{
			
			
			if(!isset($_SESSION['form_ids']))
			{
				$data_form_id=$CI->security->generate_muliple_csrf_token();
				$hidden['form_id'] =$data_form_id['form_id'];
				$hidden[$data_form_id['form_id']] = $data_form_id['uniqid'];
				
				$_SESSION['form_ids'] = serialize(array($id_form=>$data_form_id['form_id']));
			}
			else {
				$data = unserialize($_SESSION['form_ids']);
				
				if(isset($data[$id_form]))
				{
					$data_form_id=$CI->security->generate_muliple_csrf_token($data[$id_form]);
					$hidden['form_id'] =$data_form_id['form_id'];
					$hidden[$data_form_id['form_id']] = $data_form_id['uniqid'];
				}
				else {
					$data_form_id=$CI->security->generate_muliple_csrf_token();
					$hidden['form_id'] =$data_form_id['form_id'];
					$hidden[$data_form_id['form_id']] = $data_form_id['uniqid'];
					$data[$id_form] = $data_form_id['form_id'];
					$_SESSION['form_ids'] = serialize($data);
				}
			}
			
			
		}
		else if ($CI->config->item('csrf_protection') === TRUE && ! (strpos($action, $CI->config->base_url()) === FALSE OR strpos($form, 'method="get"')))
		{
			$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
		}

		if (is_array($hidden) && count($hidden) > 0)
		{
			$form .= '<div style="display:none;">'.form_hidden($hidden).'</div>';
		}

		return $form;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('cmc_form_open_multipart'))
{
	/**
	 * Form Declaration - Multipart type
	 *
	 * Creates the opening portion of the form, but with "multipart/form-data".
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function cmc_form_open_multipart($form_id = '', $action = '', $attributes = array(), $hidden = array())
	{
		if (is_string($attributes))
		{
			$attributes .= ' enctype="multipart/form-data"';
		}
		else
		{
			$attributes['enctype'] = 'multipart/form-data';
		}

		return cmc_form_open($form_id,$action, $attributes, $hidden);
	}
}

if ( ! function_exists('cmc_json_encode'))
{
	/**
	 * Form Declaration - Multipart type
	 *
	 * Creates the opening portion of the form, but with "multipart/form-data".
	 *
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	function cmc_json_encode($data)
	{
		if(isset($_SESSION['form_id']))
		{
			$data_form = $CI->security->generate_muliple_csrf_token();
			$data['form_id'] = $data_form['form_id'];
			$data[$data_form['form_id']] = $data_form['uniqid'];
		}
		
	}
}

