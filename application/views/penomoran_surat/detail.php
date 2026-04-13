<div class="page active">
    <div class="card">
        <div class="section-title">Detail Nomor Surat</div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px;">
            <div><strong>Jenis Surat:</strong><br><?= html_escape($row->jenis_surat_label); ?></div>
            <div><strong>Kode Keamanan:</strong><br><?= html_escape($row->kode_keamanan); ?></div>
            <div><strong>Nomor Urut:</strong><br><?= (int) $row->nomor_urut; ?></div>
            <div><strong>Catatan:</strong><br><?= html_escape($row->catatan ?: '-'); ?></div>
            <div><strong>Kode Klasifikasi:</strong><br><?= html_escape($row->kode_klasifikasi); ?></div>
            <div><strong>Kode Umum:</strong><br><?= html_escape($row->kode_umum ?: '-'); ?></div>
            <div><strong>Tahun:</strong><br><?= (int) $row->tahun; ?></div>
            <div><strong>Tanggal Surat:</strong><br><?= html_escape($row->tanggal_surat); ?></div>
            <div><strong>Perihal:</strong><br><?= html_escape($row->perihal); ?></div>
            <div><strong>Pengolah:</strong><br><?= html_escape($row->pengolah); ?></div>
            <div style="grid-column:span 2;"><strong>Tujuan:</strong><br><?= html_escape($row->tujuan); ?></div>
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