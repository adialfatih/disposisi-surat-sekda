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