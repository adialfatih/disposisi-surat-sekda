<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('data_model');
        $this->load->model('Penomoran_surat_model');
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index(){
        $this->load->view('spreadsheet');
    }

    public function export()
    {
        // Load PhpSpreadsheet — pastikan sudah di-require via composer autoload
        // di index.php atau di MY_Controller:
        // require_once APPPATH . '../vendor/autoload.php';
 
        $rows = $this->Penomoran_surat_model->get_all();
 
        // Kelompokkan data per jenis_surat_label
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->jenis_surat_label][] = $row;
        }
 
        // Urutan sheet sesuai jenis surat (agar rapi)
        $urutan = [
            'SETDA - Surat Keluar',
            'SETDA - SPPD',
            'UMUM - Surat Keluar',
            'UMUM - SPPD',
            'NOTA DINAS',
        ];
 
        // Susun ulang: urutan prioritas dulu, sisanya append
        $sorted = [];
        foreach ($urutan as $label) {
            if (isset($grouped[$label])) {
                $sorted[$label] = $grouped[$label];
            }
        }
        foreach ($grouped as $label => $data) {
            if (!isset($sorted[$label])) {
                $sorted[$label] = $data;
            }
        }
 
        // Buat workbook
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // hapus sheet default kosong
 
        $sheet_index = 0;
 
        // Warna header — biru sesuai tema aplikasi
        $header_fill  = 'FF0C447C'; // --sogan
        $header_font  = 'FFFFFFFF';
        $accent_fill  = 'FFE8F4FF'; // baris genap
        $border_color = 'FFD5E4F0'; // --cream-dark
 
        $kolom = [
            'A' => ['label' => 'No',               'width' => 5],
            'B' => ['label' => 'Nomor Surat',       'width' => 28],
            'C' => ['label' => 'Tanggal Surat',     'width' => 16],
            'D' => ['label' => 'Perihal',           'width' => 45],
            'E' => ['label' => 'Tujuan',            'width' => 30],
            'F' => ['label' => 'Pengolah',          'width' => 22],
            'G' => ['label' => 'No. WA Pengolah',   'width' => 18],
            'H' => ['label' => 'Kode Keamanan',     'width' => 15],
            'I' => ['label' => 'Kode Klasifikasi',  'width' => 18],
            'J' => ['label' => 'Kode Umum',         'width' => 14],
            'K' => ['label' => 'Nomor Urut',        'width' => 12],
            'L' => ['label' => 'Tahun',             'width' => 8],
            'M' => ['label' => 'Catatan',           'width' => 30],
        ];
 
        foreach ($sorted as $label => $data) {
 
            $ws = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
                $spreadsheet,
                $this->_safe_sheet_name($label)
            );
            $spreadsheet->addSheet($ws, $sheet_index++);
 
            // ── Judul sheet ──────────────────────────────────────
            $ws->mergeCells('A1:M1');
            $ws->setCellValue('A1', 'Buku Agenda Penomoran Surat — ' . $label);
            $ws->getStyle('A1')->applyFromArray([
                'font'      => ['bold' => TRUE, 'size' => 13, 'color' => ['argb' => 'FF0C447C']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);
            $ws->getRowDimension(1)->setRowHeight(22);
 
            // ── Sub-judul: tanggal export ─────────────────────────
            $ws->mergeCells('A2:M2');
            $ws->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i') . ' WIB');
            $ws->getStyle('A2')->applyFromArray([
                'font'      => ['italic' => TRUE, 'size' => 10, 'color' => ['argb' => 'FF6B8DAD']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);
            $ws->getRowDimension(2)->setRowHeight(16);
 
            // ── Baris kosong pemisah ──────────────────────────────
            $ws->getRowDimension(3)->setRowHeight(6);
 
            // ── Header kolom ──────────────────────────────────────
            $header_row = 4;
            foreach ($kolom as $col => $cfg) {
                $ws->setCellValue($col . $header_row, $cfg['label']);
                $ws->getColumnDimension($col)->setWidth($cfg['width']);
            }
 
            $ws->getStyle('A' . $header_row . ':M' . $header_row)->applyFromArray([
                'font' => [
                    'bold'  => TRUE,
                    'color' => ['argb' => $header_font],
                    'size'  => 10,
                    'name'  => 'Arial',
                ],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $header_fill],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText'   => FALSE,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFFFFFFF'],
                    ],
                ],
            ]);
            $ws->getRowDimension($header_row)->setRowHeight(20);
 
            // ── Data rows ─────────────────────────────────────────
            $no = 1;
            foreach ($data as $row) {
                $r = $header_row + $no; // baris excel = header + nomor urut data
 
                $ws->setCellValue('A' . $r, $no);
                $ws->setCellValue('B' . $r, $row->nomor_surat ?: '-');
                $ws->setCellValue('C' . $r, $row->tanggal_surat
                    ? date('d/m/Y', strtotime($row->tanggal_surat))
                    : '-');
                $ws->setCellValue('D' . $r, $row->perihal);
                $ws->setCellValue('E' . $r, $row->tujuan);
                $ws->setCellValue('F' . $r, $row->pengolah);
                $ws->setCellValue('G' . $r, $row->no_wa_pengolah && $row->no_wa_pengolah !== '0'
                    ? '+62' . $row->no_wa_pengolah
                    : '-');
                $ws->setCellValue('H' . $r, $row->kode_keamanan ?: '-');
                $ws->setCellValue('I' . $r, $row->kode_klasifikasi);
                $ws->setCellValue('J' . $r, $row->kode_umum ?: '-');
                $ws->setCellValue('K' . $r, (int) $row->nomor_urut);
                $ws->setCellValue('L' . $r, (int) $row->tahun);
                $ws->setCellValue('M' . $r, $row->catatan ?: '-');
 
                // Warna baris selang-seling
                $row_fill = ($no % 2 === 0) ? $accent_fill : 'FFFFFFFF';
 
                $ws->getStyle('A' . $r . ':M' . $r)->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 10],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => $row_fill],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => ['argb' => $border_color],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => FALSE,
                    ],
                ]);
 
                // Kolom A (No) dan K, L tengah
                $ws->getStyle('A' . $r)->getAlignment()
                   ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $ws->getStyle('K' . $r)->getAlignment()
                   ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $ws->getStyle('L' . $r)->getAlignment()
                   ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $ws->getStyle('C' . $r)->getAlignment()
                   ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
 
                $ws->getRowDimension($r)->setRowHeight(18);
                $no++;
            }
 
            // ── Freeze pane di baris header ───────────────────────
            $ws->freezePane('A' . ($header_row + 1));
 
            // ── Auto filter ───────────────────────────────────────
            $last_row = $header_row + count($data);
            if (count($data) > 0) {
                $ws->setAutoFilter('A' . $header_row . ':M' . $last_row);
            }
 
            // ── Ringkasan di bawah tabel ──────────────────────────
            $sum_row = $last_row + 2;
            $ws->mergeCells('A' . $sum_row . ':C' . $sum_row);
            $ws->setCellValue('A' . $sum_row, 'Total data: ' . count($data) . ' surat');
            $ws->getStyle('A' . $sum_row)->applyFromArray([
                'font'      => ['bold' => TRUE, 'color' => ['argb' => 'FF0C447C'], 'size' => 10],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
            ]);
 
            // ── Page setup untuk print ────────────────────────────
            $ws->getPageSetup()
               ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
               ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
               ->setFitToPage(TRUE)
               ->setFitToWidth(1)
               ->setFitToHeight(0);
 
            $ws->getHeaderFooter()
               ->setOddHeader('&C&B Buku Agenda Penomoran Surat — ' . $label)
               ->setOddFooter('&L&D &T&RHalaman &P dari &N');
        }
 
        // ── Set sheet aktif pertama ───────────────────────────────
        $spreadsheet->setActiveSheetIndex(0);
 
        // ── Metadata workbook ─────────────────────────────────────
        $spreadsheet->getProperties()
            ->setCreator('Aplikasi Nomor Surat Sekda')
            ->setTitle('Buku Agenda Penomoran Surat')
            ->setSubject('Export Penomoran Surat — ' . date('Y'))
            ->setDescription('Diekspor pada ' . date('d F Y H:i') . ' WIB');
 
        // ── Stream ke browser ─────────────────────────────────────
        $filename = 'Penomoran_Surat_' . date('Ymd_His') . '.xlsx';
 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
 
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
 
        // Bersihkan memory
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }
 
    /**
     * Sanitasi nama sheet Excel:
     * - Maks 31 karakter
     * - Tidak boleh mengandung: \ / ? * [ ] :
     */
    private function _safe_sheet_name($name)
    {
        $name = preg_replace('/[\\\\\/\?\*\[\]\:]/', '', $name);
        return substr($name, 0, 31);
    }

}