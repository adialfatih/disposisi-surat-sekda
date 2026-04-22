// assets/js/disposisi-surat.js
// Handles: index filter/search, delete confirm, dynamic penerima rows

document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // INDEX PAGE — Search, Filter, Delete confirm
    // ================================================================

    var searchInput  = document.getElementById('searchDisposisi');
    var filterStatus = document.getElementById('filterStatus');
    var tableBody    = document.querySelector('#tableDisposisi tbody');

    function applyFilter() {
        if (!tableBody) return;
        var keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var status  = filterStatus ? filterStatus.value : '';
        var rows    = tableBody.querySelectorAll('tr[data-search]');
        var visible = 0;

        rows.forEach(function (tr) {
            var ok = (!keyword || tr.dataset.search.includes(keyword))
                  && (!status  || tr.dataset.status === status);
            tr.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        var empty = tableBody.querySelector('.tr-empty-filter');
        if (visible === 0 && rows.length > 0) {
            if (!empty) {
                empty = document.createElement('tr');
                empty.className = 'tr-empty-filter';
                empty.innerHTML = '<td colspan="9" class="td-empty">'
                    + '<span class="material-icons" style="font-size:32px;display:block;margin-bottom:6px;color:var(--text-muted);">search_off</span>'
                    + 'Tidak ada data yang sesuai.</td>';
                tableBody.appendChild(empty);
            }
            empty.style.display = '';
        } else if (empty) {
            empty.style.display = 'none';
        }
    }

    if (searchInput)  searchInput.addEventListener('input', applyFilter);
    if (filterStatus) filterStatus.addEventListener('change', applyFilter);

    // Delete confirm
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

    // ================================================================
    // FORM PAGE — Dynamic Penerima Rows
    // ================================================================

    var container = document.getElementById('penerimaContainer');
    var btnTambah = document.getElementById('btnTambahPenerima');

    if (!container || !btnTambah) return; // bukan halaman form, stop

    // Buat baris penerima baru
    function makeRow() {
        var div = document.createElement('div');
        div.className = 'penerima-row';
        div.innerHTML =
            '<div class="penerima-input-wrap">'
            + '<span class="material-icons penerima-icon">person</span>'
            + '<input type="text" name="penerima[]" class="form-control" placeholder="Nama jabatan / bagian / orang">'
            + '<button type="button" class="btn-hapus-penerima" title="Hapus baris ini">'
            + '<span class="material-icons">close</span>'
            + '</button>'
            + '</div>';
        return div;
    }

    // Hapus baris — minimal 1 baris harus tetap ada
    function hapusRow(row) {
        var allRows = container.querySelectorAll('.penerima-row');
        if (allRows.length > 1) {
            row.remove();
        } else {
            // Baris terakhir: kosongkan saja
            var input = row.querySelector('input');
            if (input) input.value = '';
        }
    }

    // Pasang event hapus ke baris yang sudah ada di DOM saat load
    container.querySelectorAll('.btn-hapus-penerima').forEach(function (btn) {
        btn.addEventListener('click', function () {
            hapusRow(btn.closest('.penerima-row'));
        });
    });

    // Event delegation untuk baris yang dibuat dinamis
    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-hapus-penerima');
        if (!btn) return;
        // Pastikan btn ini belum punya listener manual (dari loop di atas)
        // Event delegation aman karena closest() hanya match yg ada di container
        hapusRow(btn.closest('.penerima-row'));
    });

    // Tombol tambah penerima
    btnTambah.addEventListener('click', function () {
        var newRow = makeRow();
        container.appendChild(newRow);
        // Focus ke input baru
        var input = newRow.querySelector('input');
        if (input) input.focus();
    });

});