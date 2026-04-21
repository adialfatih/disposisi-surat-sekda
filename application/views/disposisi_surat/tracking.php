<div class="page active">
    <div class="card">

        <?php if ($this->session->flashdata('crud_success')): ?>
            <div class="alert-flash alert-flash-success" style="margin-bottom:20px;">
                <span class="material-icons">check_circle</span>
                <?= $this->session->flashdata('crud_success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('crud_error')): ?>
            <div class="alert-flash alert-flash-error" style="margin-bottom:20px;">
                <span class="material-icons">error_outline</span>
                <?= $this->session->flashdata('crud_error'); ?>
            </div>
        <?php endif; ?>

        <!-- Header Info -->
        <div class="dsp-tracking-header">
            <div>
                <div class="sm-detail-label">Tracking Disposisi</div>
                <div class="sm-detail-nomor"><?= html_escape($disposisi->nomor_disposisi); ?></div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">
                    <?= html_escape($disposisi->perihal_surat ?? '-'); ?> ·
                    <strong><?= html_escape($disposisi->asal_surat ?? '-'); ?></strong>
                </div>
            </div>
            <div class="dsp-tracking-penerima-badge">
                <span class="material-icons">person</span>
                <?= html_escape($penerima->nama_penerima); ?>
            </div>
        </div>

        <!-- Progress Visual -->
        <div class="dsp-progress" style="margin-bottom:28px;">
            <div class="dsp-step <?= $penerima->status_kirim === 'sudah' ? 'done' : 'active'; ?>">
                <div class="dsp-step-icon"><span class="material-icons"><?= $penerima->status_kirim === 'sudah' ? 'check' : 'send'; ?></span></div>
                <div class="dsp-step-info">
                    <div class="dsp-step-label">Pengiriman</div>
                    <div class="dsp-step-sub"><?= $penerima->status_kirim === 'sudah' ? 'Selesai · ' . date('d/m/Y H:i', strtotime($penerima->tgl_kirim)) : 'Belum dilakukan'; ?></div>
                </div>
            </div>
            <div class="dsp-step-connector <?= $penerima->status_kirim === 'sudah' ? 'done' : ''; ?>"></div>
            <div class="dsp-step <?= $penerima->status_terima === 'sudah' ? 'done' : ($penerima->status_kirim === 'sudah' ? 'active' : 'pending'); ?>">
                <div class="dsp-step-icon"><span class="material-icons"><?= $penerima->status_terima === 'sudah' ? 'check' : 'move_to_inbox'; ?></span></div>
                <div class="dsp-step-info">
                    <div class="dsp-step-label">Penerimaan</div>
                    <div class="dsp-step-sub"><?= $penerima->status_terima === 'sudah' ? 'Selesai · ' . date('d/m/Y H:i', strtotime($penerima->tgl_terima)) : 'Belum dilakukan'; ?></div>
                </div>
            </div>
        </div>

        <div class="dsp-tracking-panels">

            <!-- PANEL KIRI: Pengiriman -->
            <div class="dsp-tracking-panel <?= $penerima->status_kirim === 'sudah' ? 'panel-done' : 'panel-active'; ?>">
                <div class="dsp-panel-title">
                    <span class="material-icons">send</span>
                    Bukti Pengiriman
                    <?php if ($penerima->status_kirim === 'sudah'): ?>
                        <span class="badge badge-green" style="margin-left:auto;">Selesai</span>
                    <?php endif; ?>
                </div>

                <?php if ($penerima->status_kirim === 'sudah'): ?>
                    <!-- Tampilkan bukti yang sudah ada -->
                    <div class="dsp-bukti-preview">
                        <div class="dsp-bukti-row">
                            <span class="material-icons">person</span>
                            <span><strong>Pengirim:</strong> <?= html_escape($penerima->nama_pengirim ?: '-'); ?></span>
                        </div>
                        <div class="dsp-bukti-row">
                            <span class="material-icons">schedule</span>
                            <span><?= date('d F Y, H:i', strtotime($penerima->tgl_kirim)); ?> WIB</span>
                        </div>
                        <div class="dsp-bukti-files">
                            <?php if ($penerima->foto_bukti_kirim): ?>
                                <div class="dsp-file-thumb">
                                    <img src="<?= base_url($penerima->foto_bukti_kirim); ?>" alt="Bukti Kirim">
                                    <span>Foto Bukti</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($penerima->ttd_pengirim): ?>
                                <div class="dsp-file-thumb">
                                    <img src="<?= base_url($penerima->ttd_pengirim); ?>" alt="TTD Pengirim" class="ttd-preview">
                                    <span>Tanda Tangan</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <form method="post" action="<?= base_url('disposisi-surat/kirim/' . $disposisi->id . '/' . $penerima->id); ?>"
                          enctype="multipart/form-data" id="formKirim">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="ttd_pengirim_data" id="ttdPengirimData">
                        <button type="button" class="btn btn-outline btn-sm" onclick="toggleUpdateKirim()">
                            <span class="material-icons">edit</span>Perbarui Bukti
                        </button>
                    </form>
                    <div id="updateKirimPanel" style="display:none;margin-top:16px;">
                        <?php $this->load->view('disposisi_surat/_form_kirim', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>
                    </div>
                <?php else: ?>
                    <?php $this->load->view('disposisi_surat/_form_kirim', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>
                <?php endif; ?>
            </div>

            <!-- PANEL KANAN: Penerimaan -->
            <div class="dsp-tracking-panel <?= $penerima->status_terima === 'sudah' ? 'panel-done' : ($penerima->status_kirim === 'sudah' ? 'panel-active' : 'panel-locked'); ?>">
                <div class="dsp-panel-title">
                    <span class="material-icons">move_to_inbox</span>
                    Bukti Penerimaan
                    <?php if ($penerima->status_terima === 'sudah'): ?>
                        <span class="badge badge-green" style="margin-left:auto;">Selesai</span>
                    <?php elseif ($penerima->status_kirim !== 'sudah'): ?>
                        <span class="badge badge-gray" style="margin-left:auto;">Terkunci</span>
                    <?php endif; ?>
                </div>

                <?php if ($penerima->status_kirim !== 'sudah'): ?>
                    <div class="dsp-locked-msg">
                        <span class="material-icons">lock</span>
                        Selesaikan proses pengiriman terlebih dahulu.
                    </div>

                <?php elseif ($penerima->status_terima === 'sudah'): ?>
                    <div class="dsp-bukti-preview">
                        <div class="dsp-bukti-row">
                            <span class="material-icons">person</span>
                            <span><strong>Penerima:</strong> <?= html_escape($penerima->nama_penerima_ttd ?: '-'); ?></span>
                        </div>
                        <div class="dsp-bukti-row">
                            <span class="material-icons">schedule</span>
                            <span><?= date('d F Y, H:i', strtotime($penerima->tgl_terima)); ?> WIB</span>
                        </div>
                        <div class="dsp-bukti-files">
                            <?php if ($penerima->foto_bukti_terima): ?>
                                <div class="dsp-file-thumb">
                                    <img src="<?= base_url($penerima->foto_bukti_terima); ?>" alt="Bukti Terima">
                                    <span>Foto Bukti</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($penerima->ttd_penerima): ?>
                                <div class="dsp-file-thumb">
                                    <img src="<?= base_url($penerima->ttd_penerima); ?>" alt="TTD Penerima" class="ttd-preview">
                                    <span>Tanda Tangan</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleUpdateTerima()">
                        <span class="material-icons">edit</span>Perbarui Bukti
                    </button>
                    <div id="updateTerimaPanel" style="display:none;margin-top:16px;">
                        <?php $this->load->view('disposisi_surat/_form_terima', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>
                    </div>
                <?php else: ?>
                    <?php $this->load->view('disposisi_surat/_form_terima', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>
                <?php endif; ?>
            </div>

        </div><!-- end panels -->

        <div class="form-actions" style="margin-top:24px;">
            <a href="<?= base_url('disposisi-surat/detail/' . $disposisi->id); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali ke Detail
            </a>
        </div>

    </div>
</div>

<script>
function toggleUpdateKirim() {
    const p = document.getElementById('updateKirimPanel');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}
function toggleUpdateTerima() {
    const p = document.getElementById('updateTerimaPanel');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}
</script>