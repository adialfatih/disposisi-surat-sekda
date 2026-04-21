(function initSignaturePads() {
    // Inisialisasi semua canvas yang ada di halaman
    document.querySelectorAll('.sig-canvas').forEach(function (canvas) {
        initPad(canvas);
    });

    function initPad(canvas) {
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let lastX = 0, lastY = 0;
        let hasDrawn = false;

        // Sesuaikan resolusi canvas dengan ukuran tampilan
        function resize() {
            const rect = canvas.getBoundingClientRect();
            const dpr  = window.devicePixelRatio || 1;

            // Simpan gambar sebelumnya
            const imgData = canvas.toDataURL();

            canvas.width  = rect.width  * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);

            ctx.strokeStyle = '#1e1e2d';
            ctx.lineWidth   = 2.2;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';

            // Restore gambar
            if (hasDrawn) {
                const img = new Image();
                img.onload = () => ctx.drawImage(img, 0, 0, rect.width, rect.height);
                img.src = imgData;
            }
        }

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            if (e.touches) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top,
                };
            }
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        function start(e) {
            e.preventDefault();
            drawing = true;
            const pos = getPos(e);
            lastX = pos.x;
            lastY = pos.y;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
        }

        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
            hasDrawn = true;
        }

        function stop(e) {
            if (!drawing) return;
            drawing = false;
            ctx.closePath();
        }

        canvas.addEventListener('mousedown',  start);
        canvas.addEventListener('mousemove',  draw);
        canvas.addEventListener('mouseup',    stop);
        canvas.addEventListener('mouseleave', stop);
        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove',  draw,  { passive: false });
        canvas.addEventListener('touchend',   stop);

        canvas._clear = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasDrawn = false;
        };

        canvas._isEmpty = function () { return !hasDrawn; };

        resize();
        window.addEventListener('resize', resize);
    }
})();

// --- Fungsi global untuk tombol clear & prepare submit ---
function clearSig(canvasId, hiddenId) {
    const canvas = document.getElementById(canvasId);
    if (canvas && canvas._clear) {
        canvas._clear();
        const hidden = document.getElementById(hiddenId);
        if (hidden) hidden.value = '';
    }
}

function prepSig(canvasId, hiddenId) {
    const canvas = document.getElementById(canvasId);
    const hidden = document.getElementById(hiddenId);
    if (!canvas || !hidden) return;

    if (canvas._isEmpty && canvas._isEmpty()) {
        // Tidak ada tanda tangan — kosongkan saja
        hidden.value = '';
        return;
    }

    // Export ke base64 PNG
    hidden.value = canvas.toDataURL('image/png');
}