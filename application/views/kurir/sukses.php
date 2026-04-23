<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Penerimaan Berhasil</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/kurir.css'); ?>">
</head>
<body>

<div class="k-sukses-wrap">
    <div class="k-sukses-anim">
        <span class="material-icons">verified</span>
    </div>

    <h2 class="k-sukses-title">Penerimaan Dikonfirmasi!</h2>
    <p class="k-sukses-sub">
        Tanda terima berhasil disimpan.<br>
        Surat telah resmi diterima oleh <strong><?= html_escape($penerima->nama_penerima_ttd ?? $penerima->nama_penerima); ?></strong>.
    </p>

    <div class="k-sukses-detail">
        <div class="k-sukses-detail-row">
            <strong>No. Disposisi</strong>
            <span><?= html_escape($disposisi->nomor_disposisi); ?></span>
        </div>
        <div class="k-sukses-detail-row">
            <strong>Perihal</strong>
            <span><?= html_escape($disposisi->perihal_surat ?? '-'); ?></span>
        </div>
        <div class="k-sukses-detail-row">
            <strong>Diterima oleh</strong>
            <span><?= html_escape($penerima->nama_penerima_ttd ?? '-'); ?></span>
        </div>
        <div class="k-sukses-detail-row">
            <strong>Waktu</strong>
            <span><?= date('d/m/Y H:i'); ?> WIB</span>
        </div>
    </div>

    <a href="<?= base_url('kurir'); ?>" class="k-btn-home">
        <span class="material-icons">home</span>
        Kembali ke Daftar Surat
    </a>
</div>

</body>
</html>