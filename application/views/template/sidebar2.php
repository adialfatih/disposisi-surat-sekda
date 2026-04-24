<nav id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark"><img src="<?= base_url('uploads/logo.png'); ?>" alt="Logo DINAMIT"></div>
        <div class="logo-text">
            DINAMIT
            <small>Sekda Kota Pekalongan</small>
        </div>
    </div>

    <div class="nav-section-label">Navigasi Utama</div>

    <a href="<?= base_url('dashboard'); ?>" class="nav-item <?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
        <span class="material-icons">dashboard</span>
        Dashboard
    </a>

    <a href="<?= base_url('penomoran-surat'); ?>" class="nav-item <?= ($active_menu == 'penomoran_surat') ? 'active' : ''; ?>">
        <span class="material-icons">tag</span>
        Penomoran Surat
        <span class="nav-badge">Baru</span>
    </a>

    <a href="<?= base_url('disposisi-surat'); ?>" class="nav-item <?= ($active_menu == 'agenda_disposisi') ? 'active' : ''; ?>">
        <span class="material-icons">assignment</span>
        Agenda Disposisi
    </a>

    <a href="<?= base_url('surat-masuk'); ?>" class="nav-item <?= ($active_menu == 'agenda_surat_masuk') ? 'active' : ''; ?>">
        <span class="material-icons">mail</span>
        Agenda Surat Masuk
    </a>
    <a href="<?= base_url('kurir'); ?>" class="nav-item" target="_blank">
        <span class="material-icons">local_shipping</span>
        Portal Kurir
    </a>

    <div class="sidebar-footer">
        <div class="user-avatar"><?= strtoupper(substr($session_nama ?? 'A', 0, 1)); ?></div>
        <div class="user-info">
            <div class="user-name"><?= html_escape($session_nama ?? 'Administrator'); ?></div>
            <div class="user-role"><?= html_escape($session_hak_akses ?? 'User'); ?></div>
        </div>
        <a href="<?= base_url('logout'); ?>" class="btn-logout" title="Logout">
            <span class="material-icons">logout</span>
        </a>
    </div>
</nav>

<div id="overlay"></div>
<div id="main">