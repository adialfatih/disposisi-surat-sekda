<div class="page active">
    <?php if ($this->session->flashdata('crud_success')): ?>
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:10px;background:#E8F5E9;color:#2E7D32;font-size:13px;line-height:1.6;">
            <?= $this->session->flashdata('crud_success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('crud_error')): ?>
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:10px;background:#FFEBEE;color:#C62828;font-size:13px;line-height:1.6;">
            <?= $this->session->flashdata('crud_error'); ?>
        </div>
    <?php endif; ?>

    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchPenomoranSurat" placeholder="Cari perihal, pengolah, tujuan..." readonly>
        </div>

        <select class="filter-select" id="filterJenisSurat">
            <option value="">Semua Jenis Surat</option>
            <option value="setda-surat-keluar">SETDA - Surat Keluar</option>
            <option value="setda-sppd">SETDA - SPPD</option>
            <option value="umum-surat-keluar">UMUM - Surat Keluar</option>
            <option value="umum-sppd">UMUM - SPPD</option>
            <option value="nota-dinas">NOTA DINAS</option>
        </select>

        <button type="button" class="btn btn-primary" id="btnTambahNomorSurat">
            <span class="material-icons">add</span>Tambah Nomor Surat
        </button>

        <button type="button" class="btn btn-outline" id="btnExportPenomoran">
            <span class="material-icons">file_download</span>Export
        </button>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Surat</th>
                        <th>Kode Keamanan</th>
                        <th>Nomor Urut</th>
                        <th>Kode Klasifikasi</th>
                        <th>Kode Umum</th>
                        <th>Tahun</th>
                        <th>Tanggal Surat</th>
                        <th>Perihal</th>
                        <th>Pengolah</th>
                        <th>Tujuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= html_escape($row->jenis_surat_label); ?></td>
                                <td><?= html_escape($row->kode_keamanan); ?></td>
                                <td><?= (int) $row->nomor_urut; ?></td>
                                <td><?= html_escape($row->kode_klasifikasi); ?></td>
                                <td><?= html_escape($row->kode_umum ?: '-'); ?></td>
                                <td><?= (int) $row->tahun; ?></td>
                                <td><?= html_escape($row->tanggal_surat); ?></td>
                                <td><?= html_escape($row->perihal); ?></td>
                                <td><?= html_escape($row->pengolah); ?></td>
                                <td><?= html_escape($row->tujuan); ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= base_url('penomoran-surat/detail/' . $row->id); ?>" class="btn btn-outline btn-sm" title="Detail">
                                            <span class="material-icons">visibility</span>
                                        </a>

                                        <a href="<?= base_url('penomoran-surat/edit/' . $row->id); ?>" class="btn btn-outline btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>

                                        <form method="post" action="<?= base_url('penomoran-surat/delete/' . $row->id); ?>" class="formDeleteSurat" style="display:inline-block;">
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
                            <td colspan="12" style="text-align:center;">Belum ada data penomoran surat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>