<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposisi_surat_model extends CI_Model
{
    protected $table          = 'disposisi_surat';
    protected $table_penerima = 'disposisi_penerima';

    // ----------------------------------------------------------------
    // DISPOSISI
    // ----------------------------------------------------------------

    public function get_all()
    {
        $this->db->select('d.*, sm.nomor_surat, sm.perihal AS perihal_surat, sm.asal_surat');
        $this->db->select('(SELECT COUNT(*) FROM disposisi_penerima dp WHERE dp.disposisi_id = d.id) AS jumlah_penerima');
        $this->db->from('disposisi_surat d');
        $this->db->join('surat_masuk sm', 'sm.id = d.surat_masuk_id', 'left');
        $this->db->order_by('d.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('d.*, sm.nomor_surat, sm.perihal AS perihal_surat, sm.asal_surat, sm.nomor_agenda');
        $this->db->from('disposisi_surat d');
        $this->db->join('surat_masuk sm', 'sm.id = d.surat_masuk_id', 'left');
        $this->db->where('d.id', (int) $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int) $id)->delete($this->table);
    }

    public function check_duplicate_nomor($nomor, $exclude_id = null)
    {
        $this->db->where('nomor_disposisi', $nomor);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    public function get_next_nomor($tahun)
    {
        $prefix = 'DSP-' . $tahun . '-';

        $result = $this->db
            ->select('nomor_disposisi')
            ->like('nomor_disposisi', $prefix, 'after')
            ->order_by('nomor_disposisi', 'ASC')
            ->get($this->table)
            ->result();

        if (empty($result)) {
            return 'DSP-' . $tahun . '-' . str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $used = [];
        foreach ($result as $row) {
            $parts = explode('-', $row->nomor_disposisi);
            if (count($parts) === 3 && is_numeric($parts[2])) {
                $used[] = (int) $parts[2];
            }
        }

        sort($used);
        $candidate = 1;
        foreach ($used as $n) {
            if ($n == $candidate) $candidate++;
            elseif ($n > $candidate) break;
        }

        return 'DSP-' . $tahun . '-' . str_pad($candidate, 3, '0', STR_PAD_LEFT);
    }

    // Update status disposisi otomatis berdasarkan status penerima
    public function sync_status($disposisi_id)
    {
        $penerima = $this->get_penerima($disposisi_id);
        if (empty($penerima)) return;

        $all_selesai  = TRUE;
        $all_diterima = TRUE;
        $any_dikirim  = FALSE;

        foreach ($penerima as $p) {
            if ($p->status_terima !== 'sudah') $all_diterima = FALSE;
            if ($p->status_kirim  !== 'sudah') $all_selesai  = FALSE;
            if ($p->status_kirim  === 'sudah') $any_dikirim  = TRUE;
        }

        if ($all_diterima) {
            $status = 'selesai';
        } elseif ($all_selesai || $any_dikirim) {
            $status = 'dikirim';
        } else {
            $status = 'draft';
        }

        $this->db->where('id', (int) $disposisi_id)->update($this->table, ['status' => $status]);
    }

    // ----------------------------------------------------------------
    // PENERIMA
    // ----------------------------------------------------------------

    public function get_penerima($disposisi_id)
    {
        return $this->db
            ->where('disposisi_id', (int) $disposisi_id)
            ->order_by('id', 'ASC')
            ->get($this->table_penerima)
            ->result();
    }

    public function get_penerima_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->limit(1)
            ->get($this->table_penerima)
            ->row();
    }

    public function insert_penerima($data)
    {
        $this->db->insert($this->table_penerima, $data);
        return $this->db->insert_id();
    }

    public function update_penerima($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table_penerima, $data);
    }

    public function delete_penerima($id)
    {
        return $this->db->where('id', (int) $id)->delete($this->table_penerima);
    }

    public function get_surat_masuk_options()
    {
        return $this->db
            ->select('id, nomor_agenda, nomor_surat, perihal, asal_surat')
            ->order_by('id', 'DESC')
            ->get('surat_masuk')
            ->result();
    }
}