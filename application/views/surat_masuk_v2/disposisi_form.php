<?php
$action_url = base_url('surat-masuk-v2/disposisi-store/' . $row->id);

// Pecah penerima yang sudah tersimpan (jika ada)
$penerima_list = [];
if (!empty($row->diteruskan_kepada)) {
    $penerima_list = array_values(array_filter(
        array_map('trim', explode("\n", $row->diteruskan_kepada))
    ));
}
// Pastikan minimal 5 slot input
while (count($penerima_list) < 5) {
    $penerima_list[] = '';
}
?>

<div class="page active penomoran-page">

    <!-- Info Surat (ringkasan) -->
    <div class="form-meta-box" style="margin-bottom:16px;">
        <div class="meta-left">
            <h3>Input Disposisi Pimpinan</h3>
            <p>
                Masukkan data isian disposisi dari lembar kertas yang sudah diisi pimpinan.
                Data ini akan disimpan ke sistem dan status surat diperbarui menjadi <strong>Didisposisi</strong>.
            </p>
        </div>
        <div class="form-type-badge" style="background:#FFF3E0;color:#E65100;border:1.5px solid #FFCC80;">
            <span class="material-icons">edit_note</span>
            Input Disposisi
        </div>
    </div>

    <!-- Ringkasan surat -->
    <div style="background:var(--sogan-faint,#f3f0eb);border-radius:10px;padding:14px 18px;margin-bottom:20px;border-left:4px solid var(--sogan,#5C3317);">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--sogan);font-weight:700;margin-bottom:8px;">Surat yang Didisposisi</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px;font-size:13px;">
            <div><span style="color:#888;">No. Agenda:</span><br><strong><?= html_escape($row->nomor_agenda); ?></strong></div>
            <div><span style="color:#888;">Asal Surat:</span><br><strong><?= html_escape($row->asal_surat); ?></strong></div>
            <div><span style="color:#888;">Nomor Surat:</span><br><strong><?= html_escape($row->nomor_surat); ?></strong></div>
            <div><span style="color:#888;">Tgl Terima:</span><br><strong><?= html_escape(date('d/m/Y', strtotime($row->tanggal_terima))); ?></strong></div>
            <div style="grid-column:1/-1"><span style="color:#888;">Perihal:</span><br><strong><?= html_escape($row->perihal); ?></strong></div>
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- SECTION 1: Diteruskan Kepada -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">group</span>
                Diteruskan Kepada Srd.
            </div>

            <div style="font-size:12px;color:#888;margin-bottom:10px;">
                Isi nama-nama penerima disposisi sesuai yang dicentang pimpinan (maks. 5 penerima).
            </div>

            <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="form-group" style="margin-bottom:8px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:13px;color:#888;min-width:20px;text-align:right;"><?= $i + 1; ?>.</span>
                    <input
                        type="text"
                        name="penerima[]"
                        class="form-control"
                        maxlength="150"
                        placeholder="Nama pejabat / unit / bagian..."
                        value="<?= html_escape($penerima_list[$i] ?? ''); ?>"
                        style="flex:1;"
                    >
                </div>
            </div>
            <?php endfor; ?>

            <div class="field-note" style="margin-top:4px;">
                Kosongkan baris yang tidak digunakan.
            </div>
        </div>

        <!-- SECTION 2: Instruksi / Dengan Hormat Harap -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">checklist</span>
                Dengan Hormat Harap (Instruksi)
            </div>

            <div class="form-grid cols-2">

                <div class="form-group" style="display:flex;align-items:flex-start;gap:10px;">
                    <label class="sm-checkbox-label">
                        <input type="checkbox"
                               name="instruksi_tanggapan"
                               value="1"
                               <?= !empty($row->instruksi_tanggapan) ? 'checked' : ''; ?>
                               class="sm-checkbox">
                        <span class="sm-checkbox-box"></span>
                        <span>Tanggapan dan Saran</span>
                    </label>
                </div>

                <div class="form-group" style="display:flex;align-items:flex-start;gap:10px;">
                    <label class="sm-checkbox-label">
                        <input type="checkbox"
                               name="instruksi_proses_lanjut"
                               value="1"
                               <?= !empty($row->instruksi_proses_lanjut) ? 'checked' : ''; ?>
                               class="sm-checkbox">
                        <span class="sm-checkbox-box"></span>
                        <span>Proses lebih lanjut</span>
                    </label>
                </div>

                <div class="form-group" style="display:flex;align-items:flex-start;gap:10px;">
                    <label class="sm-checkbox-label">
                        <input type="checkbox"
                               name="instruksi_koordinasi"
                               value="1"
                               <?= !empty($row->instruksi_koordinasi) ? 'checked' : ''; ?>
                               class="sm-checkbox">
                        <span class="sm-checkbox-box"></span>
                        <span>Koordinasi / Konfirmasi</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Instruksi Lainnya</label>
                    <input
                        type="text"
                        name="instruksi_lainnya"
                        class="form-control"
                        maxlength="255"
                        placeholder="Tulis instruksi lainnya jika ada..."
                        value="<?= html_escape($row->instruksi_lainnya ?? ''); ?>"
                    >
                    <div class="field-note">Opsional. Instruksi khusus yang dituliskan pimpinan.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: Catatan -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">sticky_note_2</span>
                Catatan Disposisi
            </div>

            <div class="form-group">
                <textarea
                    name="catatan_disposisi"
                    class="form-control"
                    rows="4"
                    placeholder="Catatan dari pimpinan (opsional)..."
                    style="resize:vertical;"
                ><?= html_escape($row->catatan_disposisi ?? ''); ?></textarea>
                <div class="field-note">Opsional. Catatan tambahan dari pimpinan di lembar disposisi.</div>
            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="background:#E65100;border-color:#E65100;">
                    <span class="material-icons">save</span>Simpan Disposisi
                </button>
                <a href="<?= base_url('surat-masuk-v2/cetak/' . $row->id); ?>"
                   target="_blank"
                   class="btn btn-outline"
                   style="color:#6A1B9A;border-color:#6A1B9A;">
                    <span class="material-icons">print</span>Cetak Ulang
                </a>
                <a href="<?= base_url('surat-masuk-v2/detail/' . $row->id); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>

<style>
/* Custom checkbox styling untuk form disposisi */
.sm-checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    user-select: none;
    padding: 10px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    transition: border-color .2s, background .2s;
    width: 100%;
}
.sm-checkbox-label:hover {
    border-color: var(--sogan, #5C3317);
    background: var(--sogan-faint, #f3f0eb);
}
.sm-checkbox {
    display: none;
}
.sm-checkbox-box {
    width: 20px;
    height: 20px;
    border: 2px solid #bbb;
    border-radius: 4px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    background: #fff;
}
.sm-checkbox:checked ~ .sm-checkbox-box {
    background: #E65100;
    border-color: #E65100;
}
.sm-checkbox:checked ~ .sm-checkbox-box::after {
    content: '';
    display: block;
    width: 5px;
    height: 9px;
    border: 2px solid #fff;
    border-top: none;
    border-left: none;
    transform: rotate(45deg) translateY(-1px);
}
.sm-checkbox:checked ~ span:last-child {
    font-weight: 700;
    color: #E65100;
}
</style>