<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Surat_masuk_model');
    }

    private function render($view, $data = [])
    {
        $data['session_nama']      = $this->session->userdata('nama');
        $data['session_username']  = $this->session->userdata('username');
        $data['session_hak_akses'] = $this->session->userdata('hak_akses');

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer', $data);
    }

    private function get_kategori_options()
    {
        return [
            'permohonan' => 'Permohonan',
            'undangan'   => 'Undangan',
            'lainnya'    => 'Lainnya',
        ];
    }

    private function get_status_options()
    {
        return [
            'masuk'       => 'Masuk',
            'didisposisi' => 'Didisposisi',
            'selesai'     => 'Selesai',
        ];
    }

    private function validate_form()
    {
        $this->form_validation->set_rules('kategori',       'Kategori',       'trim|required|in_list[permohonan,undangan,lainnya]');
        $this->form_validation->set_rules('nomor_agenda',   'Nomor Agenda',   'trim|required|max_length[30]');
        $this->form_validation->set_rules('asal_surat',     'Asal Surat',     'trim|required|max_length[255]');
        $this->form_validation->set_rules('tanggal_surat',  'Tanggal Surat',  'trim|required');
        $this->form_validation->set_rules('nomor_surat',    'Nomor Surat',    'trim|required|max_length[100]');
        $this->form_validation->set_rules('perihal',        'Perihal',        'trim|required|max_length[255]');
        $this->form_validation->set_rules('asal_berkas',    'Asal Berkas',    'trim|max_length[150]');
        $this->form_validation->set_rules('tanggal_terima', 'Tanggal Terima', 'trim|required');
        $this->form_validation->set_rules('catatan',        'Catatan',        'trim');
        $this->form_validation->set_rules('status',         'Status',         'trim|required|in_list[masuk,didisposisi,selesai]');
    }
    
    public function get_next_nomor_agenda()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $tahun = (int) $this->input->post('tahun', TRUE);

        if ($tahun < 2000) {
            echo json_encode(['success' => FALSE, 'next_nomor' => 'SM-' . date('Y') . '-001']);
            return;
        }

        $next = $this->Surat_masuk_model->get_next_nomor_ajax($tahun);
        echo json_encode(['success' => TRUE, 'next_nomor' => $next]);
    }

    public function index()
    {
        $data = [
            'page_title'    => 'Agenda Surat Masuk',
            'page_subtitle' => 'Pencatatan dan pengelolaan surat masuk Sekda Kota Pekalongan',
            'active_menu'   => 'agenda_surat_masuk',
            'page_js'       => 'surat-masuk.js',
            'rows'          => $this->Surat_masuk_model->get_all(),
        ];

        $this->render('surat_masuk/index', $data);
    }

    public function create()
    {
        $data = [
            'page_title'        => 'Tambah Surat Masuk',
            'page_subtitle'     => 'Form input agenda surat masuk baru',
            'active_menu'       => 'agenda_surat_masuk',
            'kategori_options'  => $this->get_kategori_options(),
            'status_options'    => $this->get_status_options(),
            'mode'              => 'create',
            'row'               => null,
        ];

        $this->render('surat_masuk/form', $data);
    }

    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk');
        }

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'        => 'Tambah Surat Masuk',
                'page_subtitle'     => 'Form input agenda surat masuk baru',
                'active_menu'       => 'agenda_surat_masuk',
                'kategori_options'  => $this->get_kategori_options(),
                'status_options'    => $this->get_status_options(),
                'mode'              => 'create',
                'row'               => null,
            ];

            $this->render('surat_masuk/form', $data);
            return;
        }

        // Cek duplikasi nomor agenda
        $nomor_agenda = trim($this->input->post('nomor_agenda', TRUE));
        if ($this->Surat_masuk_model->check_duplicate_agenda($nomor_agenda)) {
            $this->session->set_flashdata('crud_error', 'Nomor agenda tersebut sudah digunakan.');
            redirect('surat-masuk/create');
        }

        $insert_data = [
            'kategori'        => trim($this->input->post('kategori', TRUE)),
            'nomor_agenda'    => $nomor_agenda,
            'asal_surat'      => trim($this->input->post('asal_surat', TRUE)),
            'tanggal_surat'   => trim($this->input->post('tanggal_surat', TRUE)),
            'nomor_surat'     => trim($this->input->post('nomor_surat', TRUE)),
            'perihal'         => trim($this->input->post('perihal', TRUE)),
            'asal_berkas'     => trim($this->input->post('asal_berkas', TRUE)),
            'tanggal_terima'  => trim($this->input->post('tanggal_terima', TRUE)),
            'catatan'         => trim($this->input->post('catatan', TRUE)),
            'status'          => trim($this->input->post('status', TRUE)),
            'created_by'      => (int) $this->session->userdata('user_id'),
        ];

        $this->Surat_masuk_model->insert($insert_data);
        $this->session->set_flashdata('crud_success', 'Surat masuk berhasil dicatat.');
        redirect('surat-masuk');
    }

    public function detail($id = 0)
    {
        $row = $this->Surat_masuk_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Detail Surat Masuk',
            'page_subtitle' => 'Informasi lengkap surat masuk',
            'active_menu'   => 'agenda_surat_masuk',
            'row'           => $row,
        ];

        $this->render('surat_masuk/detail', $data);
    }

    public function edit($id = 0)
    {
        $row = $this->Surat_masuk_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'        => 'Edit Surat Masuk',
            'page_subtitle'     => 'Perbarui data agenda surat masuk',
            'active_menu'       => 'agenda_surat_masuk',
            'kategori_options'  => $this->get_kategori_options(),
            'status_options'    => $this->get_status_options(),
            'mode'              => 'edit',
            'row'               => $row,
        ];

        $this->render('surat_masuk/form', $data);
    }

    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk');
        }

        $row = $this->Surat_masuk_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'        => 'Edit Surat Masuk',
                'page_subtitle'     => 'Perbarui data agenda surat masuk',
                'active_menu'       => 'agenda_surat_masuk',
                'kategori_options'  => $this->get_kategori_options(),
                'status_options'    => $this->get_status_options(),
                'mode'              => 'edit',
                'row'               => $row,
            ];

            $this->render('surat_masuk/form', $data);
            return;
        }

        $nomor_agenda = trim($this->input->post('nomor_agenda', TRUE));
        if ($this->Surat_masuk_model->check_duplicate_agenda($nomor_agenda, $id)) {
            $this->session->set_flashdata('crud_error', 'Nomor agenda tersebut sudah digunakan.');
            redirect('surat-masuk/edit/' . $id);
        }

        $update_data = [
            'kategori'        => trim($this->input->post('kategori', TRUE)),
            'nomor_agenda'    => $nomor_agenda,
            'asal_surat'      => trim($this->input->post('asal_surat', TRUE)),
            'tanggal_surat'   => trim($this->input->post('tanggal_surat', TRUE)),
            'nomor_surat'     => trim($this->input->post('nomor_surat', TRUE)),
            'perihal'         => trim($this->input->post('perihal', TRUE)),
            'asal_berkas'     => trim($this->input->post('asal_berkas', TRUE)),
            'tanggal_terima'  => trim($this->input->post('tanggal_terima', TRUE)),
            'catatan'         => trim($this->input->post('catatan', TRUE)),
            'status'          => trim($this->input->post('status', TRUE)),
        ];

        $this->Surat_masuk_model->update($id, $update_data);
        $this->session->set_flashdata('crud_success', 'Data surat masuk berhasil diperbarui.');
        redirect('surat-masuk');
    }

    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk');
        }

        $row = $this->Surat_masuk_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $this->Surat_masuk_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Data surat masuk berhasil dihapus.');
        redirect('surat-masuk');
    }
}