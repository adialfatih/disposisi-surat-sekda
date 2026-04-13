(function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const hamburger = document.getElementById('hamburger');
    const btnNotif = document.getElementById('btnNotif');
    const btnSearch = document.getElementById('btnSearch');
    const btnLogout = document.getElementById('btnLogout');
    const chartBars = document.getElementById('chartBars');

    function openSidebar() {
        if (!sidebar || !overlay || !hamburger) return;
        sidebar.classList.add('open');
        overlay.classList.add('show');
        hamburger.classList.add('open');
    }

    function closeSidebar() {
        if (!sidebar || !overlay || !hamburger) return;
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        hamburger.classList.remove('open');
    }

    if (hamburger) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    if (btnNotif) {
        btnNotif.addEventListener('click', function () {
            Swal.fire({
                title: 'Notifikasi',
                text: 'Ada agenda surat yang perlu ditindaklanjuti.',
                icon: 'info',
                confirmButtonColor: '#5C3317'
            });
        });
    }

    if (btnSearch) {
        btnSearch.addEventListener('click', function () {
            Swal.fire({
                title: 'Pencarian',
                text: 'Fitur pencarian global akan kita hubungkan pada tahap berikutnya.',
                icon: 'info',
                confirmButtonColor: '#5C3317'
            });
        });
    }

    if (btnLogout) {
        btnLogout.addEventListener('click', function () {
            Swal.fire({
                title: 'Keluar dari Sistem?',
                text: 'Template logout belum dihubungkan ke autentikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#5C3317',
                cancelButtonColor: '#A0673A'
            });
        });
    }

    function buildChart() {
        if (!chartBars) return;

        const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        const suratKeluar = [16, 22, 18, 27, 24, 12, 8];
        const suratMasuk = [10, 14, 12, 15, 17, 9, 6];
        const maxVal = Math.max(...suratKeluar, ...suratMasuk);

        chartBars.innerHTML = days.map((day, i) => {
            return `
                <div class="bar-group">
                    <div style="display:flex;gap:3px;align-items:flex-end;height:100px">
                        <div class="bar primary" style="height:${(suratKeluar[i] / maxVal) * 100}%" title="${suratKeluar[i]} surat keluar"></div>
                        <div class="bar secondary" style="height:${(suratMasuk[i] / maxVal) * 100}%" title="${suratMasuk[i]} surat masuk"></div>
                    </div>
                    <div class="bar-label">${day}</div>
                </div>
            `;
        }).join('');
    }

    buildChart();
})();