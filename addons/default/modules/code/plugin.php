<?php defined('BASEPATH') or exit('No direct script access allowed');

class Plugin_code extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en' => 'Code Plugin.',
        'id' => 'Plugin Kode.',
    );

    public $description = array(
        'en' => 'Code Plugin.',
        'id' => 'Plugin Kode.',
    );

    public function code_page()
    {
        return $this->load->view('code/plugin/code', array(), true);
    }
}
