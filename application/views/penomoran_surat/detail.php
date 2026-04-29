<?php
$wa_raw = !empty($row->no_wa_pengolah) ? preg_replace('/\D+/', '', $row->no_wa_pengolah) : '';
$wa_raw = ltrim($wa_raw, '0');
$wa_target = (strpos($wa_raw, '62') === 0) ? $wa_raw : '62' . $wa_raw;
$wa_phone = $wa_raw ? '+' . $wa_target : '';
$wa_url = $wa_raw
    ? 'https://wa.me/' . $wa_target . '?text=' . rawurlencode('Nomor surat: ' . $row->nomor_surat)
    : '';
?>

<div class="page active">
    <div class="card">
        <div class="section-title">Detail Nomor Surat</div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px;">
            <div style="grid-column:span 2;background:#EEF2FF;border-radius:8px;padding:12px 16px;">
                <strong style="font-size:12px;color:#6366F1;text-transform:uppercase;letter-spacing:.5px;">
                    Nomor Surat
                </strong><br>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap;">
                    <span id="nomorSuratText" style="font-size:18px;font-weight:700;letter-spacing:.5px;color:#1e1e2d;">
                        <?= html_escape($row->nomor_surat ?: '-'); ?>
                    </span>

                    <?php if (!empty($row->nomor_surat)): ?>
                    <button
                        type="button"
                        id="btnCopyNomor"
                        title="Salin nomor surat"
                        onclick="copyNomorSurat()"
                        style="display:inline-flex;align-items:center;gap:4px;
                            padding:4px 10px;border-radius:6px;border:1.5px solid #6366F1;
                            background:transparent;color:#6366F1;font-size:12px;font-weight:600;
                            cursor:pointer;transition:all .2s ease;">
                        <span class="material-icons" style="font-size:15px;">content_copy</span>
                        Salin
                    </button>
                    <?php endif; ?>

                    <?php if ($wa_url): ?>
                    <a
                        href="<?= html_escape($wa_url); ?>"
                        target="_blank"
                        rel="noopener"
                        title="Kirim WhatsApp ke <?= html_escape($wa_phone); ?>"
                        style="display:inline-flex;align-items:center;gap:4px;
                            padding:4px 10px;border-radius:6px;border:1.5px solid #16A34A;
                            background:#16A34A;color:#fff;font-size:12px;font-weight:600;
                            text-decoration:none;cursor:pointer;transition:all .2s ease;">
                        <span class="material-icons" style="font-size:15px;">send</span>
                        KIRIM WA
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div><strong>Jenis Surat:</strong><br><?= html_escape($row->jenis_surat_label); ?></div>
            <div><strong>Kode Keamanan:</strong><br><?= html_escape($row->kode_keamanan); ?></div>
            <div><strong>Nomor Urut:</strong><br><?= (int) $row->nomor_urut; ?></div>
            <div><strong>Catatan:</strong><br><?= html_escape($row->catatan ?: '-'); ?></div>
            <div><strong>Kode Klasifikasi:</strong><br><?= html_escape($row->kode_klasifikasi); ?></div>
            <div><strong>Kode Umum:</strong><br><?= html_escape($row->kode_umum ?: '-'); ?></div>
            <div><strong>Tahun:</strong><br><?= (int) $row->tahun; ?></div>
            <div><strong>Tanggal Surat:</strong><br><?= html_escape($row->tanggal_surat); ?></div>
            <div><strong>Perihal:</strong><br><?= html_escape($row->perihal); ?></div>
            <div><strong>Pengolah:</strong><br><?= html_escape($row->pengolah); ?></div>
            <div><strong>No. WA Pengolah:</strong><br><?= $wa_phone ? html_escape($wa_phone) : '-'; ?></div>
            <div style="grid-column:span 2;"><strong>Tujuan:</strong><br><?= html_escape($row->tujuan); ?></div>
        </div>

        <div class="form-actions" style="margin-top:20px;">
            <a href="<?= base_url('penomoran-surat/edit/' . $row->id); ?>" class="btn btn-primary">
                <span class="material-icons">edit</span>Edit
            </a>
            <a href="<?= base_url('penomoran-surat'); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali
            </a>
        </div>
    </div>
<?php if (!empty($row->nomor_surat)): ?>
<script>
function copyNomorSurat() {
    const nomor = document.getElementById('nomorSuratText').textContent.trim();
    const btn   = document.getElementById('btnCopyNomor');

    navigator.clipboard.writeText(nomor).then(function () {
        // Feedback visual: ubah tampilan tombol sementara
        btn.innerHTML = '<span class="material-icons" style="font-size:15px;">check</span> Tersalin!';
        btn.style.background    = '#6366F1';
        btn.style.color         = '#fff';
        btn.style.borderColor   = '#6366F1';

        setTimeout(function () {
            btn.innerHTML = '<span class="material-icons" style="font-size:15px;">content_copy</span> Salin';
            btn.style.background  = 'transparent';
            btn.style.color       = '#6366F1';
            btn.style.borderColor = '#6366F1';
        }, 2000);
    }).catch(function () {
        // Fallback untuk browser lama
        const el = document.createElement('textarea');
        el.value = nomor;
        el.style.position = 'fixed';
        el.style.opacity  = '0';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        btn.innerHTML = '<span class="material-icons" style="font-size:15px;">check</span> Tersalin!';
        btn.style.background  = '#6366F1';
        btn.style.color       = '#fff';

        setTimeout(function () {
            btn.innerHTML = '<span class="material-icons" style="font-size:15px;">content_copy</span> Salin';
            btn.style.background  = 'transparent';
            btn.style.color       = '#6366F1';
        }, 2000);
    });
}
</script>
<?php endif; ?>
</div>
