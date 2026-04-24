<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposisi_surat extends MY_Controller
{
    protected $upload_path_ttd  = 'uploads/ttd/';
    protected $upload_path_foto = 'uploads/bukti/';

    /**
     * Konversi path DB (uploads/ttd/file.png) ke URL browser yang benar.
     * Mengatasi masalah CI ada di subfolder tapi uploads/ di document root.
     */
    private function upload_url($db_path)
    {
        if (empty($db_path)) return '';
        $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'];
        $doc_root = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/') . '/';
        $fcpath   = rtrim(str_replace('\\', '/', realpath(FCPATH)), '/') . '/';
        // Relative path CI app dari document root
        $rel_app  = ltrim(str_replace($doc_root, '', $fcpath), '/');
        // Naik ke document root dari subfolder CI
        $parts    = array_filter(explode('/', rtrim($rel_app, '/')));
        $up       = count($parts) > 0 ? str_repeat('../', count($parts)) : '';
        // Hitung URL uploads relatif terhadap document root
        return $scheme . '://' . $host . '/' . ltrim($db_path, '/');
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Disposisi_surat_model');
        $this->load->library('upload');
        $this->load->helper('upload'); // application/helpers/upload_helper.php
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

    // private function validate_form()
    // {
    //     $this->form_validation->set_rules('surat_masuk_id',    'Surat Masuk',        'trim|required|integer');
    //     $this->form_validation->set_rules('nomor_disposisi',   'Nomor Disposisi',    'trim|required|max_length[30]');
    //     $this->form_validation->set_rules('tanggal_disposisi', 'Tanggal Disposisi',  'trim|required');
    //     $this->form_validation->set_rules('perintah',          'Perintah',           'trim|required');
    //     $this->form_validation->set_rules('catatan',           'Catatan',            'trim');
    //     $this->form_validation->set_rules('status',            'Status',             'trim|required|in_list[draft,dikirim,diterima,selesai]');
    // }
    private function validate_form()
    {
        $this->form_validation->set_rules('nomor_disposisi',   'Nomor Disposisi',   'trim|required|max_length[30]');
        $this->form_validation->set_rules('tanggal_disposisi', 'Tanggal Disposisi', 'trim|required');
        $this->form_validation->set_rules('perintah',          'Perintah',          'trim|required');
        $this->form_validation->set_rules('catatan',           'Catatan',           'trim');
        $this->form_validation->set_rules('status',            'Status',            'trim|required|in_list[draft,dikirim,diterima,selesai]');
        $this->form_validation->set_rules('mode_surat',        'Mode Surat',        'trim|required|in_list[agenda,manual]');

        $mode = $this->input->post('mode_surat');
        if ($mode === 'agenda') {
            $this->form_validation->set_rules('surat_masuk_id', 'Surat Masuk', 'trim|required|integer');
        } else {
            $this->form_validation->set_rules('manual_nomor_surat',  'Nomor Surat',  'trim|required|max_length[100]');
            $this->form_validation->set_rules('manual_perihal',      'Perihal',      'trim|required|max_length[255]');
            $this->form_validation->set_rules('manual_asal_berkas',  'Asal Berkas',  'trim|max_length[150]');
        }
    }

    // Simpan file tanda tangan dari base64
    private function save_signature($base64_string, $prefix = 'ttd')
    {
        if (empty($base64_string)) return null;

        // Strip header base64
        if (strpos($base64_string, ',') !== FALSE) {
            $base64_string = explode(',', $base64_string)[1];
        }

        $decoded = base64_decode($base64_string);
        if (!$decoded) return null;

        $dir = FCPATH . $this->upload_path_ttd;
        if (!is_dir($dir)) mkdir($dir, 0755, TRUE);

        $filename = $prefix . '_' . uniqid() . '.png';
        file_put_contents($dir . $filename, $decoded);

        return $this->upload_path_ttd . $filename;
    }

    // Upload foto bukti
    private function upload_foto($field_name, $prefix = 'bukti')
    {
        if (empty($_FILES[$field_name]['name'])) return null;

        $dir = FCPATH . $this->upload_path_foto;
        if (!is_dir($dir)) mkdir($dir, 0755, TRUE);

        $config = [
            'upload_path'   => $dir,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size'      => 5120, // 5MB
            'file_name'     => $prefix . '_' . uniqid(),
        ];

        $this->upload->initialize($config);

        if ($this->upload->do_upload($field_name)) {
            return $this->upload_path_foto . $this->upload->data('file_name');
        }

        return null;
    }

    // Hapus file dari server
    private function delete_file($path)
    {
        if ($path && file_exists(FCPATH . $path)) {
            unlink(FCPATH . $path);
        }
    }

    // ----------------------------------------------------------------
    // CRUD DISPOSISI
    // ----------------------------------------------------------------

    public function index()
    {
        $data = [
            'page_title'    => 'Agenda Disposisi',
            'page_subtitle' => 'Manajemen disposisi dan tracking pengiriman surat',
            'active_menu'   => 'agenda_disposisi',
            'page_js'       => 'disposisi-surat.js',
            'rows'          => $this->Disposisi_surat_model->get_all(),
        ];

        $this->render('disposisi_surat/index', $data);
    }

    public function create()
    {
        $data = [
            'page_title'       => 'Buat Disposisi',
            'page_subtitle'    => 'Form input disposisi surat masuk',
            'active_menu'      => 'agenda_disposisi',
            'page_js'          => 'disposisi-surat.js',
            'mode'             => 'create',
            'row'              => null,
            'penerima_list'    => [],
            'surat_options'    => $this->Disposisi_surat_model->get_surat_masuk_options(),
        ];

        $this->render('disposisi_surat/form', $data);
    }

    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('disposisi-surat');

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'    => 'Buat Disposisi',
                'page_subtitle' => 'Form input disposisi surat masuk',
                'active_menu'   => 'agenda_disposisi',
                'page_js'       => 'disposisi-surat.js',
                'mode'          => 'create',
                'row'           => null,
                'penerima_list' => [],
                'surat_options' => $this->Disposisi_surat_model->get_surat_masuk_options(),
            ];
            $this->render('disposisi_surat/form', $data);
            return;
        }

        $nomor = trim($this->input->post('nomor_disposisi', TRUE));
        if ($this->Disposisi_surat_model->check_duplicate_nomor($nomor)) {
            $this->session->set_flashdata('crud_error', 'Nomor disposisi sudah digunakan.');
            redirect('disposisi-surat/create');
        }

        // Simpan header disposisi
        // $insert = [
        //     'surat_masuk_id'    => (int) $this->input->post('surat_masuk_id', TRUE),
        //     'nomor_disposisi'   => $nomor,
        //     'tanggal_disposisi' => trim($this->input->post('tanggal_disposisi', TRUE)),
        //     'perintah'          => trim($this->input->post('perintah', TRUE)),
        //     'catatan'           => trim($this->input->post('catatan', TRUE)),
        //     'status'            => trim($this->input->post('status', TRUE)),
        //     'created_by'        => (int) $this->session->userdata('user_id'),
        // ];
        $mode = trim($this->input->post('mode_surat', TRUE));

        $insert = [
            'nomor_disposisi'   => trim($this->input->post('nomor_disposisi', TRUE)),
            'surat_masuk_id'    => $mode === 'agenda' ? (int) $this->input->post('surat_masuk_id', TRUE) : NULL,
            'mode_surat'        => $mode,
            'manual_asal_berkas'=> $mode === 'manual' ? trim($this->input->post('manual_asal_berkas', TRUE)) : NULL,
            'manual_nomor_surat'=> $mode === 'manual' ? trim($this->input->post('manual_nomor_surat', TRUE)) : NULL,
            'manual_perihal'    => $mode === 'manual' ? trim($this->input->post('manual_perihal', TRUE)) : NULL,
            'tanggal_disposisi' => trim($this->input->post('tanggal_disposisi', TRUE)),
            'perintah'          => trim($this->input->post('perintah', TRUE)),
            'catatan'           => trim($this->input->post('catatan', TRUE)),
            'status'            => trim($this->input->post('status', TRUE)),
            'created_by'        => (int) $this->session->userdata('user_id'),
        ];

        $disposisi_id = $this->Disposisi_surat_model->insert($insert);

        // Simpan daftar penerima
        $penerima_list = $this->input->post('penerima', TRUE);
        if (!empty($penerima_list) && is_array($penerima_list)) {
            foreach ($penerima_list as $nama) {
                $nama = trim($nama);
                if ($nama !== '') {
                    $this->Disposisi_surat_model->insert_penerima([
                        'disposisi_id'  => $disposisi_id,
                        'nama_penerima' => $nama,
                        'status_kirim'  => 'belum',
                        'status_terima' => 'belum',
                    ]);
                }
            }
        }

        // Update status surat masuk menjadi didisposisi
        if ($mode === 'agenda' && !empty($insert['surat_masuk_id'])) {
            $this->db->where('id', (int) $insert['surat_masuk_id'])
                    ->update('surat_masuk', ['status' => 'didisposisi']);
        }
        // $this->db->where('id', (int) $insert['surat_masuk_id'])
        //          ->update('surat_masuk', ['status' => 'didisposisi']);

        $this->session->set_flashdata('crud_success', 'Disposisi berhasil dibuat.');
        redirect('disposisi-surat');
    }

    public function detail($id = 0)
    {
        $row = $this->Disposisi_surat_model->get_by_id($id);
        if (!$row) show_404();

        $data = [
            'page_title'    => 'Detail Disposisi',
            'page_subtitle' => 'Informasi lengkap dan tracking disposisi',
            'active_menu'   => 'agenda_disposisi',
            'page_js'       => 'disposisi-surat.js',
            'row'           => $row,
            'penerima_list' => $this->Disposisi_surat_model->get_penerima($id),
        ];

        $this->render('disposisi_surat/detail', $data);
    }

    public function edit($id = 0)
    {
        $row = $this->Disposisi_surat_model->get_by_id($id);
        if (!$row) show_404();

        $data = [
            'page_title'    => 'Edit Disposisi',
            'page_subtitle' => 'Perbarui data disposisi',
            'active_menu'   => 'agenda_disposisi',
            'page_js'       => 'disposisi-surat.js',
            'mode'          => 'edit',
            'row'           => $row,
            'penerima_list' => $this->Disposisi_surat_model->get_penerima($id),
            'surat_options' => $this->Disposisi_surat_model->get_surat_masuk_options(),
        ];

        $this->render('disposisi_surat/form', $data);
    }

    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('disposisi-surat');

        $row = $this->Disposisi_surat_model->get_by_id($id);
        if (!$row) show_404();

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'    => 'Edit Disposisi',
                'page_subtitle' => 'Perbarui data disposisi',
                'active_menu'   => 'agenda_disposisi',
                'page_js'       => 'disposisi-surat.js',
                'mode'          => 'edit',
                'row'           => $row,
                'penerima_list' => $this->Disposisi_surat_model->get_penerima($id),
                'surat_options' => $this->Disposisi_surat_model->get_surat_masuk_options(),
            ];
            $this->render('disposisi_surat/form', $data);
            return;
        }

        $nomor = trim($this->input->post('nomor_disposisi', TRUE));
        if ($this->Disposisi_surat_model->check_duplicate_nomor($nomor, $id)) {
            $this->session->set_flashdata('crud_error', 'Nomor disposisi sudah digunakan.');
            redirect('disposisi-surat/edit/' . $id);
        }
        $mode = trim($this->input->post('mode_surat', TRUE));

        $update_data = [
            'surat_masuk_id'    => $mode === 'agenda' ? (int) $this->input->post('surat_masuk_id', TRUE) : NULL,
            'mode_surat'        => $mode,
            'manual_asal_berkas'=> $mode === 'manual' ? trim($this->input->post('manual_asal_berkas', TRUE)) : NULL,
            'manual_nomor_surat'=> $mode === 'manual' ? trim($this->input->post('manual_nomor_surat', TRUE)) : NULL,
            'manual_perihal'    => $mode === 'manual' ? trim($this->input->post('manual_perihal', TRUE)) : NULL,
            'nomor_disposisi'   => $nomor,
            'tanggal_disposisi' => trim($this->input->post('tanggal_disposisi', TRUE)),
            'perintah'          => trim($this->input->post('perintah', TRUE)),
            'catatan'           => trim($this->input->post('catatan', TRUE)),
            'status'            => trim($this->input->post('status', TRUE)),
        ];

        $this->Disposisi_surat_model->update($id, $update_data);

        // $this->Disposisi_surat_model->update($id, [
        //     'surat_masuk_id'    => (int) $this->input->post('surat_masuk_id', TRUE),
        //     'nomor_disposisi'   => $nomor,
        //     'tanggal_disposisi' => trim($this->input->post('tanggal_disposisi', TRUE)),
        //     'perintah'          => trim($this->input->post('perintah', TRUE)),
        //     'catatan'           => trim($this->input->post('catatan', TRUE)),
        //     'status'            => trim($this->input->post('status', TRUE)),
        // ]);

        // Sinkronisasi penerima: hapus lama, insert baru
        // (Hanya penerima yang belum ada tracking-nya yang boleh dihapus)
        $existing   = $this->Disposisi_surat_model->get_penerima($id);
        $existing_ids = array_column($existing, 'id');

        // Hapus penerima yang tidak ada tracking
        foreach ($existing as $p) {
            if ($p->status_kirim === 'belum' && $p->status_terima === 'belum') {
                $this->Disposisi_surat_model->delete_penerima($p->id);
            }
        }

        // Insert penerima baru
        $penerima_list = $this->input->post('penerima', TRUE);
        if (!empty($penerima_list) && is_array($penerima_list)) {
            foreach ($penerima_list as $nama) {
                $nama = trim($nama);
                if ($nama !== '') {
                    // Cek apakah sudah ada
                    $ada = FALSE;
                    foreach ($existing as $p) {
                        if ($p->nama_penerima === $nama) { $ada = TRUE; break; }
                    }
                    if (!$ada) {
                        $this->Disposisi_surat_model->insert_penerima([
                            'disposisi_id'  => $id,
                            'nama_penerima' => $nama,
                            'status_kirim'  => 'belum',
                            'status_terima' => 'belum',
                        ]);
                    }
                }
            }
        }

        $this->session->set_flashdata('crud_success', 'Data disposisi berhasil diperbarui.');
        redirect('disposisi-surat');
    }

    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('disposisi-surat');

        $row = $this->Disposisi_surat_model->get_by_id($id);
        if (!$row) show_404();

        // Hapus semua file terkait
        $penerima = $this->Disposisi_surat_model->get_penerima($id);
        foreach ($penerima as $p) {
            $this->delete_file($p->foto_bukti_kirim);
            $this->delete_file($p->ttd_pengirim);
            $this->delete_file($p->foto_bukti_terima);
            $this->delete_file($p->ttd_penerima);
        }

        $this->Disposisi_surat_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Disposisi berhasil dihapus.');
        redirect('disposisi-surat');
    }

    // ----------------------------------------------------------------
    // TRACKING — halaman khusus tracking per penerima
    // ----------------------------------------------------------------

    public function tracking($disposisi_id = 0, $penerima_id = 0)
    {
        $disposisi = $this->Disposisi_surat_model->get_by_id($disposisi_id);
        if (!$disposisi) show_404();

        $penerima = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);
        if (!$penerima || $penerima->disposisi_id != $disposisi_id) show_404();

        $data = [
            'page_title'    => 'Tracking Disposisi',
            'page_subtitle' => 'Bukti pengiriman & penerimaan disposisi',
            'active_menu'   => 'agenda_disposisi',
            'page_js'       => 'disposisi-tracking.js',
            'disposisi'     => $disposisi,
            'penerima'      => $penerima,
        ];

        $this->render('disposisi_surat/tracking', $data);
    }

    public function store_kirim($disposisi_id = 0, $penerima_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('disposisi-surat');

        $disposisi = $this->Disposisi_surat_model->get_by_id($disposisi_id);
        $penerima  = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);

        if (!$disposisi || !$penerima || $penerima->disposisi_id != $disposisi_id) show_404();

        // Hapus file lama jika ada
        $this->delete_file($penerima->foto_bukti_kirim);
        $this->delete_file($penerima->ttd_pengirim);

        $foto_path = $this->upload_foto('foto_bukti_kirim', 'kirim');
        $ttd_path  = $this->save_signature(
            $this->input->post('ttd_pengirim_data', FALSE),
            'ttd_kirim'
        );

        $update = [
            'status_kirim'  => 'sudah',
            'tgl_kirim'     => date('Y-m-d H:i:s'),
            'nama_pengirim' => trim($this->input->post('nama_pengirim', TRUE)),
        ];

        if ($foto_path) $update['foto_bukti_kirim'] = $foto_path;
        if ($ttd_path)  $update['ttd_pengirim']     = $ttd_path;

        $this->Disposisi_surat_model->update_penerima($penerima_id, $update);
        $this->Disposisi_surat_model->sync_status($disposisi_id);

        $this->session->set_flashdata('crud_success', 'Bukti pengiriman berhasil disimpan.');
        redirect('disposisi-surat/tracking/' . $disposisi_id . '/' . $penerima_id);
    }

    public function store_terima($disposisi_id = 0, $penerima_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('disposisi-surat');

        $disposisi = $this->Disposisi_surat_model->get_by_id($disposisi_id);
        $penerima  = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);

        if (!$disposisi || !$penerima || $penerima->disposisi_id != $disposisi_id) show_404();
        if ($penerima->status_kirim !== 'sudah') {
            $this->session->set_flashdata('crud_error', 'Surat belum dikirim. Selesaikan pengiriman terlebih dahulu.');
            redirect('disposisi-surat/tracking/' . $disposisi_id . '/' . $penerima_id);
        }

        $this->delete_file($penerima->foto_bukti_terima);
        $this->delete_file($penerima->ttd_penerima);

        $foto_path = $this->upload_foto('foto_bukti_terima', 'terima');
        $ttd_path  = $this->save_signature(
            $this->input->post('ttd_penerima_data', FALSE),
            'ttd_terima'
        );

        $update = [
            'status_terima'     => 'sudah',
            'tgl_terima'        => date('Y-m-d H:i:s'),
            'nama_penerima_ttd' => trim($this->input->post('nama_penerima_ttd', TRUE)),
        ];

        if ($foto_path) $update['foto_bukti_terima'] = $foto_path;
        if ($ttd_path)  $update['ttd_penerima']      = $ttd_path;

        $this->Disposisi_surat_model->update_penerima($penerima_id, $update);
        $this->Disposisi_surat_model->sync_status($disposisi_id);

        // Cek apakah semua penerima sudah terima → update status surat masuk
        $all_penerima = $this->Disposisi_surat_model->get_penerima($disposisi_id);
        $all_done = TRUE;
        foreach ($all_penerima as $p) {
            if ($p->status_terima !== 'sudah') { $all_done = FALSE; break; }
        }
        if ($all_done) {
            $this->db->where('id', $disposisi->surat_masuk_id)
                     ->update('surat_masuk', ['status' => 'selesai']);
        }

        $this->session->set_flashdata('crud_success', 'Bukti penerimaan berhasil disimpan.');
        redirect('disposisi-surat/tracking/' . $disposisi_id . '/' . $penerima_id);
    }

    // CSRF refresh & next nomor (AJAX)
    public function get_next_nomor()
    {
        if ($this->input->method(TRUE) !== 'POST') show_404();
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($tahun < 2000) $tahun = (int) date('Y');

        echo json_encode([
            'success'    => TRUE,
            'next_nomor' => $this->Disposisi_surat_model->get_next_nomor($tahun),
            'csrf_hash'  => $this->security->get_csrf_hash(),
        ]);
    }

    public function get_csrf_token()
    {
        echo json_encode([
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }
}