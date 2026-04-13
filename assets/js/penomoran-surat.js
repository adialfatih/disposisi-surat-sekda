(function () {
    const btnTambah = document.getElementById('btnTambahNomorSurat');
    const btnExport = document.getElementById('btnExportPenomoran');
    const filterJenis = document.getElementById('filterJenisSurat');
    const deleteForms = document.querySelectorAll('.formDeleteSurat');

    function openJenisSuratPicker() {
        Swal.fire({
            width: 760,
            customClass: {
                popup: 'surat-picker-popup'
            },
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Tutup',
            cancelButtonColor: '#A0673A',
            html: `
                <div class="surat-picker-head">
                    <h3>Pilih Jenis Surat</h3>
                    <p>
                        Pilih tipe surat terlebih dahulu. Setelah itu sistem akan mengarahkan Anda
                        ke form input yang sesuai dengan kebutuhan bidang terkait.
                    </p>
                </div>

                <div class="surat-picker-grid">
                    <button type="button" class="surat-picker-card pilihJenisSurat" data-url="${window.BASE_URL}penomoran-surat/create/setda-surat-keluar">
                        <div class="surat-picker-icon"><span class="material-icons">outgoing_mail</span></div>
                        <div class="surat-picker-content">
                            <div class="surat-picker-title">SETDA - Surat Keluar</div>
                            <div class="surat-picker-desc">Untuk surat keluar Sekretariat Daerah tanpa kode umum.</div>
                        </div>
                        <div class="surat-picker-arrow"><span class="material-icons">chevron_right</span></div>
                    </button>

                    <button type="button" class="surat-picker-card pilihJenisSurat" data-url="${window.BASE_URL}penomoran-surat/create/setda-sppd">
                        <div class="surat-picker-icon"><span class="material-icons">map</span></div>
                        <div class="surat-picker-content">
                            <div class="surat-picker-title">SETDA - SPPD</div>
                            <div class="surat-picker-desc">Untuk penomoran dokumen perjalanan dinas pada lingkungan SETDA.</div>
                        </div>
                        <div class="surat-picker-arrow"><span class="material-icons">chevron_right</span></div>
                    </button>

                    <button type="button" class="surat-picker-card pilihJenisSurat" data-url="${window.BASE_URL}penomoran-surat/create/umum-surat-keluar">
                        <div class="surat-picker-icon"><span class="material-icons">folder_open</span></div>
                        <div class="surat-picker-content">
                            <div class="surat-picker-title">UMUM - Surat Keluar</div>
                            <div class="surat-picker-desc">Untuk surat keluar umum yang memerlukan kode umum tambahan.</div>
                        </div>
                        <div class="surat-picker-arrow"><span class="material-icons">chevron_right</span></div>
                    </button>

                    <button type="button" class="surat-picker-card pilihJenisSurat" data-url="${window.BASE_URL}penomoran-surat/create/umum-sppd">
                        <div class="surat-picker-icon"><span class="material-icons">badge</span></div>
                        <div class="surat-picker-content">
                            <div class="surat-picker-title">UMUM - SPPD</div>
                            <div class="surat-picker-desc">Untuk dokumen SPPD bidang umum dengan struktur form lengkap.</div>
                        </div>
                        <div class="surat-picker-arrow"><span class="material-icons">chevron_right</span></div>
                    </button>

                    <button type="button" class="surat-picker-card pilihJenisSurat" data-url="${window.BASE_URL}penomoran-surat/create/nota-dinas">
                        <div class="surat-picker-icon"><span class="material-icons">description</span></div>
                        <div class="surat-picker-content">
                            <div class="surat-picker-title">NOTA DINAS</div>
                            <div class="surat-picker-desc">Untuk pencatatan nota dinas yang menggunakan kode umum.</div>
                        </div>
                        <div class="surat-picker-arrow"><span class="material-icons">chevron_right</span></div>
                    </button>
                </div>

                <div class="surat-picker-footnote">
                    Pastikan jenis surat yang dipilih sudah sesuai, karena field form akan menyesuaikan otomatis.
                </div>
            `,
            didOpen: function () {
                document.querySelectorAll('.pilihJenisSurat').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const url = this.getAttribute('data-url');
                        window.location.href = url;
                    });
                });
            }
        });
    }

    if (btnTambah) {
        btnTambah.addEventListener('click', openJenisSuratPicker);
    }

    if (btnExport) {
        btnExport.addEventListener('click', function () {
            Swal.fire({
                title: 'Export Data',
                text: 'Fitur export akan kita sambungkan setelah modul CRUD inti selesai.',
                icon: 'info',
                confirmButtonColor: '#5C3317'
            });
        });
    }

    if (filterJenis) {
        filterJenis.addEventListener('change', function () {
            Swal.fire({
                title: 'Filter Jenis Surat',
                text: 'Dropdown ini dipertahankan dan nanti akan disambungkan ke filter backend.',
                icon: 'info',
                confirmButtonColor: '#5C3317'
            });
        });
    }

    if (deleteForms.length > 0) {
        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus Data?',
                    text: 'Data penomoran surat akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#C62828',
                    cancelButtonColor: '#5C3317'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    }
})();