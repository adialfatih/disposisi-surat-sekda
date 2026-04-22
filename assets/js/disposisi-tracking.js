// assets/js/disposisi-tracking.js
// Signature Pad — halaman tracking pengiriman & penerimaan

document.addEventListener('DOMContentLoaded', function () {

    // Inisialisasi semua canvas .sig-canvas di halaman
    document.querySelectorAll('.sig-canvas').forEach(function (canvas) {
        initPad(canvas);
    });

    function initPad(canvas) {
        var ctx      = canvas.getContext('2d');
        var drawing  = false;
        var hasDrawn = false;
        var lastX    = 0;
        var lastY    = 0;

        function setupCtx() {
            ctx.strokeStyle = '#1e1e2d';
            ctx.lineWidth   = 2.2;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';
        }

        function resize() {
            var rect = canvas.getBoundingClientRect();
            var dpr  = window.devicePixelRatio || 1;

            // Simpan gambar sebelumnya
            var saved = hasDrawn ? canvas.toDataURL() : null;

            canvas.width  = rect.width  * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);
            setupCtx();

            // Restore
            if (saved) {
                var img    = new Image();
                var w      = rect.width;
                var h      = rect.height;
                img.onload = function () { ctx.drawImage(img, 0, 0, w, h); };
                img.src    = saved;
            }
        }

        function getPos(e) {
            var rect = canvas.getBoundingClientRect();
            if (e.touches && e.touches.length > 0) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top,
                };
            }
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top,
            };
        }

        function onStart(e) {
            e.preventDefault();
            drawing = true;
            var pos = getPos(e);
            lastX = pos.x;
            lastY = pos.y;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
        }

        function onMove(e) {
            if (!drawing) return;
            e.preventDefault();
            var pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX    = pos.x;
            lastY    = pos.y;
            hasDrawn = true;
        }

        function onStop() {
            if (!drawing) return;
            drawing = false;
            ctx.closePath();
        }

        // Mouse events
        canvas.addEventListener('mousedown',  onStart);
        canvas.addEventListener('mousemove',  onMove);
        canvas.addEventListener('mouseup',    onStop);
        canvas.addEventListener('mouseleave', onStop);

        // Touch events
        canvas.addEventListener('touchstart', onStart, { passive: false });
        canvas.addEventListener('touchmove',  onMove,  { passive: false });
        canvas.addEventListener('touchend',   onStop);

        // Expose API ke canvas element
        canvas._sigClear = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasDrawn = false;
        };

        canvas._sigIsEmpty = function () {
            return !hasDrawn;
        };

        canvas._sigExport = function () {
            if (!hasDrawn) return '';
            return canvas.toDataURL('image/png');
        };

        resize();
        window.addEventListener('resize', resize);
    }

});

// -------------------------------------------------------
// Global helpers — dipanggil dari onclick di HTML
// -------------------------------------------------------

function clearSig(canvasId, hiddenId) {
    var canvas = document.getElementById(canvasId);
    var hidden = document.getElementById(hiddenId);
    if (canvas && canvas._sigClear) {
        canvas._sigClear();
    }
    if (hidden) hidden.value = '';
}

function prepSig(canvasId, hiddenId) {
    var canvas = document.getElementById(canvasId);
    var hidden = document.getElementById(hiddenId);
    if (!canvas || !hidden) return;

    if (canvas._sigIsEmpty && canvas._sigIsEmpty()) {
        hidden.value = ''; // kosong = tidak ada TTD baru
        return;
    }

    if (canvas._sigExport) {
        hidden.value = canvas._sigExport();
    }
}


// ----------------------------------------------------------------
// Preview foto sebelum upload
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {

    // Map: input id → preview wrapper id & img id
    var previewMap = {
        'fotoBuktiKirim'  : { wrap: 'previewKirim',  img: 'previewKirimImg'  },
        'fotoBuktiTerima' : { wrap: 'previewTerima', img: 'previewTerimaImg' },
    };

    Object.keys(previewMap).forEach(function (inputId) {
        var input = document.getElementById(inputId);
        if (!input) return;

        var cfg   = previewMap[inputId];
        var wrap  = document.getElementById(cfg.wrap);
        var img   = document.getElementById(cfg.img);

        input.addEventListener('change', function () {
            var file = input.files[0];
            if (!file) return;

            // Validasi ukuran di client (maks 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                img.src          = e.target.result;
                wrap.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        });
    });
});

// Hapus preview & reset input
function removePreview(inputId, wrapId) {
    var input = document.getElementById(inputId);
    var wrap  = document.getElementById(wrapId);
    if (input) input.value = '';
    if (wrap)  wrap.style.display = 'none';
}