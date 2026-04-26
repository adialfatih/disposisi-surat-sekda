(function () {
    'use strict';

    // ── Search & Filter ──────────────────────────────────
    var searchInput    = document.getElementById('searchSMv2');
    var filterKategori = document.getElementById('filterKategoriV2');
    var filterStatus   = document.getElementById('filterStatusV2');
    var tableBody      = document.querySelector('#tableSMv2 tbody');

    function applyFilter() {
        if (!tableBody) return;

        var keyword  = searchInput    ? searchInput.value.toLowerCase().trim() : '';
        var kategori = filterKategori ? filterKategori.value                   : '';
        var status   = filterStatus   ? filterStatus.value                     : '';

        var rows    = tableBody.querySelectorAll('tr[data-search]');
        var visible = 0;

        rows.forEach(function (tr) {
            var matchSearch   = !keyword  || tr.dataset.search.includes(keyword);
            var matchKategori = !kategori || tr.dataset.kategori === kategori;
            var matchStatus   = !status   || tr.dataset.status   === status;

            if (matchSearch && matchKategori && matchStatus) {
                tr.style.display = '';
                visible++;
            } else {
                tr.style.display = 'none';
            }
        });

        var emptyRow = tableBody.querySelector('.tr-empty-filter');
        if (visible === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'tr-empty-filter';
                emptyRow.innerHTML =
                    '<td colspan="10" class="td-empty">' +
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

    if (searchInput)    searchInput.addEventListener('input', applyFilter);
    if (filterKategori) filterKategori.addEventListener('change', applyFilter);
    if (filterStatus)   filterStatus.addEventListener('change', applyFilter);

    // ── Konfirmasi Hapus ─────────────────────────────────
    document.querySelectorAll('.formDeleteSMv2').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title:              'Hapus Surat Masuk?',
                text:               'Data surat masuk ini akan dihapus permanen beserta data disposisinya.',
                icon:               'warning',
                showCancelButton:   true,
                confirmButtonColor: '#C62828',
                cancelButtonColor:  '#9C7B62',
                confirmButtonText:  'Ya, Hapus',
                cancelButtonText:   'Batal',
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // ── Badge warna purple (untuk status Dicetak) ────────
    // Tambahkan style jika belum ada di CSS utama
    var styleTag = document.getElementById('smv2-extra-style');
    if (!styleTag) {
        styleTag = document.createElement('style');
        styleTag.id = 'smv2-extra-style';
        styleTag.textContent = [
            '.badge-purple { background:#EDE7F6; color:#6A1B9A; border:1px solid #CE93D8; }',
            '.badge-red    { background:#FFEBEE; color:#B71C1C; border:1px solid #EF9A9A; }',
        ].join('\n');
        document.head.appendChild(styleTag);
    }

})();