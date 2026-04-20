<div class="page active">
    <div class="card">

        <?php
        $status_map = [
            'masuk'       => ['label' => 'Masuk',       'class' => 'badge-blue'],
            'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
            'selesai'     => ['label' => 'Selesai',     'class' => 'badge-green'],
        ];
        $kategori_map = [
            'permohonan' => ['label' => 'Permohonan', 'class' => 'badge-blue'],
            'undangan'   => ['label' => 'Undangan',   'class' => 'badge-yellow'],
            'lainnya'    => ['label' => 'Lainnya',    'class' => 'badge-gray'],
        ];
        $s = $status_map[$row->status]     ?? ['label' => $row->status,    'class' => 'badge-gray'];
        $k = $kategori_map[$row->kategori] ?? ['label' => $row->kategori,  'class' => 'badge-gray'];
        ?>

        <!-- Header strip: nomor agenda + status -->
        <div class="sm-detail-header">
            <div class="sm-detail-header-left">
                <div class="sm-detail-label">Nomor Agenda</div>
                <div class="sm-detail-nomor-wrap">
                    <span id="nomorAgendaText" class="sm-detail-nomor">
                        <?= html_escape($row->nomor_agenda); ?>
                    </span>
                    <button type="button" id="btnCopyAgenda" onclick="copyNomorAgenda()" class="sm-copy-btn" title="Salin nomor agenda">
                        <span class="material-icons">content_copy</span>
                        Salin
                    </button>
                </div>
            </div>
            <div class="sm-detail-header-right">
                <span class="badge <?= $s['class']; ?>" style="font-size:13px;padding:5px 14px;">
                    <?= $s['label']; ?>
                </span>
                <span class="badge <?= $k['class']; ?>" style="font-size:13px;padding:5px 14px;">
                    <?= $k['label']; ?>
                </span>
            </div>
        </div>

        <!-- Grid detail -->
        <div class="sm-detail-grid">

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">send</span>Asal / Pengirim
                </div>
                <div class="sm-detail-item-val"><?= html_escape($row->asal_surat); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">confirmation_number</span>Nomor Surat
                </div>
                <div class="sm-detail-item-val">
                    <code style="background:var(--sogan-faint);padding:3px 8px;border-radius:5px;font-size:13px;color:var(--sogan);">
                        <?= html_escape($row->nomor_surat); ?>
                    </code>
                </div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">calendar_today</span>Tanggal Surat
                </div>
                <div class="sm-detail-item-val">
                    <?= html_escape(date('d F Y', strtotime($row->tanggal_surat))); ?>
                </div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">event_available</span>Tanggal Terima
                </div>
                <div class="sm-detail-item-val">
                    <?= html_escape(date('d F Y', strtotime($row->tanggal_terima))); ?>
                </div>
            </div>

            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label">
                    <span class="material-icons">subject</span>Perihal
                </div>
                <div class="sm-detail-item-val"><?= html_escape($row->perihal); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">local_shipping</span>Asal Berkas
                </div>
                <div class="sm-detail-item-val"><?= html_escape($row->asal_berkas ?: '-'); ?></div>
            </div>

            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label">
                    <span class="material-icons">sticky_note_2</span>Catatan
                </div>
                <div class="sm-detail-item-val"><?= nl2br(html_escape($row->catatan ?: '-')); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label">
                    <span class="material-icons">schedule</span>Dicatat pada
                </div>
                <div class="sm-detail-item-val">
                    <?= html_escape(date('d F Y, H:i', strtotime($row->created_at))); ?> WIB
                </div>
            </div>

        </div>

        <div class="form-actions" style="margin-top:24px;">
            <a href="<?= base_url('surat-masuk/edit/' . $row->id); ?>" class="btn btn-primary">
                <span class="material-icons">edit</span>Edit
            </a>
            <a href="<?= base_url('surat-masuk'); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali
            </a>
        </div>

    </div>
</div>

<script>
function copyNomorAgenda() {
    const nomor = document.getElementById('nomorAgendaText').textContent.trim();
    const btn   = document.getElementById('btnCopyAgenda');

    const doFeedback = function() {
        btn.innerHTML = '<span class="material-icons" style="font-size:15px;">check</span> Tersalin!';
        btn.style.background  = '#6366F1';
        btn.style.color       = '#fff';
        btn.style.borderColor = '#6366F1';

        setTimeout(function () {
            btn.innerHTML = '<span class="material-icons" style="font-size:15px;">content_copy</span> Salin';
            btn.style.background  = 'transparent';
            btn.style.color       = '#6366F1';
            btn.style.borderColor = '#6366F1';
        }, 2000);
    };

    if (navigator.clipboard) {
        navigator.clipboard.writeText(nomor).then(doFeedback).catch(function() {
            fallbackCopy(nomor);
            doFeedback();
        });
    } else {
        fallbackCopy(nomor);
        doFeedback();
    }
}

function fallbackCopy(text) {
    const el = document.createElement('textarea');
    el.value = text;
    el.style.cssText = 'position:fixed;opacity:0;';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}
</script>