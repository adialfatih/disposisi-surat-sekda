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
        $dicetak    = count(array_filter($rows, fn($r) => $r->status === 'dicetak'));
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
                <div class="sm-stat-lbl">Belum Dicetak</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#6A1B9A;">print</span>
            <div>
                <div class="sm-stat-val" style="color:#6A1B9A;"><?= $dicetak; ?></div>
                <div class="sm-stat-lbl">Dicetak</div>
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

    <!-- Toolbar -->
    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchSMv2" placeholder="Cari nomor, perihal, asal surat...">
        </div>

        <select class="filter-select" id="filterKategoriV2">
            <option value="">Semua Kategori</option>
            <option value="permohonan">Permohonan</option>
            <option value="undangan">Undangan</option>
            <option value="lainnya">Lainnya</option>
        </select>

        <select class="filter-select" id="filterStatusV2">
            <option value="">Semua Status</option>
            <option value="masuk">Masuk</option>
            <option value="dicetak">Dicetak</option>
            <option value="didisposisi">Didisposisi</option>
            <option value="selesai">Selesai</option>
        </select>

        <a href="<?= base_url('surat-masuk-v2/create'); ?>" class="btn btn-primary">
            <span class="material-icons">add</span>Tambah Surat
        </a>
    </div>

    <!-- Tabel -->
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table" id="tableSMv2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Agenda</th>
                        <th>Kategori</th>
                        <th>Sifat</th>
                        <th>Tgl Terima</th>
                        <th>Asal Surat</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
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
                                <td>
                                    <?php
                                    $sifat_map = [
                                        'sangat_segera' => ['label' => 'Sangat Segera', 'class' => 'badge-red'],
                                        'segera'        => ['label' => 'Segera',        'class' => 'badge-yellow'],
                                        'rahasia'       => ['label' => 'Rahasia',       'class' => 'badge-gray'],
                                        'biasa'         => ['label' => 'Biasa',         'class' => 'badge-green'],
                                    ];
                                    $sf = $sifat_map[$row->sifat] ?? ['label' => $row->sifat, 'class' => 'badge-gray'];
                                    ?>
                                    <span class="badge <?= $sf['class']; ?>"><?= $sf['label']; ?></span>
                                </td>
                                <td><?= html_escape(date('d/m/Y', strtotime($row->tanggal_terima))); ?></td>
                                <td class="td-asal"><?= html_escape($row->asal_surat); ?></td>
                                <td>
                                    <span class="nomor-surat-inline"><?= html_escape($row->nomor_surat); ?></span>
                                </td>
                                <td class="td-perihal"><?= html_escape($row->perihal); ?></td>
                                <td>
                                    <?php
                                    $status_map = [
                                        'masuk'       => ['label' => 'Masuk',       'class' => 'badge-blue'],
                                        'dicetak'     => ['label' => 'Dicetak',     'class' => 'badge-purple'],
                                        'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
                                        'selesai'     => ['label' => 'Selesai',     'class' => 'badge-green'],
                                    ];
                                    $s = $status_map[$row->status] ?? ['label' => $row->status, 'class' => 'badge-gray'];
                                    ?>
                                    <span class="badge <?= $s['class']; ?>"><?= $s['label']; ?></span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <!-- Detail -->
                                        <a href="<?= base_url('surat-masuk-v2/detail/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Detail">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <!-- Cetak PDF -->
                                        <a href="<?= base_url('surat-masuk-v2/cetak/' . $row->id); ?>"
                                           target="_blank"
                                           class="btn btn-outline btn-sm" title="Cetak Lembar Disposisi"
                                           style="color:#6A1B9A;border-color:#6A1B9A;">
                                            <span class="material-icons">print</span>
                                        </a>
                                        <!-- Input Disposisi (jika sudah dicetak / didisposisi) -->
                                        <?php if (in_array($row->status, ['dicetak', 'didisposisi'])): ?>
                                        <a href="<?= base_url('surat-masuk-v2/disposisi/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Input Disposisi"
                                           style="color:#E65100;border-color:#E65100;">
                                            <span class="material-icons">edit_note</span>
                                        </a>
                                        <?php endif; ?>
                                        <!-- Edit -->
                                        <a href="<?= base_url('surat-masuk-v2/edit/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <!-- Hapus -->
                                        <form method="post"
                                              action="<?= base_url('surat-masuk-v2/delete/' . $row->id); ?>"
                                              class="formDeleteSMv2"
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

    <!-- Panduan alur -->
    <div style="margin-top:20px;padding:14px 18px;background:var(--sogan-faint,#f3f0eb);border-radius:10px;border-left:4px solid var(--sogan,#5C3317);">
        <div style="font-weight:700;font-size:13px;color:var(--sogan);margin-bottom:6px;display:flex;align-items:center;gap:6px;">
            <span class="material-icons" style="font-size:16px;">info</span>
            Alur Proses Surat Masuk
        </div>
        <div style="font-size:12px;color:#555;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:4px;">
                <span class="badge badge-blue" style="font-size:11px;">Masuk</span>
                <span class="material-icons" style="font-size:14px;color:#999;">arrow_forward</span>
            </span>
            <span style="display:flex;align-items:center;gap:4px;">
                <span class="material-icons" style="font-size:14px;color:#6A1B9A;">print</span>
                Cetak Lembar Disposisi
                <span class="material-icons" style="font-size:14px;color:#999;">arrow_forward</span>
            </span>
            <span style="display:flex;align-items:center;gap:4px;">
                <span class="badge badge-purple" style="font-size:11px;">Dicetak</span>
                <span class="material-icons" style="font-size:14px;color:#999;">arrow_forward</span>
            </span>
            <span style="display:flex;align-items:center;gap:4px;">
                <span class="material-icons" style="font-size:14px;color:#E65100;">edit_note</span>
                Pimpinan isi &amp; input disposisi
                <span class="material-icons" style="font-size:14px;color:#999;">arrow_forward</span>
            </span>
            <span class="badge badge-yellow" style="font-size:11px;">Didisposisi</span>
        </div>
    </div>

</div>