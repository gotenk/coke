<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class Black_list_word_m extends MY_Model
{
	protected $_table = 'search_engine_list_name';	
	
	public function check_exists($field, $value = '', $id = 0)
	{
		if (is_array($field))
		{
			$params = $field;
			$id = $value;
		}
		else
		{
			$params[$this->db->dbprefix( $this->_table).'.'.$field] = $value;
			$this->db->or_where($this->db->dbprefix( $this->_table).'.'.$field,strtolower($value));
			$this->db->or_where($this->db->dbprefix( $this->_table).'.'.$field,strtoupper($value));
			$this->db->or_where($this->db->dbprefix( $this->_table).'.'.$field,ucfirst($value));
		}
		
		$params[$this->db->dbprefix( $this->_table).'.id !='] = (int)$id;
		//join for and clause
		if((int)$id)
		{
			$this->db->join( '(SELECT * from default_'.$this->_table.' where id != '.$id.' and status != "delete" ) as default_ab','default_ab.id=default_'.$this->_table.'.id' );
		}
		else
		{
			$this->db->join( '(SELECT * from default_'.$this->_table.'  where status != "delete" ) as default_ab','default_ab.id=default_'.$this->_table.'.id' );
		}
		
		return parent::count_by($params) == 0;
	}
	
	public function get_all()
	{
		$this->db->where('status !=', 'deleted');
		//$this->db->where('status !=', 'black_listed');
		return parent::get_all();
	}
	
	 public function count_by()
    {
		
        $where = func_get_args();
        $this->normalize_where($where);
		$this->db->where('status !=', 'deleted');
		//$this->db->where('status !=', 'black_listed');
        return $this->db->count_all_results($this->_table);
    }
	
	public function normalize_where($params)
	{
		 if (count($params) == 1)
        {
            $this->db->where($params[0]);
        }
        else
        {
            $this->db->where($params[0], $params[1]);
        }
	}
}