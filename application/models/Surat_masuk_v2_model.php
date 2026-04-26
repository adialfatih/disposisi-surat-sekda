<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk_v2_model extends CI_Model
{
    protected $table = 'surat_masuk_v2';

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

    public function check_duplicate_agenda($nomor_agenda, $exclude_id = null)
    {
        $this->db->where('nomor_agenda', $nomor_agenda);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    public function get_next_nomor_agenda($tahun)
    {
        $prefix = 'SM-' . $tahun . '-';

        $result = $this->db
            ->select('nomor_agenda')
            ->like('nomor_agenda', $prefix, 'after')
            ->order_by('nomor_agenda', 'ASC')
            ->get($this->table)
            ->result();

        if (empty($result)) {
            return str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $used = [];
        foreach ($result as $row) {
            $parts = explode('-', $row->nomor_agenda);
            if (count($parts) === 3 && is_numeric($parts[2])) {
                $used[] = (int) $parts[2];
            }
        }

        sort($used);

        $candidate = 1;
        foreach ($used as $nomor) {
            if ($nomor == $candidate) {
                $candidate++;
            } elseif ($nomor > $candidate) {
                break;
            }
        }

        return str_pad($candidate, 3, '0', STR_PAD_LEFT);
    }

    public function get_next_nomor_ajax($tahun)
    {
        $urut = $this->get_next_nomor_agenda($tahun);
        return 'SM-' . $tahun . '-' . $urut;
    }

    // Statistik ringkasan
    public function count_by_status($status)
    {
        return $this->db
            ->where('status', $status)
            ->count_all_results($this->table);
    }
}