<form method="post"
      action="<?= base_url('disposisi-surat/kirim/' . $disposisi->id . '/' . $penerima->id); ?>"
      enctype="multipart/form-data"
      id="formKirimBukti"
      class="dsp-bukti-form">

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="ttd_pengirim_data" id="ttdPengirimData">

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Nama Pengirim <span class="req">*</span></label>
        <input type="text" name="nama_pengirim" class="form-control"
               placeholder="Nama lengkap yang mengirimkan"
               value="<?= html_escape($penerima->nama_pengirim ?? ''); ?>">
    </div>

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Foto Bukti Pengiriman</label>
        <?php if ($penerima->foto_bukti_kirim): ?>
            <div style="margin-bottom:6px;">
                <img src="<?= base_url($penerima->foto_bukti_kirim); ?>"
                     style="max-height:80px;border-radius:6px;border:1px solid var(--cream-dark);" alt="Bukti">
                <div class="field-note">Upload baru untuk mengganti.</div>
            </div>
        <?php endif; ?>
        <input type="file" name="foto_bukti_kirim" class="form-control dsp-file-input"
               accept="image/jpeg,image/png,image/gif">
        <div class="field-note">Format: JPG, PNG, GIF. Maks 5MB.</div>
    </div>

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Tanda Tangan Pengirim</label>
        <div class="signature-wrap" id="sigWrapKirim">
            <canvas id="sigCanvasKirim" class="sig-canvas"></canvas>
            <div class="sig-toolbar">
                <span class="sig-hint">Tanda tangan di kotak ini</span>
                <button type="button" class="sig-clear-btn" onclick="clearSig('sigCanvasKirim', 'ttdPengirimData')">
                    <span class="material-icons">refresh</span>Hapus
                </button>
            </div>
        </div>
        <?php if ($penerima->ttd_pengirim): ?>
            <div class="field-note" style="margin-top:4px;">Tanda tangan lama akan diganti jika Anda menggambar yang baru.</div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary" onclick="prepSig('sigCanvasKirim','ttdPengirimData')">
        <span class="material-icons">save</span>Simpan Bukti Pengiriman
    </button>
</form>