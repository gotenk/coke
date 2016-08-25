<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\dago_gallery\Models
 */
class List_word_m extends MY_Model
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
		$this->db->where('status !=', 'black_listed');
		return parent::get_all();
	}
	
	 public function count_by()
    {
		
        $where = func_get_args();
        $this->normalize_where($where);
		$this->db->where('status !=', 'deleted');
		$this->db->where('status !=', 'black_listed');
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
	
	public function get_range_word($id)
	{
		//get position gallery id by submission
				$posisi = $this->db->query('SELECT x.id, 
												   x.position,
												   x.status
										  FROM (SELECT *,
										               @rownum := @rownum + 1 AS position
										          FROM default_search_engine_list_name 
										          JOIN (SELECT @rownum := 0) r
										     	  WHERE status ="live" ORDER BY name ASC ) x
												  WHERE  x.id = '.$id)->row();
													   
				$count_total = $this->count_by(array('status'=>'live'))-1;
				$margin = 4;				
				if($posisi && $count_total > 9 )					
				{
					
					$posisi_tengah = intval($posisi->position)-1;
					$offset_start = ($posisi_tengah - $margin) < 0 ? ($count_total + ($posisi_tengah - $margin)) : ($posisi_tengah - $margin) ;
					$offset_end =  ($posisi_tengah +$margin) > $count_total ? ($posisi_tengah +$margin)-$count_total : $posisi_tengah +$margin;
					
					$query_offset_start_modified =false;
					$query_offset_end_modified = false;
					$tmp_offset_start = array();
					$tmp_offset_end = array();
					$data_normal = array();
					$sisa_query_end = 0 ;
					$sisa_query_start = 0;
					if($offset_start > $posisi_tengah)
					{
						if($posisi_tengah > 0)
						{
							$sisa_query_start = $posisi_tengah;
						}
						$query_offset_start_modified = true;
						//query get 
						$this->order_by('name','asc');
						$this->limit(abs($posisi_tengah - $margin),$count_total-abs($posisi_tengah - $margin));
						$raw_offset_start = $this->get_many_by(array('status'=>'live'));
						$inc = 0;
						foreach($raw_offset_start as $item )
						{
							$tmp_offset_start[$inc] = $item->name;
							$inc++;
						}
						
					}
					
					if($offset_end < $posisi_tengah)
					{
						
						$query_offset_end_modified = true;
						//sisa query
						
						if($posisi_tengah < $count_total)
						{
							$sisa_query_end  =$count_total - $posisi_tengah;
						}
						//query_get						
						$this->order_by('name','asc');
						$this->limit(($posisi_tengah +$margin)-$count_total,0);
						$raw_offset_end= $this->get_many_by(array('status'=>'live'));
						
						$inc = 5;
						foreach($raw_offset_end as $item )
						{
							$tmp_offset_end[$inc] = $item->name;
							$inc++;
						}
					}
					
					if(!$query_offset_end_modified && $query_offset_start_modified)
					{
						
						$this->order_by('name','asc');
						$this->limit(($margin)+$sisa_query_start+1,$posisi_tengah-$sisa_query_start);
						$raw_data = $this->get_many_by(array('status'=>'live'));
						$inc = 5-($sisa_query_start+1);
						foreach($raw_data as $item )
						{
							$tmp_offset_end[$inc] = $item->name;
							$inc++;
						}
						/*var_dump('start modified');
						var_dump($tmp_offset_start);
						var_dump($tmp_offset_end);*/
						return ($tmp_offset_start+$tmp_offset_end);
					}
					else if($query_offset_end_modified && !$query_offset_start_modified)
					{
						$this->order_by('name','asc');
						
						$this->limit($margin+$sisa_query_end+1,$posisi_tengah-$margin);
						$raw_data = $this->get_many_by(array('status'=>'live'));
						//var_dump($this->db->last_query());
						$inc = 0;
						foreach($raw_data as $item )
						{
							$tmp_offset_start[$inc] = $item->name;
							$inc++;
						}
						/*var_dump('end modified');
						
						var_dump($tmp_offset_end);
						var_dump($tmp_offset_start);*/
						return ($tmp_offset_start+$tmp_offset_end);
						
					}
					else if($query_offset_end_modified && $query_offset_start_modified)
					{
					 /* var_dump('start modified');
						var_dump('end modified');
						var_dump(	$tmp_offset_start);
						var_dump($tmp_offset_end);*/
						return ($tmp_offset_start+$tmp_offset_end);
					}
					else {
						//normal query
						$this->order_by('name','asc');
						$this->limit(($margin*2)+1,$offset_start);
						$raw_data = $this->get_many_by(array('status'=>'live'));
						//var_dump('normal');
						
						$inc = 0;
						foreach($raw_data as $item )
						{
							$data_normal[$inc] = $item->name;
							$inc++;
						}
						/*var_dump($data_normal);*/
						
						return $data_normal;
					}
				}
				else {
					return false;
				}
	}
	
	public function get_random_word()
	{
		

		$data =$this->db->query('SELECT DISTINCT(slug),name,id FROM '.$this->db->dbprefix($this->_table).' WHERE status="live" ORDER BY RAND() LIMIT 9');
		$data = array_for_select($data->result(),'name');
		return $data ;

	
	}
}