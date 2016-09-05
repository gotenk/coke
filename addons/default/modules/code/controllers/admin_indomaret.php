<?php defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

class Admin_indomaret extends Admin_Controller
{
    protected $redirect = ADMIN_URL.'/code/indomaret';

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->lang->load('code');

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
        $total_rows = $this->code_m->getIndomaretCodeList($parameter)->num_rows();
        $pagination = create_pagination(ADMIN_URL.'/code/indomaret/index', $total_rows, $per_page, 5);
        $codes = $this->code_m->getIndomaretCodeList($parameter, $pagination)->result();

        foreach ($codes as $code) {
            $data[] = array(
                'code_id'      => $code->code_id,
                'code'         => $code->code,
                'is_used'      => ((int) $code->is_used === 1) ? 'Yes' : 'No',
                'user'         => $code->display_name,
                'date_used'    => ($code->date_used != '0000-00-00 00:00:00') ? date('d M Y', strtotime($code->date_used)) : null,
                'date_created' => date('d M Y', strtotime($code->date_created)),
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

    public function upload()
    {
        // Since this method will process many codes,
        // disable access on staging or live environment
        if (BASE_URL != 'http://coke-tune.dev/') {
            redirect($this->redirect);
        }

        $file = FCPATH.'/addons/default/modules/code/data/code.xlsx';

        if (is_file($file)) {
            $this->codeProcessing($file);
        }

        $this->template
            ->title($this->module_details['name'])
            ->build('admin/indomaret/upload');
    }

    public function delete($id = 0)
    {
        $this->code_m->deleteData('indomaret_code', 'code_id', $id);
        $this->session->set_flashdata('success', lang('code:delete_code'));

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
                    if ($action == 'delete') {
                        $this->code_m->deleteData('indomaret_code', 'code_id', $id);
                    }
                }

                $success = true;
            }

            if ($success) {
                $this->session->set_flashdata('success', lang('code:'.$action.'_code'));
            } else {
                $this->session->set_flashdata('error', lang('code:error'));
            }
        }

        redirect($this->redirect);
    }

    private function codeProcessing($input)
    {
        #http://stackoverflow.com/questions/9695695/how-to-use-phpexcel-to-read-data-and-insert-into-database
        $this->load->library('excel');

        try {
            $inputFileType = PHPExcel_IOFactory::identify($input);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($input);
        } catch(Exception $e) {
            die('Error: '.$e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, false);

            $data = array(
                'code'         => $rowData[0][0],
                'date_created' => date('Y-m-d H:i:s')
            );

            $this->code_m->insertIgnore('indomaret_code', $data);
        }

        return true;
    }
}
