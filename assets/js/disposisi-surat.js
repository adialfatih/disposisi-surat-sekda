(function () {
    // --- Search & Filter ---
    const searchInput = document.getElementById('searchDisposisi');
    const filterStatus = document.getElementById('filterStatus');
    const tableBody = document.querySelector('#tableDisposisi tbody');

    function applyFilter() {
        if (!tableBody) return;
        const keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const status  = filterStatus ? filterStatus.value : '';

        let visible = 0;
        tableBody.querySelectorAll('tr[data-search]').forEach(function (tr) {
            const ok = (!keyword || tr.dataset.search.includes(keyword))
                    && (!status  || tr.dataset.status === status);
            tr.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        let empty = tableBody.querySelector('.tr-empty-filter');
        if (visible === 0 && tableBody.querySelectorAll('tr[data-search]').length > 0) {
            if (!empty) {
                empty = document.createElement('tr');
                empty.className = 'tr-empty-filter';
                empty.innerHTML = '<td colspan="9" class="td-empty"><span class="material-icons" style="font-size:32px;display:block;margin-bottom:6px;color:var(--text-muted);">search_off</span>Tidak ada data yang sesuai.</td>';
                tableBody.appendChild(empty);
            }
            empty.style.display = '';
        } else if (empty) {
            empty.style.display = 'none';
        }
    }

    if (searchInput)  searchInput.addEventListener('input', applyFilter);
    if (filterStatus) filterStatus.addEventListener('change', applyFilter);

    // --- Konfirmasi hapus ---
    document.querySelectorAll('.formDeleteDisposisi').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Disposisi?',
                text: 'Semua data penerima dan file bukti terkait akan ikut terhapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#9C7B62',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(function (r) { if (r.isConfirmed) form.submit(); });
        });
    });

    // --- Dynamic Penerima Rows ---
    const container     = document.getElementById('penerimaContainer');
    const btnTambah     = document.getElementById('btnTambahPenerima');

    function makeRow() {
        const div = document.createElement('div');
        div.className = 'penerima-row';
        div.innerHTML =
            '<div class="penerima-input-wrap">' +
            '<span class="material-icons penerima-icon">person</span>' +
            '<input type="text" name="penerima[]" class="form-control" placeholder="Nama jabatan / bagian / orang">' +
            '<button type="button" class="btn-hapus-penerima" title="Hapus"><span class="material-icons">close</span></button>' +
            '</div>';

        div.querySelector('.btn-hapus-penerima').addEventListener('click', function () {
            if (container.querySelectorAll('.penerima-row').length > 1) {
                div.remove();
            } else {
                div.querySelector('input').value = '';
            }
        });
        return div;
    }

    // Attach hapus ke baris yang sudah ada
    if (container) {
        container.querySelectorAll('.btn-hapus-penerima').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const row = btn.closest('.penerima-row');
                if (container.querySelectorAll('.penerima-row').length > 1) {
                    row.remove();
                } else {
                    row.querySelector('input').value = '';
                }
            });
        });

        if (btnTambah) {
            btnTambah.addEventListener('click', function () {
                container.appendChild(makeRow());
                container.lastElementChild.querySelector('input').focus();
            });
        }
    }
})();