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

    <!-- Stats strip -->
    <div class="sm-stats-strip">
        <?php
        $total      = count($rows);
        $masuk      = count(array_filter($rows, fn($r) => $r->status === 'masuk'));
        $disposisi  = count(array_filter($rows, fn($r) => $r->status === 'didisposisi'));
        $selesai    = count(array_filter($rows, fn($r) => $r->status === 'selesai'));
        ?>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:var(--sogan);">inbox</span>
            <div>
                <div class="sm-stat-val"><?= $total; ?></div>
                <div class="sm-stat-lbl">Total Surat</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#1565C0;">mark_email_unread</span>
            <div>
                <div class="sm-stat-val" style="color:#1565C0;"><?= $masuk; ?></div>
                <div class="sm-stat-lbl">Belum Diproses</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#F57F17;">pending_actions</span>
            <div>
                <div class="sm-stat-val" style="color:#F57F17;"><?= $disposisi; ?></div>
                <div class="sm-stat-lbl">Didisposisi</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#2E7D32;">task_alt</span>
            <div>
                <div class="sm-stat-val" style="color:#2E7D32;"><?= $selesai; ?></div>
                <div class="sm-stat-lbl">Selesai</div>
            </div>
        </div>
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchSuratMasuk" placeholder="Cari nomor, perihal, asal surat...">
        </div>

        <select class="filter-select" id="filterKategori">
            <option value="">Semua Kategori</option>
            <option value="permohonan">Permohonan</option>
            <option value="undangan">Undangan</option>
            <option value="lainnya">Lainnya</option>
        </select>

        <select class="filter-select" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="masuk">Masuk</option>
            <option value="didisposisi">Didisposisi</option>
            <option value="selesai">Selesai</option>
        </select>

        <a href="<?= base_url('surat-masuk/create'); ?>" class="btn btn-primary">
            <span class="material-icons">add</span>Tambah Surat
        </a>

        <button type="button" class="btn btn-outline" id="btnExportSuratMasuk">
            <span class="material-icons">file_download</span>Export
        </button>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table" id="tableSuratMasuk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Agenda</th>
                        <th>Kategori</th>
                        <th>Tgl Terima</th>
                        <th>Asal Surat</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Asal Berkas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <tr
                                data-search="<?= strtolower(html_escape($row->nomor_agenda . ' ' . $row->perihal . ' ' . $row->asal_surat . ' ' . $row->nomor_surat)); ?>"
                                data-kategori="<?= html_escape($row->kategori); ?>"
                                data-status="<?= html_escape($row->status); ?>"
                            >
                                <td><?= $no++; ?></td>
                                <td>
                                    <code class="nomor-agenda-code"><?= html_escape($row->nomor_agenda); ?></code>
                                </td>
                                <td>
                                    <?php
                                    $kategori_map = [
                                        'permohonan' => ['label' => 'Permohonan', 'class' => 'badge-blue'],
                                        'undangan'   => ['label' => 'Undangan',   'class' => 'badge-yellow'],
                                        'lainnya'    => ['label' => 'Lainnya',    'class' => 'badge-gray'],
                                    ];
                                    $k = $kategori_map[$row->kategori] ?? ['label' => $row->kategori, 'class' => 'badge-gray'];
                                    ?>
                                    <span class="badge <?= $k['class']; ?>"><?= $k['label']; ?></span>
                                </td>
                                <td><?= html_escape(date('d/m/Y', strtotime($row->tanggal_terima))); ?></td>
                                <td class="td-asal"><?= html_escape($row->asal_surat); ?></td>
                                <td>
                                    <span class="nomor-surat-inline"><?= html_escape($row->nomor_surat); ?></span>
                                </td>
                                <td class="td-perihal"><?= html_escape($row->perihal); ?></td>
                                <td><?= html_escape($row->asal_berkas ?: '-'); ?></td>
                                <td>
                                    <?php
                                    $status_map = [
                                        'masuk'       => ['label' => 'Masuk',       'class' => 'badge-blue'],
                                        'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
                                        'selesai'     => ['label' => 'Selesai',     'class' => 'badge-green'],
                                    ];
                                    $s = $status_map[$row->status] ?? ['label' => $row->status, 'class' => 'badge-gray'];
                                    ?>
                                    <span class="badge <?= $s['class']; ?>"><?= $s['label']; ?></span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= base_url('surat-masuk/detail/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Detail">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="<?= base_url('surat-masuk/edit/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form method="post"
                                              action="<?= base_url('surat-masuk/delete/' . $row->id); ?>"
                                              class="formDeleteSuratMasuk"
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
                            <td colspan="10" class="td-empty">
                                <span class="material-icons" style="font-size:36px;color:var(--text-muted);display:block;margin-bottom:8px;">inbox</span>
                                Belum ada data surat masuk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>