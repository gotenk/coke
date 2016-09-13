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
		'email' => array(
			'field' => 'email',
			'label' => 'lang:global:email',
			'rules' => 'required|max_length[60]|valid_email'
		),
		'password' => array(
			'field' => 'password',
			'label' => 'lang:global:password',
			'rules' => 'min_length[8]|max_length[20]'
		),
		'retype_password' => array(
			'field' => 'retype_password',
			'label' => 'lang:global:retype_password',
			'rules' => 'matches[password]'
		),
		'username' => array(
			'field' => 'username',
			'label' => 'lang:user_username',
			'rules' => 'required|alpha_dot_dash|min_length[3]|max_length[20]'
		),
		array(
			'field' => 'group_id',
			'label' => 'lang:user_group_label',
			'rules' => 'required|callback__group_check'
		),
		array(
			'field' => 'active',
			'label' => 'lang:user_active_label',
			'rules' => ''
		),
		array(
			'field' => 'display_name',
			'label' => 'lang:profile_display_name',
			'rules' => 'required'
		)
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
		$this->load->helper('user');
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
	 * Create a new user
	 */
	public function create()
	{
		// Extra validation for basic data
		$this->validation_rules['email']['rules'] .= '|callback__email_check';
		$this->validation_rules['password']['rules'] .= '|required|callback_password_complexcity';
		$this->validation_rules['username']['rules'] .= '|callback__username_check';

		// Get the profile fields validation array from streams
		$this->load->driver('Streams');
		$profile_validation = $this->streams->streams->validation_array('profiles', 'users');

		// Set the validation rules
		$this->form_validation->set_rules(array_merge($this->validation_rules, $profile_validation));

		$email = strtolower($this->input->post('email'));
		$password = $this->input->post('password');
		$username = $this->input->post('username');
		$group_id = $this->input->post('group_id');
		$activate = $this->input->post('active');

		// keep non-admins from creating admin accounts. If they aren't an admin then force new one as a "user" account
		$group_id = ($this->current_user->group !== 'admin' and $group_id == 1) ? 2 : $group_id;

		// Get user profile data. This will be passed to our
		// streams insert_entry data in the model.
		$assignments = $this->streams->streams->get_assignments('profiles', 'users');
		$profile_data = array();

		foreach ($assignments as $assign)
		{
			$profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
		}

		// Some stream fields need $_POST as well.
		$profile_data = array_merge($profile_data, $_POST);

		$profile_data['display_name'] = $this->input->post('display_name');

		if ($this->form_validation->run() !== false)
		{
			if ($activate === '2')
			{
				// we're sending an activation email regardless of settings
				Settings::temp('activation_email', true);
			}
			else
			{
				// we're either not activating or we're activating instantly without an email
				Settings::temp('activation_email', false);
			}

			$group = $this->group_m->get($group_id);

			// Register the user (they are activated by default if an activation email isn't requested)
			if ($user_id = $this->ion_auth->register($username, $password, $email, $email, $group_id, $profile_data, $group->name))
			{
				if ($activate === '0')
				{
					// admin selected Inactive
					$this->ion_auth_model->deactivate($user_id);
				}
				
					//save password history
					$user_info = $this->user_m->get(array('id' => $user_id));
					$hashed_new_pass = $this->ion_auth_model->hash_password($password ,$user_info->salt?$user_info->salt:'');
					$this->history_password_m->insert(array('user_id'=>$user_id,
					'password_new'=>$hashed_new_pass,
					'password_old'=>$hashed_new_pass,
					'salt'=>$user_info->salt,
					'message'=>'password created',
					'created_on'=>now()
					));
				

				// Fire an event. A new user has been created. 
				Events::trigger('user_created', $user_id);

				// Set the flashdata message and redirect
				$this->session->set_flashdata('success', $this->ion_auth->messages());

				// Redirect back to the form or main page
				$this->input->post('btnAction') === 'save_exit' ? redirect(ADMIN_URL.'/users') : redirect(ADMIN_URL.'/users/edit/'.$user_id);
			}
			// Error
			else
			{
				$this->template->error_string = $this->ion_auth->errors();
			}
		}
		else
		{
			// Dirty hack that fixes the issue of having to
			// re-add all data upon an error
			if ($_POST)
			{
				$member = (object) $_POST;
			}

		}

		if ( ! isset($member))
		{
			$member = new stdClass();
		}

		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$member->{$rule['field']} = set_value($rule['field']);
		}

		$stream_fields = $this->streams_m->get_stream_fields($this->streams_m->get_stream_id_from_slug('profiles', 'users'));

		// Set Values
		$values = $this->fields->set_values($stream_fields, null, 'new');

		// Run stream field events
		$this->fields->run_field_events($stream_fields, array(), $values);

		$this->template
			->title($this->module_details['name'], lang('user:add_title'))
			->set('member', $member)
			->set('display_name', set_value('display_name', $this->input->post('display_name')))
			->set('profile_fields', $this->streams->fields->get_stream_fields('profiles', 'users', $values))
			->build('admincoketune/form');
	}

	/**
	 * Edit an existing user
	 *
	 * @param int $id The id of the user.
	 */
	public function edit($id = 0)
	{
		// Get the user's data
		if ( ! ($member = $this->ion_auth->get_user($id)))
		{
			$this->session->set_flashdata('error', lang('user:edit_user_not_found_error'));
			redirect(ADMIN_URL.'/users');
		}
		
		// Check to see if we are changing usernames
		if ($member->username != $this->input->post('username'))
		{
			$this->validation_rules['username']['rules'] .= '|callback__username_check';
		}

		// Check to see if we are changing emails
		if ($member->email != $this->input->post('email'))
		{
			$this->validation_rules['email']['rules'] .= '|callback__email_check';
		}
		
		if($member->password != $this->input->post('password') && $this->input->post('password')!=""){
			$this->validation_rules['password']['rules'] .= '|callback_password_complexcity['.$id.']';
		}
		
		//checkold password
		//if($this->input->post('old_password'))
		//{
			
			$this->validation_rules['old_password']= array(
															'field' => 'group_id',
															'label' => 'lang:global:old_password',
															'rules' => 'required|callback__check_old_password['.$this->current_user->id.']'
														);
		//}

		// Get the profile fields validation array from streams
		$this->load->driver('Streams');
		$profile_validation = $this->streams->streams->validation_array('profiles', 'users', 'edit', array(), $id);

		// Set the validation rules
		$this->form_validation->set_rules(array_merge($this->validation_rules, $profile_validation));

		// Get user profile data. This will be passed to our
		// streams insert_entry data in the model.
		$assignments = $this->streams->streams->get_assignments('profiles', 'users');
		$profile_data = array();

		foreach ($assignments as $assign)
		{
			if (isset($_POST[$assign->field_slug]))
			{
				$profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
			}
			elseif (isset($member->{$assign->field_slug}))
			{
				$profile_data[$assign->field_slug] = $member->{$assign->field_slug};
			}
		}

		if ($this->form_validation->run() === true)
		{
			if (MAX_DEMO)
			{
				$this->session->set_flashdata('notice', lang('global:demo_restrictions'));
				redirect(ADMIN_URL.'/users');
			}

			// Get the POST data
			$update_data['email'] = $this->input->post('email');
			$update_data['active'] = $this->input->post('active');
			$update_data['username'] = $this->input->post('username');
			// allow them to update their one group but keep users with user editing privileges from escalating their accounts to admin
			$update_data['group_id'] = ($this->current_user->group !== 'admin' and $this->input->post('group_id') == 1) ? $member->group_id : $this->input->post('group_id');

			if ($update_data['active'] === '2')
			{
				$this->ion_auth->activation_email($id);
				unset($update_data['active']);
			}
			else
			{
				$update_data['active'] = (bool) $update_data['active'];
			}

			$profile_data = array();

			// Grab the profile data
			foreach ($assignments as $assign)
			{
				$profile_data[$assign->field_slug] = $this->input->post($assign->field_slug);
			}

			// Some stream fields need $_POST as well.
			$profile_data = array_merge($profile_data, $_POST);

			// We need to manually do display_name
			$profile_data['display_name'] = $this->input->post('display_name');

			// Password provided, hash it for storage
			if ($this->input->post('password'))
			{
				$update_data['password'] = $this->input->post('password');
			}

			if ($this->ion_auth->update_user($id, $update_data, $profile_data))
			{
				// Fire an event. A user has been updated. 
				Events::trigger('user_updated', $id);

				$this->session->set_flashdata('success', $this->ion_auth->messages());
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
			}

			// Redirect back to the form or main page
			$this->input->post('btnAction') === 'save_exit' ? redirect(ADMIN_URL.'/users') : redirect(ADMIN_URL.'/users/edit/'.$id);
		}
		else
		{
			// Dirty hack that fixes the issue of having to re-add all data upon an error
			if ($_POST)
			{
				$member = (object) $_POST;
			}
		}

		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== null)
			{
				$member->{$rule['field']} = set_value($rule['field']);
			}
		}

		// We need the profile ID to pass to get_stream_fields.
		// This theoretically could be different from the actual user id.
		if ($id)
		{
			$profile_id = $this->db->limit(1)->select('id')->where('user_id', $id)->get('profiles')->row()->id;
		}
		else
		{
			$profile_id = null;
		}

		$stream_fields = $this->streams_m->get_stream_fields($this->streams_m->get_stream_id_from_slug('profiles', 'users'));

		$profile = $this->db->limit(1)->where('user_id', $id)->get('profiles')->row();

		// Set Values
		$values = $this->fields->set_values($stream_fields, $profile, 'edit');

		// Run stream field events
		$this->fields->run_field_events($stream_fields, array(), $values);

		$this->template
			->title($this->module_details['name'], sprintf(lang('user:edit_title'), $member->username))
			->set('display_name', $member->display_name)
			->set('profile_fields', $this->streams->fields->get_stream_fields('profiles', 'users', $values, $profile_id))
			->set('member', $member)
			->build('admincoketune/form');
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