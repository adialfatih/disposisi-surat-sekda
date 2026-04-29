<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Konfirmasi Penerimaan</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/kurir.css?v=3'); ?>">
</head>
<body>

<?php $this->load->view('kurir/_header', ['step' => 1, 'label' => 'Isi Nama Penerima']); ?>

<div class="k-step-wrap">
    <?php $this->load->view('kurir/_surat_info', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>

    <div class="k-form-card">
        <div class="k-form-card-title">
            <span class="material-icons">badge</span>
            Nama Penerima
        </div>
        <p class="k-form-card-desc">
            Masukkan nama lengkap Anda sebagai bukti penerimaan resmi surat ini.
        </p>

        <form method="post" action="<?= base_url('kurir/step-kamera'); ?>">
            <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
            <input type="hidden" name="penerima_id" value="<?= $penerima->id; ?>">

            <div class="k-input-wrap">
                <span class="material-icons k-input-icon">person</span>
                <input type="text"
                       name="nama_penerima_ttd"
                       class="k-input"
                       placeholder="Nama lengkap Anda"
                       autocomplete="name"
                       autofocus
                       required>
            </div>

            <button type="submit" class="k-btn-next">
                Lanjut — Ambil Foto
                <span class="material-icons">arrow_forward</span>
            </button>
        </form>
    </div>
</div>

</body>
</html>
