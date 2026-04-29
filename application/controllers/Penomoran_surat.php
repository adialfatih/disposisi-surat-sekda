<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penomoran_surat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penomoran_surat_model');
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

    private function get_jenis_surat_options()
    {
        return [
            'setda-surat-keluar' => [
                'label' => 'SETDA - Surat Keluar',
                'show_kode_umum' => FALSE
            ],
            'setda-sppd' => [
                'label' => 'SETDA - SPPD',
                'show_kode_umum' => FALSE
            ],
            'umum-surat-keluar' => [
                'label' => 'UMUM - Surat Keluar',
                'show_kode_umum' => TRUE
            ],
            'umum-sppd' => [
                'label' => 'UMUM - SPPD',
                'show_kode_umum' => TRUE
            ],
            'nota-dinas' => [
                'label' => 'NOTA DINAS',
                'show_kode_umum' => FALSE
            ],
        ];
    }

   private function validate_form($jenis_slug, $is_edit = FALSE)
    {
        $jenis_options = $this->get_jenis_surat_options();
 
        if (!isset($jenis_options[$jenis_slug])) {
            show_404();
        }
 
        $show_kode_umum = $jenis_options[$jenis_slug]['show_kode_umum'];
 
        // Kode keamanan — TIDAK wajib (opsional)
        $this->form_validation->set_rules(
            'kode_keamanan',
            'Kode Keamanan',
            'trim|max_length[20]'
        );
 
        $this->form_validation->set_rules(
            'nomor_urut',
            'Nomor Urut',
            $is_edit ? 'trim|required|integer|greater_than[0]' : 'trim|integer|greater_than[0]'
        );
 
        $this->form_validation->set_rules(
            'catatan',
            'Catatan',
            'trim'
        );
 
        $this->form_validation->set_rules(
            'kode_klasifikasi',
            'Kode Klasifikasi',
            'trim|required|max_length[50]'
        );
 
        if ($show_kode_umum) {
            $this->form_validation->set_rules(
                'kode_umum',
                'Kode Umum',
                'trim|required|max_length[50]'
            );
        }
 
        $this->form_validation->set_rules(
            'tahun',
            'Tahun',
            'trim|required|integer|greater_than_equal_to[2000]|less_than_equal_to[2100]'
        );
 
        $this->form_validation->set_rules(
            'tanggal_surat',
            'Tanggal Surat',
            'trim|required'
        );
 
        $this->form_validation->set_rules(
            'perihal',
            'Perihal',
            'trim|required|max_length[255]'
        );
 
        $this->form_validation->set_rules(
            'pengolah',
            'Pengolah',
            'trim|required|max_length[150]'
        );
 
        // No WA — wajib, hanya angka, maks 15 karakter
        $this->form_validation->set_rules(
            'no_wa_pengolah',
            'No. WA Pengolah',
            'trim|required|numeric|max_length[15]'
        );
 
        $this->form_validation->set_rules(
            'tujuan',
            'Tujuan',
            'trim|required|max_length[255]'
        );
    }

    public function index()
    {
        $data = [
            'page_title'    => 'Penomoran Surat',
            'page_subtitle' => 'Pengelolaan nomor surat Sekda Kota Pekalongan',
            'active_menu'   => 'penomoran_surat',
            'page_js'       => 'penomoran-surat.js',
            'rows'          => $this->Penomoran_surat_model->get_all()
        ];

        $this->render('penomoran_surat/index', $data);
    }

    public function create($jenis_slug = '')
    {
        $jenis_options = $this->get_jenis_surat_options();

        if (!isset($jenis_options[$jenis_slug])) {
            show_404();
        }

        $data = [
            'page_title'        => 'Tambah Nomor Surat',
            'page_subtitle'     => 'Form input penomoran surat',
            'active_menu'       => 'penomoran_surat',
            'jenis_surat_slug'  => $jenis_slug,
            'jenis_surat_label' => $jenis_options[$jenis_slug]['label'],
            'show_kode_umum'    => $jenis_options[$jenis_slug]['show_kode_umum'],
            'mode'              => 'create',
            'row'               => null
        ];

        $this->render('penomoran_surat/form', $data);
    }
public function get_csrf_token()
{
    echo json_encode([
        'csrf_name' => $this->security->get_csrf_token_name(),
        'csrf_hash' => $this->security->get_csrf_hash(),
    ]);
}
    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('penomoran-surat');
        }

        $jenis_slug = $this->input->post('jenis_surat_slug', TRUE);
        $jenis_options = $this->get_jenis_surat_options();

        if (!isset($jenis_options[$jenis_slug])) {
            show_404();
        }

        $this->validate_form($jenis_slug, FALSE);

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'        => 'Tambah Nomor Surat',
                'page_subtitle'     => 'Form input penomoran surat',
                'active_menu'       => 'penomoran_surat',
                'jenis_surat_slug'  => $jenis_slug,
                'jenis_surat_label' => $jenis_options[$jenis_slug]['label'],
                'show_kode_umum'    => $jenis_options[$jenis_slug]['show_kode_umum'],
                'mode'              => 'create',
                'row'               => null
            ];

            $this->render('penomoran_surat/form', $data);
            return;
        }

        $insert_data = [
            'jenis_surat_slug'  => $jenis_slug,
            'jenis_surat_label' => $jenis_options[$jenis_slug]['label'],
            'kode_keamanan'     => trim($this->input->post('kode_keamanan', TRUE)),
            'nomor_urut'        => 0,
            'catatan'           => trim($this->input->post('catatan', TRUE)),
            'kode_klasifikasi'  => trim($this->input->post('kode_klasifikasi', TRUE)),
            'kode_umum'         => $jenis_options[$jenis_slug]['show_kode_umum'] ? trim($this->input->post('kode_umum', TRUE)) : null,
            'tahun'             => (int) $this->input->post('tahun', TRUE),
            'tanggal_surat'     => trim($this->input->post('tanggal_surat', TRUE)),
            'perihal'           => trim($this->input->post('perihal', TRUE)),
            'pengolah'          => trim($this->input->post('pengolah', TRUE)),
            'no_wa_pengolah'    => trim($this->input->post('no_wa_pengolah', TRUE)),
            'tujuan'            => trim($this->input->post('tujuan', TRUE)),
            'created_by'        => (int) $this->session->userdata('user_id'),
        ];

        $insert_data['nomor_urut'] = $this->Penomoran_surat_model->get_next_nomor_urut(
            $insert_data['jenis_surat_slug'],
            $insert_data['tahun'],
            $insert_data['tanggal_surat']
        );

        if ($insert_data['nomor_urut'] === null) {
            $this->session->set_flashdata('crud_error', 'Kuota nomor surat untuk jenis dan tanggal tersebut sudah penuh (maksimal 100 nomor per hari).');
            redirect('penomoran-surat/create/' . $jenis_slug);
        }

        $exists = $this->Penomoran_surat_model->check_duplicate_nomor(
            $insert_data['jenis_surat_slug'],
            $insert_data['tahun'],
            $insert_data['nomor_urut']
        );

        if ($exists) {
            $this->session->set_flashdata('crud_error', 'Nomor urut untuk jenis surat dan tahun tersebut sudah digunakan.');
            redirect('penomoran-surat/create/' . $jenis_slug);
        }
        $insert_data['nomor_surat'] = $this->Penomoran_surat_model->generate_nomor_surat(
            $insert_data['jenis_surat_slug'],
            $insert_data['nomor_urut'],
            $insert_data['kode_klasifikasi'],
            $insert_data['kode_keamanan'],
            $insert_data['kode_umum'],
            $insert_data['tahun']
        );
        $this->Penomoran_surat_model->insert($insert_data);
        $this->session->set_flashdata('crud_success', 'Data penomoran surat berhasil disimpan.');
        redirect('penomoran-surat');
    }

    public function detail($id = 0)
    {
        $row = $this->Penomoran_surat_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Detail Nomor Surat',
            'page_subtitle' => 'Informasi lengkap penomoran surat',
            'active_menu'   => 'penomoran_surat',
            'row'           => $row
        ];

        $this->render('penomoran_surat/detail', $data);
    }

    public function edit($id = 0)
    {
        $row = $this->Penomoran_surat_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $jenis_options = $this->get_jenis_surat_options();

        $data = [
            'page_title'        => 'Edit Nomor Surat',
            'page_subtitle'     => 'Perbarui data penomoran surat',
            'active_menu'       => 'penomoran_surat',
            'jenis_surat_slug'  => $row->jenis_surat_slug,
            'jenis_surat_label' => $row->jenis_surat_label,
            'show_kode_umum'    => $jenis_options[$row->jenis_surat_slug]['show_kode_umum'],
            'mode'              => 'edit',
            'row'               => $row
        ];

        $this->render('penomoran_surat/form', $data);
    }

    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('penomoran-surat');
        }

        $row = $this->Penomoran_surat_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $jenis_slug = $row->jenis_surat_slug;
        $jenis_options = $this->get_jenis_surat_options();

        $this->validate_form($jenis_slug, TRUE);

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'        => 'Edit Nomor Surat',
                'page_subtitle'     => 'Perbarui data penomoran surat',
                'active_menu'       => 'penomoran_surat',
                'jenis_surat_slug'  => $row->jenis_surat_slug,
                'jenis_surat_label' => $row->jenis_surat_label,
                'show_kode_umum'    => $jenis_options[$row->jenis_surat_slug]['show_kode_umum'],
                'mode'              => 'edit',
                'row'               => $row
            ];

            $this->render('penomoran_surat/form', $data);
            return;
        }

        // $update_data = [
        //     'kode_keamanan'    => trim($this->input->post('kode_keamanan', TRUE)),
        //     'nomor_urut'       => (int) $this->input->post('nomor_urut', TRUE),
        //     'catatan'          => trim($this->input->post('catatan', TRUE)),
        //     'kode_klasifikasi' => trim($this->input->post('kode_klasifikasi', TRUE)),
        //     'kode_umum'        => $jenis_options[$jenis_slug]['show_kode_umum'] ? trim($this->input->post('kode_umum', TRUE)) : null,
        //     'tahun'            => (int) $this->input->post('tahun', TRUE),
        //     'tanggal_surat'    => trim($this->input->post('tanggal_surat', TRUE)),
        //     'perihal'          => trim($this->input->post('perihal', TRUE)),
        //     'pengolah'         => trim($this->input->post('pengolah', TRUE)),
        //     'tujuan'           => trim($this->input->post('tujuan', TRUE))
        // ];
        $update_data = [
            'kode_keamanan'    => trim($this->input->post('kode_keamanan', TRUE)),
            'nomor_urut'       => (int) $this->input->post('nomor_urut', TRUE),
            'catatan'          => trim($this->input->post('catatan', TRUE)),
            'kode_klasifikasi' => trim($this->input->post('kode_klasifikasi', TRUE)),
            'kode_umum'        => $jenis_options[$jenis_slug]['show_kode_umum'] ? trim($this->input->post('kode_umum', TRUE)) : null,
            'tahun'            => (int) $this->input->post('tahun', TRUE),
            'tanggal_surat'    => trim($this->input->post('tanggal_surat', TRUE)),
            'perihal'          => trim($this->input->post('perihal', TRUE)),
            'pengolah'         => trim($this->input->post('pengolah', TRUE)),
            'no_wa_pengolah'   => trim($this->input->post('no_wa_pengolah', TRUE)),
            'tujuan'           => trim($this->input->post('tujuan', TRUE)),
        ];

        $exists = $this->Penomoran_surat_model->check_duplicate_nomor(
            $jenis_slug,
            $update_data['tahun'],
            $update_data['nomor_urut'],
            $id
        );

        if ($exists) {
            $this->session->set_flashdata('crud_error', 'Nomor urut untuk jenis surat dan tahun tersebut sudah digunakan.');
            redirect('penomoran-surat/edit/' . $id);
        }

        $update_data['nomor_surat'] = $this->Penomoran_surat_model->generate_nomor_surat(
            $jenis_slug,
            $update_data['nomor_urut'],
            $update_data['kode_klasifikasi'],
            $update_data['kode_keamanan'],
            $update_data['kode_umum'],
            $update_data['tahun']
        );

        $this->Penomoran_surat_model->update($id, $update_data);
        $this->session->set_flashdata('crud_success', 'Data penomoran surat berhasil diperbarui.');
        redirect('penomoran-surat');
    }

    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('penomoran-surat');
        }

        $row = $this->Penomoran_surat_model->get_by_id($id);

        if (!$row) {
            show_404();
        }

        $this->Penomoran_surat_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Data penomoran surat berhasil dihapus.');
        redirect('penomoran-surat');
    }
    public function get_next_nomor_urut()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
        }

        $jenis_slug = $this->input->post('jenis_surat_slug', TRUE);
        $tahun      = (int) $this->input->post('tahun', TRUE);
        $tanggal_surat = $this->input->post('tanggal_surat', TRUE);

        $jenis_options = $this->get_jenis_surat_options();

        if (!isset($jenis_options[$jenis_slug]) || $tahun < 2000 || empty($tanggal_surat)) {
            echo json_encode(['success' => FALSE, 'next_nomor' => 1]);
            return;
        }

        $next = $this->Penomoran_surat_model->get_next_nomor_urut($jenis_slug, $tahun, $tanggal_surat);
        $bounds = $this->Penomoran_surat_model->get_daily_nomor_bounds($tanggal_surat);

        echo json_encode([
            'success'    => ($next !== null),
            'next_nomor' => $next,
            'range'      => $bounds,
        ]);
    }
}
