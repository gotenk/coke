<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_indomaret extends Admin_Controller
{
    protected $redirect;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->lang->load('code');
        $this->redirect = ADMIN_URL.'/code/indomaret';
        $this->template->active_section = 'indomaret';
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
        $parameter = array('is_used' => 'all');

        if ($this->input->post('f_is_used')) {
            $parameter['is_used'] = $this->input->post('f_is_used');
        }

        if ($this->input->post('f_code')) {
            $parameter['code'] = $this->input->post('f_code');
        }

        $data = array();
        $per_page = Settings::get('records_per_page');
        $total_rows = 10; //->code_m->getIndomaretCodeList($parameter)->num_rows();
        $pagination = create_pagination(ADMIN_URL.'/code/indomaret/index', $total_rows, $per_page, 5);
        $codes = $this->code_m->getIndomaretCodeList($parameter, $pagination)->result();

        foreach ($codes as $code) {
            $data[] = array(
                'code_id'      => $code->code_id,
                'code'         => $code->code,
                'is_used'      => ((int) $code->is_used === 1) ? 'Yes' : 'No',
                'user_id'      => $code->user_id,
                'user'         => $code->display_name,
                'date_used'    => ($code->date_used != '0000-00-00 00:00:00') ? date('d M Y', strtotime($code->date_used)) : null,
                'date_created' => date('d M Y', strtotime($code->date_created)),
                'pemenang_id'  => $code->pemenang_id,
            );
        }

        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/indomaret/partials/filters')
            ->set('total_rows', $total_rows)
            ->set('pagination', $pagination)
            ->set('data', $data);

        $this->input->is_ajax_request()
        ? $this->template->build('admin/indomaret/tables/code')
        : $this->template->build('admin/indomaret/index');
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
                        $success = $this->code_m->setAsWinner($id);
                    }
                }

                $success = true;
            }

            if ($success) {
                $this->session->set_flashdata('success', lang('code:set_as_winner'));
            } else {
                $this->session->set_flashdata('error', lang('code:error'));
            }
        }

        redirect($this->redirect);
    }
}
