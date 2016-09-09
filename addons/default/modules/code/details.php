<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_code extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name'        => array(
                'en' => 'Coke Tune Code',
                'id' => 'Kode Coke Tune'
            ),
            'description' => array(
                'en' => 'Manage Coke Tune Code',
                'id' => 'Manajemen Kode Coke Tune'
            ),
            'frontend'    => true,
            'backend'     => true,
            'skip_xss'    => true,
            'menu'        => '',
            'sections'    => array(
                'alfamart' => array(
                    'name' => 'code:alfamart_title',
                    'uri'  => ADMIN_URL.'/code/alfamart'
                ),
                'indomaret' => array(
                    'name' => 'code:indomaret_title',
                    'uri'  => ADMIN_URL.'/code/indomaret'
                ),
                'pemenang' => array(
                    'name' => 'code:pemenang_title',
                    'uri'  => ADMIN_URL.'/code/daftar_pemenang'
                )
            )
        );

        return $info;
    }

    public function admin_menu(&$menu)
    {
        $this->lang->load('code/code');
        $menu[lang('code:main_menu')] = ADMIN_URL.'/code';
        add_admin_menu_place(lang('code:main_menu'), 2);
    }

    public function install()
    {
        $alfamart_code_fields = array(
            'code_id'          => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
            'user_id'          => array('type' => 'INT', 'constraint' => 11),
            'vendor'           => array('type' => 'VARCHAR', 'constraint' => 100),
            'unique_code'      => array('type' => 'VARCHAR', 'constraint' => 100),
            'transaction_code' => array('type' => 'VARCHAR', 'constraint' => 100),
            'date_created'     => array('type' => 'DATETIME'),
        );

        $alfamart_fail_fields = array(
            'fail_id'               => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
            'user_id'               => array('type' => 'INT', 'constraint' => 11),
            'ip_address'            => array('type' => 'VARCHAR', 'constraint' => 100),
            'fail_unique_code'      => array('type' => 'VARCHAR', 'constraint' => 100),
            'fail_transaction_code' => array('type' => 'VARCHAR', 'constraint' => 100),
            'date_failed'           => array('type' => 'DATETIME'),
        );

        $indomaret_code_fields = array(
            'code_id'      => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
            'user_id'      => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
            'code'         => array('type' => 'VARCHAR', 'constraint' => 100),
            'is_used'      => array('type' => 'SMALLINT', 'constraint' => 6, 'default' => 0),
            'date_used'    => array('type' => 'DATETIME', 'default' => '0000-00-00 00:00:00'),
            'date_created' => array('type' => 'DATETIME'),
        );

        $indomaret_fail_fields = array(
            'fail_id'     => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
            'user_id'     => array('type' => 'INT', 'constraint' => 11),
            'ip_address'  => array('type' => 'VARCHAR', 'constraint' => 100),
            'fail_code'   => array('type' => 'VARCHAR', 'constraint' => 100),
            'date_failed' => array('type' => 'DATETIME'),
        );

        $this->install_tables(array(
            'alfamart_code'  => $alfamart_code_fields,
            'alfamart_fail'  => $alfamart_fail_fields,
            'indomaret_code' => $indomaret_code_fields,
            'indomaret_fail' => $indomaret_fail_fields,
        ));

        // Add UNIQUE index
        $this->db->query('ALTER TABLE default_indomaret_code ADD UNIQUE INDEX (code)');

        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }
}
