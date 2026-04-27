<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acara_model extends CI_Model
{
    protected $table = 'acara';

    public function get_all()
    {
        // Join ke surat_masuk_v2 untuk tampilkan asal_surat & perihal surat
        return $this->db
            ->select('a.*, s.asal_surat, s.perihal AS perihal_surat, s.kategori, s.status AS status_surat, s.tanggal_terima')
            ->from('acara a')
            ->join('surat_masuk_v2 s', 's.nomor_agenda = a.nomor_agenda', 'left')
            ->order_by('a.tanggal_acara', 'ASC')
            ->order_by('a.jam_acara', 'ASC')
            ->get()
            ->result();
    }

    public function get_upcoming()
    {
        return $this->db
            ->select('a.*, s.asal_surat, s.perihal AS perihal_surat')
            ->from('acara a')
            ->join('surat_masuk_v2 s', 's.nomor_agenda = a.nomor_agenda', 'left')
            ->where('a.tanggal_acara >=', date('Y-m-d'))
            ->order_by('a.tanggal_acara', 'ASC')
            ->order_by('a.jam_acara', 'ASC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('a.*, s.asal_surat, s.perihal AS perihal_surat, s.kategori, s.status AS status_surat')
            ->from('acara a')
            ->join('surat_masuk_v2 s', 's.nomor_agenda = a.nomor_agenda', 'left')
            ->where('a.id', (int) $id)
            ->limit(1)
            ->get()
            ->row();
    }

    public function get_by_nomor_agenda($nomor_agenda)
    {
        return $this->db
            ->where('nomor_agenda', $nomor_agenda)
            ->order_by('tanggal_acara', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function insert_id()
    {
        return $this->db->insert_id();
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

    public function count_upcoming()
    {
        return $this->db
            ->where('tanggal_acara >=', date('Y-m-d'))
            ->count_all_results($this->table);
    }

    public function count_today()
    {
        return $this->db
            ->where('tanggal_acara', date('Y-m-d'))
            ->count_all_results($this->table);
    }
}