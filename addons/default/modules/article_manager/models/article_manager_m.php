<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article_manager_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = 'article_manager';
	}
	
	/**
	 * Count by
	 *
	 * @param array $params
	 *
	 * @return int
	 */
	public function count_by($params = array())
	{
	
		if ( ( isset($params['active'])) && ($params['active'] != -1))
		{
			$this->db->where('status', $params['active']);
		}

		
		
		if ( ! empty($params['name']))
		{
			$this->db
				->like('data', trim($params['name']))
				->or_like('id', trim($params['name']));
				
			if ( ! empty($params['date']))
			{
				$this->db->escape_exception = true;
				$this->db
					->or_like('data', trim($params['date']),'none');
					$this->db->escape_exception = false;
					
			}
				
		}
		else if ( ! empty($params['date']))
		{
			$this->db->escape_exception = true;
			$this->db
					->like('data', trim($params['date']),'none');
					$this->db->escape_exception = false;
					
		}

		
		$this->db->from($this->_table);
		return $this->db->count_all_results();
	}

	/**
	 * Get by many
	 *
	 * @param array $params
	 *
	 * @return object
	 */
	public function get_many_by($params = array())
	{

		if ( ( isset($params['active'])) && ($params['active'] != -1) )
		{
			$this->db->where('status', $params['active']);
		
		}
		
		if ( ( isset($params['group_id']))  )
		{
			$this->db->where('group_id', $params['group_id']);
		
		}
		
		if ( ! empty($params['name']))
		{
			$this->db
				->like('data', trim($params['name']))
				->or_like('id', trim($params['name']));
				
			if ( ! empty($params['date']))
			{
				$this->db->escape_exception = true;
				$this->db
					->or_like('data', trim($params['date']),'none');
					$this->db->escape_exception = false;
					
			}
				
		}
		else if ( ! empty($params['date']))
		{
			$this->db->escape_exception = true;
			$this->db
					->like('data', trim($params['date']),'none');
					$this->db->escape_exception = false;
					
		}

		return $this->db->get($this->_table);
	}
	
	/**
	 * Callback method for validating the title
	 *
	 * @param string $title The title to validate
	 * @param int    $id    The id to check
	 *
	 * @return mixed
	 */
	public function check_title($title = '', $id = 0)
	{
		return (bool)$this->db->where('title', $title)
			->where('id != ', $id)
			->from($this->_table)
			->count_all_results();
	}

	/**
	 * Callback method for validating the slug
	 *
	 * @param string $slug The slug to validate
	 * @param int    $id   The id to check
	 *
	 * @return bool
	 */
	public function check_slug($slug = '', $id = 0)
	{
		return (bool)$this->db->where('slug', $slug)
			->where('id != ', $id)
			->from($this->_table)
			->count_all_results();
	}

}