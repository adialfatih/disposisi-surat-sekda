<div class="page active">
    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" placeholder="Cari disposisi, perihal, asal surat...">
        </div>

        <select class="filter-select">
            <option value="">Semua Status</option>
            <option value="">Baru</option>
            <option value="">Diproses</option>
            <option value="">Selesai</option>
        </select>

        <button class="btn btn-primary">
            <span class="material-icons">add</span>Tambah Disposisi
        </button>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No Agenda</th>
                        <th>Tanggal</th>
                        <th>Asal Surat</th>
                        <th>Perihal</th>
                        <th>Diteruskan Ke</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DISP-001</strong></td>
                        <td>13-04-2026</td>
                        <td>Dinas Kominfo</td>
                        <td>Permohonan Data Pendukung</td>
                        <td>Bagian Pemerintahan</td>
                        <td><span class="badge badge-yellow">Diproses</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-outline btn-sm"><span class="material-icons">visibility</span></button>
                                <button class="btn btn-outline btn-sm"><span class="material-icons">edit</span></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DISP-002</strong></td>
                        <td>12-04-2026</td>
                        <td>BPKAD</td>
                        <td>Penyampaian Laporan Triwulan</td>
                        <td>Bagian Keuangan</td>
                        <td><span class="badge badge-green">Selesai</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-outline btn-sm"><span class="material-icons">visibility</span></button>
                                <button class="btn btn-outline btn-sm"><span class="material-icons">edit</span></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DISP-003</strong></td>
                        <td>11-04-2026</td>
                        <td>Inspektorat</td>
                        <td>Permintaan Klarifikasi</td>
                        <td>Bagian Hukum</td>
                        <td><span class="badge badge-red">Belum Diproses</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-outline btn-sm"><span class="material-icons">visibility</span></button>
                                <button class="btn btn-outline btn-sm"><span class="material-icons">edit</span></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div>Menampilkan 1–3 dari 3 data</div>
            <div class="page-btns">
                <button class="page-btn active">1</button>
            </div>
        </div>
    </div>
</div>