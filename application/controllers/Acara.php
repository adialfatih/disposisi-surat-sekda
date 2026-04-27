<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acara extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Acara_model');
        $this->load->model('Surat_masuk_v2_model');
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

    // ─────────────────────────────────────────────
    // INDEX – daftar semua acara
    // ─────────────────────────────────────────────
    public function index()
    {
        $data = [
            'page_title'    => 'Agenda Acara',
            'page_subtitle' => 'Daftar acara dari surat undangan yang diterima',
            'active_menu'   => 'acara',
            'page_js'       => 'acara.js',
            'rows'          => $this->Acara_model->get_all(),
        ];

        $this->render('acara/index', $data);
    }

    // ─────────────────────────────────────────────
    // STORE via AJAX – simpan acara dari modal
    // ─────────────────────────────────────────────
    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $this->form_validation->set_rules('nomor_agenda',  'Nomor Agenda',  'trim|required|max_length[30]');
        $this->form_validation->set_rules('tanggal_acara', 'Tanggal Acara', 'trim|required');
        $this->form_validation->set_rules('jam_acara',     'Jam Acara',     'trim|required');
        $this->form_validation->set_rules('tempat_acara',  'Tempat Acara',  'trim|required|max_length[255]');
        $this->form_validation->set_rules('perihal_acara', 'Perihal Acara', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('catatan_acara', 'Catatan',       'trim');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'success' => FALSE,
                'errors'  => validation_errors(),
            ]);
            return;
        }

        $insert_data = [
            'nomor_agenda'  => trim($this->input->post('nomor_agenda', TRUE)),
            'tanggal_acara' => trim($this->input->post('tanggal_acara', TRUE)),
            'jam_acara'     => trim($this->input->post('jam_acara', TRUE)),
            'tempat_acara'  => trim($this->input->post('tempat_acara', TRUE)),
            'perihal_acara' => trim($this->input->post('perihal_acara', TRUE)),
            'catatan_acara' => trim($this->input->post('catatan_acara', TRUE)) ?: null,
            'created_by'    => (int) $this->session->userdata('user_id'),
        ];

        $this->Acara_model->insert($insert_data);

        echo json_encode([
            'success'    => TRUE,
            'message'    => 'Data acara berhasil disimpan.',
            'csrf_name'  => $this->security->get_csrf_token_name(),
            'csrf_hash'  => $this->security->get_csrf_hash(),
        ]);
    }

    // ─────────────────────────────────────────────
    // EDIT – form edit acara
    // ─────────────────────────────────────────────
    public function edit($id = 0)
    {
        $row = $this->Acara_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Edit Acara',
            'page_subtitle' => 'Perbarui data acara',
            'active_menu'   => 'acara',
            'row'           => $row,
        ];

        $this->render('acara/form', $data);
    }

    // ─────────────────────────────────────────────
    // UPDATE – simpan perubahan acara
    // ─────────────────────────────────────────────
    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('acara');
        }

        $row = $this->Acara_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->form_validation->set_rules('tanggal_acara', 'Tanggal Acara', 'trim|required');
        $this->form_validation->set_rules('jam_acara',     'Jam Acara',     'trim|required');
        $this->form_validation->set_rules('tempat_acara',  'Tempat Acara',  'trim|required|max_length[255]');
        $this->form_validation->set_rules('perihal_acara', 'Perihal Acara', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('catatan_acara', 'Catatan',       'trim');

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'    => 'Edit Acara',
                'page_subtitle' => 'Perbarui data acara',
                'active_menu'   => 'acara',
                'row'           => $row,
            ];
            $this->render('acara/form', $data);
            return;
        }

        $update_data = [
            'tanggal_acara' => trim($this->input->post('tanggal_acara', TRUE)),
            'jam_acara'     => trim($this->input->post('jam_acara', TRUE)),
            'tempat_acara'  => trim($this->input->post('tempat_acara', TRUE)),
            'perihal_acara' => trim($this->input->post('perihal_acara', TRUE)),
            'catatan_acara' => trim($this->input->post('catatan_acara', TRUE)) ?: null,
        ];

        $this->Acara_model->update($id, $update_data);
        $this->session->set_flashdata('crud_success', 'Data acara berhasil diperbarui.');
        redirect('acara');
    }

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('acara');
        }

        $row = $this->Acara_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->Acara_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Data acara berhasil dihapus.');
        redirect('acara');
    }

    // ─────────────────────────────────────────────
    // GET CSRF TOKEN (untuk refresh setelah AJAX)
    // ─────────────────────────────────────────────
    public function csrf_token()
    {
        echo json_encode([
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }
}