<?php
$stats = isset($stats) ? $stats : [];
$weekly_chart = isset($weekly_chart) ? $weekly_chart : ['labels' => [], 'penomoran' => [], 'surat_masuk' => []];
$activities = isset($activities) ? $activities : [];
$recent_agendas = isset($recent_agendas) ? $recent_agendas : [];

function dash_number($value)
{
    return number_format((int) $value, 0, ',', '.');
}

function dash_date($date)
{
    return $date ? date('d-m-Y', strtotime($date)) : '-';
}
?>
<div class="page active">
    <div class="stats-grid">
        <div class="stat-card c1">
            <div class="stat-icon"><span class="material-icons">confirmation_number</span></div>
            <div class="stat-val"><?= dash_number($stats['nomor_surat_terbit'] ?? 0); ?></div>
            <div class="stat-label">Nomor Surat Terbit</div>
            <div class="stat-change up">
                <span class="material-icons">calendar_month</span><?= dash_number($stats['nomor_bulan_ini'] ?? 0); ?> nomor bulan ini
            </div>
        </div>

        <div class="stat-card c2">
            <div class="stat-icon"><span class="material-icons">forward_to_inbox</span></div>
            <div class="stat-val"><?= dash_number($stats['surat_masuk'] ?? 0); ?></div>
            <div class="stat-label">Surat Masuk</div>
            <div class="stat-change up">
                <span class="material-icons">today</span><?= dash_number($stats['surat_masuk_hari_ini'] ?? 0); ?> surat baru hari ini
            </div>
        </div>

        <div class="stat-card c3">
            <div class="stat-icon"><span class="material-icons">assignment_turned_in</span></div>
            <div class="stat-val"><?= (int) ($stats['disposisi_percent'] ?? 0); ?>%</div>
            <div class="stat-label">Disposisi Selesai</div>
            <div class="stat-change up">
                <span class="material-icons">check_circle</span><?= dash_number($stats['disposisi_selesai'] ?? 0); ?> disposisi selesai
            </div>
        </div>

        <div class="stat-card c4">
            <div class="stat-icon"><span class="material-icons">pending_actions</span></div>
            <div class="stat-val"><?= dash_number($stats['menunggu_tindak_lanjut'] ?? 0); ?></div>
            <div class="stat-label">Menunggu Tindak Lanjut</div>
            <div class="stat-change down">
                <span class="material-icons">schedule</span>agenda belum selesai
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="section-title">
                Aktivitas Penomoran Mingguan
                <span style="font-size:12px;font-family:'Roboto',sans-serif;font-weight:400;color:var(--text-muted)">dokumen/hari</span>
            </div>
            <div class="chart-bars" id="chartBars"></div>
            <div class="chart-legend">
                <div class="legend-item"><div class="legend-dot" style="background:var(--sogan)"></div>Nomor Surat Keluar</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--gold-light)"></div>Agenda Surat Masuk</div>
            </div>
        </div>

        <div class="card">
            <div class="section-title">Aktivitas Terkini</div>

            <div id="activityList">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-dot <?= html_escape($activity['class']); ?>">
                                <span class="material-icons"><?= html_escape($activity['icon']); ?></span>
                            </div>
                            <div class="activity-text">
                                <strong><?= html_escape($activity['title']); ?></strong>
                                <span><?= html_escape($activity['text']); ?></span>
                            </div>
                            <div class="activity-time"><?= html_escape($activity['time']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="activity-item">
                        <div class="activity-dot dot-info"><span class="material-icons">info</span></div>
                        <div class="activity-text">
                            <strong>Belum ada aktivitas</strong>
                            <span>Aktivitas terbaru akan muncul setelah data dibuat.</span>
                        </div>
                        <div class="activity-time">-</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            Ringkasan Agenda Terbaru
            <a href="<?= base_url('surat-masuk-v2'); ?>" class="btn btn-outline btn-sm">
                <span class="material-icons">open_in_new</span>Lihat Semua
            </a>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Asal / Tujuan</th>
                        <th>Jenis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_agendas)): ?>
                        <?php foreach ($recent_agendas as $agenda): ?>
                            <tr>
                                <td><?= html_escape(dash_date($agenda['tanggal'])); ?></td>
                                <td><?= html_escape($agenda['nomor_surat'] ?: '-'); ?></td>
                                <td><?= html_escape($agenda['perihal'] ?: '-'); ?></td>
                                <td><?= html_escape($agenda['asal_tujuan'] ?: '-'); ?></td>
                                <td><span class="badge <?= html_escape($agenda['jenis_class']); ?>"><?= html_escape($agenda['jenis']); ?></span></td>
                                <td><span class="badge <?= html_escape($agenda['status_class']); ?>"><?= html_escape($agenda['status']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">Belum ada agenda terbaru.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.DASHBOARD_CHART_DATA = <?= json_encode($weekly_chart); ?>;
</script>
