<form method="post"
      action="<?= base_url('disposisi-surat/kirim/' . $disposisi->id . '/' . $penerima->id); ?>"
      enctype="multipart/form-data"
      id="formKirimBukti"
      class="dsp-bukti-form">

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="ttd_pengirim_data" id="ttdPengirimData">

    <!-- Nama Pengirim -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Nama Pengirim <span class="req">*</span></label>
        <input type="text" name="nama_pengirim" class="form-control"
               placeholder="Nama lengkap yang mengirimkan"
               value="<?= html_escape($penerima->nama_pengirim ?? ''); ?>">
    </div>

    <!-- Upload Foto Bukti Kirim -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Foto Bukti Pengiriman</label>

        <?php if (!empty($penerima->foto_bukti_kirim)): ?>
            <div class="dsp-existing-foto">
                <img src="<?= base_url($penerima->foto_bukti_kirim); ?>" alt="Bukti Kirim">
                <span class="field-note">Upload baru untuk mengganti foto lama.</span>
            </div>
        <?php endif; ?>

        <div class="dsp-upload-wrap">
            <!-- <label class="dsp-upload-label" for="fotoBuktiKirim">
                <span class="material-icons">add_a_photo</span>
                <span class="dsp-upload-text">
                    <strong>Ambil Foto / Upload</strong><br>
                    <small>Kamera (mobile) atau pilih file (desktop)</small>
                </span>
            </label> -->
            <!--
                capture="environment" → buka kamera belakang di mobile
                Jika tidak didukung browser akan fallback ke file picker biasa
                Di desktop: attribute capture diabaikan, langsung file picker
            -->
            <input type="file"
                   name="foto_bukti_kirim"
                   id="fotoBuktiKirim"
                   class="dsp-file-hidden"
                   accept="image/jpeg,image/png,image/gif"
                   capture="environment">
        </div>

        <!-- Preview foto yang dipilih sebelum upload -->
        <div id="previewKirim" class="dsp-foto-preview" style="display:none;">
            <img id="previewKirimImg" src="" alt="Preview">
            <button type="button" class="dsp-preview-remove" onclick="removePreview('fotoBuktiKirim','previewKirim')">
                <span class="material-icons">close</span>
            </button>
        </div>

        <div class="field-note" style="margin-top:4px;">Format: JPG, PNG. Maks 5MB.</div>
    </div>

    <!-- Signature Pad Pengirim -->
    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Tanda Tangan Pengirim</label>
        <div class="signature-wrap">
            <canvas id="sigCanvasKirim" class="sig-canvas"></canvas>
            <div class="sig-toolbar">
                <span class="sig-hint">Tanda tangan di kotak ini menggunakan mouse atau jari</span>
                <button type="button" class="sig-clear-btn" onclick="clearSig('sigCanvasKirim','ttdPengirimData')">
                    <span class="material-icons">refresh</span>Hapus
                </button>
            </div>
        </div>
        <?php if (!empty($penerima->ttd_pengirim)): ?>
            <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                <img src="<?= base_url($penerima->ttd_pengirim); ?>"
                     style="height:40px;border:1px solid var(--cream-dark);border-radius:4px;background:#fff;"
                     alt="TTD lama">
                <span class="field-note">TTD lama. Gambar baru di atas untuk mengganti.</span>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary"
            onclick="prepSig('sigCanvasKirim','ttdPengirimData')">
        <span class="material-icons">save</span>Simpan Bukti Pengiriman
    </button>
</form>
