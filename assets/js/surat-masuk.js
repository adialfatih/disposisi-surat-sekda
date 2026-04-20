// assets/js/surat-masuk.js

(function () {
    // --- Search & Filter ---
    const searchInput    = document.getElementById('searchSuratMasuk');
    const filterKategori = document.getElementById('filterKategori');
    const filterStatus   = document.getElementById('filterStatus');
    const tableBody      = document.querySelector('#tableSuratMasuk tbody');

    function applyFilter() {
        if (!tableBody) return;

        const keyword  = (searchInput ? searchInput.value.toLowerCase().trim() : '');
        const kategori = (filterKategori ? filterKategori.value : '');
        const status   = (filterStatus ? filterStatus.value : '');

        const rows = tableBody.querySelectorAll('tr[data-search]');
        let visible = 0;

        rows.forEach(function (tr) {
            const matchSearch   = !keyword  || tr.dataset.search.includes(keyword);
            const matchKategori = !kategori || tr.dataset.kategori === kategori;
            const matchStatus   = !status   || tr.dataset.status   === status;

            if (matchSearch && matchKategori && matchStatus) {
                tr.style.display = '';
                visible++;
            } else {
                tr.style.display = 'none';
            }
        });

        // Tampilkan baris "tidak ditemukan" jika semua tersembunyi
        let emptyRow = tableBody.querySelector('.tr-empty-filter');
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

    // --- Konfirmasi Hapus ---
    document.querySelectorAll('.formDeleteSuratMasuk').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus Surat Masuk?',
                text: 'Data surat masuk ini akan dihapus permanen dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#9C7B62',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // --- Export (placeholder) ---
    const btnExport = document.getElementById('btnExportSuratMasuk');
    if (btnExport) {
        btnExport.addEventListener('click', function () {
            Swal.fire({
                title: 'Export Data',
                text: 'Fitur export akan tersedia pada tahap berikutnya.',
                icon: 'info',
                confirmButtonColor: '#5C3317',
            });
        });
    }
})();