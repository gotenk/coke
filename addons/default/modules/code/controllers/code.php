<?php defined('BASEPATH') or exit('No direct script access allowed');

class Code extends Public_Controller
{
    private $alfamart_validation = array(
        array(
            'field' => 'alfamart_code',
            'label' => 'Kode Unik',
            'rules' => 'required|trim|xss_clean|htmlspecialchars|max_length[10]|callback__alphanumeric'
        ),
        array(
            'field' => 'transaction_code',
            'label' => 'Kode Transaksi',
            'rules' => 'required|trim|xss_clean|htmlspecialchars|max_length[25]|callback__alphanumeric'
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
            'rules' => 'required|trim|xss_clean|htmlspecialchars|max_length[10]|callback__alphanumeric'
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

        if ($vendor == 'alfamart' || $vendor == 'alfamidi') {
            $this->form_validation->set_rules($this->alfamart_validation);

            if ($this->form_validation->run()) {
                // If user is not logged in, save code input in session and redirect them to the registration page
                if ($this->belum_login()) {
                    $this->session->set_userdata('code_temp', [
                        'vendor'         => $vendor,
                        'code'           => $this->input->post('alfamart_code'),
                        'code_transaksi' => $this->input->post('transaction_code')
                    ]);

                    echo json_encode(array('message' => 0));
                    return;
                }

                $data = array(
                    'vendor'           => $vendor,
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
                // If user is not logged in, save code input in session and redirect them to the registration page
                if ($this->belum_login()) {
                    $this->session->set_userdata('code_temp', [
                        'vendor'         => $vendor,
                        'code'           => $this->input->post('indomaret_code'),
                        'code_transaksi' => ''
                    ]);

                    echo json_encode(array('message' => 0));
                    return;
                }

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
        } else {
            // Abort if vendor other than alfamart, alfamidi, indomaret
            echo json_encode(array('message' => 'Terjadi kesalahan.'));
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
            'vendor'           => $data['vendor'],
            'unique_code'      => $data['alfamart_code'],
            'transaction_code' => $data['transaction_code'],
            'date_created'     => date('Y-m-d H:i:s'),
        );

        $this->code_m->insertData('alfamart_code', $success);

        // Check - if user has been in pemenang table then they are not valid anymore
        $exist = $this->code_m->getSingleData('pemenang', 'user_id', $this->current_user->id);

        if (!$exist) {
            $temp = array(
                'user_id' => $this->current_user->id,
                'vendor'  => $data['vendor'],
                'code'    => $data['alfamart_code'].','.$data['transaction_code'],
            );

            $this->code_m->insertData('pemenang_temp', $temp);
            $this->code_m->updateTempCount(1, 'more');
        }

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

            // Check - if user has been in pemenang table then they are not valid anymore
            $exist = $this->code_m->getSingleData('pemenang', 'user_id', $this->current_user->id);

            if (!$exist) {
                $temp = array(
                    'user_id' => $this->current_user->id,
                    'vendor'  => 'indomaret',
                    'code'    => $data['indomaret_code'],
                );

                $this->code_m->insertData('pemenang_temp', $temp);
                $this->code_m->updateTempCount(1, 'more');
            }

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

        $this->form_validation->set_message('_alphanumeric', 'Kode input hanya boleh berupa huruf dan angka.');

        return false;
    }

    // Temp method - should be deleted when this is not used anymore
    /*public function create_pemenang_temp()
    {
        // INSERT SETTING DATA
        $setting = array(
            'slug'        => 'pemenang_temp_count',
            'title'       => 'Pemenang Temp Table Count',
            'description' => 'Pemenang Temp Table Count',
            'type'        => 'text',
            'default'     => 0,
            'value'       => 0,
            'options'     => '',
            'is_required' => 0,
            'is_gui'      => 0,
            'module'      => '',
            'order'       => 0,
        );

        $this->code_m->insertData('settings', $setting);
        // INSERT SETTING DATA

        // CREATE default_pemenang_temp TABLE
        $sql1 = "CREATE TABLE `default_pemenang_temp` (`pemenang_temp_id` INT(11) NOT NULL AUTO_INCREMENT, `user_id` INT(11) NOT NULL, `vendor` VARCHAR(100) NOT NULL, `code` VARCHAR(100) NOT NULL, PRIMARY KEY (`pemenang_temp_id`)) ENGINE = InnoDB;";
        $this->db->query($sql1);
        // CREATE default_pemenang_temp TABLE

        // CREATE default_pemenang_temp_password TABLE
        $sql2 = "CREATE TABLE `default_pemenang_temp_password` (`slug` VARCHAR(100) NOT NULL, `password` VARCHAR(100) NOT NULL, `salt` VARCHAR(6) NOT NULL) ENGINE = InnoDB;";
        $this->db->query($sql2);

        $pass = array(
            'slug'     => 'winner_password',
            'password' => '5f038cc23de6792762d5e5769c7e201008381d57', // 43lw9rj2
            'salt'     => '7aa3fe',
        );

        $this->code_m->insertData('pemenang_temp_password', $pass);
        // CREATE default_pemenang_temp_password TABLE

        return;
    }*/
}
