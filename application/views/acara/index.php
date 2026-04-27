<div class="page active">

    <?php if ($this->session->flashdata('crud_success')): ?>
        <div class="alert-flash alert-flash-success">
            <span class="material-icons">check_circle</span>
            <?= $this->session->flashdata('crud_success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('crud_error')): ?>
        <div class="alert-flash alert-flash-error">
            <span class="material-icons">error_outline</span>
            <?= $this->session->flashdata('crud_error'); ?>
        </div>
    <?php endif; ?>

    <!-- Stats Strip -->
    <div class="sm-stats-strip">
        <?php
        $today     = date('Y-m-d');
        $total     = count($rows);
        $hari_ini  = count(array_filter($rows, fn($r) => $r->tanggal_acara === $today));
        $akan_dtg  = count(array_filter($rows, fn($r) => $r->tanggal_acara > $today));
        $lewat     = count(array_filter($rows, fn($r) => $r->tanggal_acara < $today));
        ?>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:var(--sogan);">event_note</span>
            <div>
                <div class="sm-stat-val"><?= $total; ?></div>
                <div class="sm-stat-lbl">Total Acara</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#C62828;">today</span>
            <div>
                <div class="sm-stat-val" style="color:#C62828;"><?= $hari_ini; ?></div>
                <div class="sm-stat-lbl">Hari Ini</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#1565C0;">upcoming</span>
            <div>
                <div class="sm-stat-val" style="color:#1565C0;"><?= $akan_dtg; ?></div>
                <div class="sm-stat-lbl">Akan Datang</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#aaa;">event_busy</span>
            <div>
                <div class="sm-stat-val" style="color:#aaa;"><?= $lewat; ?></div>
                <div class="sm-stat-lbl">Sudah Lewat</div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchAcara" placeholder="Cari perihal, tempat, asal surat...">
        </div>

        <select class="filter-select" id="filterWaktuAcara">
            <option value="">Semua Waktu</option>
            <option value="hari_ini">Hari Ini</option>
            <option value="akan_datang">Akan Datang</option>
            <option value="lewat">Sudah Lewat</option>
        </select>
    </div>

    <!-- Tabel -->
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table" id="tableAcara">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Agenda</th>
                        <th>Asal Surat</th>
                        <th>Tanggal Acara</th>
                        <th>Jam</th>
                        <th>Tempat</th>
                        <th>Perihal Acara</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <?php
                            $tgl       = $row->tanggal_acara;
                            $waktu_key = $tgl === $today ? 'hari_ini' : ($tgl > $today ? 'akan_datang' : 'lewat');
                            ?>
                            <tr
                                data-search="<?= strtolower(html_escape($row->perihal_acara . ' ' . $row->tempat_acara . ' ' . $row->asal_surat . ' ' . $row->nomor_agenda)); ?>"
                                data-waktu="<?= $waktu_key; ?>"
                            >
                                <td><?= $no++; ?></td>
                                <td>
                                    <code class="nomor-agenda-code"><?= html_escape($row->nomor_agenda); ?></code>
                                </td>
                                <td class="td-asal"><?= html_escape($row->asal_surat ?? '-'); ?></td>
                                <td>
                                    <span style="font-weight:600;color:<?= $tgl === $today ? '#C62828' : ($tgl > $today ? '#1565C0' : '#aaa'); ?>">
                                        <?= html_escape(date('d/m/Y', strtotime($tgl))); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-family:monospace;font-size:13px;font-weight:600;">
                                        <?= html_escape(substr($row->jam_acara, 0, 5)); ?>
                                    </span>
                                </td>
                                <td class="td-perihal"><?= html_escape($row->tempat_acara); ?></td>
                                <td class="td-perihal"><?= html_escape($row->perihal_acara); ?></td>
                                <td>
                                    <?php if ($tgl === $today): ?>
                                        <span class="badge badge-red">Hari Ini</span>
                                    <?php elseif ($tgl > $today): ?>
                                        <span class="badge badge-blue">Akan Datang</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Sudah Lewat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= base_url('acara/edit/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form method="post"
                                              action="<?= base_url('acara/delete/' . $row->id); ?>"
                                              class="formDeleteAcara"
                                              style="display:inline-block;">
                                            <input type="hidden"
                                                   name="<?= $this->security->get_csrf_token_name(); ?>"
                                                   value="<?= $this->security->get_csrf_hash(); ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="td-empty">
                                <span class="material-icons" style="font-size:36px;color:var(--text-muted);display:block;margin-bottom:8px;">event_busy</span>
                                Belum ada data acara.
                                <div style="font-size:12px;margin-top:6px;color:#bbb;">
                                    Data acara otomatis masuk saat mencatat surat undangan.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info -->
    <div style="margin-top:16px;padding:12px 16px;background:var(--sogan-faint,#f3f0eb);border-radius:10px;border-left:4px solid var(--sogan,#5C3317);font-size:12px;color:#666;">
        <span class="material-icons" style="font-size:14px;vertical-align:middle;color:var(--sogan);">info</span>
        Data acara berasal dari surat masuk berkategori <strong>Undangan</strong>. Input melalui menu
        <a href="<?= base_url('surat-masuk-v2/create'); ?>" style="color:var(--sogan);font-weight:600;">Tambah Surat Masuk &amp; Disposisi</a>.
    </div>

</div>