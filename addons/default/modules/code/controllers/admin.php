<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    protected $redirect;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->lang->load('code');
        $this->redirect = ADMIN_URL.'/code/daftar_pemenang';
        $this->template->active_section = 'code';
    }

    public function index()
    {
        redirect(ADMIN_URL.'/code/daftar_pemenang');
    }

    public function daftar_pemenang()
    {
        $this->template->active_section = 'pemenang';

        $parameter = array();

        if ($this->input->post('f_name')) {
            $parameter['name'] = $this->input->post('f_name');
        }

        $data = array();
        $per_page = Settings::get('records_per_page');
        $total_rows = $this->code_m->getPemenangList($parameter)->num_rows();
        $pagination = create_pagination(ADMIN_URL.'/code/daftar_pemenang/index', $total_rows, $per_page, 5);
        $lists = $this->code_m->getPemenangList($parameter, $pagination)->result();

        foreach ($lists as $list) {
            $data[] = array(
                'pemenang_id' => $list->pemenang_id,
                'user_id'     => $list->user_id,
                'name'        => $list->name,
            );
        }

        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/pemenang/partials/filters')
            ->set('total_rows', $total_rows)
            ->set('pagination', $pagination)
            ->set('data', $data);

        $this->input->is_ajax_request()
        ? $this->template->build('admin/pemenang/tables/table')
        : $this->template->build('admin/pemenang/index');
    }

    public function winner($id = 0)
    {
        $exist = $this->code_m->setAsWinner($id);

        if ($exist) {
            // Remove previous session
            $this->session->unset_userdata('temp_winner');
            $this->session->set_flashdata('success', lang('code:set_as_winner'));
        }

        redirect(ADMIN_URL.'/code/pilih_pemenang');
    }

    public function pilih_pemenang()
    {
        $this->template->active_section = 'select_pemenang';

        $temp_winner = array();

        if (null !== $this->session->userdata('temp_winner')) {
            $temp_winner = $this->session->userdata('temp_winner');
        }

        $parameter = array();

        if ($this->input->post('f_name')) {
            $parameter['name'] = $this->input->post('f_name');
        }

        $data = array();
        $per_page = Settings::get('records_per_page');
        $total_rows = Settings::get('pemenang_temp_count');
        $pagination = create_pagination(ADMIN_URL.'/code/pilih_pemenang/index', $total_rows, $per_page, 5);
        $lists = $this->code_m->getTempPemenangList($parameter, $pagination)->result();

        foreach ($lists as $list) {
            $data[$list->user_id] = array(
                'user_id' => $list->user_id,
                'name'    => $list->display_name,
                'count'   => $list->count,
            );

            if (isset($temp_winner->user_id) && $temp_winner->user_id == $list->user_id) {
                $temp_winner->name = $list->display_name;
                $temp_winner->count = $list->count;
                unset($data[$list->user_id]);
            }
        }

        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/pemenang/partials/filters_form')
            ->set('temp_winner', $temp_winner)
            ->set('total_rows', $total_rows)
            ->set('pagination', $pagination)
            ->set('data', $data);

        $this->input->is_ajax_request()
        ? $this->template->build('admin/pemenang/tables/table_form')
        : $this->template->build('admin/pemenang/form');
    }

    public function process_pemenang()
    {
        $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|htmlspecialchars');

        if ($this->form_validation->run()) {
            $password = $this->input->post('password');
            $current = $this->code_m->getSingleData('pemenang_temp_password', 'slug', 'winner_password');
            $check = sha1($password.$current->salt);

            if ($check == $current->password) {
                $temp_winner = $this->getTempWinner();

                if ($temp_winner) {
                    $this->session->set_userdata('temp_winner', $temp_winner);
                    redirect(ADMIN_URL.'/code/pilih_pemenang');
                }

                $this->session->set_flashdata('error', lang('code:no_winner'));
                redirect(ADMIN_URL.'/code/pilih_pemenang');
            }

            $this->session->set_flashdata('error', lang('code:wrong_password'));
            redirect(ADMIN_URL.'/code/pilih_pemenang');
        } else {
            $this->session->set_flashdata('error', form_error('password'));
        }

        redirect(ADMIN_URL.'/code/pilih_pemenang');
    }

    private function getTempWinner()
    {
        $max = Settings::get('pemenang_temp_count');

        if ($max > 0) {
            do {
                $selected = mt_rand(1, $max);
                $result = $this->code_m->getSingleData('pemenang_temp', 'pemenang_temp_id', $selected);
            } while (!$result);

            return $result;
        }

        return false;
    }

    public function password()
    {
        $this->template->active_section = 'password';

        $this->form_validation->set_rules('old_password', 'Old Password', 'required|trim|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|xss_clean|htmlspecialchars|min_length[6]');

        if ($this->form_validation->run()) {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');

            $current = $this->code_m->getSingleData('pemenang_temp_password', 'slug', 'winner_password');
            $check = sha1($old_password.$current->salt);

            if ($check == $current->password) {
                $data = array('password' => sha1($new_password.$current->salt));
                $this->code_m->updateData('pemenang_temp_password', $data, 'slug', 'winner_password');

                $this->session->set_flashdata('success', lang('code:change_password'));
            } else {
                $this->session->set_flashdata('error', lang('code:password_not_match'));
            }

            redirect(ADMIN_URL.'/code/password');
        }

        $this->template
            ->title($this->module_details['name'])
            ->build('admin/password');
    }
}
