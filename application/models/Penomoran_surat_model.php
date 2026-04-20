<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penomoran_surat_model extends CI_Model
{
    protected $table = 'penomoran_surat';

    public function get_all()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    public function check_duplicate_nomor($jenis_surat_slug, $tahun, $nomor_urut, $exclude_id = null)
    {
        $this->db->where('jenis_surat_slug', $jenis_surat_slug);
        $this->db->where('tahun', (int) $tahun);
        $this->db->where('nomor_urut', (int) $nomor_urut);

        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }

        return $this->db->get($this->table)->row();
    }
    /**
     * Generate nomor surat sesuai format masing-masing jenis.
     *
     * Format:
     *  - nota-dinas          : N/K/Y       → {nomor_urut}/{kode_klasifikasi}/{tahun}
     *  - setda-sppd          : K/N/Y       → {kode_klasifikasi}/{nomor_urut}/{tahun}
     *  - umum-sppd           : K/N/Y       → {kode_klasifikasi}/{nomor_urut}/{tahun}
     *  - setda-surat-keluar  : KS/N/K/S/Y  → {kode_keamanan}/{nomor_urut}/{kode_klasifikasi}/{kode_umum}/{tahun}
     *                          (S = kode_umum, khusus Sekda)
     *  - umum-surat-keluar   : KS/N/K/Y    → {kode_keamanan}/{nomor_urut}/{kode_klasifikasi}/{tahun}
     *
     * @param  string      $jenis_slug
     * @param  int         $nomor_urut
     * @param  string      $kode_klasifikasi
     * @param  string      $kode_keamanan
     * @param  string|null $kode_umum
     * @param  int         $tahun
     * @return string
     */
    public function generate_nomor_surat(
        $jenis_slug,
        $nomor_urut,
        $kode_klasifikasi,
        $kode_keamanan,
        $kode_umum,
        $tahun
    ) {
        switch ($jenis_slug) {
            // N/K/Y
            case 'nota-dinas':
                return implode('/', [
                    $nomor_urut,
                    $kode_klasifikasi,
                    $tahun,
                ]);

            // K/N/Y
            case 'setda-sppd':
            case 'umum-sppd':
                return implode('/', [
                    $kode_klasifikasi,
                    $nomor_urut,
                    $tahun,
                ]);

            // KS/N/K/S/Y — Sekda: sifat/nomor/klasifikasi/kode_umum/tahun
            case 'setda-surat-keluar':
                return implode('/', [
                    $kode_keamanan,
                    $nomor_urut,
                    $kode_klasifikasi,
                    $kode_umum,
                    $tahun,
                ]);

            // KS/N/K/Y — Umum: sifat/nomor/klasifikasi/tahun
            case 'umum-surat-keluar':
                return implode('/', [
                    $kode_keamanan,
                    $nomor_urut,
                    $kode_klasifikasi,
                    $tahun,
                ]);

            default:
                return implode('/', [
                    $kode_keamanan,
                    $nomor_urut,
                    $kode_klasifikasi,
                    $tahun,
                ]);
        }
    }
    // public function get_next_nomor_urut($jenis_surat_slug, $tahun)
    // {
    //     $row = $this->db
    //         ->select_max('nomor_urut')
    //         ->where('jenis_surat_slug', $jenis_surat_slug)
    //         ->where('tahun', (int) $tahun)
    //         ->get($this->table)
    //         ->row();

    //     $last = ($row && $row->nomor_urut !== null) ? (int) $row->nomor_urut : 0;

    //     return $last + 1;
    // }
    
    ## di atas ini adalah ambil last nomor urut, di bawah ini adalah cari gap nomor urut terkecil yang tersedia

    public function get_next_nomor_urut($jenis_surat_slug, $tahun)
    {
        // Ambil semua nomor urut yang sudah terpakai untuk jenis+tahun ini
        $result = $this->db
            ->select('nomor_urut')
            ->where('jenis_surat_slug', $jenis_surat_slug)
            ->where('tahun', (int) $tahun)
            ->order_by('nomor_urut', 'ASC')
            ->get($this->table)
            ->result();

        if (empty($result)) {
            return 1;
        }

        $used = array_map(function($row) {
            return (int) $row->nomor_urut;
        }, $result);

        // Cari gap terkecil mulai dari 1
        $candidate = 1;
        foreach ($used as $nomor) {
            if ($nomor == $candidate) {
                $candidate++;
            } elseif ($nomor > $candidate) {
                // Ada gap, candidate ini tersedia
                break;
            }
        }

        return $candidate;
    }
}