<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function get_login_stats()
    {
        $disposisi = $this->get_disposisi_summary();

        return [
            'nomor_surat'       => $this->count_all('penomoran_surat'),
            'surat_masuk'       => $this->count_surat_masuk_total(),
            'disposisi_percent' => $disposisi['percent'],
        ];
    }

    public function get_dashboard_data()
    {
        $disposisi = $this->get_disposisi_summary();

        return [
            'stats' => [
                'nomor_surat_terbit' => $this->count_all('penomoran_surat'),
                'nomor_bulan_ini'    => $this->count_current_month('penomoran_surat', 'created_at'),
                'surat_masuk'        => $this->count_surat_masuk_total(),
                'surat_masuk_hari_ini' => $this->count_surat_masuk_today(),
                'disposisi_selesai'  => $disposisi['selesai'],
                'disposisi_percent'  => $disposisi['percent'],
                'menunggu_tindak_lanjut' => $disposisi['pending'],
            ],
            'weekly_chart'   => $this->get_weekly_chart(),
            'activities'     => $this->get_recent_activities(5),
            'recent_agendas' => $this->get_recent_agendas(6),
        ];
    }

    private function count_all($table)
    {
        return (int) $this->db->count_all_results($table);
    }

    private function count_where($table, $where)
    {
        return (int) $this->db->where($where)->count_all_results($table);
    }

    private function count_current_month($table, $date_field)
    {
        return (int) $this->db
            ->where('YEAR(' . $date_field . ') = ' . (int) date('Y'), NULL, FALSE)
            ->where('MONTH(' . $date_field . ') = ' . (int) date('m'), NULL, FALSE)
            ->count_all_results($table);
    }

    private function count_surat_masuk_total()
    {
        return $this->count_all('surat_masuk') + $this->count_all('surat_masuk_v2');
    }

    private function count_surat_masuk_today()
    {
        $today = date('Y-m-d');

        $old = (int) $this->db
            ->where('DATE(created_at) = ' . $this->db->escape($today), NULL, FALSE)
            ->count_all_results('surat_masuk');

        $new = (int) $this->db
            ->where('DATE(created_at) = ' . $this->db->escape($today), NULL, FALSE)
            ->count_all_results('surat_masuk_v2');

        return $old + $new;
    }

    private function get_disposisi_summary()
    {
        $old_total = $this->count_all('disposisi_surat');
        $old_selesai = $this->count_where('disposisi_surat', ['status' => 'selesai']);
        $old_pending = (int) $this->db
            ->where('status !=', 'selesai')
            ->count_all_results('disposisi_surat');

        $v2_total = (int) $this->db
            ->where_in('status', ['dicetak', 'didisposisi', 'selesai'])
            ->count_all_results('surat_masuk_v2');
        $v2_selesai = $this->count_where('surat_masuk_v2', ['status' => 'selesai']);
        $v2_pending = (int) $this->db
            ->where_in('status', ['dicetak', 'didisposisi'])
            ->count_all_results('surat_masuk_v2');

        $total = $old_total + $v2_total;
        $selesai = $old_selesai + $v2_selesai;

        return [
            'total' => $total,
            'selesai' => $selesai,
            'pending' => $old_pending + $v2_pending,
            'percent' => $this->percent($selesai, $total),
        ];
    }

    private function percent($part, $total)
    {
        $total = (int) $total;
        if ($total <= 0) {
            return 0;
        }

        return (int) round(((int) $part / $total) * 100);
    }

    private function get_weekly_chart()
    {
        $labels = [];
        $dates = [];
        $day_names = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        for ($i = 6; $i >= 0; $i--) {
            $timestamp = strtotime('-' . $i . ' days');
            $date = date('Y-m-d', $timestamp);
            $dates[] = $date;
            $labels[] = $day_names[(int) date('w', $timestamp)];
        }

        return [
            'labels' => $labels,
            'penomoran' => $this->counts_by_dates('penomoran_surat', 'created_at', $dates),
            'surat_masuk' => $this->counts_surat_masuk_by_dates($dates),
        ];
    }

    private function counts_by_dates($table, $date_field, $dates)
    {
        $map = array_fill_keys($dates, 0);

        $rows = $this->db
            ->select('DATE(' . $date_field . ') AS tanggal, COUNT(*) AS total', FALSE)
            ->where('DATE(' . $date_field . ') >= ' . $this->db->escape($dates[0]), NULL, FALSE)
            ->where('DATE(' . $date_field . ') <= ' . $this->db->escape($dates[count($dates) - 1]), NULL, FALSE)
            ->group_by('DATE(' . $date_field . ')')
            ->get($table)
            ->result();

        foreach ($rows as $row) {
            if (isset($map[$row->tanggal])) {
                $map[$row->tanggal] = (int) $row->total;
            }
        }

        return array_values($map);
    }

    private function counts_surat_masuk_by_dates($dates)
    {
        $old = $this->counts_by_dates('surat_masuk', 'created_at', $dates);
        $new = $this->counts_by_dates('surat_masuk_v2', 'created_at', $dates);
        $merged = [];

        foreach ($old as $i => $count) {
            $merged[] = $count + $new[$i];
        }

        return $merged;
    }

    private function get_recent_activities($limit)
    {
        $items = [];

        foreach ($this->get_latest_rows('penomoran_surat', 'created_at', 3) as $row) {
            $items[] = [
                'time_key' => $row->created_at,
                'time' => $this->format_time($row->created_at),
                'icon' => 'confirmation_number',
                'class' => 'dot-ok',
                'title' => 'Nomor surat berhasil diterbitkan',
                'text' => trim($row->nomor_surat . ' - ' . $row->perihal),
            ];
        }

        foreach ($this->get_latest_rows('surat_masuk_v2', 'created_at', 3) as $row) {
            $items[] = [
                'time_key' => $row->created_at,
                'time' => $this->format_time($row->created_at),
                'icon' => 'mail',
                'class' => 'dot-info',
                'title' => 'Surat masuk baru diterima',
                'text' => trim($row->asal_surat . ' - ' . $row->perihal),
            ];
        }

        foreach ($this->get_latest_rows('surat_masuk', 'created_at', 3) as $row) {
            $items[] = [
                'time_key' => $row->created_at,
                'time' => $this->format_time($row->created_at),
                'icon' => 'mail',
                'class' => 'dot-info',
                'title' => 'Surat masuk baru diterima',
                'text' => trim($row->asal_surat . ' - ' . $row->perihal),
            ];
        }

        foreach ($this->get_latest_rows('disposisi_surat', 'created_at', 3) as $row) {
            $items[] = [
                'time_key' => $row->created_at,
                'time' => $this->format_time($row->created_at),
                'icon' => $row->status === 'selesai' ? 'assignment_turned_in' : 'assignment',
                'class' => $row->status === 'selesai' ? 'dot-ok' : 'dot-prod',
                'title' => $row->status === 'selesai' ? 'Disposisi selesai' : 'Disposisi baru dibuat',
                'text' => trim(($row->nomor_disposisi ?: 'Disposisi') . ' - ' . ($row->manual_perihal ?: $row->perintah)),
            ];
        }

        usort($items, function($a, $b) {
            return strtotime($b['time_key']) <=> strtotime($a['time_key']);
        });

        return array_slice($items, 0, $limit);
    }

    private function get_latest_rows($table, $date_field, $limit)
    {
        return $this->db
            ->order_by($date_field, 'DESC')
            ->limit((int) $limit)
            ->get($table)
            ->result();
    }

    private function get_recent_agendas($limit)
    {
        $items = [];

        foreach ($this->get_latest_rows('penomoran_surat', 'created_at', $limit) as $row) {
            $items[] = [
                'time_key' => $row->created_at,
                'tanggal' => $row->tanggal_surat,
                'nomor_surat' => $row->nomor_surat,
                'perihal' => $row->perihal,
                'asal_tujuan' => $row->tujuan,
                'jenis' => 'Keluar',
                'jenis_class' => 'badge-blue',
                'status' => 'Selesai',
                'status_class' => 'badge-green',
            ];
        }

        foreach ($this->get_latest_rows('surat_masuk_v2', 'created_at', $limit) as $row) {
            $status = $this->map_surat_masuk_v2_status($row->status);
            $items[] = [
                'time_key' => $row->created_at,
                'tanggal' => $row->tanggal_terima,
                'nomor_surat' => $row->nomor_surat,
                'perihal' => $row->perihal,
                'asal_tujuan' => $row->asal_surat,
                'jenis' => 'Masuk',
                'jenis_class' => 'badge-gray',
                'status' => $status['label'],
                'status_class' => $status['class'],
            ];
        }

        foreach ($this->get_latest_rows('surat_masuk', 'created_at', $limit) as $row) {
            $status = $this->map_surat_masuk_status($row->status);
            $items[] = [
                'time_key' => $row->created_at,
                'tanggal' => $row->tanggal_terima,
                'nomor_surat' => $row->nomor_surat,
                'perihal' => $row->perihal,
                'asal_tujuan' => $row->asal_surat,
                'jenis' => 'Masuk',
                'jenis_class' => 'badge-gray',
                'status' => $status['label'],
                'status_class' => $status['class'],
            ];
        }

        usort($items, function($a, $b) {
            return strtotime($b['time_key']) <=> strtotime($a['time_key']);
        });

        return array_slice($items, 0, $limit);
    }

    private function map_surat_masuk_status($status)
    {
        $map = [
            'masuk' => ['label' => 'Masuk', 'class' => 'badge-blue'],
            'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
            'selesai' => ['label' => 'Selesai', 'class' => 'badge-green'],
        ];

        return isset($map[$status]) ? $map[$status] : ['label' => ucfirst((string) $status), 'class' => 'badge-gray'];
    }

    private function map_surat_masuk_v2_status($status)
    {
        $map = [
            'masuk' => ['label' => 'Masuk', 'class' => 'badge-blue'],
            'dicetak' => ['label' => 'Dicetak', 'class' => 'badge-purple'],
            'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
            'selesai' => ['label' => 'Selesai', 'class' => 'badge-green'],
        ];

        return isset($map[$status]) ? $map[$status] : ['label' => ucfirst((string) $status), 'class' => 'badge-gray'];
    }

    private function format_time($datetime)
    {
        if (empty($datetime)) {
            return '-';
        }

        return date('H:i', strtotime($datetime));
    }
}
