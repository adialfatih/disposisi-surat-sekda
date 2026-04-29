<?php
/**
 * application/views/kurir/_header.php
 *
 * Variabel yang diterima:
 * $step  → nomor step aktif (1, 2, atau 3)
 * $label → judul step yang ditampilkan
 */
?>
<?php $this->load->view('kurir/_app_header'); ?>

<div class="k-step-header">
    <a href="<?= base_url('kurir'); ?>" class="k-back-btn" title="Kembali ke daftar">
        <span class="material-icons">arrow_back</span>
    </a>
    <div class="k-step-header-info">
        <h2><?= html_escape($label); ?></h2>
        <div class="k-step-indicator">Langkah <?= (int)$step; ?> dari 3</div>
    </div>
    <div class="k-step-dots">
        <div class="k-step-dot <?= $step > 1 ? 'done' : ($step == 1 ? 'active' : ''); ?>"></div>
        <div class="k-step-dot <?= $step > 2 ? 'done' : ($step == 2 ? 'active' : ''); ?>"></div>
        <div class="k-step-dot <?= $step > 3 ? 'done' : ($step == 3 ? 'active' : ''); ?>"></div>
    </div>
</div>
