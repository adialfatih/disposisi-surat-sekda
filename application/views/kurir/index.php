<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Caraka - Surat Masuk</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --ink:      #0F1923;
    --ink-mid:  #3D4F5C;
    --ink-muted:#7A8F9E;
    --surface:  #F4F7F9;
    --white:    #FFFFFF;
    --accent:   #1A56DB;
    --accent-lt:#E8F0FE;
    --green:    #0B7A4E;
    --green-lt: #D1FAE5;
    --gold:     #B45309;
    --gold-lt:  #FEF3C7;
    --red:      #C0392B;
    --border:   #DDE3E8;
    --radius:   14px;
    --shadow:   0 2px 12px rgba(15,25,35,.10);
}

body {
    font-family: 'Sora', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    -webkit-tap-highlight-color: transparent;
}

/* ── Header ── */
.k-header {
    background: var(--ink);
    color: var(--white);
    padding: 20px 18px 16px;
    position: sticky; top: 0; z-index: 10;
}
.k-header-top {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
}
.k-header-icon {
    width: 36px; height: 36px;
    background: var(--accent);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
}
.k-header-icon .material-icons { font-size: 20px; }
.k-header h1 {
    font-size: 17px;
    font-weight: 700;
    letter-spacing: -.01em;
}
.k-header-sub {
    font-size: 12px;
    color: rgba(255,255,255,.5);
    margin-top: 2px;
    margin-left: 46px;
}

/* ── Flash ── */
.k-flash {
    margin: 14px 16px 0;
    padding: 11px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    display: flex; align-items: center; gap: 8px;
}
.k-flash.info    { background: var(--accent-lt); color: var(--accent); }
.k-flash.success { background: var(--green-lt);  color: var(--green);  }
.k-flash .material-icons { font-size: 17px; }

/* ── Counter ── */
.k-counter {
    margin: 16px 16px 0;
    display: flex; align-items: center; justify-content: space-between;
}
.k-counter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
}
.k-badge-count {
    background: var(--accent);
    color: var(--white);
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}

/* ── Daftar surat ── */
.k-list {
    padding: 12px 16px 100px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.k-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: slideUp .3s ease both;
}
.k-card:nth-child(1) { animation-delay: .04s; }
.k-card:nth-child(2) { animation-delay: .08s; }
.k-card:nth-child(3) { animation-delay: .12s; }
.k-card:nth-child(4) { animation-delay: .16s; }
.k-card:nth-child(n+5) { animation-delay: .20s; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.k-card-stripe {
    height: 4px;
    background: linear-gradient(90deg, var(--accent) 0%, #6EA8FE 100%);
}

.k-card-body { padding: 14px 16px 0; }

.k-card-nomor {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--accent-lt);
    color: var(--accent);
    font-size: 11px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    margin-bottom: 8px;
    letter-spacing: .02em;
}
.k-card-nomor .material-icons { font-size: 13px; }

.k-card-perihal {
    font-size: 15px;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.4;
    margin-bottom: 6px;
}

.k-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 14px;
    margin-bottom: 10px;
}
.k-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--ink-muted);
}
.k-meta-item .material-icons { font-size: 14px; }

.k-card-penerima {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--gold-lt);
    border-radius: 8px;
    padding: 7px 10px;
    margin-bottom: 12px;
    font-size: 12px;
    color: var(--gold);
    font-weight: 600;
}
.k-card-penerima .material-icons { font-size: 16px; }

.k-card-footer {
    border-top: 1px solid var(--border);
    padding: 12px 16px;
}

.k-btn-terima {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px;
    background: var(--ink);
    color: var(--white);
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s, transform .1s;
    -webkit-tap-highlight-color: transparent;
}
.k-btn-terima:active { background: #263545; transform: scale(.98); }
.k-btn-terima .material-icons { font-size: 18px; }

/* ── Empty state ── */
.k-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 24px;
    text-align: center;
    color: var(--ink-muted);
}
.k-empty-icon {
    width: 72px; height: 72px;
    background: var(--green-lt);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
}
.k-empty-icon .material-icons { font-size: 36px; color: var(--green); }
.k-empty h3 { font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
.k-empty p  { font-size: 13px; line-height: 1.6; }

/* ── Refresh FAB ── */
.k-fab {
    position: fixed;
    bottom: 24px; right: 20px;
    width: 52px; height: 52px;
    background: var(--accent);
    color: var(--white);
    border: none;
    border-radius: 50%;
    box-shadow: 0 4px 16px rgba(26,86,219,.35);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0;
    transition: transform .15s;
    text-decoration: none;
}
.k-fab:active { transform: scale(.93); }
.k-fab .material-icons { font-size: 22px; }
</style>
</head>
<body>

<div class="k-header">
    <div class="k-header-top">
        <div class="k-header-icon"><span class="material-icons">local_shipping</span></div>
        <h1>Portal Caraka</h1>
    </div>
    <div class="k-header-sub">Konfirmasi penerimaan surat disposisi</div>
</div>

<?php if ($this->session->flashdata('info')): ?>
    <div class="k-flash info">
        <span class="material-icons">info</span>
        <?= $this->session->flashdata('info'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
    <div class="k-flash success">
        <span class="material-icons">check_circle</span>
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="k-counter">
    <span class="k-counter-label">Menunggu Konfirmasi</span>
    <span class="k-badge-count"><?= count($rows); ?> surat</span>
</div>

<?php if (!empty($rows)): ?>
<div class="k-list">
    <?php foreach ($rows as $row): ?>
    <div class="k-card">
        <div class="k-card-stripe"></div>
        <div class="k-card-body">

            <div class="k-card-nomor">
                <span class="material-icons">tag</span>
                <?= html_escape($row->nomor_disposisi); ?>
            </div>

            <div class="k-card-perihal"><?= html_escape($row->perihal); ?></div>

            <div class="k-card-meta">
                <div class="k-meta-item">
                    <span class="material-icons">business</span>
                    <?= html_escape($row->asal_surat); ?>
                </div>
                <div class="k-meta-item">
                    <span class="material-icons">confirmation_number</span>
                    <?= html_escape($row->nomor_surat); ?>
                </div>
                <div class="k-meta-item">
                    <span class="material-icons">send</span>
                    Dikirim <?= $row->tgl_kirim ? date('d/m/Y H:i', strtotime($row->tgl_kirim)) : '-'; ?>
                </div>
                <?php if ($row->nama_pengirim): ?>
                <div class="k-meta-item">
                    <span class="material-icons">person_pin</span>
                    oleh <?= html_escape($row->nama_pengirim); ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="k-card-penerima">
                <span class="material-icons">person</span>
                Ditujukan: <?= html_escape($row->nama_penerima); ?>
            </div>

        </div>
        <div class="k-card-footer">
            <a href="<?= base_url('kurir/terima/' . $row->penerima_id); ?>" class="k-btn-terima">
                <span class="material-icons">how_to_reg</span>
                Konfirmasi Diterima
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="k-empty">
    <div class="k-empty-icon">
        <span class="material-icons">task_alt</span>
    </div>
    <h3>Semua Surat Sudah Diterima</h3>
    <p>Tidak ada surat yang menunggu konfirmasi penerimaan saat ini.</p>
</div>
<?php endif; ?>

<a href="<?= base_url('kurir'); ?>" class="k-fab" title="Refresh">
    <span class="material-icons">refresh</span>
</a>

</body>
</html>