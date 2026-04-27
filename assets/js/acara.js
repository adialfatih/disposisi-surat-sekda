/**
 * assets/js/acara.js
 * JavaScript untuk modul Agenda Acara
 */

(function () {
    'use strict';

    // ── Search & Filter ──────────────────────────────────
    var searchInput = document.getElementById('searchAcara');
    var filterWaktu = document.getElementById('filterWaktuAcara');
    var tableBody = document.querySelector('#tableAcara tbody');

    function applyFilter() {
        if (!tableBody) return;

        var keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var waktu = filterWaktu ? filterWaktu.value : '';

        var rows = tableBody.querySelectorAll('tr[data-search]');
        var visible = 0;

        rows.forEach(function (tr) {
            var matchSearch = !keyword || tr.dataset.search.includes(keyword);
            var matchWaktu = !waktu || tr.dataset.waktu === waktu;

            if (matchSearch && matchWaktu) {
                tr.style.display = '';
                visible++;
            } else {
                tr.style.display = 'none';
            }
        });

        // Baris kosong jika tidak ada hasil
        var emptyRow = tableBody.querySelector('.tr-empty-filter');
        if (visible === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'tr-empty-filter';
                emptyRow.innerHTML =
                    '<td colspan="9" class="td-empty">' +
                    '<span class="material-icons" style="font-size:32px;color:var(--text-muted);display:block;margin-bottom:6px;">search_off</span>' +
                    'Tidak ada data yang sesuai filter.' +
                    '</td>';
                tableBody.appendChild(emptyRow);
            }
            emptyRow.style.display = '';
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    if (filterWaktu) filterWaktu.addEventListener('change', applyFilter);

    // ── Highlight baris hari ini ─────────────────────────
    tableBody && tableBody.querySelectorAll('tr[data-waktu="hari_ini"]').forEach(function (tr) {
        tr.style.background = '#FFF8E1';
    });

    // ── Konfirmasi Hapus ─────────────────────────────────
    document.querySelectorAll('.formDeleteAcara').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus Data Acara?',
                text: 'Data acara ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#9C7B62',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });

})();