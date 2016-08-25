<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class Photo_m extends MY_Model
{
	protected $_table = 'default_photo';

	public function get_all($created_on ='')
	{
	   $this->db
			->select('photo.*')
			->select('users.username, profiles.display_name')
			->join('profiles', 'profiles.user_id = photo.author_id', 'left')
			->join('users', 'photo.author_id = users.id', 'left')
			->order_by('created_on', (! empty($created_on)? : 'DESC'));

		return $this->db->get($this->_table)->result();
	}

	public function get_many_by($params = array())
	{
		/*
		if ( ! empty($params['month']))
		{
			$this->db->where('MONTH(FROM_UNIXTIME('.$this->db->dbprefix('photo').'.created_on))', $params['month']);
		}

		if ( ! empty($params['year']))
		{
			$this->db->where('YEAR(FROM_UNIXTIME('.$this->db->dbprefix('photo').'.created_on))', $params['year']);
		}
		*/
		if ( ! empty($params['total_vote']))
		{
			$this->db->order_by('photo.total_vote', trim($params['total_vote']));
		}

		if ( ! empty($params['kid_name']))
		{
			$this->db
				->like('photo.kid_name', trim($params['kid_name']));
		}

		// Is a status set?
		if ( ! empty($params['status']))
		{
			// If it's all, then show whatever the status
			if ($params['status'] != 'all')
			{
				// Otherwise, show only the specific status
				$this->db->where('status', $params['status']);
			}else{
				$this->db->where('status !=', 'deleted');
			}
		}

		// Nothing mentioned, show live only (general frontend stuff)
		else
		{
			$this->db->where('status', 'live');
		}

		// By default, dont show future posts
		if ( ! isset($params['show_future']) || (isset($params['show_future']) && $params['show_future'] == false))
		{
			$this->db->where('photo.created_on <=', now());
		}

		// Limit the results based on 1 number or 2 (2nd is offset)
		if (isset($params['limit']) && is_array($params['limit']))
		{
			$this->db->limit($params['limit'][0], $params['limit'][1]);
		}
		elseif (isset($params['limit']))
		{
			$this->db->limit($params['limit']);
		}

		return $this->get_all();
	}

	function insert_data($data){
        $this->db->insert('photo', $data);
        return $this->db->insert_id();
    }

    function update_photo_filename($id, $new_filename, $thumb_file){
		return $this->db->update('photo', array('filename'=>$new_filename, 'thumb_file'=>$thumb_file), array('id'=>$id));    	
    }

    function update_photo_by($id, $data_photo){
    	return $this->db->update('photo', $data_photo, array('id'=>$id)); 
    }

    function get_photo_by($id){
		return $this->db->get_where('photo', array('id'=>$id))->result();    	
    }


}