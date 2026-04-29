<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_admin();
        $this->load->model('User_model');
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

    public function index()
    {
        $data = [
            'page_title'    => 'Management User',
            'page_subtitle' => 'Kelola akun admin dan kurir Caraka',
            'active_menu'   => 'user_management',
            'page_js'       => 'user-management.js',
            'rows'          => $this->User_model->get_all(),
        ];

        $this->render('user_management/index', $data);
    }

    public function create()
    {
        $data = [
            'page_title'    => 'Tambah User',
            'page_subtitle' => 'Buat akun admin atau kurir Caraka',
            'active_menu'   => 'user_management',
            'mode'          => 'create',
            'row'           => null,
        ];

        $this->render('user_management/form', $data);
    }

    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('management-user');
        }

        if (!$this->validate_form()) {
            $this->render('user_management/form', [
                'page_title'    => 'Tambah User',
                'page_subtitle' => 'Buat akun admin atau kurir Caraka',
                'active_menu'   => 'user_management',
                'mode'          => 'create',
                'row'           => null,
            ]);
            return;
        }

        $username = trim($this->input->post('username', TRUE));
        if ($this->User_model->username_exists($username)) {
            $this->render('user_management/form', [
                'page_title'    => 'Tambah User',
                'page_subtitle' => 'Buat akun admin atau kurir Caraka',
                'active_menu'   => 'user_management',
                'mode'          => 'create',
                'row'           => null,
                'form_error'    => 'Username sudah digunakan.',
            ]);
            return;
        }

        $insert_data = [
            'nama'       => trim($this->input->post('nama', TRUE)),
            'username'   => $username,
            'password'   => password_hash((string) $this->input->post('password', TRUE), PASSWORD_DEFAULT),
            'hak_akses'  => trim($this->input->post('hak_akses', TRUE)),
            'is_active'  => (int) $this->input->post('is_active', TRUE),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->User_model->insert($insert_data);
        $this->session->set_flashdata('crud_success', 'Data user berhasil ditambahkan.');
        redirect('management-user');
    }

    public function edit($id = 0)
    {
        $row = $this->User_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Edit User',
            'page_subtitle' => 'Perbarui data akun pengguna',
            'active_menu'   => 'user_management',
            'mode'          => 'edit',
            'row'           => $row,
        ];

        $this->render('user_management/form', $data);
    }

    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('management-user');
        }

        $row = $this->User_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        if (!$this->validate_form($id)) {
            $this->render('user_management/form', [
                'page_title'    => 'Edit User',
                'page_subtitle' => 'Perbarui data akun pengguna',
                'active_menu'   => 'user_management',
                'mode'          => 'edit',
                'row'           => $row,
            ]);
            return;
        }

        $username = trim($this->input->post('username', TRUE));
        if ($this->User_model->username_exists($username, $id)) {
            $this->render('user_management/form', [
                'page_title'    => 'Edit User',
                'page_subtitle' => 'Perbarui data akun pengguna',
                'active_menu'   => 'user_management',
                'mode'          => 'edit',
                'row'           => $row,
                'form_error'    => 'Username sudah digunakan.',
            ]);
            return;
        }

        $update_data = [
            'nama'       => trim($this->input->post('nama', TRUE)),
            'username'   => $username,
            'hak_akses'  => trim($this->input->post('hak_akses', TRUE)),
            'is_active'  => (int) $this->input->post('is_active', TRUE),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $password = trim((string) $this->input->post('password', TRUE));
        if ($password !== '') {
            $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ((int) $id === (int) $this->session->userdata('user_id')) {
            $update_data['hak_akses'] = 'admin';
            $update_data['is_active'] = 1;
        }

        $this->User_model->update($id, $update_data);

        if ((int) $id === (int) $this->session->userdata('user_id')) {
            $this->session->set_userdata([
                'nama'      => $update_data['nama'],
                'username'  => $update_data['username'],
                'hak_akses' => $update_data['hak_akses'],
            ]);
        }

        $this->session->set_flashdata('crud_success', 'Data user berhasil diperbarui.');
        redirect('management-user');
    }

    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('management-user');
        }

        $row = $this->User_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        if ((int) $id === (int) $this->session->userdata('user_id')) {
            $this->session->set_flashdata('crud_error', 'Akun yang sedang login tidak bisa dihapus.');
            redirect('management-user');
        }

        $this->User_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Data user berhasil dihapus.');
        redirect('management-user');
    }

    private function validate_form($id = null)
    {
        $password_rules = $id ? 'trim|min_length[6]|max_length[100]' : 'trim|required|min_length[6]|max_length[100]';

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('password', 'Password', $password_rules);
        $this->form_validation->set_rules('hak_akses', 'Hak Akses', 'trim|required|in_list[admin,user]');
        $this->form_validation->set_rules('is_active', 'Status', 'trim|required|in_list[0,1]');

        return $this->form_validation->run();
    }
}
