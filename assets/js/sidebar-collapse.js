/**
 * sidebar-collapse.js
 * Fitur show/hide sidebar di desktop dengan persistensi localStorage.
 * Tidak mengubah logika hamburger mobile yang sudah ada.
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'dinamit_sidebar_collapsed';
    const COLLAPSED_CLASS = 'sidebar-collapsed';
    const MOBILE_BREAKPOINT = 768;

    /* ── Buat tombol toggle ── */
    function createToggleButton() {
        const btn = document.createElement('button');
        btn.id = 'sidebar-toggle';
        btn.title = 'Sembunyikan / tampilkan sidebar';
        btn.setAttribute('aria-label', 'Toggle sidebar');
        btn.innerHTML = '<span class="material-icons">chevron_left</span>';
        document.body.appendChild(btn);
        return btn;
    }

    /* ── Tambahkan data-tooltip ke setiap nav-item ── */
    function attachTooltips() {
        document.querySelectorAll('.nav-item').forEach(function (item) {
            // Ambil teks pertama (text node) dari nav-item, bukan teks badge
            const clone = item.cloneNode(true);
            // Hapus badge dan icons dari clone agar dapat teks bersih
            clone.querySelectorAll('.material-icons, .nav-badge').forEach(function (el) {
                el.remove();
            });
            const label = clone.textContent.trim();
            if (label) {
                item.setAttribute('data-tooltip', label);
            }
        });
    }

    /* ── Terapkan state collapsed/expanded ── */
    function applyState(collapsed) {
        if (collapsed) {
            document.body.classList.add(COLLAPSED_CLASS);
        } else {
            document.body.classList.remove(COLLAPSED_CLASS);
        }
    }

    /* ── Simpan state ke localStorage ── */
    function saveState(collapsed) {
        try {
            localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        } catch (e) { /* localStorage tidak tersedia */ }
    }

    /* ── Baca state dari localStorage ── */
    function loadState() {
        try {
            return localStorage.getItem(STORAGE_KEY) === '1';
        } catch (e) {
            return false;
        }
    }

    /* ── Inisialisasi ── */
    function init() {
        // Jangan jalankan di mobile
        if (window.innerWidth <= MOBILE_BREAKPOINT) return;

        attachTooltips();

        const toggleBtn = createToggleButton();

        // Terapkan state tersimpan
        applyState(loadState());

        // Klik tombol toggle
        toggleBtn.addEventListener('click', function () {
            const isNowCollapsed = !document.body.classList.contains(COLLAPSED_CLASS);
            applyState(isNowCollapsed);
            saveState(isNowCollapsed);
        });

        // Saat resize ke mobile: reset collapsed, sembunyikan tombol
        window.addEventListener('resize', function () {
            if (window.innerWidth <= MOBILE_BREAKPOINT) {
                // Jangan ubah state tersimpan, hanya hapus class agar mobile normal
                document.body.classList.remove(COLLAPSED_CLASS);
            } else {
                // Kembali ke desktop: terapkan ulang state tersimpan
                applyState(loadState());
            }
        });
    }

    // Jalankan setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();