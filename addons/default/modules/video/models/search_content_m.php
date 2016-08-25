<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class Search_content_m extends MY_Model
{
	protected $_table = 'video';	
	
	public function insert_search_content($data)
	{
		$this->insert_ignore($this->_table,$data);
	}

}