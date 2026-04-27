<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk_v2 extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Surat_masuk_v2_model');
    }

    // ─────────────────────────────────────────────
    // Helper: render halaman dengan template
    // ─────────────────────────────────────────────
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
    // Helper: opsi dropdown
    // ─────────────────────────────────────────────
    private function get_kategori_options()
    {
        return [
            'permohonan' => 'Permohonan',
            'undangan'   => 'Undangan',
            'lainnya'    => 'Lainnya',
        ];
    }

    private function get_sifat_options()
    {
        return [
            'biasa'        => 'Biasa',
            'segera'       => 'Segera',
            'sangat_segera'=> 'Sangat Segera',
            'rahasia'      => 'Rahasia',
        ];
    }

    private function get_status_options()
    {
        return [
            'masuk'       => 'Masuk',
            'dicetak'     => 'Dicetak',
            'didisposisi' => 'Didisposisi',
            'selesai'     => 'Selesai',
        ];
    }

    // ─────────────────────────────────────────────
    // CSRF token (untuk AJAX)
    // ─────────────────────────────────────────────
    public function get_csrf_token()
    {
        echo json_encode([
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ]);
    }

    // ─────────────────────────────────────────────
    // Validasi form input
    // ─────────────────────────────────────────────
    private function validate_form()
    {
        $this->form_validation->set_rules('kategori',      'Kategori',      'trim|required|in_list[permohonan,undangan,lainnya]');
        $this->form_validation->set_rules('sifat',         'Sifat Surat',   'trim|required|in_list[biasa,segera,sangat_segera,rahasia]');
        $this->form_validation->set_rules('nomor_agenda',  'Nomor Agenda',  'trim|required|max_length[30]');
        $this->form_validation->set_rules('asal_surat',    'Asal Surat',    'trim|required|max_length[255]');
        $this->form_validation->set_rules('tanggal_surat', 'Tanggal Surat', 'trim|required');
        $this->form_validation->set_rules('nomor_surat',   'Nomor Surat',   'trim|required|max_length[100]');
        $this->form_validation->set_rules('perihal',       'Perihal',       'trim|required|max_length[255]');
        $this->form_validation->set_rules('asal_berkas',   'Asal Berkas',   'trim|max_length[150]');
        $this->form_validation->set_rules('tanggal_terima','Tanggal Terima','trim|required');
    }

    // ─────────────────────────────────────────────
    // Auto-nomor agenda (AJAX POST)
    // ─────────────────────────────────────────────
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

        $next = $this->Surat_masuk_v2_model->get_next_nomor_ajax($tahun);
        echo json_encode(['success' => TRUE, 'next_nomor' => $next]);
    }

    // ─────────────────────────────────────────────
    // INDEX – daftar semua surat masuk v2
    // ─────────────────────────────────────────────
    public function index()
    {
        $rows = $this->Surat_masuk_v2_model->get_all();

        $data = [
            'page_title'    => 'Agenda Surat Masuk & Disposisi',
            'page_subtitle' => 'Pencatatan surat masuk dan lembar disposisi Sekda Kota Pekalongan',
            'active_menu'   => 'surat_masuk_v2',
            'page_js'       => 'surat-masuk-v2.js',
            'rows'          => $rows,
        ];

        $this->render('surat_masuk_v2/index', $data);
    }

    // ─────────────────────────────────────────────
    // CREATE – form input baru
    // ─────────────────────────────────────────────
    public function create()
    {
        $data = [
            'page_title'       => 'Catat Surat Masuk',
            'page_subtitle'    => 'Input surat masuk baru beserta sifat surat',
            'active_menu'      => 'surat_masuk_v2',
            'kategori_options' => $this->get_kategori_options(),
            'sifat_options'    => $this->get_sifat_options(),
            'mode'             => 'create',
            'row'              => null,
        ];

        $this->render('surat_masuk_v2/form', $data);
    }

    // ─────────────────────────────────────────────
    // STORE – simpan data baru
    // ─────────────────────────────────────────────
    public function store()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk-v2');
        }

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'       => 'Catat Surat Masuk',
                'page_subtitle'    => 'Input surat masuk baru beserta sifat surat',
                'active_menu'      => 'surat_masuk_v2',
                'kategori_options' => $this->get_kategori_options(),
                'sifat_options'    => $this->get_sifat_options(),
                'mode'             => 'create',
                'row'              => null,
            ];
            $this->render('surat_masuk_v2/form', $data);
            return;
        }

        $nomor_agenda = trim($this->input->post('nomor_agenda', TRUE));
        if ($this->Surat_masuk_v2_model->check_duplicate_agenda($nomor_agenda)) {
            $this->session->set_flashdata('crud_error', 'Nomor agenda tersebut sudah digunakan.');
            redirect('surat-masuk-v2/create');
            return;
        }

        $insert_data = [
            'kategori'       => trim($this->input->post('kategori', TRUE)),
            'sifat'          => trim($this->input->post('sifat', TRUE)),
            'nomor_agenda'   => $nomor_agenda,
            'asal_surat'     => trim($this->input->post('asal_surat', TRUE)),
            'tanggal_surat'  => trim($this->input->post('tanggal_surat', TRUE)),
            'nomor_surat'    => trim($this->input->post('nomor_surat', TRUE)),
            'perihal'        => trim($this->input->post('perihal', TRUE)),
            'asal_berkas'    => trim($this->input->post('asal_berkas', TRUE)),
            'tanggal_terima' => trim($this->input->post('tanggal_terima', TRUE)),
            'status'         => 'masuk',
            'created_by'     => (int) $this->session->userdata('user_id'),
        ];

        $this->Surat_masuk_v2_model->insert($insert_data);
        $new_id = $this->Surat_masuk_v2_model->insert_id();

        $this->session->set_flashdata('crud_success', 'Surat masuk berhasil dicatat. Silakan cetak lembar disposisi.');
        redirect('surat-masuk-v2/detail/' . $new_id);
    }

    // ─────────────────────────────────────────────
    // DETAIL – lihat detail surat
    // ─────────────────────────────────────────────
    public function detail($id = 0)
    {
        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Detail Surat Masuk',
            'page_subtitle' => 'Informasi lengkap surat dan status disposisi',
            'active_menu'   => 'surat_masuk_v2',
            'row'           => $row,
        ];

        $this->render('surat_masuk_v2/detail', $data);
    }

    // ─────────────────────────────────────────────
    // EDIT – form edit data awal
    // ─────────────────────────────────────────────
    public function edit($id = 0)
    {
        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'       => 'Edit Surat Masuk',
            'page_subtitle'    => 'Perbarui data surat masuk',
            'active_menu'      => 'surat_masuk_v2',
            'kategori_options' => $this->get_kategori_options(),
            'sifat_options'    => $this->get_sifat_options(),
            'mode'             => 'edit',
            'row'              => $row,
        ];

        $this->render('surat_masuk_v2/form', $data);
    }

    // ─────────────────────────────────────────────
    // UPDATE – simpan perubahan data awal
    // ─────────────────────────────────────────────
    public function update($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk-v2');
        }

        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->validate_form();

        if ($this->form_validation->run() === FALSE) {
            $data = [
                'page_title'       => 'Edit Surat Masuk',
                'page_subtitle'    => 'Perbarui data surat masuk',
                'active_menu'      => 'surat_masuk_v2',
                'kategori_options' => $this->get_kategori_options(),
                'sifat_options'    => $this->get_sifat_options(),
                'mode'             => 'edit',
                'row'              => $row,
            ];
            $this->render('surat_masuk_v2/form', $data);
            return;
        }

        $nomor_agenda = trim($this->input->post('nomor_agenda', TRUE));
        if ($this->Surat_masuk_v2_model->check_duplicate_agenda($nomor_agenda, $id)) {
            $this->session->set_flashdata('crud_error', 'Nomor agenda tersebut sudah digunakan.');
            redirect('surat-masuk-v2/edit/' . $id);
            return;
        }

        $update_data = [
            'kategori'       => trim($this->input->post('kategori', TRUE)),
            'sifat'          => trim($this->input->post('sifat', TRUE)),
            'nomor_agenda'   => $nomor_agenda,
            'asal_surat'     => trim($this->input->post('asal_surat', TRUE)),
            'tanggal_surat'  => trim($this->input->post('tanggal_surat', TRUE)),
            'nomor_surat'    => trim($this->input->post('nomor_surat', TRUE)),
            'perihal'        => trim($this->input->post('perihal', TRUE)),
            'asal_berkas'    => trim($this->input->post('asal_berkas', TRUE)),
            'tanggal_terima' => trim($this->input->post('tanggal_terima', TRUE)),
        ];

        $this->Surat_masuk_v2_model->update($id, $update_data);
        $this->session->set_flashdata('crud_success', 'Data surat masuk berhasil diperbarui.');
        redirect('surat-masuk-v2/detail/' . $id);
    }

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    public function delete($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk-v2');
        }

        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $this->Surat_masuk_v2_model->delete($id);
        $this->session->set_flashdata('crud_success', 'Data surat masuk berhasil dihapus.');
        redirect('surat-masuk-v2');
    }

    // ─────────────────────────────────────────────
    // CETAK – generate PDF lembar disposisi (FPDF)
    // ─────────────────────────────────────────────
    public function cetak($id = 0)
    {
        $this->load->library('pdf');
        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        // Update status jadi 'dicetak' jika masih 'masuk'
        if ($row->status === 'masuk') {
            $this->Surat_masuk_v2_model->update($id, ['status' => 'dicetak']);
        }

        // Pastikan FPDF sudah di-autoload CI atau require manual
        // Jika autoload CI belum include FPDF, uncomment baris berikut:
        // require_once APPPATH . 'third_party/fpdf/fpdf.php';

        $pdf = new FPDF('P', 'mm', 'A4'); // A4 portrait
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(FALSE);

        // ── Margin & ukuran kertas ──
        // A4 = 210 x 297 mm, gunakan konten area 170mm (margin 20mm kiri-kanan)
        $lm = 20; // left margin
        $w  = 170; // content width

        // ── HEADER: Logo + Nama Instansi ──
        // Logo (jika ada, sesuaikan path)
        $logo_path = FCPATH . 'uploads/Lambang_Kota_Pekalongan.png';
        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $lm, 10, 18, 18, 'PNG');
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY($lm + 20, 10);
        $pdf->Cell($w - 20, 5, 'PEMERINTAH KOTA PEKALONGAN', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetX($lm + 20);
        $pdf->Cell($w - 20, 6, 'SEKRETARIAT DAERAH', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetX($lm + 20);
        $pdf->Cell($w - 20, 4, 'Jalan Mataram No.1, Kota Pekalongan, Jawa Tengah 51111', 0, 1, 'C');
        $pdf->SetX($lm + 20);
        $pdf->Cell($w - 20, 4, 'Telepon (0285) 421091  Faksimile (0285) 424061', 0, 1, 'C');
        $pdf->SetX($lm + 20);
        $pdf->Cell($w - 20, 4, 'Pos-el: setda@pekalongankota.go.id    Laman: www.pekalongankota.go.id', 0, 1, 'C');

        // Garis tebal bawah header
        $pdf->SetLineWidth(0.6);
        $pdf->Line($lm, 33, $lm + $w, 33);
        $pdf->SetLineWidth(0.2);
        $pdf->Line($lm, 34.5, $lm + $w, 34.5);

        // ── JUDUL KOTAK: LEMBAR DISPOSISI ──
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY($lm, 37);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Cell($w, 8, 'LEMBAR DISPOSISI', 1, 1, 'C');

        // ── BARIS INFORMASI SURAT ──
        // Layout: kolom kiri (info surat) | kolom kanan (terima, agenda, sifat)
        // Sifat dibuat 1 baris penuh sendiri di bawah No.Agenda agar tidak terpotong
        $y_start = 47;
        $col_l   = 85; // lebar kolom kiri
        $col_r   = $w - $col_l;

        $pdf->SetFont('Arial', '', 9);

        // Tinggi tiap baris info surat
        $row_h = 8;

        // ── Baris 1: Surat dari | Diterima Tgl ──
        $pdf->SetXY($lm, $y_start);
        $pdf->Cell($col_l, $row_h, 'Surat dari  :  ' . $this->_truncate($row->asal_surat, 35), 'LTR', 0, 'L');
        $pdf->SetXY($lm + $col_l, $y_start);
        $pdf->Cell($col_r, $row_h, 'Diterima Tgl  :  ' . date('d-m-Y', strtotime($row->tanggal_terima)), 'LTR', 1, 'L');

        // ── Baris 2: No. Surat | No. Agenda ──
        $pdf->SetXY($lm, $y_start + $row_h);
        $pdf->Cell($col_l, $row_h, 'No. Surat    :  ' . $this->_truncate($row->nomor_surat, 30), 'LR', 0, 'L');
        $pdf->SetXY($lm + $col_l, $y_start + $row_h);
        $pdf->Cell($col_r, $row_h, 'No. Agenda  :  ' . $row->nomor_agenda, 'LR', 1, 'L');

        // ── Baris 3: Tgl. Surat | Sifat (label saja) ──
        $pdf->SetXY($lm, $y_start + $row_h * 2);
        $pdf->Cell($col_l, $row_h, 'Tgl. Surat    :  ' . date('d-m-Y', strtotime($row->tanggal_surat)), 'LR', 0, 'L');
        $pdf->SetXY($lm + $col_l, $y_start + $row_h * 2);
        $pdf->Cell($col_r, $row_h, 'Sifat         :', 'LR', 1, 'L');

        // ── Baris 4: kosong kiri | Checkbox Sifat (baris penuh, tidak terpotong) ──
        $cb_sangat  = ($row->sifat === 'sangat_segera') ? '[X]' : '[  ]';
        $cb_segera  = ($row->sifat === 'segera')        ? '[X]' : '[  ]';
        $cb_rahasia = ($row->sifat === 'rahasia')       ? '[X]' : '[  ]';

        // Lebar proporsional agar semua muat di col_r
        $w_sangat  = 32;
        $w_segera  = 21;
        $w_rahasia = $col_r - $w_sangat - $w_segera;

        $pdf->SetXY($lm, $y_start + $row_h * 3);
        $pdf->Cell($col_l, $row_h, '', 'LBR', 0, 'L'); // kolom kiri kosong — tutup border bawah

        $pdf->SetXY($lm + $col_l, $y_start + $row_h * 3);
        $pdf->Cell($w_sangat,  $row_h, $cb_sangat  . ' Sangat Segera', 'LB',  0, 'L');
        $pdf->Cell($w_segera,  $row_h, $cb_segera  . ' Segera',        'B',   0, 'L');
        $pdf->Cell($w_rahasia, $row_h, $cb_rahasia . ' Rahasia',       'BR',  1, 'L');

        // ── HAL ── (di bawah 4 baris info)
        $y_hal = $y_start + $row_h * 4;
        $pdf->SetXY($lm, $y_hal);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($w, 8, 'Hal    :    ' . $this->_truncate($row->perihal, 80), 1, 1, 'L');

        // ── DITERUSKAN & INSTRUKSI ──
        // Kiri  : header(8) + 5 penerima × 7 + dan_seterusnya(7) = 8 + 42 = 50
        // Kanan : header(8) + 3 instruksi × 8 + lainnya(8) + sisa = 8 + 32 + sisa
        // Total tinggi kotak = 8 + 42 = 50 — kanan harus = 50 juga
        $row_pen    = 7;  // tinggi baris penerima
        $row_ins    = 8;  // tinggi baris instruksi
        $h_header   = 8;
        $h_kiri_isi = 6 * $row_pen;          // 6 baris × 7 = 42
        $h_total    = $h_header + $h_kiri_isi; // 50

        $y_dt = $y_hal + 10;

        // Header kiri
        $pdf->SetXY($lm, $y_dt);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($col_l, $h_header, 'Diteruskan kepada Srd :', 'LTR', 0, 'L');

        // Header kanan
        $pdf->SetXY($lm + $col_l, $y_dt);
        $pdf->Cell($col_r, $h_header, 'Dengan hormat harap :', 'LTR', 1, 'L');

        $pdf->SetFont('Arial', '', 9);

        // Isi penerima (5 baris kosong + "dan seterusnya")
        $penerima_list = [];
        if (!empty($row->diteruskan_kepada)) {
            $penerima_list = array_values(array_filter(
                array_map('trim', explode("\n", $row->diteruskan_kepada))
            ));
        }

        $y_penerima = $y_dt + $h_header;
        for ($i = 0; $i < 5; $i++) {
            $pdf->SetXY($lm, $y_penerima + ($i * $row_pen));
            $isi = $penerima_list[$i] ?? '';
            $pdf->Cell(6,          $row_pen, !empty($isi) ? '[X]' : '[  ]', 'L', 0, 'C');
            $pdf->Cell($col_l - 6, $row_pen, $isi !== '' ? $isi : ' ...................................', 'R', 1, 'L');
        }

        // Baris "Dan seterusnya" — menutup kotak kiri
        $pdf->SetXY($lm, $y_penerima + 5 * $row_pen);
        $pdf->Cell(6,          $row_pen, '[  ]', 'LB', 0, 'C');
        $pdf->Cell($col_l - 6, $row_pen, 'Dan seterusnya ............', 'RB', 1, 'L');

        // ── Kolom Kanan: instruksi ──
        // 3 instruksi × 8 = 24 | lainnya = 8 | sisa agar flush = h_kiri_isi - 4*row_ins = 42 - 32 = 10
        $instruksi_list = [
            'instruksi_tanggapan'     => 'Tanggapan dan Saran',
            'instruksi_proses_lanjut' => 'Proses lebih lanjut',
            'instruksi_koordinasi'    => 'Koordinasi/konfirmasi',
        ];

        $y_ins = $y_dt + $h_header;
        $i_ins = 0;
        foreach ($instruksi_list as $field => $label) {
            $pdf->SetXY($lm + $col_l, $y_ins + ($i_ins * $row_ins));
            $cb = !empty($row->$field) ? '[X]' : '[  ]';
            $pdf->Cell(8,          $row_ins, $cb,    'L', 0, 'C');
            $pdf->Cell($col_r - 8, $row_ins, $label, 'R', 1, 'L');
            $i_ins++;
        }

        // Instruksi lainnya
        $lainnya_text = !empty($row->instruksi_lainnya)
            ? $this->_truncate($row->instruksi_lainnya, 28)
            : ' ...................................';
        $pdf->SetXY($lm + $col_l, $y_ins + ($i_ins * $row_ins));
        $pdf->Cell(8,          $row_ins, !empty($row->instruksi_lainnya) ? '[X]' : '[  ]', 'L', 0, 'C');
        $pdf->Cell($col_r - 8, $row_ins, $lainnya_text, 'R', 1, 'L');
        $i_ins++;

        // Baris sisa kanan agar flush dengan bawah kotak kiri
        $tinggi_kanan_sudah = $i_ins * $row_ins;        // 4 × 8 = 32
        $sisa_kanan         = $h_kiri_isi - $tinggi_kanan_sudah; // 42 - 32 = 10
        $pdf->SetXY($lm + $col_l, $y_ins + $tinggi_kanan_sudah);
        $pdf->Cell($col_r, $sisa_kanan, ' ...................................', 'LBR', 1, 'L');

        // h_dt = tinggi total kotak diteruskan (untuk y_cat)
        $h_dt = $h_total; // = 50

        // ── CATATAN ──
        $y_cat = $y_dt + $h_dt + 2;
        $pdf->SetXY($lm, $y_cat);
        $pdf->SetFont('Arial', '', 9);
        $catatan_text = !empty($row->catatan_disposisi) ? $row->catatan_disposisi : '';
        $pdf->Cell($w, 6, 'Catatan :  ' . $this->_truncate($catatan_text, 80), 'LTR', 1, 'L');
        $pdf->SetXY($lm, $y_cat + 6);
        $pdf->Cell($w, 10, '', 'LBR', 1, 'L'); // area kosong catatan

        // ── TTD ──
        $y_ttd = $y_cat + 20;
        $pdf->SetXY($lm + ($w / 2), $y_ttd);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($w / 2, 5, 'Kepala Bagian Umum', 0, 1, 'C');
        $pdf->SetXY($lm + ($w / 2), $y_ttd + 5);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($w / 2, 5, 'Sekretariat Daerah Kota Pekalongan', 0, 1, 'C');

        // Ruang tanda tangan
        $pdf->SetXY($lm + ($w / 2), $y_ttd + 10);
        $pdf->Cell($w / 2, 22, '', 0, 1, 'C');

        // Nama (garis kosong)
        $pdf->SetXY($lm + ($w / 2), $y_ttd + 32);
        $pdf->Cell($w / 2, 5, '( .................................................. )', 0, 1, 'C');

        // Output PDF langsung ke browser
        $filename = 'Disposisi_' . $row->nomor_agenda . '.pdf';
        $filename = str_replace(['/', '\\', ' '], ['-', '-', '_'], $filename);
        $pdf->Output('I', $filename);
        exit;
    }

    // ─────────────────────────────────────────────
    // DISPOSISI – form isian manual pimpinan
    // ─────────────────────────────────────────────
    public function disposisi($id = 0)
    {
        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        $data = [
            'page_title'    => 'Input Disposisi',
            'page_subtitle' => 'Masukkan isian disposisi dari pimpinan ke sistem',
            'active_menu'   => 'surat_masuk_v2',
            'row'           => $row,
        ];

        $this->render('surat_masuk_v2/disposisi_form', $data);
    }

    // ─────────────────────────────────────────────
    // DISPOSISI STORE – simpan isian disposisi
    // ─────────────────────────────────────────────
    public function disposisi_store($id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('surat-masuk-v2');
        }

        $row = $this->Surat_masuk_v2_model->get_by_id($id);
        if (!$row) {
            show_404();
        }

        // Ambil penerima: array of text, gabung dengan newline
        $penerima_raw  = $this->input->post('penerima', TRUE);
        $penerima_arr  = is_array($penerima_raw) ? $penerima_raw : [];
        $penerima_bersih = array_filter(array_map('trim', $penerima_arr));
        $diteruskan    = implode("\n", $penerima_bersih);

        $update_data = [
            'diteruskan_kepada'       => $diteruskan ?: null,
            'instruksi_tanggapan'     => (int) (bool) $this->input->post('instruksi_tanggapan'),
            'instruksi_proses_lanjut' => (int) (bool) $this->input->post('instruksi_proses_lanjut'),
            'instruksi_koordinasi'    => (int) (bool) $this->input->post('instruksi_koordinasi'),
            'instruksi_lainnya'       => trim((string) $this->input->post('instruksi_lainnya', TRUE)) ?: null,
            'catatan_disposisi'       => trim((string) $this->input->post('catatan_disposisi', TRUE)) ?: null,
            'status'                  => 'didisposisi',
        ];

        $this->Surat_masuk_v2_model->update($id, $update_data);
        $this->session->set_flashdata('crud_success', 'Data disposisi berhasil disimpan.');
        redirect('surat-masuk-v2/detail/' . $id);
    }

    // ─────────────────────────────────────────────
    // Helper: truncate string aman
    // ─────────────────────────────────────────────
    private function _truncate($str, $len = 40)
    {
        $str = strip_tags((string) $str);
        if (mb_strlen($str) > $len) {
            return mb_substr($str, 0, $len) . '...';
        }
        return $str;
    }
}