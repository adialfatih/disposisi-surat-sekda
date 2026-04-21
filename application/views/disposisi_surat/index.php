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
        $total    = count($rows);
        $draft    = count(array_filter($rows, fn($r) => $r->status === 'draft'));
        $dikirim  = count(array_filter($rows, fn($r) => $r->status === 'dikirim'));
        $diterima = count(array_filter($rows, fn($r) => $r->status === 'diterima'));
        $selesai  = count(array_filter($rows, fn($r) => $r->status === 'selesai'));
        ?>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:var(--sogan);">assignment</span>
            <div><div class="sm-stat-val"><?= $total ?></div><div class="sm-stat-lbl">Total</div></div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#9C7B62;">drafts</span>
            <div><div class="sm-stat-val" style="color:#9C7B62;"><?= $draft ?></div><div class="sm-stat-lbl">Draft</div></div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#1565C0;">send</span>
            <div><div class="sm-stat-val" style="color:#1565C0;"><?= $dikirim ?></div><div class="sm-stat-lbl">Dikirim</div></div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#F57F17;">move_to_inbox</span>
            <div><div class="sm-stat-val" style="color:#F57F17;"><?= $diterima ?></div><div class="sm-stat-lbl">Diterima</div></div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#2E7D32;">task_alt</span>
            <div><div class="sm-stat-val" style="color:#2E7D32;"><?= $selesai ?></div><div class="sm-stat-lbl">Selesai</div></div>
        </div>
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchDisposisi" placeholder="Cari nomor, perihal, perintah...">
        </div>
        <select class="filter-select" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="dikirim">Dikirim</option>
            <option value="diterima">Diterima</option>
            <option value="selesai">Selesai</option>
        </select>
        <a href="<?= base_url('disposisi-surat/create'); ?>" class="btn btn-primary">
            <span class="material-icons">add</span>Buat Disposisi
        </a>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table" id="tableDisposisi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Disposisi</th>
                        <th>Tgl Disposisi</th>
                        <th>Surat Masuk</th>
                        <th>Perihal Surat</th>
                        <th>Perintah</th>
                        <th>Penerima</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; foreach ($rows as $row): ?>
                        <tr data-search="<?= strtolower(html_escape($row->nomor_disposisi . ' ' . $row->perihal_surat . ' ' . $row->perintah)); ?>"
                            data-status="<?= $row->status; ?>">
                            <td><?= $no++; ?></td>
                            <td><code class="nomor-agenda-code"><?= html_escape($row->nomor_disposisi); ?></code></td>
                            <td><?= date('d/m/Y', strtotime($row->tanggal_disposisi)); ?></td>
                            <td>
                                <div style="font-size:11px;color:var(--text-muted);"><?= html_escape($row->nomor_surat ?: '-'); ?></div>
                                <div style="font-size:12px;color:var(--sogan);font-weight:600;"><?= html_escape($row->asal_surat ?: '-'); ?></div>
                            </td>
                            <td class="td-perihal"><?= html_escape($row->perihal_surat ?: '-'); ?></td>
                            <td class="td-perihal"><?= html_escape($row->perintah); ?></td>
                            <td>
                                <span class="dsp-penerima-count">
                                    <span class="material-icons" style="font-size:14px;">people</span>
                                    <?= (int)$row->jumlah_penerima; ?> orang
                                </span>
                            </td>
                            <td><?php
                                $sm = [
                                    'draft'    => ['Draft',    'badge-gray'],
                                    'dikirim'  => ['Dikirim',  'badge-blue'],
                                    'diterima' => ['Diterima', 'badge-yellow'],
                                    'selesai'  => ['Selesai',  'badge-green'],
                                ];
                                $s = $sm[$row->status] ?? [$row->status, 'badge-gray'];
                                echo '<span class="badge '.$s[1].'">'.$s[0].'</span>';
                            ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="<?= base_url('disposisi-surat/detail/'.$row->id); ?>" class="btn btn-outline btn-sm" title="Detail">
                                        <span class="material-icons">visibility</span>
                                    </a>
                                    <a href="<?= base_url('disposisi-surat/edit/'.$row->id); ?>" class="btn btn-outline btn-sm" title="Edit">
                                        <span class="material-icons">edit</span>
                                    </a>
                                    <form method="post" action="<?= base_url('disposisi-surat/delete/'.$row->id); ?>" class="formDeleteDisposisi" style="display:inline-block;">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="td-empty">
                            <span class="material-icons" style="font-size:36px;color:var(--text-muted);display:block;margin-bottom:8px;">assignment</span>
                            Belum ada data disposisi.
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>