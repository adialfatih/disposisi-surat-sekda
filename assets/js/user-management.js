(function () {
    'use strict';

    var searchInput = document.getElementById('searchUser');
    var filterRole = document.getElementById('filterRoleUser');
    var filterStatus = document.getElementById('filterStatusUser');
    var tableBody = document.querySelector('#tableUser tbody');

    function applyFilter() {
        if (!tableBody) return;

        var keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var role = filterRole ? filterRole.value : '';
        var status = filterStatus ? filterStatus.value : '';
        var rows = tableBody.querySelectorAll('tr[data-search]');
        var visible = 0;

        rows.forEach(function (tr) {
            var matchSearch = !keyword || tr.dataset.search.includes(keyword);
            var matchRole = !role || tr.dataset.role === role;
            var matchStatus = !status || tr.dataset.status === status;

            if (matchSearch && matchRole && matchStatus) {
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
                    '<td colspan="8" class="td-empty">' +
                    '<span class="material-icons" style="font-size:32px;color:var(--text-muted);display:block;margin-bottom:6px;">search_off</span>' +
                    'Tidak ada user yang sesuai filter.' +
                    '</td>';
                tableBody.appendChild(emptyRow);
            }
            emptyRow.style.display = '';
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    if (filterRole) filterRole.addEventListener('change', applyFilter);
    if (filterStatus) filterStatus.addEventListener('change', applyFilter);

    document.querySelectorAll('.formDeleteUser').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus User?',
                text: 'Akun user ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#9C7B62',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
})();
