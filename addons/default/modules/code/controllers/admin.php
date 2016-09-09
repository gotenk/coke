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
            $this->session->set_flashdata('success', lang('code:set_as_winner'));
        }

        redirect($this->redirect);
    }
}
