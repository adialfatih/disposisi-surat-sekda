<?php
$caraka_nama = $session_nama ?? $this->session->userdata('nama') ?? 'Caraka';
$caraka_role = $session_hak_akses ?? $this->session->userdata('hak_akses') ?? 'user';
?>
<div class="k-app-header">
    <div class="k-brand">
        <div class="k-brand-mark">
            <img src="<?= base_url('uploads/logo.png'); ?>" alt="Logo DINAMIT">
        </div>
        <div class="k-brand-text">
            DINAMIT
            <small>Portal Caraka</small>
        </div>
    </div>

    <div class="k-user-panel">
        <div class="k-user-avatar"><?= strtoupper(substr($caraka_nama ?: 'C', 0, 1)); ?></div>
        <div class="k-user-meta">
            <strong><?= html_escape($caraka_nama); ?></strong>
            <span><?= $caraka_role === 'user' ? 'Caraka' : html_escape(ucfirst($caraka_role)); ?></span>
        </div>
        <a href="<?= base_url('logout'); ?>" class="k-logout" title="Logout">
            <span class="material-icons">logout</span>
        </a>
    </div>
</div>
