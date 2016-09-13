<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_alfamart extends Admin_Controller
{

    protected $redirect;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->lang->load('code');
        $this->redirect = ADMIN_URL.'/code/alfamart';
        $this->template->active_section = 'alfamart';
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }

        redirect($this->redirect);
    }

    public function index()
    {
        $parameter = array();

        if ($this->input->post('f_unique_code')) {
            $parameter['unique_code'] = $this->input->post('f_unique_code');
        }

        if ($this->input->post('f_transaction_code')) {
            $parameter['transaction_code'] = $this->input->post('f_transaction_code');
        }

        $data = array();
        $per_page = Settings::get('records_per_page');
        $total_rows = $this->code_m->getAlfamartCodeList($parameter)->num_rows();
        $pagination = create_pagination(ADMIN_URL.'/code/alfamart/index', $total_rows, $per_page, 5);
        $codes = $this->code_m->getAlfamartCodeList($parameter, $pagination)->result();

        foreach ($codes as $code) {
            $data[] = array(
                'code_id'          => $code->code_id,
                'unique_code'      => $code->unique_code,
                'transaction_code' => $code->transaction_code,
                'user_id'          => $code->user_id,
                'user'             => $code->display_name,
                'date_created'     => date('d M Y', strtotime($code->date_created)),
                'pemenang_id'      => $code->pemenang_id,
                'pemenang_temp_id' => $code->pemenang_temp_id,
            );
        }

        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/alfamart/partials/filters')
            ->set('total_rows', $total_rows)
            ->set('pagination', $pagination)
            ->set('data', $data);

        $this->input->is_ajax_request()
        ? $this->template->build('admin/alfamart/tables/code')
        : $this->template->build('admin/alfamart/index');
    }

    public function winner($id = 0)
    {
        $code = $this->code_m->getSingleData('alfamart_code', 'code_id', $id);

        if ($code) {
            $this->code_m->setAsTempWinner($code);
            $this->session->set_flashdata('success', lang('code:set_as_temp_winner'));
        }

        redirect($this->redirect);
    }

    public function action()
    {
        if ($this->input->method() == 'post') {
            $success = false;
            $action = $this->input->post('btnAction');
            $ids = $this->input->post('action_to');

            if ($ids) {
                foreach ($ids as $id) {
                    if ($action == 'winner') {
                        $code = $this->code_m->getSingleData('alfamart_code', 'code_id', $id);
                        $success = $this->code_m->setAsTempWinner($code);
                    }
                }
            }

            if ($success) {
                $this->session->set_flashdata('success', lang('code:set_as_temp_winner'));
            } else {
                $this->session->set_flashdata('error', lang('code:error'));
            }
        }

        redirect($this->redirect);
    }
}
