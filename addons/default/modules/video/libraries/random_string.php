<?php defined('BASEPATH') OR exit('No Direct Script Access Allowed');

class Random_string
{
	
	 protected $character_library =array('lowercase'=>array('characters'=>'abcdefghijklmnopqrstuvwxyz','count'=>17),
	 									 'uppercase'=>array('characters'=>'ABCDEFGHIJKLMNOPQRSTUVWXYZ','count'=>19),
										  'numeric'=>array('characters'=>'0123456789','count'=>9));
	 								
	 protected $character_set_array = array();	
	 function __construct($_config = array())
	 {
	 	$this->initialize($_config);
	 }
	 
	 function initialize($_config = array())
	 {
	 	if (count($_config))
		{
	 		$this->character_library =  array_replace_recursive($this->character_library,$_config);
		}
		foreach($this->character_library as $_index => $_value)
		{
			$this->character_set_array[] = $_value;
		}
	 }
	 public function get_total()
	 {
	 	$_total_count;
	 	foreach($this->character_library as $_value)
		{
			$_total_count += $_value['count'];
		}
		
		return $_total_count;
	 }
	  public function generate($_total_count= 0 )
	  {
	    $temp_array = array( );
		if ($_total_count != 0 && $_total_count !=  $this->get_total() && ($_total_count> $this->get_total()) )
		{
			for ($t=0 ; $t < ($_total_count- $this->get_total());$t ++)
			{
				$_random_index_array = rand(0,count($this->character_set_array)-1);
				$_count_sub_array = $this->character_set_array[$_random_index_array]['count'];
				$this->character_set_array[$_random_index_array]['count'] = ($_count_sub_array+1) ;
			}
			
		}
		
	    foreach ( $this->character_set_array as $character_set )
	    {
	    $_length_characters= strlen( $character_set[ 'characters' ] );
	      for ( $i = 0; $i < $character_set[ 'count' ]; $i++ )
	      {
	        $temp_array[ ] = $character_set[ 'characters' ][ rand( 0,$_length_characters - 1 ) ];
	      }
	    }
	    shuffle( $temp_array );
	    return implode( '', $temp_array );
	  }
}
