<form method="post"
      action="<?= base_url('disposisi-surat/terima/' . $disposisi->id . '/' . $penerima->id); ?>"
      enctype="multipart/form-data"
      id="formTerimaBukti"
      class="dsp-bukti-form"
      style="margin-top:0;">

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="ttd_penerima_data" id="ttdPenerimaData">

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Nama Penerima <span class="req">*</span></label>
        <input type="text" name="nama_penerima_ttd" class="form-control"
               placeholder="Nama lengkap yang menerima"
               value="<?= html_escape($penerima->nama_penerima_ttd ?? ''); ?>">
    </div>

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Foto Bukti Penerimaan</label>
        <?php if ($penerima->foto_bukti_terima): ?>
            <div style="margin-bottom:6px;">
                <img src="<?= base_url($penerima->foto_bukti_terima); ?>"
                     style="max-height:80px;border-radius:6px;border:1px solid var(--cream-dark);" alt="Bukti">
                <div class="field-note">Upload baru untuk mengganti.</div>
            </div>
        <?php endif; ?>
        <input type="file" name="foto_bukti_terima" class="form-control dsp-file-input"
               accept="image/jpeg,image/png,image/gif">
        <div class="field-note">Format: JPG, PNG, GIF. Maks 5MB.</div>
    </div>

    <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Tanda Tangan Penerima</label>
        <div class="signature-wrap" id="sigWrapTerima">
            <canvas id="sigCanvasTerima" class="sig-canvas"></canvas>
            <div class="sig-toolbar">
                <span class="sig-hint">Tanda tangan di kotak ini</span>
                <button type="button" class="sig-clear-btn" onclick="clearSig('sigCanvasTerima','ttdPenerimaData')">
                    <span class="material-icons">refresh</span>Hapus
                </button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" onclick="prepSig('sigCanvasTerima','ttdPenerimaData')">
        <span class="material-icons">save</span>Simpan Bukti Penerimaan
    </button>
</form>