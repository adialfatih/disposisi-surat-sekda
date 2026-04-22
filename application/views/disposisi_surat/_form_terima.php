<form method="post"
      action="<?= base_url('disposisi-surat/terima/' . $disposisi->id . '/' . $penerima->id); ?>"
      enctype="multipart/form-data"
      id="formTerimaBukti"
      class="dsp-bukti-form">

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="ttd_penerima_data" id="ttdPenerimaData">

    <!-- Nama Penerima -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Nama Penerima <span class="req">*</span></label>
        <input type="text" name="nama_penerima_ttd" class="form-control"
               placeholder="Nama lengkap yang menerima"
               value="<?= html_escape($penerima->nama_penerima_ttd ?? ''); ?>">
    </div>

    <!-- Upload Foto Bukti Terima -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Foto Bukti Penerimaan</label>

        <?php if (!empty($penerima->foto_bukti_terima)): ?>
            <div class="dsp-existing-foto">
                <img src="<?= base_url($penerima->foto_bukti_terima); ?>" alt="Bukti Terima">
                <span class="field-note">Upload baru untuk mengganti foto lama.</span>
            </div>
        <?php endif; ?>

        <div class="dsp-upload-wrap">
            <label class="dsp-upload-label" for="fotoBuktiTerima">
                <span class="material-icons">add_a_photo</span>
            </label>
            <input type="file"
                   name="foto_bukti_terima"
                   id="fotoBuktiTerima"
                   class="dsp-file-hidden"
                   accept="image/jpeg,image/png,image/gif"
                   capture="environment">
        </div>

        <div id="previewTerima" class="dsp-foto-preview" style="display:none;">
            <img id="previewTerimaImg" src="" alt="Preview">
            <button type="button" class="dsp-preview-remove" onclick="removePreview('fotoBuktiTerima','previewTerima')">
                <span class="material-icons">close</span>
            </button>
        </div>

        <div class="field-note" style="margin-top:4px;">Format: JPG, PNG. Maks 5MB.</div>
    </div>

    <!-- Signature Pad Penerima -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Tanda Tangan Penerima</label>
        <div class="signature-wrap">
            <canvas id="sigCanvasTerima" class="sig-canvas"></canvas>
            <div class="sig-toolbar">
                <span class="sig-hint">Tanda tangan di kotak ini menggunakan mouse atau jari</span>
                <button type="button" class="sig-clear-btn" onclick="clearSig('sigCanvasTerima','ttdPenerimaData')">
                    <span class="material-icons">refresh</span>Hapus
                </button>
            </div>
        </div>
        <?php if (!empty($penerima->ttd_penerima)): ?>
            <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                <img src="<?= base_url($penerima->ttd_penerima); ?>"
                     style="height:40px;border:1px solid var(--cream-dark);border-radius:4px;background:#fff;"
                     alt="TTD lama">
                <span class="field-note">TTD lama. Gambar baru di atas untuk mengganti.</span>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary"
            onclick="prepSig('sigCanvasTerima','ttdPenerimaData')">
        <span class="material-icons">save</span>Simpan Bukti Penerimaan
    </button>
</form>