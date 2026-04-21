<div class="page active">
    <div class="card">
        <?php
        $sm_status = [
            'draft'    => ['Draft',    'badge-gray'],
            'dikirim'  => ['Dikirim',  'badge-blue'],
            'diterima' => ['Diterima', 'badge-yellow'],
            'selesai'  => ['Selesai',  'badge-green'],
        ];
        $s = $sm_status[$row->status] ?? [$row->status, 'badge-gray'];
        ?>

        <!-- Header -->
        <div class="sm-detail-header">
            <div class="sm-detail-header-left">
                <div class="sm-detail-label">Nomor Disposisi</div>
                <div class="sm-detail-nomor-wrap">
                    <span id="nomorDspText" class="sm-detail-nomor"><?= html_escape($row->nomor_disposisi); ?></span>
                    <button type="button" id="btnCopyDsp" onclick="copyNomor()" class="sm-copy-btn">
                        <span class="material-icons">content_copy</span>Salin
                    </button>
                </div>
            </div>
            <div class="sm-detail-header-right">
                <span class="badge <?= $s[1]; ?>" style="font-size:13px;padding:5px 14px;"><?= $s[0]; ?></span>
            </div>
        </div>

        <!-- Info Surat Masuk -->
        <div class="dsp-surat-ref">
            <span class="material-icons">mail</span>
            <div>
                <div class="dsp-surat-ref-label">Berdasarkan Surat Masuk</div>
                <div class="dsp-surat-ref-val">
                    <strong>[<?= html_escape($row->nomor_agenda ?? '-'); ?>]</strong>
                    <?= html_escape($row->nomor_surat ?? '-'); ?> —
                    <?= html_escape($row->perihal_surat ?? '-'); ?>
                    <span style="color:var(--text-muted);"> dari <?= html_escape($row->asal_surat ?? '-'); ?></span>
                </div>
            </div>
        </div>

        <!-- Grid Detail -->
        <div class="sm-detail-grid" style="margin-bottom:24px;">
            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">calendar_today</span>Tanggal Disposisi</div>
                <div class="sm-detail-item-val"><?= date('d F Y', strtotime($row->tanggal_disposisi)); ?></div>
            </div>
            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">schedule</span>Dicatat</div>
                <div class="sm-detail-item-val"><?= date('d F Y, H:i', strtotime($row->created_at)); ?> WIB</div>
            </div>
            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label"><span class="material-icons">gavel</span>Perintah</div>
                <div class="sm-detail-item-val"><?= nl2br(html_escape($row->perintah)); ?></div>
            </div>
            <?php if (!empty($row->catatan)): ?>
            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label"><span class="material-icons">sticky_note_2</span>Catatan</div>
                <div class="sm-detail-item-val"><?= nl2br(html_escape($row->catatan)); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Daftar Penerima + Progress Tracking -->
        <div class="dsp-section-title">
            <span class="material-icons">people</span>
            Daftar Penerima & Status Tracking
        </div>

        <?php if (!empty($penerima_list)): ?>
            <div class="dsp-penerima-list">
                <?php foreach ($penerima_list as $p): ?>
                <div class="dsp-penerima-card">
                    <div class="dsp-penerima-top">
                        <div class="dsp-penerima-nama">
                            <span class="material-icons">person</span>
                            <?= html_escape($p->nama_penerima); ?>
                        </div>
                        <a href="<?= base_url('disposisi-surat/tracking/' . $row->id . '/' . $p->id); ?>"
                           class="btn btn-outline btn-sm">
                            <span class="material-icons">track_changes</span>Tracking
                        </a>
                    </div>

                    <!-- Progress bar 2 langkah -->
                    <div class="dsp-progress">
                        <!-- Step: Kirim -->
                        <div class="dsp-step <?= $p->status_kirim === 'sudah' ? 'done' : 'pending'; ?>">
                            <div class="dsp-step-icon">
                                <span class="material-icons"><?= $p->status_kirim === 'sudah' ? 'check' : 'send'; ?></span>
                            </div>
                            <div class="dsp-step-info">
                                <div class="dsp-step-label">Pengiriman</div>
                                <?php if ($p->status_kirim === 'sudah'): ?>
                                    <div class="dsp-step-sub">
                                        <?= date('d/m/Y H:i', strtotime($p->tgl_kirim)); ?>
                                        <?= $p->nama_pengirim ? '· ' . html_escape($p->nama_pengirim) : ''; ?>
                                    </div>
                                    <div class="dsp-step-bukti">
                                        <?php if ($p->foto_bukti_kirim): ?>
                                            <a href="<?= base_url($p->foto_bukti_kirim); ?>" target="_blank" class="dsp-bukti-link">
                                                <span class="material-icons">photo</span>Foto
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($p->ttd_pengirim): ?>
                                            <a href="<?= base_url($p->ttd_pengirim); ?>" target="_blank" class="dsp-bukti-link">
                                                <span class="material-icons">draw</span>TTD
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="dsp-step-sub dsp-sub-pending">Belum dikirim</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="dsp-step-connector <?= $p->status_kirim === 'sudah' ? 'done' : ''; ?>"></div>

                        <!-- Step: Terima -->
                        <div class="dsp-step <?= $p->status_terima === 'sudah' ? 'done' : ($p->status_kirim === 'sudah' ? 'active' : 'pending'); ?>">
                            <div class="dsp-step-icon">
                                <span class="material-icons"><?= $p->status_terima === 'sudah' ? 'check' : 'move_to_inbox'; ?></span>
                            </div>
                            <div class="dsp-step-info">
                                <div class="dsp-step-label">Penerimaan</div>
                                <?php if ($p->status_terima === 'sudah'): ?>
                                    <div class="dsp-step-sub">
                                        <?= date('d/m/Y H:i', strtotime($p->tgl_terima)); ?>
                                        <?= $p->nama_penerima_ttd ? '· ' . html_escape($p->nama_penerima_ttd) : ''; ?>
                                    </div>
                                    <div class="dsp-step-bukti">
                                        <?php if ($p->foto_bukti_terima): ?>
                                            <a href="<?= base_url($p->foto_bukti_terima); ?>" target="_blank" class="dsp-bukti-link">
                                                <span class="material-icons">photo</span>Foto
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($p->ttd_penerima): ?>
                                            <a href="<?= base_url($p->ttd_penerima); ?>" target="_blank" class="dsp-bukti-link">
                                                <span class="material-icons">draw</span>TTD
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($p->status_kirim === 'sudah'): ?>
                                    <div class="dsp-step-sub" style="color:#F57F17;">Menunggu tanda terima</div>
                                <?php else: ?>
                                    <div class="dsp-step-sub dsp-sub-pending">Belum tersedia</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:13px;">
                <span class="material-icons" style="font-size:32px;display:block;margin-bottom:6px;">people_outline</span>
                Belum ada penerima ditambahkan.
            </div>
        <?php endif; ?>

        <div class="form-actions" style="margin-top:24px;">
            <a href="<?= base_url('disposisi-surat/edit/'.$row->id); ?>" class="btn btn-primary">
                <span class="material-icons">edit</span>Edit
            </a>
            <a href="<?= base_url('disposisi-surat'); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali
            </a>
        </div>
    </div>
</div>

<script>
function copyNomor() {
    const nomor = document.getElementById('nomorDspText').textContent.trim();
    const btn   = document.getElementById('btnCopyDsp');
    const done  = () => {
        btn.innerHTML = '<span class="material-icons" style="font-size:15px;">check</span> Tersalin!';
        btn.style.cssText += 'background:#6366F1;color:#fff;border-color:#6366F1;';
        setTimeout(() => {
            btn.innerHTML = '<span class="material-icons" style="font-size:15px;">content_copy</span> Salin';
            btn.style.background = 'transparent';
            btn.style.color      = '#6366F1';
        }, 2000);
    };
    if (navigator.clipboard) navigator.clipboard.writeText(nomor).then(done).catch(done);
    else { const el=document.createElement('textarea');el.value=nomor;document.body.appendChild(el);el.select();document.execCommand('copy');document.body.removeChild(el);done(); }
}
</script>