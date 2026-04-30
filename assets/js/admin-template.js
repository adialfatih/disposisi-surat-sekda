(function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const hamburger = document.getElementById('hamburger');
    const btnNotif = document.getElementById('btnNotif');
    const btnSearch = document.getElementById('btnSearch');
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

    function buildChart() {
        if (!chartBars) return;

        const dashboardData = window.DASHBOARD_CHART_DATA || {};
        const days = dashboardData.labels || ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        const suratKeluar = dashboardData.penomoran || [0, 0, 0, 0, 0, 0, 0];
        const suratMasuk = dashboardData.surat_masuk || [0, 0, 0, 0, 0, 0, 0];
        const maxVal = Math.max(...suratKeluar, ...suratMasuk, 1);

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
