<div class="page active">

    <?php if ($this->session->flashdata('crud_success')): ?>
        <div class="alert-flash alert-flash-success">
            <span class="material-icons">check_circle</span>
            <?= $this->session->flashdata('crud_success'); ?>
        </div>
    <?php endif; ?>

    <?php
    $status_map = [
        'masuk'       => ['label' => 'Masuk',       'class' => 'badge-blue'],
        'dicetak'     => ['label' => 'Dicetak',     'class' => 'badge-purple'],
        'didisposisi' => ['label' => 'Didisposisi', 'class' => 'badge-yellow'],
        'selesai'     => ['label' => 'Selesai',     'class' => 'badge-green'],
    ];
    $kategori_map = [
        'permohonan' => ['label' => 'Permohonan', 'class' => 'badge-blue'],
        'undangan'   => ['label' => 'Undangan',   'class' => 'badge-yellow'],
        'lainnya'    => ['label' => 'Lainnya',    'class' => 'badge-gray'],
    ];
    $sifat_map = [
        'sangat_segera' => ['label' => 'Sangat Segera', 'class' => 'badge-red'],
        'segera'        => ['label' => 'Segera',        'class' => 'badge-yellow'],
        'rahasia'       => ['label' => 'Rahasia',       'class' => 'badge-gray'],
        'biasa'         => ['label' => 'Biasa',         'class' => 'badge-green'],
    ];
    $s  = $status_map[$row->status]     ?? ['label' => $row->status,    'class' => 'badge-gray'];
    $k  = $kategori_map[$row->kategori] ?? ['label' => $row->kategori,  'class' => 'badge-gray'];
    $sf = $sifat_map[$row->sifat]       ?? ['label' => $row->sifat,     'class' => 'badge-gray'];
    ?>

    <div class="card">

        <!-- Header strip -->
        <div class="sm-detail-header">
            <div class="sm-detail-header-left">
                <div class="sm-detail-label">Nomor Agenda</div>
                <div class="sm-detail-nomor-wrap">
                    <span id="nomorAgendaText" class="sm-detail-nomor">
                        <?= html_escape($row->nomor_agenda); ?>
                    </span>
                    <button type="button" onclick="copyNomor()" class="sm-copy-btn" title="Salin nomor agenda">
                        <span class="material-icons">content_copy</span>Salin
                    </button>
                </div>
            </div>
            <div class="sm-detail-header-right">
                <span class="badge <?= $s['class']; ?>" style="font-size:13px;padding:5px 14px;"><?= $s['label']; ?></span>
                <span class="badge <?= $k['class']; ?>" style="font-size:13px;padding:5px 14px;"><?= $k['label']; ?></span>
                <span class="badge <?= $sf['class']; ?>" style="font-size:13px;padding:5px 14px;"><?= $sf['label']; ?></span>
            </div>
        </div>

        <!-- Grid Info Surat -->
        <div style="margin-bottom:4px;font-weight:700;font-size:12px;color:var(--sogan);text-transform:uppercase;letter-spacing:.5px;padding-bottom:4px;border-bottom:1px solid #eee;margin-top:8px;">
            <span class="material-icons" style="font-size:14px;vertical-align:middle;">mail</span>
            Informasi Surat
        </div>

        <div class="sm-detail-grid">

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">send</span>Asal / Pengirim</div>
                <div class="sm-detail-item-val"><?= html_escape($row->asal_surat); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">confirmation_number</span>Nomor Surat</div>
                <div class="sm-detail-item-val">
                    <code style="background:var(--sogan-faint);padding:3px 8px;border-radius:5px;font-size:13px;color:var(--sogan);">
                        <?= html_escape($row->nomor_surat); ?>
                    </code>
                </div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">calendar_today</span>Tanggal Surat</div>
                <div class="sm-detail-item-val"><?= html_escape(date('d F Y', strtotime($row->tanggal_surat))); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">event_available</span>Tanggal Terima</div>
                <div class="sm-detail-item-val"><?= html_escape(date('d F Y', strtotime($row->tanggal_terima))); ?></div>
            </div>

            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label"><span class="material-icons">subject</span>Perihal / Hal</div>
                <div class="sm-detail-item-val"><?= html_escape($row->perihal); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">local_shipping</span>Asal Berkas</div>
                <div class="sm-detail-item-val"><?= html_escape($row->asal_berkas ?: '-'); ?></div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">schedule</span>Dicatat Pada</div>
                <div class="sm-detail-item-val"><?= html_escape(date('d F Y, H:i', strtotime($row->created_at))); ?> WIB</div>
            </div>

        </div>

        <!-- Disposisi Section -->
        <?php if (in_array($row->status, ['didisposisi', 'selesai'])): ?>
        <div style="margin:20px 0 4px;font-weight:700;font-size:12px;color:#E65100;text-transform:uppercase;letter-spacing:.5px;padding-bottom:4px;border-bottom:1px solid #eee;">
            <span class="material-icons" style="font-size:14px;vertical-align:middle;">edit_note</span>
            Isian Disposisi Pimpinan
        </div>

        <div class="sm-detail-grid">

            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label"><span class="material-icons">group</span>Diteruskan Kepada</div>
                <div class="sm-detail-item-val">
                    <?php if (!empty($row->diteruskan_kepada)): ?>
                        <ul style="margin:0;padding-left:18px;">
                            <?php foreach (explode("\n", $row->diteruskan_kepada) as $penerima): ?>
                                <?php if (trim($penerima) !== ''): ?>
                                    <li><?= html_escape(trim($penerima)); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <span style="color:#aaa;">-</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sm-detail-item">
                <div class="sm-detail-item-label"><span class="material-icons">checklist</span>Instruksi</div>
                <div class="sm-detail-item-val">
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <span class="material-icons" style="font-size:16px;color:<?= $row->instruksi_tanggapan ? '#2E7D32' : '#ccc'; ?>">
                                <?= $row->instruksi_tanggapan ? 'check_box' : 'check_box_outline_blank'; ?>
                            </span>
                            Tanggapan dan Saran
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <span class="material-icons" style="font-size:16px;color:<?= $row->instruksi_proses_lanjut ? '#2E7D32' : '#ccc'; ?>">
                                <?= $row->instruksi_proses_lanjut ? 'check_box' : 'check_box_outline_blank'; ?>
                            </span>
                            Proses lebih lanjut
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <span class="material-icons" style="font-size:16px;color:<?= $row->instruksi_koordinasi ? '#2E7D32' : '#ccc'; ?>">
                                <?= $row->instruksi_koordinasi ? 'check_box' : 'check_box_outline_blank'; ?>
                            </span>
                            Koordinasi/konfirmasi
                        </label>
                        <?php if (!empty($row->instruksi_lainnya)): ?>
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <span class="material-icons" style="font-size:16px;color:#2E7D32;">check_box</span>
                            <?= html_escape($row->instruksi_lainnya); ?>
                        </label>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($row->catatan_disposisi)): ?>
            <div class="sm-detail-item sm-span-2">
                <div class="sm-detail-item-label"><span class="material-icons">sticky_note_2</span>Catatan Disposisi</div>
                <div class="sm-detail-item-val"><?= nl2br(html_escape($row->catatan_disposisi)); ?></div>
            </div>
            <?php endif; ?>

        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="form-actions" style="margin-top:24px;flex-wrap:wrap;gap:10px;">
            <!-- Cetak -->
            <a href="<?= base_url('surat-masuk-v2/cetak/' . $row->id); ?>"
               target="_blank"
               class="btn btn-primary"
               style="background:#6A1B9A;border-color:#6A1B9A;">
                <span class="material-icons">print</span>Cetak Lembar Disposisi
            </a>

            <!-- Input Disposisi -->
            <?php if (in_array($row->status, ['dicetak', 'didisposisi'])): ?>
            <a href="<?= base_url('surat-masuk-v2/disposisi/' . $row->id); ?>"
               class="btn btn-primary"
               style="background:#E65100;border-color:#E65100;">
                <span class="material-icons">edit_note</span>
                <?= $row->status === 'didisposisi' ? 'Update Disposisi' : 'Input Disposisi'; ?>
            </a>
            <?php endif; ?>

            <!-- Edit Data -->
            <a href="<?= base_url('surat-masuk-v2/edit/' . $row->id); ?>" class="btn btn-outline">
                <span class="material-icons">edit</span>Edit Data
            </a>

            <!-- Kembali -->
            <a href="<?= base_url('surat-masuk-v2'); ?>" class="btn btn-outline">
                <span class="material-icons">arrow_back</span>Kembali
            </a>
        </div>

        <!-- Status alur visual -->
        <div style="margin-top:20px;padding:12px 16px;background:#f9f9f9;border-radius:8px;font-size:12px;color:#555;">
            <?php
            $steps = [
                'masuk'       => ['icon' => 'inbox',      'label' => 'Dicatat'],
                'dicetak'     => ['icon' => 'print',      'label' => 'Dicetak'],
                'didisposisi' => ['icon' => 'edit_note',  'label' => 'Didisposisi'],
                'selesai'     => ['icon' => 'task_alt',   'label' => 'Selesai'],
            ];
            $step_keys   = array_keys($steps);
            $current_idx = array_search($row->status, $step_keys);
            ?>
            <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
            <?php foreach ($steps as $key => $step):
                $idx     = array_search($key, $step_keys);
                $done    = $idx <= $current_idx;
                $current = $key === $row->status;
                $color   = $current ? 'var(--sogan,#5C3317)' : ($done ? '#2E7D32' : '#ccc');
            ?>
                <span style="display:flex;align-items:center;gap:4px;font-weight:<?= $current ? '700' : '400'; ?>;color:<?= $color; ?>">
                    <span class="material-icons" style="font-size:15px;"><?= $step['icon']; ?></span>
                    <?= $step['label']; ?>
                </span>
                <?php if ($key !== 'selesai'): ?>
                <span class="material-icons" style="font-size:14px;color:#ccc;">arrow_forward</span>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>
function copyNomor() {
    const nomor = document.getElementById('nomorAgendaText').textContent.trim();
    const btn   = document.querySelector('.sm-copy-btn');
    const doFeedback = function() {
        btn.innerHTML = '<span class="material-icons" style="font-size:15px;">check</span> Tersalin!';
        btn.style.cssText += 'background:#6366F1;color:#fff;border-color:#6366F1;';
        setTimeout(function() {
            btn.innerHTML = '<span class="material-icons" style="font-size:15px;">content_copy</span> Salin';
            btn.style.background = btn.style.color = '';
        }, 2000);
    };
    if (navigator.clipboard) {
        navigator.clipboard.writeText(nomor).then(doFeedback).catch(function() {
            fallbackCopy(nomor); doFeedback();
        });
    } else { fallbackCopy(nomor); doFeedback(); }
}
function fallbackCopy(t) {
    var el = document.createElement('textarea');
    el.value = t;
    el.style.cssText = 'position:fixed;opacity:0;';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}
</script>