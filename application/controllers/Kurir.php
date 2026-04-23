<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kurir extends CI_Controller
{
    protected $upload_path_foto = 'uploads/bukti/';
    protected $upload_path_ttd  = 'uploads/ttd/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Disposisi_surat_model');
        $this->load->library('upload');
        $this->load->helper(['upload', 'url', 'form']);
    }

    // Render halaman kurir tanpa template admin
    private function render_kurir($view, $data = [])
    {
        $this->load->view('kurir/' . $view, $data);
    }

    // ----------------------------------------------------------------
    // DAFTAR SURAT YANG PERLU DITERIMA
    // Tampilkan penerima yang: status_kirim=sudah, status_terima=belum
    // ----------------------------------------------------------------
    public function index()
    {
        $this->db->select('
            dp.id            AS penerima_id,
            dp.nama_penerima,
            dp.tgl_kirim,
            dp.nama_pengirim,
            ds.id            AS disposisi_id,
            ds.nomor_disposisi,
            ds.tanggal_disposisi,
            ds.perintah,
            sm.nomor_agenda,
            sm.nomor_surat,
            sm.perihal,
            sm.asal_surat,
            sm.tanggal_surat
        ');
        $this->db->from('disposisi_penerima dp');
        $this->db->join('disposisi_surat ds', 'ds.id = dp.disposisi_id', 'inner');
        $this->db->join('surat_masuk sm',     'sm.id = ds.surat_masuk_id', 'left');
        $this->db->where('dp.status_kirim',  'sudah');
        $this->db->where('dp.status_terima', 'belum');
        $this->db->order_by('dp.tgl_kirim', 'DESC');

        $rows = $this->db->get()->result();

        $this->render_kurir('index', [
            'rows'      => $rows,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // STEP 1 — Form nama penerima
    // ----------------------------------------------------------------
    public function terima($penerima_id = 0)
    {
        $penerima = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);
        if (!$penerima || $penerima->status_kirim !== 'sudah') show_404();

        // Sudah diterima → redirect ke index dengan pesan
        if ($penerima->status_terima === 'sudah') {
            $this->session->set_flashdata('info', 'Surat ini sudah ditandai diterima.');
            redirect('kurir');
        }

        $disposisi = $this->Disposisi_surat_model->get_by_id($penerima->disposisi_id);

        $this->render_kurir('step_nama', [
            'penerima'  => $penerima,
            'disposisi' => $disposisi,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // STEP 2 — Kamera (POST dari step_nama, tampilkan step_kamera)
    // ----------------------------------------------------------------
    public function step_kamera()
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('kurir');

        $penerima_id = (int) $this->input->post('penerima_id', TRUE);
        $nama        = trim($this->input->post('nama_penerima_ttd', TRUE));

        if (!$penerima_id || !$nama) {
            redirect('kurir/terima/' . $penerima_id);
        }

        $penerima  = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);
        $disposisi = $this->Disposisi_surat_model->get_by_id($penerima->disposisi_id);

        // Simpan sementara di session
        $this->session->set_userdata('kurir_nama',        $nama);
        $this->session->set_userdata('kurir_penerima_id', $penerima_id);

        $this->render_kurir('step_kamera', [
            'penerima'   => $penerima,
            'disposisi'  => $disposisi,
            'nama'       => $nama,
            'csrf_name'  => $this->security->get_csrf_token_name(),
            'csrf_hash'  => $this->security->get_csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // STEP 3 — Tanda tangan (POST dari step_kamera dengan foto)
    // Foto disimpan sementara, lanjut ke halaman TTD
    // ----------------------------------------------------------------
    public function step_ttd()
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('kurir');

        $penerima_id = (int) $this->session->userdata('kurir_penerima_id');
        $nama        = $this->session->userdata('kurir_nama');

        if (!$penerima_id || !$nama) redirect('kurir');

        $penerima  = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);
        $disposisi = $this->Disposisi_surat_model->get_by_id($penerima->disposisi_id);

        // Upload foto dari kamera (opsional — boleh skip)
        $foto_path = null;
        if (!empty($_FILES['foto_kamera']['name'])) {
            $dir = FCPATH . $this->upload_path_foto;
            if (!is_dir($dir)) mkdir($dir, 0755, TRUE);

            $config = [
                'upload_path'   => $dir,
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 8192,
                'file_name'     => 'terima_' . uniqid(),
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('foto_kamera')) {
                $foto_path = $this->upload_path_foto . $this->upload->data('file_name');
            }
        }

        // Juga terima foto base64 dari capture in-page (fallback)
        $foto_b64 = $this->input->post('foto_base64', FALSE);
        if (!$foto_path && !empty($foto_b64)) {
            $foto_path = $this->save_base64_image($foto_b64, 'terima');
        }

        // Simpan path foto sementara di session
        $this->session->set_userdata('kurir_foto', $foto_path);

        $this->render_kurir('step_ttd', [
            'penerima'   => $penerima,
            'disposisi'  => $disposisi,
            'nama'       => $nama,
            'foto_path'  => $foto_path,
            'csrf_name'  => $this->security->get_csrf_token_name(),
            'csrf_hash'  => $this->security->get_csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // SIMPAN FINAL — POST dari step_ttd
    // ----------------------------------------------------------------
    public function simpan()
    {
        if ($this->input->method(TRUE) !== 'POST') redirect('kurir');

        $penerima_id = (int) $this->session->userdata('kurir_penerima_id');
        $nama        = $this->session->userdata('kurir_nama');
        $foto_path   = $this->session->userdata('kurir_foto');

        if (!$penerima_id || !$nama) redirect('kurir');

        $penerima = $this->Disposisi_surat_model->get_penerima_by_id($penerima_id);
        if (!$penerima) redirect('kurir');

        // Simpan tanda tangan
        $ttd_data = $this->input->post('ttd_data', FALSE);
        $ttd_path = $this->save_signature($ttd_data, 'ttd_terima');

        // Update penerima
        $update = [
            'status_terima'     => 'sudah',
            'tgl_terima'        => date('Y-m-d H:i:s'),
            'nama_penerima_ttd' => $nama,
        ];
        if ($foto_path) $update['foto_bukti_terima'] = $foto_path;
        if ($ttd_path)  $update['ttd_penerima']      = $ttd_path;

        $this->Disposisi_surat_model->update_penerima($penerima_id, $update);
        $this->Disposisi_surat_model->sync_status($penerima->disposisi_id);

        // Cek apakah semua penerima disposisi ini sudah terima
        $disposisi   = $this->Disposisi_surat_model->get_by_id($penerima->disposisi_id);
        $all_penerima = $this->Disposisi_surat_model->get_penerima($penerima->disposisi_id);
        $all_done    = TRUE;
        foreach ($all_penerima as $p) {
            if ($p->status_terima !== 'sudah') { $all_done = FALSE; break; }
        }
        if ($all_done && $disposisi) {
            $this->db->where('id', $disposisi->surat_masuk_id)
                     ->update('surat_masuk', ['status' => 'selesai']);
        }

        // Bersihkan session kurir
        $this->session->unset_userdata(['kurir_nama', 'kurir_penerima_id', 'kurir_foto']);

        $this->render_kurir('sukses', [
            'penerima'  => $penerima,
            'disposisi' => $disposisi,
        ]);
    }

    // ----------------------------------------------------------------
    // HELPERS
    // ----------------------------------------------------------------
    private function save_signature($base64, $prefix = 'ttd')
    {
        if (empty($base64)) return null;
        if (strpos($base64, ',') !== FALSE) {
            $base64 = explode(',', $base64)[1];
        }
        $decoded = base64_decode($base64);
        if (!$decoded) return null;

        $dir = FCPATH . $this->upload_path_ttd;
        if (!is_dir($dir)) mkdir($dir, 0755, TRUE);

        $filename = $prefix . '_' . uniqid() . '.png';
        file_put_contents($dir . $filename, $decoded);
        return $this->upload_path_ttd . $filename;
    }

    private function save_base64_image($base64, $prefix = 'foto')
    {
        if (empty($base64)) return null;
        if (strpos($base64, ',') !== FALSE) {
            $base64 = explode(',', $base64)[1];
        }
        $decoded = base64_decode($base64);
        if (!$decoded) return null;

        $dir = FCPATH . $this->upload_path_foto;
        if (!is_dir($dir)) mkdir($dir, 0755, TRUE);

        $filename = $prefix . '_' . uniqid() . '.jpg';
        file_put_contents($dir . $filename, $decoded);
        return $this->upload_path_foto . $filename;
    }

    // CSRF refresh
    public function csrf()
    {
        echo json_encode([
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }
}