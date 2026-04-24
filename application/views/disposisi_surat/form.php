<?php
$is_edit    = ($mode === 'edit');
$action_url = $is_edit
    ? base_url('disposisi-surat/update/' . $row->id)
    : base_url('disposisi-surat/store');

function dsp_old($ci, $row, $field, $default = '')
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
            <h3><?= $is_edit ? 'Edit Disposisi' : 'Buat Disposisi Baru'; ?></h3>
            <p>Isi data disposisi dan tambahkan penerima. Tracking pengiriman & penerimaan dilakukan setelah disposisi disimpan.</p>
        </div>
        <div class="form-type-badge">
            <span class="material-icons">assignment</span>
            Agenda Disposisi
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- SECTION 1: Identitas Disposisi -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">assignment</span>
                Identitas Disposisi
            </div>
            <div class="form-grid cols-3">

                <div class="form-group">
                    <label class="form-label">Nomor Disposisi <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input type="text" name="nomor_disposisi" id="inputNomorDisposisi"
                               class="form-control" maxlength="30"
                               placeholder="Otomatis terisi..."
                               value="<?= html_escape(dsp_old($this, $row, 'nomor_disposisi')); ?>"
                               <?= !$is_edit ? 'readonly' : ''; ?>
                               style="padding-right:90px;">
                        <?php if (!$is_edit): ?>
                        <span id="nomorDspStatus" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:11px;color:#9CA3AF;font-weight:600;white-space:nowrap;">Memuat...</span>
                        <?php endif; ?>
                    </div>
                    <div class="field-note">Format: DSP-{TAHUN}-{NNN}. Terisi otomatis.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Disposisi <span class="req">*</span></label>
                    <input type="date" name="tanggal_disposisi" id="inputTanggalDsp"
                           class="form-control"
                           value="<?= html_escape(dsp_old($this, $row, 'tanggal_disposisi') ?: date('Y-m-d')); ?>">
                    <div class="field-note">Tanggal disposisi dibuat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="req">*</span></label>
                    <select name="status" class="form-control">
                        <?php
                        $statuses = ['draft' => 'Draft', 'dikirim' => 'Dikirim', 'diterima' => 'Diterima', 'selesai' => 'Selesai'];
                        foreach ($statuses as $val => $lbl):
                        ?>
                            <option value="<?= $val; ?>" <?= dsp_old($this, $row, 'status', 'draft') === $val ? 'selected' : ''; ?>>
                                <?= $lbl; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <!-- SECTION 2: Surat Masuk Terkait -->
        <!-- <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">mail</span>
                Surat Masuk yang Didisposisi
            </div>
            <div class="form-grid cols-3">
                <div class="form-group col-span-3">
                    <label class="form-label">Pilih Surat Masuk <span class="req">*</span></label>
                    <select name="surat_masuk_id" class="form-control" id="selectSuratMasuk">
                        <option value="">— Pilih surat masuk —</option>
                        <?php foreach ($surat_options as $sm): ?>
                            <option value="<?= $sm->id; ?>"
                                <?= dsp_old($this, $row, 'surat_masuk_id') == $sm->id ? 'selected' : ''; ?>>
                                [<?= html_escape($sm->nomor_agenda); ?>] <?= html_escape($sm->nomor_surat); ?> — <?= html_escape($sm->perihal); ?> (<?= html_escape($sm->asal_surat); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="field-note">Pilih surat masuk yang akan didisposisi.</div>
                </div>
            </div>
        </div> -->

        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">mail</span>
                Surat yang Didisposisi
            </div>

            <?php
            // Tentukan mode saat ini (edit atau POST ulang)
            $current_mode = 'agenda';
            if ($this->input->post('mode_surat')) {
                $current_mode = $this->input->post('mode_surat');
            } elseif ($is_edit && isset($row->mode_surat)) {
                $current_mode = $row->mode_surat;
            }
            ?>

            <!-- Toggle Mode -->
            <input type="hidden" name="mode_surat" id="inputModeSurat" value="<?= $current_mode; ?>">

            <div class="mode-toggle-wrap">
                <button type="button" class="mode-toggle-btn <?= $current_mode === 'agenda' ? 'active' : ''; ?>"
                        id="btnModeAgenda" onclick="setSuratMode('agenda')">
                    <span class="material-icons">list_alt</span>
                    Pilih dari Agenda
                </button>
                <button type="button" class="mode-toggle-btn <?= $current_mode === 'manual' ? 'active' : ''; ?>"
                        id="btnModeManual" onclick="setSuratMode('manual')">
                    <span class="material-icons">edit_note</span>
                    Input Manual
                </button>
            </div>

            <!-- Panel: Pilih dari Agenda -->
            <div id="panelAgenda" style="<?= $current_mode !== 'agenda' ? 'display:none;' : ''; ?>">
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label class="form-label">Pilih Surat Masuk <span class="req">*</span></label>
                        <select name="surat_masuk_id" class="form-control" id="selectSuratMasuk">
                            <option value="">— Pilih surat masuk dari agenda —</option>
                            <?php foreach ($surat_options as $sm): ?>
                                <option value="<?= $sm->id; ?>"
                                    <?= dsp_old($this, $row, 'surat_masuk_id') == $sm->id ? 'selected' : ''; ?>>
                                    [<?= html_escape($sm->nomor_agenda); ?>]
                                    <?= html_escape($sm->nomor_surat); ?> —
                                    <?= html_escape($sm->perihal); ?>
                                    (<?= html_escape($sm->asal_surat); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="field-note">Pilih surat masuk yang sudah tercatat di agenda surat masuk.</div>
                    </div>
                </div>
            </div>

            <!-- Panel: Input Manual -->
            <div id="panelManual" style="<?= $current_mode !== 'manual' ? 'display:none;' : ''; ?>">
                <div class="manual-surat-box">
                    <div class="manual-surat-box-header">
                        <span class="material-icons">description</span>
                        Data Surat (Input Manual)
                    </div>
                    <div class="form-grid cols-3">
                        <div class="form-group">
                            <label class="form-label">Nomor Surat <span class="req">*</span></label>
                            <input type="text" name="manual_nomor_surat" class="form-control"
                                placeholder="Contoh: 800/123/IV/2026"
                                value="<?= html_escape(dsp_old($this, $row, 'manual_nomor_surat', $is_edit ? ($row->manual_nomor_surat ?? '') : '')); ?>">
                        </div>
                        <div class="form-group col-span-2">
                            <label class="form-label">Perihal Surat <span class="req">*</span></label>
                            <input type="text" name="manual_perihal" class="form-control"
                                placeholder="Perihal/isi singkat surat"
                                value="<?= html_escape(dsp_old($this, $row, 'manual_perihal', $is_edit ? ($row->manual_perihal ?? '') : '')); ?>">
                        </div>
                        <div class="form-group col-span-3">
                            <label class="form-label">Asal Berkas / Pengirim</label>
                            <input type="text" name="manual_asal_berkas" class="form-control"
                                placeholder="Contoh: Dinas Pendidikan, BKPSDM, dll."
                                value="<?= html_escape(dsp_old($this, $row, 'manual_asal_berkas', $is_edit ? ($row->manual_asal_berkas ?? '') : '')); ?>">
                            <div class="field-note">Nama instansi/bagian/orang pengirim surat (opsional).</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Instruksi -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">edit_note</span>
                Instruksi Disposisi
            </div>
            <div class="form-grid">
                <div class="form-group col-span-2">
                    <label class="form-label">Perintah <span class="req">*</span></label>
                    <textarea name="perintah" class="form-control" rows="3"
                              placeholder="Tuliskan perintah/instruksi disposisi secara jelas"
                              style="resize:vertical;"><?= html_escape(dsp_old($this, $row, 'perintah')); ?></textarea>
                    <div class="field-note">Contoh: Harap ditindaklanjuti dan dilaporkan, Untuk diketahui, Mohon dibuat jadwal, dll.</div>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2"
                              placeholder="Catatan tambahan (opsional)"
                              style="resize:vertical;"><?= html_escape(dsp_old($this, $row, 'catatan')); ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 4: Daftar Penerima (Dynamic) -->
        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">people</span>
                Daftar Penerima Disposisi
            </div>

            <div id="penerimaContainer">
                <?php if (!empty($penerima_list)): ?>
                    <?php foreach ($penerima_list as $p): ?>
                    <div class="penerima-row">
                        <div class="penerima-input-wrap">
                            <span class="material-icons penerima-icon">person</span>
                            <input type="text" name="penerima[]" class="form-control"
                                   placeholder="Nama jabatan / bagian / orang"
                                   value="<?= html_escape($p->nama_penerima); ?>"
                                   <?= ($p->status_kirim === 'sudah') ? 'readonly title="Sudah dikirim, tidak dapat diubah"' : ''; ?>>
                            <?php if ($p->status_kirim === 'sudah'): ?>
                                <span class="badge badge-blue" style="white-space:nowrap;">
                                    <span class="material-icons" style="font-size:12px;">send</span>Terkirim
                                </span>
                            <?php else: ?>
                                <button type="button" class="btn-hapus-penerima" title="Hapus penerima ini">
                                    <span class="material-icons">close</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="penerima-row">
                        <div class="penerima-input-wrap">
                            <span class="material-icons penerima-icon">person</span>
                            <input type="text" name="penerima[]" class="form-control"
                                   placeholder="Nama jabatan / bagian / orang">
                            <button type="button" class="btn-hapus-penerima" title="Hapus">
                                <span class="material-icons">close</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" id="btnTambahPenerima" class="btn btn-outline" style="margin-top:12px;">
                <span class="material-icons">add</span>Tambah Penerima
            </button>
            <div class="field-note" style="margin-top:8px;">
                Bisa lebih dari satu penerima. Tracking pengiriman & tanda tangan dilakukan per penerima setelah disposisi disimpan.
            </div>
        </div>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span>
                    <?= $is_edit ? 'Update Disposisi' : 'Simpan Disposisi'; ?>
                </button>
                <a href="<?= base_url('disposisi-surat'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>

<?php if (!$is_edit): ?>
<script>
(function () {
    const inputNomor   = document.getElementById('inputNomorDisposisi');
    const statusEl     = document.getElementById('nomorDspStatus');
    const inputTanggal = document.getElementById('inputTanggalDsp');
    const nextUrl      = '<?= base_url('disposisi-surat/next-nomor'); ?>';
    const csrfName     = '<?= $this->security->get_csrf_token_name(); ?>';
    let debounce = null;

    function getCsrf() {
        const el = document.querySelector('input[name="' + csrfName + '"]');
        return el ? el.value : '';
    }

    function refreshCsrf(cb) {
        fetch('<?= base_url('csrf-token'); ?>')
            .then(r => r.json())
            .then(d => {
                const el = document.querySelector('input[name="' + csrfName + '"]');
                if (el && d.csrf_hash) el.value = d.csrf_hash;
                if (cb) cb();
            }).catch(cb);
    }

    function fetchNomor(tahun) {
        if (!tahun || tahun < 2000) return;
        statusEl.textContent = 'Memuat...';
        statusEl.style.color = '#9CA3AF';
        inputNomor.readOnly  = true;

        const body = new URLSearchParams();
        body.append('tahun', tahun);
        body.append(csrfName, getCsrf());

        fetch(nextUrl, { method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: body.toString() })
            .then(r => r.json())
            .then(d => {
                inputNomor.value    = d.next_nomor;
                inputNomor.readOnly = false;
                statusEl.textContent = '✓ Auto';
                statusEl.style.color = '#22C55E';
                // Update CSRF sekaligus dari response
                const el = document.querySelector('input[name="' + csrfName + '"]');
                if (el && d.csrf_hash) el.value = d.csrf_hash;
            })
            .catch(() => {
                inputNomor.readOnly = false;
                statusEl.textContent = 'Gagal';
                statusEl.style.color = '#EF4444';
                refreshCsrf();
            });
    }

    inputTanggal.addEventListener('change', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => fetchNomor(new Date(this.value).getFullYear()), 300);
    });

    fetchNomor(new Date(inputTanggal.value).getFullYear() || new Date().getFullYear());
})();
function setSuratMode(mode) {
    document.getElementById('inputModeSurat').value = mode;

    // Toggle button active state
    document.getElementById('btnModeAgenda').classList.toggle('active', mode === 'agenda');
    document.getElementById('btnModeManual').classList.toggle('active', mode === 'manual');

    // Toggle panel visibility
    document.getElementById('panelAgenda').style.display = mode === 'agenda' ? '' : 'none';
    document.getElementById('panelManual').style.display = mode === 'manual' ? '' : 'none';

    // Toggle required agar validasi HTML5 tidak konflik
    const selectAgenda = document.getElementById('selectSuratMasuk');
    selectAgenda.required = (mode === 'agenda');

    const manualFields = document.querySelectorAll('#panelManual input[name="manual_nomor_surat"], #panelManual input[name="manual_perihal"]');
    manualFields.forEach(f => f.required = (mode === 'manual'));
}

// Init on load
setSuratMode(document.getElementById('inputModeSurat').value || 'agenda');
</script>
<?php endif; ?>