<?php
$is_edit    = ($mode === 'edit');
$action_url = $is_edit
    ? base_url('surat-masuk-v2/update/' . $row->id)
    : base_url('surat-masuk-v2/store');

function smv2_old($ci, $row, $field, $default = '')
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
                Setelah disimpan, cetak lembar disposisi untuk diserahkan ke pimpinan.
            </p>
        </div>
        <div class="form-type-badge">
            <span class="material-icons">assignment</span>
            Surat Masuk &amp; Disposisi
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- SECTION 1: Identitas & Agenda -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">confirmation_number</span>
                Identitas &amp; Agenda
            </div>

            <div class="form-grid cols-3">

                <!-- Nomor Agenda (auto) -->
                <div class="form-group">
                    <label class="form-label">Nomor Agenda <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input
                            type="text"
                            name="nomor_agenda"
                            id="inputNomorAgendaV2"
                            class="form-control"
                            maxlength="30"
                            placeholder="Otomatis terisi..."
                            value="<?= html_escape(smv2_old($this, $row, 'nomor_agenda')); ?>"
                            <?= !$is_edit ? 'readonly' : ''; ?>
                            style="padding-right:110px;"
                        >
                        <?php if (!$is_edit): ?>
                        <span id="nomorAgendaStatusV2"
                              style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                     font-size:11px;color:#6366F1;font-weight:600;white-space:nowrap;">
                            Memuat...
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="field-note">Format: SM-{TAHUN}-{NNN}. <?= $is_edit ? 'Ubah jika diperlukan.' : 'Terisi otomatis.'; ?></div>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori <span class="req">*</span></label>
                    <select name="kategori" class="form-control">
                        <?php foreach ($kategori_options as $val => $label): ?>
                            <option value="<?= $val; ?>"
                                <?= smv2_old($this, $row, 'kategori', 'lainnya') === $val ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="field-note">Jenis kategori surat masuk.</div>
                </div>

                <!-- Sifat Surat -->
                <div class="form-group">
                    <label class="form-label">Sifat Surat <span class="req">*</span></label>
                    <select name="sifat" class="form-control" id="selectSifat">
                        <?php foreach ($sifat_options as $val => $label): ?>
                            <option value="<?= $val; ?>"
                                <?= smv2_old($this, $row, 'sifat', 'biasa') === $val ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="field-note">Akan tercetak di lembar disposisi.</div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: Asal & Tanggal -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">send</span>
                Asal Surat &amp; Tanggal
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
                        value="<?= html_escape(smv2_old($this, $row, 'asal_surat')); ?>"
                    >
                    <div class="field-note">Nama lengkap instansi atau pengirim.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Berkas</label>
                    <input
                        type="text"
                        name="asal_berkas"
                        class="form-control"
                        maxlength="150"
                        placeholder="Contoh: Ekspedisi, Kurir, Email"
                        value="<?= html_escape(smv2_old($this, $row, 'asal_berkas')); ?>"
                    >
                    <div class="field-note">Opsional. Cara surat diterima.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Surat <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_surat"
                        class="form-control"
                        value="<?= html_escape(smv2_old($this, $row, 'tanggal_surat') ?: date('Y-m-d')); ?>"
                    >
                    <div class="field-note">Tanggal yang tertulis pada surat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Terima <span class="req">*</span></label>
                    <input
                        type="date"
                        name="tanggal_terima"
                        id="inputTanggalTerimaV2"
                        class="form-control"
                        value="<?= html_escape(smv2_old($this, $row, 'tanggal_terima') ?: date('Y-m-d')); ?>"
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
                        value="<?= html_escape(smv2_old($this, $row, 'nomor_surat')); ?>"
                    >
                    <div class="field-note">Nomor surat sesuai dokumen asli.</div>
                </div>

                <div class="form-group col-span-3">
                    <label class="form-label">Perihal / Hal <span class="req">*</span></label>
                    <input
                        type="text"
                        name="perihal"
                        class="form-control"
                        maxlength="255"
                        placeholder="Tuliskan perihal surat secara ringkas dan jelas"
                        value="<?= html_escape(smv2_old($this, $row, 'perihal')); ?>"
                    >
                    <div class="field-note">Deskripsi singkat isi/tujuan surat. Akan tercetak di lembar disposisi.</div>
                </div>

            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span>
                    <?= $is_edit ? 'Update Data' : 'Simpan &amp; Lanjut Cetak'; ?>
                </button>
                <a href="<?= base_url('surat-masuk-v2'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>


<?php if (!$is_edit): ?>
<script>
(function () {
    const inputAgenda  = document.getElementById('inputNomorAgendaV2');
    const statusEl     = document.getElementById('nomorAgendaStatusV2');
    const inputTanggal = document.getElementById('inputTanggalTerimaV2');
    const nextUrl      = '<?= base_url('surat-masuk-v2/next-agenda'); ?>';
    const csrfTokenUrl = '<?= base_url('surat-masuk-v2/csrf-token'); ?>';
    const csrfName     = '<?= $this->security->get_csrf_token_name(); ?>';

    let debounce = null;

    function getCsrfHash() {
        const el = document.querySelector('input[name="' + csrfName + '"]');
        return el ? el.value : '';
    }

    function refreshCsrfToken(callback) {
        fetch(csrfTokenUrl)
            .then(r => r.json())
            .then(data => {
                const el = document.querySelector('input[name="' + csrfName + '"]');
                if (el && data.csrf_hash) el.value = data.csrf_hash;
                if (callback) callback();
            })
            .catch(function() { if (callback) callback(); });
    }

    function fetchNextAgenda(tahun) {
        if (!tahun || tahun < 2000) return;

        statusEl.textContent = 'Memuat...';
        statusEl.style.color = '#9CA3AF';
        inputAgenda.readOnly = true;

        const body = new URLSearchParams();
        body.append('tahun', tahun);
        body.append(csrfName, getCsrfHash());

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
            refreshCsrfToken();
        })
        .catch(() => {
            inputAgenda.readOnly = false;
            statusEl.textContent = 'Gagal fetch';
            statusEl.style.color = '#EF4444';
            refreshCsrfToken();
        });
    }

    inputTanggal.addEventListener('change', function () {
        clearTimeout(debounce);
        const tahun = new Date(this.value).getFullYear();
        debounce = setTimeout(() => fetchNextAgenda(tahun), 300);
    });

    const tahunAwal = new Date(inputTanggal.value).getFullYear();
    fetchNextAgenda(tahunAwal || new Date().getFullYear());
})();
</script>
<?php endif; ?>