<div class="k-surat-info">
    <div class="k-surat-info-stripe"></div>
    <div class="k-surat-info-body">
        <div class="k-surat-info-nomor">
            <span class="material-icons">assignment</span>
            <?= html_escape($disposisi->nomor_disposisi); ?>
        </div>
        <div class="k-surat-info-perihal"><?= html_escape($disposisi->perihal_surat ?? '-'); ?></div>
        <div class="k-surat-info-meta">
            <span>
                <span class="material-icons">business</span>
                <?= html_escape($disposisi->asal_surat ?? '-'); ?>
            </span>
            <span>
                <span class="material-icons">confirmation_number</span>
                <?= html_escape($disposisi->nomor_surat ?? '-'); ?>
            </span>
            <span>
                <span class="material-icons">calendar_today</span>
                <?= date('d/m/Y', strtotime($disposisi->tanggal_disposisi)); ?>
            </span>
        </div>
        <div class="k-surat-info-penerima">
            <span class="material-icons">person</span>
            Ditujukan: <?= html_escape($penerima->nama_penerima); ?>
        </div>
    </div>
</div>