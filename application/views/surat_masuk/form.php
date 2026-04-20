<?php
$is_edit    = ($mode === 'edit');
$action_url = $is_edit
    ? base_url('surat-masuk/update/' . $row->id)
    : base_url('surat-masuk/store');

function sm_old($ci, $row, $field, $default = '')
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

    <?php if ($this->session->flashdata('crud_error')): ?>
        <div class="module-alert error">
            <span class="material-icons">error_outline</span>
            <?= $this->session->flashdata('crud_error'); ?>
        </div>
    <?php endif; ?>

    <div class="form-meta-box">
        <div class="meta-left">
            <h3><?= $is_edit ? 'Edit Data Surat Masuk' : 'Catat Surat Masuk Baru'; ?></h3>
            <p>
                Lengkapi seluruh data surat dengan teliti.
                Nomor agenda akan terisi otomatis sesuai tahun penerimaan.
            </p>
        </div>
        <div class="form-type-badge">
            <span class="material-icons">mail</span>
            Agenda Surat Masuk
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- SECTION 1: Identitas Surat -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">confirmation_number</span>
                Identitas & Agenda
            </div>

            <div class="form-grid cols-3">

                <!-- Nomor Agenda (auto) -->
                <div class="form-group">
                    <label class="form-label">Nomor Agenda <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input
                            type="text"
                            name="nomor_agenda"
                            id="inputNomorAgenda"
                            class="form-control"
                            maxlength="30"
                            placeholder="Otomatis terisi..."
                            value="<?= html_escape(sm_old($this, $row, 'nomor_agenda')); ?>"
                            <?= !$is_edit ? 'readonly' : ''; ?>
                            style="padding-right:110px;"
                        >
                        <?php if (!$is_edit): ?>
                        <span id="nomorAgendaStatus"
                              style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                     font-size:11px;color:#6366F1;font-weight:600;white-space:nowrap;">
                            Memuat...
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="field-note">
                        Format: SM-{TAHUN}-{NNN}. <?= $is_edit ? 'Ubah jika diperlukan.' : 'Terisi otomatis, dapat diubah manual.'; ?>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori <span class="req">*</span></label>
                    <select name="kategori" class="form-control" id="selectKategori">
                        <?php foreach ($kategori_options as $val => $label): ?>
                            <option value="<?= $val; ?>"
                                <?= sm_old($this, $row, 'kategori', 'lainnya') === $val ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="field-note">Pilih jenis kategori surat masuk.</div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="form-label">Status <span class="req">*</span></label>
                    <select name="status" class="form-control">
                        <?php foreach ($status_options as $val => $label): ?>
                            <option value="<?= $val; ?>"
                                <?= sm_old($this, $row, 'status', 'masuk') === $val ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="field-note">Status penanganan surat saat ini.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: Asal & Tanggal -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">send</span>
                Asal Surat & Tanggal
            </div>

            <div class="form-grid cols-3">

                <div class="form-group col-span-2">
                    <label class="form-label">Asal / Pengirim Surat <span class="req">*</span></label>
                    <input
                        type="text"
                        name="asal_surat"
                        class="form-control"
                        maxlength="255"
                        placeholder="Nama instansi atau perorangan pengirim"
                        value="<?= html_escape(sm_old($this, $row, 'asal_surat')); ?>"
                    >
                    <div class="field-note">Tuliskan nama lengkap instansi atau pengirim.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Berkas</label>
                    <input
                        type="text"
                        name="asal_berkas"
                        class="form-control"
                        maxlength="150"
                        placeholder="Contoh: Ekspedisi, Kurir, Email"
                        value="<?= html_escape(sm_old($this, $row, 'asal_berkas')); ?>"
                    >
                    <div class="field-note">Opsional. Cara surat diterima.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Surat <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_surat"
                        class="form-control"
                        value="<?= html_escape(sm_old($this, $row, 'tanggal_surat') ?: date('Y-m-d')); ?>"
                    >
                    <div class="field-note">Tanggal yang tertulis pada surat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Terima <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_terima"
                        id="inputTanggalTerima"
                        class="form-control"
                        value="<?= html_escape(sm_old($this, $row, 'tanggal_terima') ?: date('Y-m-d')); ?>"
                    >
                    <div class="field-note">Tanggal surat diterima oleh kantor.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: Detail Isi Surat -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">description</span>
                Detail Isi Surat
            </div>

            <div class="form-grid cols-3">

                <div class="form-group col-span-2">
                    <label class="form-label">Nomor Surat <span class="req">*</span></label>
                    <input
                        type="text"
                        name="nomor_surat"
                        class="form-control"
                        maxlength="100"
                        placeholder="Nomor surat dari pengirim"
                        value="<?= html_escape(sm_old($this, $row, 'nomor_surat')); ?>"
                    >
                    <div class="field-note">Nomor surat sesuai yang tertera pada dokumen asli.</div>
                </div>

                <div class="form-group col-span-3">
                    <label class="form-label">Perihal <span class="req">*</span></label>
                    <input
                        type="text"
                        name="perihal"
                        class="form-control"
                        maxlength="255"
                        placeholder="Tuliskan perihal surat secara ringkas dan jelas"
                        value="<?= html_escape(sm_old($this, $row, 'perihal')); ?>"
                    >
                    <div class="field-note">Deskripsi singkat isi/tujuan surat.</div>
                </div>

                <div class="form-group col-span-3">
                    <label class="form-label">Catatan</label>
                    <textarea
                        name="catatan"
                        class="form-control"
                        rows="3"
                        placeholder="Catatan tambahan jika diperlukan"
                        style="resize:vertical;"
                    ><?= html_escape(sm_old($this, $row, 'catatan')); ?></textarea>
                    <div class="field-note">Opsional. Informasi tambahan yang perlu dicatat.</div>
                </div>

            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span>
                    <?= $is_edit ? 'Update Data' : 'Simpan Data'; ?>
                </button>
                <a href="<?= base_url('surat-masuk'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>

<?php if (!$is_edit): ?>
<script>
(function () {
    const inputAgenda  = document.getElementById('inputNomorAgenda');
    const statusEl     = document.getElementById('nomorAgendaStatus');
    const inputTanggal = document.getElementById('inputTanggalTerima');
    const nextUrl      = '<?= base_url('surat-masuk/next-agenda'); ?>';
    const csrfName     = '<?= $this->security->get_csrf_token_name(); ?>';

    let debounce = null;

    function fetchNextAgenda(tahun) {
        if (!tahun || tahun < 2000) return;

        statusEl.textContent = 'Memuat...';
        statusEl.style.color = '#9CA3AF';
        inputAgenda.readOnly = true;

        const csrfHash = document.querySelector('input[name="' + csrfName + '"]').value;
        const body     = new URLSearchParams();
        body.append('tahun', tahun);
        body.append(csrfName, csrfHash);

        fetch(nextUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(r => r.json())
        .then(data => {
            inputAgenda.value    = data.next_nomor;
            inputAgenda.readOnly = false;
            statusEl.textContent = '✓ Auto';
            statusEl.style.color = '#22C55E';
        })
        .catch(() => {
            inputAgenda.readOnly = false;
            statusEl.textContent = 'Gagal fetch';
            statusEl.style.color = '#EF4444';
        });
    }

    // Trigger ulang saat tanggal terima berubah (tahun bisa beda)
    inputTanggal.addEventListener('change', function () {
        clearTimeout(debounce);
        const tahun = new Date(this.value).getFullYear();
        debounce = setTimeout(() => fetchNextAgenda(tahun), 300);
    });

    // Load pertama kali
    const tahunAwal = new Date(inputTanggal.value).getFullYear();
    fetchNextAgenda(tahunAwal || new Date().getFullYear());
})();
</script>
<?php endif; ?>