<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    protected function check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $hak_akses = $this->session->userdata('hak_akses');
        $controller = strtolower($this->router->fetch_class());

        if ($hak_akses === 'user' && $controller !== 'kurir') {
            redirect('kurir');
        }
    }

    protected function require_admin()
    {
        if ($this->session->userdata('hak_akses') !== 'admin') {
            show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses Ditolak');
        }
    }
}
