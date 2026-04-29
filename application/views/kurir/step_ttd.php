<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Tanda Tangan</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/kurir.css?v=3'); ?>">
</head>
<body>

<?php $this->load->view('kurir/_app_header'); ?>

<!-- Header -->
<div class="k-step-header">
    <a href="javascript:history.back()" class="k-back-btn">
        <span class="material-icons">arrow_back</span>
    </a>
    <div class="k-step-header-info">
        <h2>Tanda Tangan</h2>
        <div class="k-step-indicator">Langkah 3 dari 3 — Langkah terakhir</div>
    </div>
    <div class="k-step-dots">
        <div class="k-step-dot done"></div>
        <div class="k-step-dot done"></div>
        <div class="k-step-dot active"></div>
    </div>
</div>

<div class="k-step-wrap">
    <?php $this->load->view('kurir/_surat_info', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>

    <?php if (!empty($foto_path)): ?>
    <div class="k-form-card" style="padding:12px;">
        <div style="font-size:11px;font-weight:700;color:var(--ink-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;display:flex;align-items:center;gap:5px;">
            <span class="material-icons" style="font-size:14px;">check_circle</span>
            Foto bukti berhasil diambil
        </div>
        <img src="<?= upload_url($foto_path); ?>"
             style="width:100%;border-radius:9px;display:block;max-height:140px;object-fit:cover;">
    </div>
    <?php endif; ?>

    <div class="k-form-card">
        <div class="k-form-card-title">
            <span class="material-icons">draw</span>
            Tanda Tangan: <strong style="color:var(--accent);margin-left:4px;"><?= html_escape($nama); ?></strong>
        </div>
        <p class="k-form-card-desc">
            Tanda tangan di kotak di bawah menggunakan jari Anda. Pastikan tanda tangan jelas dan lengkap.
        </p>

        <form method="post" action="<?= base_url('kurir/simpan'); ?>" id="formTTD">
            <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
            <input type="hidden" name="ttd_data" id="ttdData">

            <!-- Signature pad -->
            <div class="k-sig-wrap" id="sigWrap">
                <canvas id="sigCanvas" class="k-sig-canvas"></canvas>
                <div class="k-sig-toolbar">
                    <span class="k-sig-hint">
                        <span class="material-icons">touch_app</span>
                        Tanda tangan di sini
                    </span>
                    <button type="button" class="k-sig-clear" id="btnClearSig">
                        <span class="material-icons">refresh</span>Hapus
                    </button>
                </div>
            </div>

            <div id="sigEmptyMsg" style="display:none;margin:8px 0;padding:10px 12px;background:#FEF3C7;border-radius:8px;font-size:12px;color:#B45309;display:none;align-items:center;gap:6px;">
                <span class="material-icons" style="font-size:16px;">warning</span>
                Tanda tangan masih kosong. Silakan tanda tangan terlebih dahulu.
            </div>

            <button type="submit" class="k-btn-next" id="btnSimpan" style="margin-top:14px;"
                    onclick="return validateAndPrep()">
                <span class="material-icons">verified</span>
                Simpan & Selesaikan
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    var canvas   = document.getElementById('sigCanvas');
    var ctx      = canvas.getContext('2d');
    var drawing  = false;
    var hasDrawn = false;

    function setupCtx() {
        ctx.strokeStyle = '#0F1923';
        ctx.lineWidth   = 2.5;
        ctx.lineCap     = 'round';
        ctx.lineJoin    = 'round';
    }

    function resize() {
        var rect = canvas.getBoundingClientRect();
        var dpr  = window.devicePixelRatio || 1;
        var saved = hasDrawn ? canvas.toDataURL() : null;

        canvas.width  = rect.width  * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);
        setupCtx();

        if (saved) {
            var img = new Image();
            img.onload = function () { ctx.drawImage(img, 0, 0, rect.width, rect.height); };
            img.src = saved;
        }
    }

    function getPos(e) {
        var rect = canvas.getBoundingClientRect();
        if (e.touches && e.touches.length > 0) {
            return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
        }
        return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }

    canvas.addEventListener('mousedown', function(e) {
        drawing = true;
        var p = getPos(e);
        ctx.beginPath(); ctx.moveTo(p.x, p.y);
    });
    canvas.addEventListener('mousemove', function(e) {
        if (!drawing) return;
        var p = getPos(e);
        ctx.lineTo(p.x, p.y); ctx.stroke();
        hasDrawn = true;
    });
    canvas.addEventListener('mouseup',    function() { drawing = false; ctx.closePath(); });
    canvas.addEventListener('mouseleave', function() { drawing = false; ctx.closePath(); });

    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault(); drawing = true;
        var p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y);
    }, { passive: false });
    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault(); if (!drawing) return;
        var p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); hasDrawn = true;
    }, { passive: false });
    canvas.addEventListener('touchend', function() { drawing = false; ctx.closePath(); });

    document.getElementById('btnClearSig').addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
        document.getElementById('ttdData').value = '';
        document.getElementById('sigEmptyMsg').style.display = 'none';
    });

    window.addEventListener('resize', resize);
    resize();

    // Export tanda tangan ke hidden input sebelum submit
    window.validateAndPrep = function () {
        if (!hasDrawn) {
            var msg = document.getElementById('sigEmptyMsg');
            msg.style.display = 'flex';
            canvas.style.border = '2px solid #B45309';
            setTimeout(function() { canvas.style.border = ''; }, 2000);
            return false;
        }
        document.getElementById('ttdData').value = canvas.toDataURL('image/png');
        return true;
    };
})();
</script>

</body>
</html>
