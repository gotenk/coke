<?php defined('BASEPATH') or exit('No direct script access allowed');

class Code extends Public_Controller
{
    private $alfamart_validation = array(
        array(
            'field' => 'alfamart_code',
            'label' => 'Kode Unik',
            'rules' => 'required|trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'transaction_code',
            'label' => 'Kode Transaksi',
            'rules' => 'required|trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'recaptcha_response_field',
            'label' => 'Recaptcha',
            'rules' => 'required|trim|xss_clean|callback__recaptcha_check_custom'
        ),
    );

    private $indomaret_validation = array(
        array(
            'field' => 'indomaret_code',
            'label' => 'Kode Unik',
            'rules' => 'required|trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'recaptcha_response_field',
            'label' => 'Recaptcha',
            'rules' => 'required|trim|xss_clean|callback__recaptcha_check_custom'
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('code_m'));
        $this->form_validation->set_error_delimiters('', '');
    }

    public function index()
    {
        if (!$this->current_user) {
            echo json_encode(array('message' => 'Silahkan login terlebih dahulu'));
            return;
        }

        $vendor = $this->input->post('vendor');

        if ($vendor == 'alfamart') {
            $this->form_validation->set_rules($this->alfamart_validation);

            if ($this->form_validation->run()) {
                $data = array(
                    'alfamart_code'    => $this->input->post('alfamart_code'),
                    'transaction_code' => $this->input->post('transaction_code'),
                );

                $result = $this->alfamartCodeCheck($data);

                echo json_encode($result);
                return;
            }

            $error = array(
                'message' => form_error('alfamart_code').form_error('transaction_code').form_error('recaptcha_response_field')
            );

            echo json_encode($error);
            return;
        } elseif ($vendor == 'indomaret') {
            $this->form_validation->set_rules($this->indomaret_validation);

            if ($this->form_validation->run()) {
                $data = array('indomaret_code' => $this->input->post('indomaret_code'));

                $result = $this->indomaretCodeCheck($data);

                echo json_encode($result);
                return;
            }

            $error = array(
                'message' => form_error('indomaret_code').form_error('recaptcha_response_field')
            );

            echo json_encode($error);
            return;
        } else {
            redirect();
        }
    }

    public function _recaptcha_check_custom()
    {
        $private_key = Settings::get('recaptcha_private_key');
        $response = $this->input->post('recaptcha_response_field');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$private_key.'&response='.$response;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);

        $result = curl_exec($ch);
        curl_close($ch);
        $hasil = json_decode($result);

        if (!$hasil->success) {
            $this->form_validation->set_message('_recaptcha_check_custom', 'Recaptcha tidak valid.');

            return false;
        } else {
            return true;
        }
    }

    private function alfamartCodeCheck($data)
    {
        $existing = $this->code_m->checkExistingCode($data);

        if ($existing) {
            // Code has been used
            $fail = array(
                'user_id'               => $this->current_user->id,
                'ip_address'            => $this->input->ip_address(),
                'fail_unique_code'      => $data['alfamart_code'],
                'fail_transaction_code' => $data['transaction_code'],
                'date_failed'           => date('Y-m-d H:i:s'),
            );

            // $this->code_m->insertData('alfamart_fail', $fail);

            return array('message' => 'Kode sudah pernah digunakan.');
        }

        // Code is new - let's check whether they match each other
        $check = $this->confidential($data['transaction_code']);

        if ($check != $data['alfamart_code']) {
            // Code is not match
            return array('message' => 'Kode yang dimasukkan salah.');
        }

        return array('message' => 'Kode valid.');
    }

    private function indomaretCodeCheck($data)
    {
        $code = $this->code_m->getSingleData('indomaret_code', 'code', $data['indomaret_code']);

        if ($code && $code->is_used == '0') {
            // Code found and has not been used
            $data = array(
                'user_id'   => $this->current_user->id,
                'is_used'   => 1,
                'date_used' => date('Y-m-d H:i:s'),
            );

            // $this->code_m->updateData('indomaret_code', $data, 'code', $data['indomaret_code']);

            return array('message' => 'Kode valid.');
        }

        // Code is not found or has been used
        return array('message' => 'Kode yang dimasukkan salah atau sudah pernah digunakan.');
    }

    private function confidential($no_trans)
    {
        $string = '340'.$no_trans.'#37F0PJ0T';
        $hasil = dechex(crc32($string));
        $length = strlen($hasil);

        if ($length < 8) {
            $kurang = 8 - $length;
            $tambahan = '';

            for($i = 0; $i < $kurang; $i++){
                $tambahan .= '0';
            }

            $hasil = $tambahan.$hasil;
        }

        return strtoupper($hasil);
    }
}
