<div class="page active">
    <div class="stats-grid">
        <div class="stat-card c1">
            <div class="stat-icon"><span class="material-icons">confirmation_number</span></div>
            <div class="stat-val">1,248</div>
            <div class="stat-label">Nomor Surat Terbit</div>
            <div class="stat-change up">
                <span class="material-icons">trending_up</span>8.4% dari bulan lalu
            </div>
        </div>

        <div class="stat-card c2">
            <div class="stat-icon"><span class="material-icons">forward_to_inbox</span></div>
            <div class="stat-val">324</div>
            <div class="stat-label">Surat Masuk</div>
            <div class="stat-change up">
                <span class="material-icons">trending_up</span>12 surat baru hari ini
            </div>
        </div>

        <div class="stat-card c3">
            <div class="stat-icon"><span class="material-icons">assignment_turned_in</span></div>
            <div class="stat-val">89%</div>
            <div class="stat-label">Disposisi Selesai</div>
            <div class="stat-change up">
                <span class="material-icons">trending_up</span>+3% minggu ini
            </div>
        </div>

        <div class="stat-card c4">
            <div class="stat-icon"><span class="material-icons">pending_actions</span></div>
            <div class="stat-val">41</div>
            <div class="stat-label">Menunggu Tindak Lanjut</div>
            <div class="stat-change down">
                <span class="material-icons">trending_down</span>5 agenda belum diproses
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
                <div class="activity-item">
                    <div class="activity-dot dot-ok"><span class="material-icons">check</span></div>
                    <div class="activity-text">
                        <strong>Nomor surat berhasil diterbitkan</strong>
                        <span>Surat Undangan Rapat Koordinasi telah memperoleh nomor resmi</span>
                    </div>
                    <div class="activity-time">09:41</div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot dot-info"><span class="material-icons">mail</span></div>
                    <div class="activity-text">
                        <strong>Surat masuk baru diterima</strong>
                        <span>Surat dari Dinas Kominfo menunggu pencatatan agenda</span>
                    </div>
                    <div class="activity-time">09:15</div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot dot-prod"><span class="material-icons">assignment</span></div>
                    <div class="activity-text">
                        <strong>Disposisi baru dibuat</strong>
                        <span>Surat permohonan audiensi diteruskan ke bagian terkait</span>
                    </div>
                    <div class="activity-time">08:50</div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot dot-warn"><span class="material-icons">warning</span></div>
                    <div class="activity-text">
                        <strong>Ada agenda belum selesai</strong>
                        <span>5 surat masuk belum mendapatkan tindak lanjut</span>
                    </div>
                    <div class="activity-time">08:20</div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot dot-ok"><span class="material-icons">fiber_manual_record</span></div>
                    <div class="activity-text">
                        <strong>Sistem siap digunakan</strong>
                        <span>Seluruh modul dashboard aktif normal pagi ini</span>
                    </div>
                    <div class="activity-time">07:00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            Ringkasan Agenda Terbaru
            <a href="<?= base_url('dashboard/agenda_surat_masuk'); ?>" class="btn btn-outline btn-sm">
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
                    <tr>
                        <td>13-04-2026</td>
                        <td>800/001/SEKDA/2026</td>
                        <td>Undangan Rapat Evaluasi</td>
                        <td>Bagian Pemerintahan</td>
                        <td><span class="badge badge-blue">Keluar</span></td>
                        <td><span class="badge badge-green">Selesai</span></td>
                    </tr>
                    <tr>
                        <td>13-04-2026</td>
                        <td>005/021/DISKOMINFO/2026</td>
                        <td>Permohonan Data</td>
                        <td>Dinas Kominfo</td>
                        <td><span class="badge badge-gray">Masuk</span></td>
                        <td><span class="badge badge-yellow">Diproses</span></td>
                    </tr>
                    <tr>
                        <td>12-04-2026</td>
                        <td>800/002/SEKDA/2026</td>
                        <td>Nota Dinas Internal</td>
                        <td>Bagian Umum</td>
                        <td><span class="badge badge-blue">Keluar</span></td>
                        <td><span class="badge badge-green">Selesai</span></td>
                    </tr>
                    <tr>
                        <td>12-04-2026</td>
                        <td>100/019/BPKAD/2026</td>
                        <td>Penyampaian Laporan</td>
                        <td>BPKAD</td>
                        <td><span class="badge badge-gray">Masuk</span></td>
                        <td><span class="badge badge-red">Belum Disposisi</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
