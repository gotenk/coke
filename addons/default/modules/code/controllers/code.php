<?php defined('BASEPATH') or exit('No direct script access allowed');

class Code extends Public_Controller
{
    private $alfamart_validation = array(
        array(
            'field' => 'alfamart_code',
            'label' => 'Kode Unik',
            'rules' => 'required|trim|xss_clean|htmlspecialchars|callback__alphanumeric'
        ),
        array(
            'field' => 'transaction_code',
            'label' => 'Kode Transaksi',
            'rules' => 'required|trim|xss_clean|htmlspecialchars|callback__alphanumeric'
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
            'rules' => 'required|trim|xss_clean|htmlspecialchars|callback__alphanumeric'
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
        $vendor = $this->input->post('vendor');

        // If user is not logged in, save code input in session and redirect them to the registration page.
        if ($this->belum_login()) {
            $this->session->set_userdata('code_temp', [
                'vendor'         => $vendor,
                'code'           => ($vendor == 'alfamart') ? $this->input->post('alfamart_code') : $this->input->post('indomaret_code'),
                'code_transaksi' => ($vendor == 'alfamart') ? $this->input->post('transaction_code') : ''
            ]);

            echo json_encode(array('message' => 0));
            return;
        }

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
                'message' => form_error('alfamart_code').'<br>'.form_error('transaction_code').'<br>'.form_error('recaptcha_response_field')
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
                'message' => form_error('indomaret_code').'<br>'.form_error('recaptcha_response_field')
            );

            echo json_encode($error);
            return;
        }
    }

    private function belum_login() {
        if (!$this->current_user) {
            return true;
        }

        return false;
    }

    public function _recaptcha_check_custom()
    {
        $private_key = Settings::get('recaptcha_private_key');
        $response = $this->input->post('recaptcha_response_field');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$private_key.'&response='.$response;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

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
        $this->session->unset_userdata('code_temp');

        if ($existing) {
            // Code has been used
            $fail = array(
                'user_id'               => $this->current_user->id,
                'ip_address'            => $this->input->ip_address(),
                'fail_unique_code'      => $data['alfamart_code'],
                'fail_transaction_code' => $data['transaction_code'],
                'date_failed'           => date('Y-m-d H:i:s'),
            );

            $this->code_m->insertData('alfamart_fail', $fail);

            return array('message' => 'Kode sudah pernah digunakan.');
        }

        // Code is new - let's check whether they match each other
        $check = $this->confidential($data['transaction_code']);

        if ($check != $data['alfamart_code']) {
            // Code is not match
            return array('message' => 'Kode yang dimasukkan salah.');
        }

        $success = array(
            'user_id'          => $this->current_user->id,
            'unique_code'      => $data['alfamart_code'],
            'transaction_code' => $data['transaction_code'],
            'date_created'     => date('Y-m-d H:i:s'),
        );

        $this->code_m->insertData('alfamart_code', $success);

        return array('message' => '1');
    }

    private function indomaretCodeCheck($data)
    {
        $code = $this->code_m->getSingleData('indomaret_code', 'code', $data['indomaret_code']);
        $this->session->unset_userdata('code_temp');

        if ($code && $code->is_used == '0') {
            // Code found and has not been used
            $input = array(
                'user_id'   => $this->current_user->id,
                'is_used'   => 1,
                'date_used' => date('Y-m-d H:i:s'),
            );

            $this->code_m->updateData('indomaret_code', $input, 'code', $data['indomaret_code']);

            return array('message' => '1');
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

            for ($i = 0; $i < $kurang; $i++) {
                $tambahan .= '0';
            }

            $hasil = $tambahan.$hasil;
        }

        return strtoupper($hasil);
    }

    public function _alphanumeric($string)
    {
        if (!preg_match('/[^a-zA-Z0-9\s]+/ism', $string)) {
            return true;
        }

        $this->form_validation->set_message('_alphanumeric', 'Kode input hanya boleh berupa angka dan huruf.');

        return false;
    }
}
