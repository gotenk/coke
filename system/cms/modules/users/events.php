<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Email Template Events Class
 *
 * @author      Stephen Cozart
 */
class Events_users {

    protected $ci;

    public function __construct()
    {
       
        //register the admin_controller
        Events::register('admin_controller', array($this, 'check_expired_password'));
    }

    public function check_expired_password($data = array())
    {
    	
        $this->ci =& get_instance();
		
		
		if(preg_match('/logout|login/',uri_string()))
		{
			return false;
		}
		
		$this->ci->load->config('users/ion_auth');
        $this->ci->load->model('users/history_password_m');

		$month = $this->ci->config->item('max_expired_password');
		$array_month = explode(' ',$month);
		
		
		
		switch($array_month[1])
		{
			case 'month' : 
			case 'months' : 
			case 'bulan' :
				
				$password = $this->ci->history_password_m->order_by('id','desc')->get_by(array('user_id'=>$this->ci->current_user->id));
				
				$now      = new DateTime();
				$last_created = new DateTime(date('Y-m-d H:i:s',$password->created_on));
				$interval = $now->diff($last_created);
				$current_month = intval($interval->format('%m'));
				$current_minute = intval($interval->format('%i'));
				$current_second = intval($interval->format('%s'));
				if($current_month >=intval($array_month[0]))
				{
					if ($current_second > 0 || $current_minute >0)
					{
						$this->ci->session->set_flashdata('notice','Password Anda Telah Kadaluarsa selama lebih dari '.$month.', anda wajib merubahnya');
						
						if(!preg_match('/.*(\/users\/edit\/[0-9]+|logout|login)$/',uri_string()))
						{
							redirect(ADMIN_URL.'/users/edit/'.$this->ci->current_user->id);
						}
					}
				}
				
			break;
			
			case 'day' :
			case 'days' :
			case 'hari' :
				
				$password = $this->ci->history_password_m->order_by('id','desc')->get_by(array('user_id'=>$this->ci->current_user->id));
				
				$now      = new DateTime();
				$last_created = new DateTime(date('Y-m-d H:i:s',$password->created_on));
				$interval = $now->diff($last_created);
				$current_day = intval($interval->format('%d'));
				$current_minute = intval($interval->format('%i'));
				$current_second = intval($interval->format('%s'));
				if($current_day >=intval($array_month[0]))
				{
					if ($current_second > 0 || $current_minute >0)
					{
						$this->ci->session->set_userdata('notice','Password Anda Telah Kadaluarsa selama lebih dari '.$month.', anda wajib merubahnya');
						if(!preg_match('/.*(\/users\/edit\/[0-9]+|logout|login)$/',uri_string()))
						{
							//var_dump('masuk');
							redirect(ADMIN_URL.'/users/edit/'.$this->ci->current_user->id);
						}
					}
				}
				
			break;
		}
		
		

        //return false if we can't find the necessary templates
        return false;
    }
}
/* End of file events.php */