<nav id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">
            <img src="<?= base_url('uploads/logo.png'); ?>" alt="Logo DINAMIT"
                 style="width:100%;height:100%;object-fit:contain;display:block;">
        </div>
        <div class="logo-text">
            DINAMIT
            <small>Sekda Kota Pekalongan</small>
        </div>
    </div>

    <div class="nav-section-label">Navigasi Utama</div>

    <a href="<?= base_url('dashboard'); ?>"
       class="nav-item <?= ($active_menu == 'dashboard') ? 'active' : ''; ?>"
       data-tooltip="Dashboard">
        <span class="material-icons">dashboard</span>
        Dashboard
    </a>

    <a href="<?= base_url('penomoran-surat'); ?>"
       class="nav-item <?= ($active_menu == 'penomoran_surat') ? 'active' : ''; ?>"
       data-tooltip="Penomoran Surat">
        <span class="material-icons">tag</span>
        Penomoran Surat
        <span class="nav-badge">Baru</span>
    </a>

    <a href="<?= base_url('disposisi-surat'); ?>"
       class="nav-item <?= ($active_menu == 'agenda_disposisi') ? 'active' : ''; ?>"
       data-tooltip="Agenda Disposisi">
        <span class="material-icons">assignment</span>
        Agenda Disposisi
    </a>

    <a href="<?= base_url('surat-masuk'); ?>"
       class="nav-item <?= ($active_menu == 'agenda_surat_masuk') ? 'active' : ''; ?>"
       data-tooltip="Agenda Surat Masuk">
        <span class="material-icons">mail</span>
        Agenda Surat Masuk
    </a>

    <a href="<?= base_url('kurir'); ?>"
       class="nav-item"
       data-tooltip="Portal Kurir"
       target="_blank">
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
    <header id="header">
        <div>
            <div class="page-title" id="pageTitle"><?= isset($page_title) ? $page_title : 'Dashboard'; ?></div>
            <div class="page-sub" id="pageSub"><?= isset($page_subtitle) ? $page_subtitle : 'Selamat datang di sistem'; ?></div>
        </div>

        <div class="header-actions">
            <button class="btn-icon" id="btnSearch" title="Cari">
                <span class="material-icons">search</span>
            </button>

            <button class="btn-icon" id="btnNotif" title="Notifikasi">
                <span class="material-icons">notifications</span>
                <span class="notif-dot"></span>
            </button>

            <button class="btn-icon" title="Profil">
                <span class="material-icons">account_circle</span>
            </button>

            <button id="hamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <div class="batik-stripe"></div>
    </header>

    <div id="content">