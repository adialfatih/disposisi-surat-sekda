<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Ambil Foto Bukti</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/kurir.css?v=2'); ?>">
</head>
<body>

<?php $this->load->view('kurir/_app_header'); ?>

<!-- Header -->
<div class="k-step-header">
    <a href="<?= base_url('kurir/terima/' . $penerima->id); ?>" class="k-back-btn">
        <span class="material-icons">arrow_back</span>
    </a>
    <div class="k-step-header-info">
        <h2>Ambil Foto Bukti</h2>
        <div class="k-step-indicator">Langkah 2 dari 3</div>
    </div>
    <div class="k-step-dots">
        <div class="k-step-dot done"></div>
        <div class="k-step-dot active"></div>
        <div class="k-step-dot"></div>
    </div>
</div>

<div class="k-step-wrap">
    <?php $this->load->view('kurir/_surat_info', ['penerima' => $penerima, 'disposisi' => $disposisi]); ?>

    <!-- Form POST ke step_ttd — foto dikirim sebagai base64 -->
    <form method="post" action="<?= base_url('kurir/step-ttd'); ?>" id="formKamera" enctype="multipart/form-data">
        <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
        <input type="hidden" name="foto_base64" id="fotoBase64">

        <!-- Kamera live -->
        <div id="cameraSection">
            <div class="k-camera-wrap" id="cameraWrap">
                <video id="videoEl" class="k-video" autoplay playsinline muted></video>
                <canvas id="canvasEl" style="display:none;"></canvas>
                <!-- Guide overlay -->
                <div class="k-camera-guide">
                    <div class="k-camera-guide-corner tl"></div>
                    <div class="k-camera-guide-corner tr"></div>
                    <div class="k-camera-guide-corner bl"></div>
                    <div class="k-camera-guide-corner br"></div>
                </div>
            </div>

            <div class="k-shutter-wrap">
                <button type="button" class="k-switch-cam" id="btnSwitchCam" title="Ganti kamera">
                    <span class="material-icons">flip_camera_android</span>
                </button>
                <button type="button" class="k-shutter-btn" id="btnShutter" title="Ambil foto"></button>
                <!-- Spacer -->
                <div style="width:44px;"></div>
            </div>
        </div>

        <!-- Preview hasil foto -->
        <div id="previewSection" style="display:none;" class="k-step-wrap" style="padding:0;gap:10px;">
            <div class="k-preview-result" id="previewResult" style="display:block;">
                <img id="previewImg" src="" alt="Preview foto">
            </div>
            <div class="k-preview-actions">
                <button type="button" class="k-btn-retake" id="btnRetake">
                    <span class="material-icons">replay</span>Ulang Foto
                </button>
                <button type="submit" class="k-btn-next" id="btnLanjutTTD">
                    Lanjut TTD <span class="material-icons">draw</span>
                </button>
            </div>
        </div>

        <!-- Fallback: tidak ada kamera -->
        <div id="noCameraSection" style="display:none;" class="k-no-camera">
            <span class="material-icons">no_photography</span>
            <p>Kamera tidak tersedia di perangkat ini.</p>
            <label class="k-file-label" for="fotoFile">
                <span class="material-icons">upload_file</span>
                Pilih dari galeri
            </label>
            <input type="file" name="foto_kamera" id="fotoFile"
                   class="k-file-hidden" accept="image/*">
        </div>

    </form>

    <!-- Skip foto -->
    <form method="post" action="<?= base_url('kurir/step-ttd'); ?>" id="formSkip">
        <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
        <input type="hidden" name="foto_base64" value="">
        <button type="submit" class="k-btn-skip">Lewati, langsung ke tanda tangan →</button>
    </form>
</div>

<script>
(function () {
    var video      = document.getElementById('videoEl');
    var canvas     = document.getElementById('canvasEl');
    var btnShutter = document.getElementById('btnShutter');
    var btnSwitch  = document.getElementById('btnSwitchCam');
    var btnRetake  = document.getElementById('btnRetake');
    var previewImg = document.getElementById('previewImg');
    var fotoB64    = document.getElementById('fotoBase64');
    var camSection = document.getElementById('cameraSection');
    var prevSection= document.getElementById('previewSection');
    var noCamera   = document.getElementById('noCameraSection');
    var fotoFile   = document.getElementById('fotoFile');

    var currentFacing = 'environment'; // kamera belakang default
    var stream = null;

    function startCamera(facing) {
        if (stream) {
            stream.getTracks().forEach(function (t) { t.stop(); });
        }
        var constraints = {
            video: { facingMode: facing, width: { ideal: 1280 }, height: { ideal: 960 } },
            audio: false
        };
        navigator.mediaDevices.getUserMedia(constraints)
            .then(function (s) {
                stream = s;
                video.srcObject = s;
            })
            .catch(function (err) {
                console.warn('Kamera tidak tersedia:', err);
                camSection.style.display = 'none';
                noCamera.style.display   = 'block';
            });
    }

    // Cek apakah browser support camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        startCamera(currentFacing);
    } else {
        camSection.style.display = 'none';
        noCamera.style.display   = 'block';
    }

    // Ganti kamera depan/belakang
    btnSwitch.addEventListener('click', function () {
        currentFacing = currentFacing === 'environment' ? 'user' : 'environment';
        startCamera(currentFacing);
    });

    // Ambil foto — capture frame dari video ke canvas
    btnShutter.addEventListener('click', function () {
        var w = video.videoWidth  || 640;
        var h = video.videoHeight || 480;
        canvas.width  = w;
        canvas.height = h;
        canvas.getContext('2d').drawImage(video, 0, 0, w, h);

        var dataUrl = canvas.toDataURL('image/jpeg', 0.88);
        fotoB64.value = dataUrl;
        previewImg.src = dataUrl;

        // Stop stream, tampilkan preview
        if (stream) stream.getTracks().forEach(function (t) { t.stop(); });
        camSection.style.display  = 'none';
        prevSection.style.display = 'flex';
        prevSection.style.flexDirection = 'column';
        prevSection.style.gap = '10px';
    });

    // Ulang foto
    btnRetake.addEventListener('click', function () {
        fotoB64.value = '';
        previewImg.src = '';
        prevSection.style.display = 'none';
        camSection.style.display  = 'block';
        startCamera(currentFacing);
    });

    // Fallback file input — preview
    if (fotoFile) {
        fotoFile.addEventListener('change', function () {
            var file = fotoFile.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                fotoB64.value  = e.target.result;
                previewImg.src = e.target.result;
                noCamera.style.display   = 'none';
                prevSection.style.display = 'flex';
                prevSection.style.flexDirection = 'column';
                prevSection.style.gap = '10px';
            };
            reader.readAsDataURL(file);
        });
    }

    // Stop stream saat leave
    window.addEventListener('beforeunload', function () {
        if (stream) stream.getTracks().forEach(function (t) { t.stop(); });
    });

})();
</script>

</body>
</html>
