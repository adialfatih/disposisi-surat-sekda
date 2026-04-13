<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    private function render($view, $data = [])
    {
        $data['session_nama'] = $this->session->userdata('nama');
        $data['session_username'] = $this->session->userdata('username');
        $data['session_hak_akses'] = $this->session->userdata('hak_akses');

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer', $data);
    }

    public function index()
    {
        $data = [
            'page_title'    => 'Dashboard',
            'page_subtitle' => 'Ringkasan aktivitas persuratan Sekda Kota Pekalongan',
            'active_menu'   => 'dashboard'
        ];

        $this->render('dashboard/index', $data);
    }

    public function penomoran_surat()
    {
        $data = [
            'page_title'    => 'Penomoran Surat',
            'page_subtitle' => 'Pengelolaan nomor surat keluar Sekda Kota Pekalongan',
            'active_menu'   => 'penomoran_surat'
        ];

        $this->render('penomoran_surat/index', $data);
    }

    public function agenda_disposisi()
    {
        $data = [
            'page_title'    => 'Agenda Disposisi',
            'page_subtitle' => 'Monitoring agenda disposisi surat pimpinan',
            'active_menu'   => 'agenda_disposisi'
        ];

        $this->render('disposisi/index', $data);
    }

    public function agenda_surat_masuk()
    {
        $data = [
            'page_title'    => 'Agenda Surat Masuk',
            'page_subtitle' => 'Pencatatan dan monitoring surat masuk',
            'active_menu'   => 'agenda_surat_masuk'
        ];

        $this->render('surat_masuk/index', $data);
    }
}