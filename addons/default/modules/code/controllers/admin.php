<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->lang->load('code');

        $this->template->active_section = 'code';
    }

    public function index()
    {
        redirect(ADMIN_URL.'/code/alfamart');
    }
}
