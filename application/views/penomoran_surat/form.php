<?php
$is_edit = ($mode === 'edit');
$action_url = $is_edit
    ? base_url('penomoran-surat/update/' . $row->id)
    : base_url('penomoran-surat/store');

function old_or_value($ci, $row, $field)
{
    $old = $ci->input->post($field);
    if ($old !== null) {
        return $old;
    }

    if ($row && isset($row->$field)) {
        return $row->$field;
    }

    return '';
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
            <h3><?= $is_edit ? 'Edit Data Penomoran Surat' : 'Input Nomor Surat Baru'; ?></h3>
            <p>
                Lengkapi data berikut dengan teliti agar nomor surat tersimpan rapi dan mudah ditelusuri kembali.
                Form akan menyesuaikan dengan jenis surat yang dipilih.
            </p>
        </div>

        <div class="form-type-badge">
            <span class="material-icons">folder</span>
            <?= html_escape($jenis_surat_label); ?>
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <input type="hidden" name="jenis_surat_slug" value="<?= html_escape($jenis_surat_slug); ?>">

        <!-- SECTION 1: Informasi Nomor Surat -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">info</span>
                Informasi Nomor Surat
            </div>

            <div class="form-grid cols-3">

                <div class="form-group">
                    <label class="form-label">Kode Keamanan</label>
                    <input
                        type="text"
                        name="kode_keamanan"
                        class="form-control"
                        maxlength="20"
                        placeholder="Contoh: B / Biasa / Rahasia"
                        value="<?= html_escape(old_or_value($this, $row, 'kode_keamanan')); ?>"
                    >
                    <div class="field-note">Opsional. Isi sesuai klasifikasi keamanan dokumen.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Urut <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input
                            type="number"
                            name="nomor_urut"
                            id="inputNomorUrut"
                            class="form-control"
                            min="1"
                            placeholder="Otomatis terisi..."
                            value="<?= html_escape(old_or_value($this, $row, 'nomor_urut')); ?>"
                            <?= !$is_edit ? 'readonly' : ''; ?>
                            style="padding-right:110px;"
                        >
                        <?php if (!$is_edit): ?>
                        <span id="nomorUrutStatus"
                              style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                     font-size:11px;color:#6366F1;font-weight:600;white-space:nowrap;">
                            Memuat...
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="field-note">
                        <?= $is_edit
                            ? 'Nomor urut tidak boleh sama pada jenis dan tahun yang sama.'
                            : 'Terisi otomatis sesuai kuota ' . (int) $daily_quota . ' nomor per hari untuk jenis surat ini.'; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun <span class="req">*</span></label>
                    <input
                        type="number"
                        name="tahun"
                        class="form-control"
                        min="2000"
                        max="2100"
                        placeholder="Contoh: <?= date('Y'); ?>"
                        value="<?= html_escape(old_or_value($this, $row, 'tahun') ?: date('Y')); ?>"
                    >
                    <div class="field-note">Gunakan tahun administrasi surat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Surat <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_surat"
                        class="form-control"
                        value="<?= html_escape(old_or_value($this, $row, 'tanggal_surat') ?: date('Y-m-d')); ?>"
                    >
                    <div class="field-note">Tanggal resmi yang tercantum pada surat.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: Kode & Klasifikasi -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">folder</span>
                Kode & Klasifikasi
            </div>

            <div class="form-grid cols-3">

                <div class="form-group">
                    <label class="form-label">Kode Klasifikasi <span class="req">*</span></label>
                    <input
                        type="text"
                        name="kode_klasifikasi"
                        class="form-control"
                        maxlength="50"
                        placeholder="Contoh: 800 / 005 / 090"
                        value="<?= html_escape(old_or_value($this, $row, 'kode_klasifikasi')); ?>"
                    >
                    <div class="field-note">Isi sesuai klasifikasi surat yang berlaku.</div>
                </div>

                <?php if ($show_kode_umum): ?>
                <div class="form-group">
                    <label class="form-label">Kode Umum <span class="req">*</span></label>
                    <input
                        type="text"
                        name="kode_umum"
                        class="form-control"
                        maxlength="50"
                        placeholder="Contoh: UM / TU / ADM"
                        value="<?= html_escape(old_or_value($this, $row, 'kode_umum')); ?>"
                    >
                    <div class="field-note">Wajib diisi untuk jenis surat umum dan nota dinas.</div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- SECTION 3: Detail Surat -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">edit_note</span>
                Detail Surat
            </div>

            <div class="form-grid cols-3">

                <div class="form-group col-span-2">
                    <label class="form-label">Perihal <span class="req">*</span></label>
                    <input
                        type="text"
                        name="perihal"
                        class="form-control"
                        maxlength="255"
                        placeholder="Tuliskan perihal surat secara jelas"
                        value="<?= html_escape(old_or_value($this, $row, 'perihal')); ?>"
                    >
                    <div class="field-note">Gunakan perihal yang singkat, jelas, dan mudah dipahami.</div>
                </div>

                <div class="form-group col-span-3">
                    <label class="form-label">Tujuan <span class="req">*</span></label>
                    <input
                        type="text"
                        name="tujuan"
                        class="form-control"
                        maxlength="255"
                        placeholder="Tujuan surat / instansi / pihak yang dituju"
                        value="<?= html_escape(old_or_value($this, $row, 'tujuan')); ?>"
                    >
                    <div class="field-note">Isi nama pihak tujuan secara lengkap agar mudah ditelusuri.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 4: Pengolah -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">person</span>
                Data Pengolah
            </div>

            <div class="form-grid cols-3">

                <div class="form-group col-span-2">
                    <label class="form-label">Pengolah <span class="req">*</span></label>
                    <input
                        type="text"
                        name="pengolah"
                        class="form-control"
                        maxlength="150"
                        placeholder="Nama bagian / petugas pengolah"
                        value="<?= html_escape(old_or_value($this, $row, 'pengolah')); ?>"
                    >
                    <div class="field-note">Nama bagian atau petugas yang mengolah surat ini.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">No. WA Pengolah <span class="req">*</span></label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                                     font-size:13px;color:var(--text-muted);font-weight:600;
                                     pointer-events:none;user-select:none;">
                            +62
                        </span>
                        <input
                            type="tel"
                            name="no_wa_pengolah"
                            class="form-control"
                            maxlength="15"
                            placeholder="8xxxxxxxxxx"
                            style="padding-left:44px;"
                            value="<?= html_escape(old_or_value($this, $row, 'no_wa_pengolah')); ?>"
                            pattern="[0-9]*"
                            inputmode="numeric"
                        >
                    </div>
                    <div class="field-note">
                        Nomor aktif tanpa awalan 0. Contoh: <strong>81234567890</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 5: Catatan (paling bawah) -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">sticky_note_2</span>
                Catatan
            </div>

            <div class="form-grid">
                <div class="form-group col-span-2">
                    <label class="form-label">Catatan Tambahan</label>
                    <input
                        type="text"
                        name="catatan"
                        class="form-control"
                        placeholder="Catatan tambahan jika diperlukan"
                        value="<?= html_escape(old_or_value($this, $row, 'catatan')); ?>"
                    >
                    <div class="field-note">Opsional. Dapat dikosongkan bila tidak ada catatan.</div>
                </div>
            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span>
                    <?= $is_edit ? 'Update Data' : 'Simpan Data'; ?>
                </button>
                <a href="<?= base_url('penomoran-surat'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>

<?php if (!$is_edit): ?>
<script>
(function () {
    const inputNomor   = document.getElementById('inputNomorUrut');
    const statusEl     = document.getElementById('nomorUrutStatus');
    const inputTahun   = document.querySelector('input[name="tahun"]');
    const inputTanggal = document.querySelector('input[name="tanggal_surat"]');
    const jenisSurat   = '<?= html_escape($jenis_surat_slug); ?>';
    const nextNomorUrl = '<?= base_url('penomoran-surat/next-nomor'); ?>';
    const csrfTokenUrl = '<?= base_url('penomoran-surat/csrf-token'); ?>';
    const csrfName     = '<?= $this->security->get_csrf_token_name(); ?>';

    let debounceTimer = null;

    function getCsrfHash() {
        const el = document.querySelector('input[name="' + csrfName + '"]');
        return el ? el.value : '';
    }

    function refreshCsrf() {
        fetch(csrfTokenUrl)
            .then(r => r.json())
            .then(d => {
                const el = document.querySelector('input[name="' + csrfName + '"]');
                if (el && d.csrf_hash) el.value = d.csrf_hash;
            })
            .catch(() => {});
    }

    function fetchNextNomor(tahun, tanggalSurat) {
        if (!tahun || tahun < 2000 || !tanggalSurat) return;

        statusEl.textContent = 'Memuat...';
        statusEl.style.color = '#9CA3AF';
        inputNomor.readOnly  = true;

        const body = new URLSearchParams();
        body.append('jenis_surat_slug', jenisSurat);
        body.append('tahun', tahun);
        body.append('tanggal_surat', tanggalSurat);
        body.append(csrfName, getCsrfHash());

        fetch(nextNomorUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.next_nomor) {
                inputNomor.value     = '';
                inputNomor.readOnly  = true;
                statusEl.textContent = 'Kuota penuh';
                statusEl.style.color = '#EF4444';
                refreshCsrf();
                return;
            }
            inputNomor.value     = data.next_nomor;
            inputNomor.readOnly  = false;
            statusEl.textContent = '✓ Auto #' + data.next_nomor;
            statusEl.style.color = '#22C55E';
            refreshCsrf();
        })
        .catch(() => {
            inputNomor.value     = 1;
            inputNomor.readOnly  = false;
            statusEl.textContent = 'Gagal fetch';
            statusEl.style.color = '#EF4444';
            refreshCsrf();
        });
    }

    inputTahun.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchNextNomor(parseInt(this.value), inputTanggal.value);
        }, 500);
    });

    inputTanggal.addEventListener('change', function () {
        fetchNextNomor(parseInt(inputTahun.value), this.value);
    });

    fetchNextNomor(parseInt(inputTahun.value), inputTanggal.value);
})();
</script>
<?php endif; ?>
</div>
