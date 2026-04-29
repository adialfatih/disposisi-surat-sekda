<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $redirect_to = $this->session->userdata('hak_akses') === 'user' ? 'kurir' : 'dashboard';
            redirect($redirect_to);
        }

        if ($this->input->method(TRUE) === 'POST') {
            $this->process_login();
            return;
        }

        $this->load->view('auth/login');
    }

    private function process_login()
    {
        $this->form_validation->set_rules(
            'username',
            'Username',
            'trim|required|min_length[3]|max_length[50]|alpha_dash',
            [
                'required'    => 'Username wajib diisi.',
                'min_length'  => 'Username minimal 3 karakter.',
                'max_length'  => 'Username maksimal 50 karakter.',
                'alpha_dash'  => 'Username hanya boleh huruf, angka, underscore, dan dash.'
            ]
        );

        $this->form_validation->set_rules(
            'password',
            'Password',
            'trim|required|min_length[6]|max_length[100]',
            [
                'required'    => 'Password wajib diisi.',
                'min_length'  => 'Password minimal 6 karakter.',
                'max_length'  => 'Password maksimal 100 karakter.'
            ]
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('login_error', validation_errors('<div>', '</div>'));
            $this->session->set_flashdata('old_username', $this->input->post('username', TRUE));
            redirect('login');
        }

        $username = trim($this->input->post('username', TRUE));
        $password = (string) $this->input->post('password', TRUE);

        $user = $this->Auth_model->get_user_by_username($username);

        if (!$user) {
            $this->session->set_flashdata('login_error', 'Username tidak ditemukan.');
            $this->session->set_flashdata('old_username', $username);
            redirect('login');
        }

        if (!password_verify($password, $user->password)) {
            $this->session->set_flashdata('login_error', 'Password salah.');
            $this->session->set_flashdata('old_username', $username);
            redirect('login');
        }

        $session_data = [
            'user_id'    => $user->id,
            'nama'       => $user->nama,
            'username'   => $user->username,
            'hak_akses'  => $user->hak_akses,
            'logged_in'  => TRUE
        ];

        $this->session->set_userdata($session_data);

        if ($user->hak_akses === 'user') {
            redirect('kurir');
        }

        redirect('dashboard');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
