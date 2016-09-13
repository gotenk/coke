<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for the users module
 *
 * @author		 MaxCMS Dev Team
 * @package	 MaxCMS\Core\Modules\Users\Controllers
 */
class Cokeuser extends Admin_Controller
{

	protected $section = 'users';

	/**
	 * Validation for basic profile
	 * data. The rest of the validation is
	 * built by streams.
	 *
	 * @var array
	 */
	private $validation_rules = array(
		array(
			'field' => 'active',
			'label' => 'lang:user_active_label',
			'rules' => ''
		),		
	);

	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->load->model('user_m');
		$this->load->model('groups/group_m');
		$this->load->helper(array('user','coketune'));
		$this->load->library('form_validation');
		$this->lang->load('user');

		if ($this->current_user->group != 'admin') 
		{
			$this->template->groups = $this->group_m->where_not_in('name', 'admin')->get_all();
		} 
		else 
		{
			$this->template->groups = $this->group_m->get_all();
		}
		
		$this->template->groups_select = array_for_select($this->template->groups, 'id', 'description');
	}

	/**
	 * List all users
	 */
	public function index()
	{
		$base_where = array('active' => 0);		

		// ---------------------------
		// User Filters
		// ---------------------------

		// Determine active param
		$base_where['active'] = $this->input->post('f_module') ? (int)$this->input->post('f_active') : $base_where['active'];

		// Determine group param
		$base_where = $this->input->post('f_group') ? $base_where + array('group_id' => (int)$this->input->post('f_group')) : $base_where;

		// Keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		// Create pagination links
		$pagination = create_pagination(ADMIN_URL.'/users/index', $this->user_m->count_by($base_where));

		//Skip admin
		$skip_admin = ( $this->current_user->group != 'admin' ) ? 'admin' : '';
		$skip_admin = 'admin';

		// Using this data, get the relevant results
		$this->db->order_by('active', 'desc')
			->join('groups', 'groups.id = users.group_id')
			->where_not_in('groups.name', $skip_admin)
			->limit($pagination['limit'], $pagination['offset']);

		$users = $this->user_m->get_many_by($base_where);

		// Unset the layout if we have an ajax request
		if ($this->input->is_ajax_request())
		{
			$this->template->set_layout(false);
		}

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('users', $users)
			->set_partial('filters', 'admincoketune/partials/filters')
			->append_js('admin/filter.js');

		$this->input->is_ajax_request() ? $this->template->build('admincoketune/tables/users') : $this->template->build('admincoketune/index');
	}

	/**
	 * Method for handling different form actions
	 */
	public function action()
	{
		// Max demo version restrction
		if (MAX_DEMO)
		{
			$this->session->set_flashdata('notice', lang('global:demo_restrictions'));
			redirect(ADMIN_URL.'/users');
		}

		// Determine the type of action
		switch ($this->input->post('btnAction'))
		{
			case 'activate':
				$this->activate();
				break;
			case 'delete':
				$this->delete();
				break;
			default:
				redirect(ADMIN_URL.'/users');
				break;
		}
	}




	/**
	 * Show a user preview
	 *
	 * @param	int $id The ID of the user.
	 */
	public function preview($id = 0)
	{
		$user = $this->ion_auth->get_user($id);		

		$this->template
			->set_layout('modal', 'admin')
			->set('user', $user)
			->build('admincoketune/preview');
	}

	/**
	 * Activate users
	 *
	 * Grabs the ids from the POST data (key: action_to).
	 */
	public function activate()
	{
		// Activate multiple
		if ( ! ($ids = $this->input->post('action_to')))
		{
			$this->session->set_flashdata('error', lang('user:activate_error'));
			redirect(ADMIN_URL.'/users');
		}

		$activated = 0;
		$to_activate = 0;
		foreach ($ids as $id)
		{
			if ($this->ion_auth->activate($id))
			{
				$activated++;
			}
			$to_activate++;
		}
		$this->session->set_flashdata('success', sprintf(lang('user:activate_success'), $activated, $to_activate));

		redirect(ADMIN_URL.'/users');
	}

	/**
	 * Delete an existing user
	 *
	 * @param int $id The ID of the user to delete
	 */
	public function delete()
	{
		if($id = $this->input->post('id'))
		{

			$ids = ($id > 0) ? array($id) : $this->input->post('action_to');
	
			if ( ! empty($ids))
			{
				$deleted = 0;
				$to_delete = 0;
				$deleted_ids = array();
				foreach ($ids as $id)
				{
					// Make sure the admin is not trying to delete themself
					if ($this->ion_auth->get_user()->id == $id)
					{
						$this->session->set_flashdata('notice', lang('user:delete_self_error'));
						continue;
					}
	
					if ($this->ion_auth->delete_user($id))
					{
						$deleted_ids[] = $id;
						$deleted++;
					}
					$to_delete++;
				}
	
				if ($to_delete > 0)
				{
					// Fire an event. One or more users have been deleted. 
					Events::trigger('user_deleted', $deleted_ids);
	
					$this->session->set_flashdata('success', sprintf(lang('user:mass_delete_success'), $deleted, $to_delete));
				}
			}
			// The array of id's to delete is empty
			else
			{
				$this->session->set_flashdata('error', lang('user:mass_delete_error'));
			}

			redirect(ADMIN_URL.'/users');
		}
		else {
			redirect(ADMIN_URL.'/users');
		}
	}


	function export_data($date_from=0, $date_end=0, $keywords=0, $status=0, $group=0){	

		//var_dump($date_from, $date_end, $keywords, $status, $group); die();
		$this->load->library('excel');    	           
            
        $this->excel->getActiveSheet()->setTitle('Data Export');
        $nama_file = 'data_export';
		
		$where = array();
		if($status){
			$this->db->where('users.active', $status);
		}else{
			//$this->db->where('users.active !=', 'deleted');
		}

		if($group){
			$this->db->where('users.group_id', $group);
		}

		if($keywords){
			$this->db->like('users.email', $keywords);
			$this->db->or_like('profiles.display_name', $keywords);
		}

		if($date_from){
			$arr_date = explode('-', $date_from);
			$date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' 00:00:00';
			$this->db->where('users.created_on >=', strtotime($date));
		}
		if($date_end){
			$arr_date = explode('-', $date_end);
			$date = $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' 24:00:00';
			$this->db->where('users.created_on <=', strtotime($date));
		}		

		$this->db->select('users.*, profiles.display_name, profiles.dob, profiles.address_line1');
		$this->db->join('profiles', 'users.id=profiles.user_id');
		$this->db->order_by('users.created_on', 'asc');
		$data = $this->db->get_where('users');
		
		if($data->num_rows <=0){
			$this->session->set_flashdata('error', 'Tidak ada data hasil');
			redirect(ADMIN_URL.'/users');
			die();
		}

        $count = 2;
        $this->excel->getActiveSheet()->setCellValue('A1', 'No');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Name');   
        $this->excel->getActiveSheet()->setCellValue('C1', 'Email');
        $this->excel->getActiveSheet()->setCellValue('D1', 'DOB');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Address');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Last Challenge');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Join Date');
        
        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        
        $no=1;
        foreach($data->result() as $dt){
        	$this->excel->getActiveSheet()->setCellValueExplicit('A'.$count, $no, PHPExcel_Cell_DataType::TYPE_STRING); 
        	$this->excel->getActiveSheet()->setCellValueExplicit('B'.$count, $dt->display_name, PHPExcel_Cell_DataType::TYPE_STRING); 
            $this->excel->getActiveSheet()->setCellValueExplicit('C'.$count, $dt->email, PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueExplicit('D'.$count, $dt->dob, PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueExplicit('E'.$count, $dt->address_line1, PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueExplicit('F'.$count, '-', PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueExplicit('G'.$count, date('d/m/Y', $dt->created_on), PHPExcel_Cell_DataType::TYPE_STRING);
            $count++; 
            $no++;
        }
        
        foreach(range('A','G') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $this->excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '8FB1FF'))));

        $this->excel->getActiveSheet()->freezePane('H2');
        
        $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         
        $filename = $nama_file.'_'.$date_from.'_'.$date_end.'.csv'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter->save('php://output');
       
	}




	/**
	 * Username check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $username The username.
	 *
	 * @return bool
	 */
	public function _username_check()
	{
		if ($this->ion_auth->username_check($this->input->post('username')))
		{
			$this->form_validation->set_message('_username_check', lang('user:error_username'));
			return false;
		}
		return true;
	}

	/**
	 * Email check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $email The email.
	 *
	 * @return bool
	 */
	public function _email_check()
	{
		if ($this->ion_auth->email_check($this->input->post('email')))
		{
			$this->form_validation->set_message('_email_check', lang('user:error_email'));
			return false;
		}

		return true;
	}

	/**
	 * Check that a proper group has been selected
	 *
	 * @author Stephen Cozart
	 *
	 * @param int $group
	 *
	 * @return bool
	 */
	public function _group_check($group)
	{
		if ( ! $this->group_m->get($group))
		{
			$this->form_validation->set_message('_group_check', lang('regex_match'));
			return false;
		}
		return true;
	}
	
	function password_complexcity($pass,$user_id){
			$this->form_validation->set_message('password_complexcity','Password minimal 8 karakter terdiri minimal 1 huruf, 1 angka dan 1 karakter spesial.');
			preg_match('/[^a-zA-Z0-9]+/ism', $pass,$matches);
			preg_match('/[0-9]+/ism', $pass,$matches2);
			//var_dump($matches);
			if(!empty($matches[0][0]) && isset($matches2[0][0]) && ($matches2[0][0]!='') ) {
				//compare with old password
				if($user_id!=0)
				{
					//echo $user_id;
					$user_info = $this->user_m->get(array('id' => $user_id));
					if($this->method == 'edit' && $this->input->post('old_password'))
					{
						if(!$this->_check_old_password($this->input->post('old_password'),$this->current_user->id))
						{
							return false;
						}
					}
	
					if($user_info)
					{
						$hashed_new_pass = $this->ion_auth_model->hash_password($pass ,$user_info->salt?$user_info->salt:'');
						$data_tst = $this->history_password_m->get_by(array('password_new'=>$hashed_new_pass,'user_id'=>$user_id));
	
						if($data_tst || ( $hashed_new_pass == $user_info->password))
						{
							$this->form_validation->set_message('password_complexcity','Password tidak boleh sama dengan password lama.');
							return false;
						}
						else {
							$this->history_password_m->insert(array('user_id'=>$user_id,
																	'password_new'=>$hashed_new_pass,
																	'password_old'=>$user_info->password,
																	'salt'=>$user_info->salt,
																	'message'=>'password edited',
																	'created_on'=>now()
																	));
							return true;
						}
	
					}
				}
				
				return true;
			}
			return false;
		}
	
		function _check_old_password($pass,$user_id)
		{
			$user_info = $this->user_m->get(array('id' => $user_id));
			if($user_info)
			{
				$hashed_new_pass = $this->ion_auth_model->hash_password($this->input->post('old_password') ,$user_info->salt?$user_info->salt:'');
				if($user_info->password !=  $hashed_new_pass)
				{
					$this->form_validation->set_message('_check_old_password','Current Password tidak cocok');
					return false;
				}
				else {
					return true;
				}
	
	
			}
		}
		
		
		

}