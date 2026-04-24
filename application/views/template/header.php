<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - Aplikasi Nomor Surat Sekda' : 'Aplikasi Nomor Surat Sekda'; ?></title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/admin-template.css?v=3'); ?>">
    <?php if (!empty($active_menu) && $active_menu === 'penomoran_surat'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/penomoran-surat.css'); ?>">
    <?php endif; ?>
    
    <?php if (!empty($active_menu) && $active_menu === 'agenda_surat_masuk'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/surat-masuk.css?v=2'); ?>">
    <?php endif; ?>

    <?php if (!empty($active_menu) && $active_menu === 'agenda_disposisi'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/surat-masuk.css?v=3'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/disposisi-surat.css'); ?>">
    <?php endif; ?>
</head>
<body>