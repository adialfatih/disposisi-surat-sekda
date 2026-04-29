<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - DINAMIT | Sekda Kota Pekalongan' : 'DINAMIT | Sekda Kota Pekalongan'; ?></title>
 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-template.css?v=9'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/table-responsive.css'); ?>">
 
    <!-- Fitur collapse sidebar desktop -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar-collapse.css?v=1'); ?>">
 
    <?php if (!empty($active_menu) && $active_menu === 'penomoran_surat'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/penomoran-surat.css'); ?>">
    <?php endif; ?>
 
    <?php if (!empty($active_menu) && ($active_menu === 'agenda_surat_masuk' OR $active_menu === 'surat_masuk_v2' OR $active_menu === 'acara' OR $active_menu === 'user_management')): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/surat-masuk.css?v=3'); ?>">
    <?php endif; ?>
 
    <?php if (!empty($active_menu) && $active_menu === 'agenda_disposisi'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/surat-masuk.css?v=4'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/disposisi-surat.css'); ?>">
    <?php endif; ?>
</head>
<body>
