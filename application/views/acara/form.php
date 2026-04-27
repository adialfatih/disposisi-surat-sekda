<?php
// views/acara/form.php
// Hanya digunakan untuk EDIT acara (input baru via modal di form surat masuk)
$action_url = base_url('acara/update/' . $row->id);

function acara_old($ci, $row, $field, $default = '')
{
    $old = $ci->input->post($field);
    if ($old !== null) return $old;
    if ($row && isset($row->$field)) return $row->$field;
    return $default;
}
?>

<div class="page active penomoran-page">

    <?php if (validation_errors()): ?>
        <div class="module-alert error">
            <span class="material-icons">error_outline</span>
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <!-- Info surat terkait -->
    <div style="background:var(--sogan-faint,#f3f0eb);border-radius:10px;padding:14px 18px;margin-bottom:20px;border-left:4px solid var(--sogan,#5C3317);">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--sogan);font-weight:700;margin-bottom:8px;">
            <span class="material-icons" style="font-size:13px;vertical-align:middle;">mail</span>
            Surat Terkait
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:8px;font-size:13px;">
            <div><span style="color:#888;">No. Agenda:</span><br><strong><?= html_escape($row->nomor_agenda); ?></strong></div>
            <div><span style="color:#888;">Asal Surat:</span><br><strong><?= html_escape($row->asal_surat ?? '-'); ?></strong></div>
            <div style="grid-column:1/-1"><span style="color:#888;">Perihal Surat:</span><br><strong><?= html_escape($row->perihal_surat ?? '-'); ?></strong></div>
        </div>
    </div>

    <div class="form-meta-box">
        <div class="meta-left">
            <h3>Edit Data Acara</h3>
            <p>Perbarui informasi acara dari surat undangan ini.</p>
        </div>
        <div class="form-type-badge" style="background:#E3F2FD;color:#1565C0;border-color:#90CAF9;">
            <span class="material-icons">event</span>
            Edit Acara
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">event</span>
                Detail Acara
            </div>

            <div class="form-grid cols-2">

                <div class="form-group">
                    <label class="form-label">Tanggal Acara <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_acara"
                        class="form-control"
                        value="<?= html_escape(acara_old($this, $row, 'tanggal_acara')); ?>"
                    >
                    <div class="field-note">Tanggal pelaksanaan acara.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Acara <span class="req">*</span></label>
                    <input
                        type="time"
                        name="jam_acara"
                        class="form-control"
                        value="<?= html_escape(substr(acara_old($this, $row, 'jam_acara'), 0, 5)); ?>"
                    >
                    <div class="field-note">Jam mulai acara.</div>
                </div>

                <div class="form-group col-span-2">
                    <label class="form-label">Tempat Acara <span class="req">*</span></label>
                    <input
                        type="text"
                        name="tempat_acara"
                        class="form-control"
                        maxlength="255"
                        placeholder="Nama gedung, ruangan, atau alamat..."
                        value="<?= html_escape(acara_old($this, $row, 'tempat_acara')); ?>"
                    >
                    <div class="field-note">Lokasi pelaksanaan acara.</div>
                </div>

                <div class="form-group col-span-2">
                    <label class="form-label">Perihal Acara <span class="req">*</span></label>
                    <input
                        type="text"
                        name="perihal_acara"
                        class="form-control"
                        maxlength="255"
                        placeholder="Deskripsi singkat acara..."
                        value="<?= html_escape(acara_old($this, $row, 'perihal_acara')); ?>"
                    >
                    <div class="field-note">Ringkasan agenda/acara yang akan dihadiri.</div>
                </div>

                <div class="form-group col-span-2">
                    <label class="form-label">Catatan Acara</label>
                    <textarea
                        name="catatan_acara"
                        class="form-control"
                        rows="3"
                        placeholder="Catatan tambahan (opsional)..."
                        style="resize:vertical;"
                    ><?= html_escape(acara_old($this, $row, 'catatan_acara')); ?></textarea>
                    <div class="field-note">Opsional. Informasi tambahan terkait acara.</div>
                </div>

            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span>Update Acara
                </button>
                <a href="<?= base_url('acara'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>