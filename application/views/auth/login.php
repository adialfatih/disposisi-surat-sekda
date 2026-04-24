<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Nomor Surat Sekda</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/login.css'); ?>">
</head>
<body>

<div class="bg-batik"></div>

<div class="page-wrap">
    <div class="left-panel">
        <div class="brand-logo">
            <div class="logo-mark">NS</div>
            <div class="brand-name">
                Nomor Surat
                <small>Sekda Kota Pekalongan</small>
            </div>
        </div>

        <h1 class="left-headline">
            Sistem Informasi<br>
            <em>Penomoran &amp; Agenda Surat</em><br>
            Terpadu
        </h1>

        <p class="left-desc">
            Platform untuk pengelolaan nomor surat, agenda disposisi, dan agenda surat masuk pada lingkungan Sekretariat Daerah Kota Pekalongan.
        </p>

        <div class="stats-row">
            <div class="stat-pill">
                <div class="stat-pill-val">1.2k</div>
                <div class="stat-pill-label">Nomor Surat</div>
            </div>
            <div class="stat-pill">
                <div class="stat-pill-val">324</div>
                <div class="stat-pill-label">Surat Masuk</div>
            </div>
            <div class="stat-pill">
                <div class="stat-pill-val">89%</div>
                <div class="stat-pill-label">Disposisi Selesai</div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-header">
            <h2>Masuk ke Sistem</h2>
            <p>Gunakan akun yang diberikan administrator untuk mengakses aplikasi.</p>
        </div>

        <?php if ($this->session->flashdata('login_error')): ?>
            <div style="margin-bottom:16px;padding:12px 14px;border-radius:10px;background:#FFEBEE;color:#C62828;font-size:13px;line-height:1.6;">
                <?= $this->session->flashdata('login_error'); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="<?= base_url('login'); ?>" novalidate autocomplete="off">
            <input type="hidden"
                   name="<?= $this->security->get_csrf_token_name(); ?>"
                   value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrap">
                    <div class="input-icon"><span class="material-icons">person</span></div>
                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        placeholder="Masukkan username Anda"
                        maxlength="50"
                        autocomplete="off"
                        autocapitalize="none"
                        spellcheck="false"
                        value="<?= html_escape($this->session->flashdata('old_username')); ?>"
                    >
                </div>
                <div class="field-error" id="errUsername">
                    <span class="material-icons">error_outline</span>Username wajib diisi
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <div class="input-icon"><span class="material-icons">lock</span></div>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Masukkan password Anda"
                        maxlength="100"
                        autocomplete="off"
                    >
                    <button type="button" class="toggle-pw" id="togglePw" tabindex="-1">
                        <span class="material-icons" id="eyeIcon">visibility</span>
                    </button>
                </div>
                <div class="field-error" id="errPassword">
                    <span class="material-icons">error_outline</span>Password wajib diisi
                </div>
            </div>

            <div class="form-meta">
                <input type="checkbox" id="rememberCheck" disabled>
                <label for="rememberCheck" class="remember-wrap">
                    <div class="custom-check"></div>
                    Ingat saya
                </label>

                <a href="#" class="forgot-link" id="forgotLink">Lupa password?</a>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <span class="btn-text">
                    <span class="material-icons">login</span>
                    Masuk
                </span>
                <div class="spinner"></div>
            </button>
        </form>

        <div class="divider">DINAMIT</div>

        <div class="form-footer">
            &copy; 2026 Sekretariat Daerah Kota Pekalongan &mdash;
            <a href="#">Kebijakan Privasi</a> &middot; <a href="#">Bantuan</a>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/login.js'); ?>"></script>
</body>
</html>