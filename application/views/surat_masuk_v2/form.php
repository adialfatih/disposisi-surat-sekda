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

<!-- ══════════════════════════════════════════════════
     MODAL INPUT ACARA (muncul jika kategori = undangan)
     ══════════════════════════════════════════════════ -->
<div id="modalAcara" class="modal-acara-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modalAcaraTitle">
    <div class="modal-acara-box">

        <!-- Header modal -->
        <div class="modal-acara-header">
            <div class="modal-acara-header-left">
                <span class="modal-acara-icon material-icons">event</span>
                <div>
                    <div id="modalAcaraTitle" class="modal-acara-title">Input Data Acara</div>
                    <div class="modal-acara-subtitle">Surat undangan ini memerlukan data acara</div>
                </div>
            </div>
        </div>

        <!-- Body modal -->
        <div class="modal-acara-body">

            <div id="modalAcaraError" class="module-alert error" style="display:none;margin-bottom:14px;">
                <span class="material-icons">error_outline</span>
                <span id="modalAcaraErrorMsg">Mohon lengkapi semua field wajib.</span>
            </div>

            <div class="form-grid cols-2" style="gap:12px;">

                <div class="form-group">
                    <label class="form-label">Tanggal Acara <span class="req">*</span></label>
                    <input type="date" id="acaraTanggal" class="form-control"
                           value="<?= date('Y-m-d'); ?>">
                    <div class="field-note">Tanggal pelaksanaan acara.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Acara <span class="req">*</span></label>
                    <input type="time" id="acaraJam" class="form-control" value="08:00">
                    <div class="field-note">Jam mulai acara.</div>
                </div>

                <div class="form-group" style="grid-column:span 2;">
                    <label class="form-label">Tempat Acara <span class="req">*</span></label>
                    <input type="text" id="acaraTempat" class="form-control"
                           maxlength="255" placeholder="Nama gedung, ruangan, atau alamat...">
                    <div class="field-note">Lokasi pelaksanaan acara.</div>
                </div>

                <div class="form-group" style="grid-column:span 2;">
                    <label class="form-label">Perihal Acara <span class="req">*</span></label>
                    <input type="text" id="acaraPerihal" class="form-control"
                           maxlength="255" placeholder="Deskripsi singkat acara...">
                    <div class="field-note">Ringkasan agenda/acara yang akan dihadiri.</div>
                </div>

                <div class="form-group" style="grid-column:span 2;">
                    <label class="form-label">Catatan Acara</label>
                    <textarea id="acaraCatatan" class="form-control" rows="3"
                              placeholder="Catatan tambahan (opsional)..." style="resize:vertical;"></textarea>
                </div>

            </div>
        </div>

        <!-- Footer modal -->
        <div class="modal-acara-footer">
            <button type="button" id="btnSimpanAcara" class="btn btn-primary">
                <span class="material-icons">save</span>
                Simpan &amp; Lanjutkan
            </button>
            <button type="button" id="btnSkipAcara" class="btn btn-outline">
                <span class="material-icons">skip_next</span>
                Lewati
            </button>
        </div>

    </div>
</div>

<!-- Style modal -->
<style>
.modal-acara-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: modalFadeIn .2s ease;
}

@keyframes modalFadeIn {
    from { opacity:0; }
    to   { opacity:1; }
}

.modal-acara-box {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 540px;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
    animation: modalSlideUp .25s ease;
    overflow: hidden;
}

@keyframes modalSlideUp {
    from { transform: translateY(24px); opacity:0; }
    to   { transform: translateY(0);    opacity:1; }
}

.modal-acara-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid #eee;
    background: linear-gradient(135deg, var(--sogan, #5C3317) 0%, #7a4520 100%);
}

.modal-acara-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-acara-icon {
    font-size: 28px !important;
    color: #fff;
    opacity: .9;
}

.modal-acara-title {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
}

.modal-acara-subtitle {
    font-size: 11px;
    color: rgba(255,255,255,.75);
    margin-top: 2px;
}

.modal-acara-body {
    padding: 20px 22px 16px;
}

.modal-acara-footer {
    padding: 14px 22px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-top: 1px solid #f0ece8;
    background: #faf8f6;
}

/* Badge saved acara */
.acara-saved-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #E8F5E9;
    color: #2E7D32;
    border: 1.5px solid #A5D6A7;
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<script>
(function () {
    // ── Nomor Agenda Auto ──────────────────────────
    const inputAgenda  = document.getElementById('inputNomorAgendaV2');
    const statusEl     = document.getElementById('nomorAgendaStatusV2');
    const inputTanggal = document.getElementById('inputTanggalTerimaV2');
    const nextUrl      = '<?= base_url('surat-masuk-v2/next-agenda'); ?>';
    const csrfTokenUrl = '<?= base_url('surat-masuk-v2/csrf-token'); ?>';
    const storeAcaraUrl= '<?= base_url('acara/store'); ?>';
    const csrfName     = '<?= $this->security->get_csrf_token_name(); ?>';

    let debounce = null;
    let acaraSaved   = false;  // flag: apakah data acara sudah disimpan
    let pendingSubmit = false; // flag: form sedang menunggu modal diselesaikan

    function getCsrfHash() {
        const el = document.querySelector('input[name="' + csrfName + '"]');
        return el ? el.value : '';
    }

    function setCsrfHash(hash) {
        const el = document.querySelector('input[name="' + csrfName + '"]');
        if (el && hash) el.value = hash;
    }

    function refreshCsrfToken(callback) {
        fetch(csrfTokenUrl)
            .then(r => r.json())
            .then(data => { setCsrfHash(data.csrf_hash); if (callback) callback(); })
            .catch(function() { if (callback) callback(); });
    }

    // ── Auto Nomor Agenda ──────────────────────────
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

    fetchNextAgenda(new Date(inputTanggal.value).getFullYear() || new Date().getFullYear());

    // ── Modal Acara ────────────────────────────────
    const selectKategori = document.querySelector('select[name="kategori"]');
    const modal          = document.getElementById('modalAcara');
    const btnSimpan      = document.getElementById('btnSimpanAcara');
    const btnSkip        = document.getElementById('btnSkipAcara');
    const errBox         = document.getElementById('modalAcaraError');
    const errMsg         = document.getElementById('modalAcaraErrorMsg');
    const mainForm       = document.querySelector('form[action*="store"]');

    function showModal() {
        modal.style.display = 'flex';
        document.getElementById('acaraTanggal').focus();
    }

    function hideModal() {
        modal.style.display = 'none';
        errBox.style.display = 'none';
    }

    function getKategori() {
        return selectKategori ? selectKategori.value : '';
    }

    // Tampilkan modal saat kategori berubah ke "undangan" (hanya create)
    if (selectKategori) {
        selectKategori.addEventListener('change', function () {
            if (this.value === 'undangan' && !acaraSaved) {
                showModal();
            }
        });
        // Jika sudah undangan saat halaman load
        if (selectKategori.value === 'undangan' && !acaraSaved) {
            showModal();
        }
    }

    // Tutup modal saat klik overlay (di luar box)
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            // Tidak boleh close dengan klik luar — paksa isi dulu
        }
    });

    // Simpan acara via AJAX, lalu submit form utama
    btnSimpan.addEventListener('click', function () {
        const tanggal = document.getElementById('acaraTanggal').value.trim();
        const jam     = document.getElementById('acaraJam').value.trim();
        const tempat  = document.getElementById('acaraTempat').value.trim();
        const perihal = document.getElementById('acaraPerihal').value.trim();
        const catatan = document.getElementById('acaraCatatan').value.trim();

        if (!tanggal || !jam || !tempat || !perihal) {
            errMsg.textContent  = 'Tanggal, jam, tempat, dan perihal acara wajib diisi.';
            errBox.style.display = 'flex';
            return;
        }

        errBox.style.display = 'none';
        btnSimpan.disabled   = true;
        btnSimpan.innerHTML  = '<span class="material-icons">hourglass_empty</span> Menyimpan...';

        const body = new URLSearchParams();
        body.append(csrfName,       getCsrfHash());
        body.append('nomor_agenda', inputAgenda.value.trim());
        body.append('surat_id',     ''); // belum ada surat_id, akan diupdate setelah store surat
        body.append('tanggal_acara', tanggal);
        body.append('jam_acara',     jam);
        body.append('tempat_acara',  tempat);
        body.append('perihal_acara', perihal);
        body.append('catatan_acara', catatan);

        fetch(storeAcaraUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(r => r.json())
        .then(function(data) {
            if (data.success) {
                // Simpan acara_id ke hidden input agar controller bisa update surat_id nanti
                let hiddenAcaraId = document.getElementById('_acara_id');
                if (!hiddenAcaraId) {
                    hiddenAcaraId = document.createElement('input');
                    hiddenAcaraId.type = 'hidden';
                    hiddenAcaraId.id   = '_acara_id';
                    hiddenAcaraId.name = 'acara_id';
                    mainForm.appendChild(hiddenAcaraId);
                }
                hiddenAcaraId.value = data.acara_id;

                // Refresh CSRF dari response
                setCsrfHash(data.csrf_hash);

                acaraSaved = true;
                hideModal();

                // Tampilkan badge acara tersimpan di form
                let badge = document.getElementById('acaraSavedBadge');
                if (!badge) {
                    badge = document.createElement('div');
                    badge.id = 'acaraSavedBadge';
                    badge.style.marginTop = '10px';
                    badge.innerHTML = '<span class="acara-saved-badge">'
                        + '<span class="material-icons" style="font-size:16px;">event_available</span>'
                        + 'Data acara tersimpan: ' + perihal + ' — ' + tanggal + ', ' + jam
                        + '</span>';
                    selectKategori.closest('.form-group').appendChild(badge);
                }
            } else {
                errMsg.textContent   = data.message || 'Gagal menyimpan acara.';
                errBox.style.display = 'flex';
            }
        })
        .catch(function() {
            errMsg.textContent   = 'Terjadi kesalahan jaringan. Coba lagi.';
            errBox.style.display = 'flex';
        })
        .finally(function() {
            btnSimpan.disabled  = false;
            btnSimpan.innerHTML = '<span class="material-icons">save</span> Simpan &amp; Lanjutkan';
        });
    });

    // Lewati modal tanpa simpan acara
    btnSkip.addEventListener('click', function() {
        hideModal();
    });

    // Intersep submit form utama: jika kategori undangan & acara belum disimpan, tampilkan modal dulu
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            if (getKategori() === 'undangan' && !acaraSaved) {
                e.preventDefault();
                showModal();
                pendingSubmit = true;
            }
        });
    }

})();
</script>
<?php endif; ?>