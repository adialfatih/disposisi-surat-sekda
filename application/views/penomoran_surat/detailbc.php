<div class="page active">
    <div class="card">
        <div class="section-title">Detail Penomoran Surat</div>

        <div class="table-scroll">
            <table class="data-table">
                <tbody>
                    <tr>
                        <td width="25%"><strong>Jenis Surat</strong></td>
                        <td><?= html_escape($row->jenis_surat_label); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kode Keamanan</strong></td>
                        <td><?= html_escape($row->kode_keamanan); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Urut</strong></td>
                        <td><?= html_escape($row->nomor_urut); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Catatan</strong></td>
                        <td><?= nl2br(html_escape($row->catatan)); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kode Klasifikasi</strong></td>
                        <td><?= html_escape($row->kode_klasifikasi); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kode Umum</strong></td>
                        <td><?= html_escape($row->kode_umum); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tahun</strong></td>
                        <td><?= html_escape($row->tahun); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Surat</strong></td>
                        <td><?= html_escape(date('d-m-Y', strtotime($row->tanggal_surat))); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Perihal</strong></td>
                        <td><?= nl2br(html_escape($row->perihal)); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pengolah</strong></td>
                        <td><?= html_escape($row->pengolah); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tujuan</strong></td>
                        <td><?= nl2br(html_escape($row->tujuan)); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-actions" style="margin-top:20px;">
            <a href="<?= base_url('penomoran-surat/edit/' . $row->id); ?>" class="btn btn-primary">
                <span class="material-icons">edit</span>Edit
            </a>

            <a href="<?= base_url('penomoran-surat'); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali
            </a>
        </div>
    </div>
</div>