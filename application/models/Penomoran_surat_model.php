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
}