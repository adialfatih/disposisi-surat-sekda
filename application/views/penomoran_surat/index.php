<div class="page active">
    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" placeholder="Cari nomor surat, perihal, tujuan surat...">
        </div>

        <select class="filter-select">
            <option value="">Semua Jenis</option>
            <option value="">Surat Undangan</option>
            <option value="">Nota Dinas</option>
            <option value="">Surat Tugas</option>
            <option value="">Surat Edaran</option>
        </select>

        <button class="btn btn-primary">
            <span class="material-icons">add</span>Tambah Nomor Surat
        </button>

        <button class="btn btn-outline">
            <span class="material-icons">file_download</span>Export
        </button>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Tujuan</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>13-04-2026</td>
                        <td><strong>800/001/SEKDA/2026</strong></td>
                        <td>Undangan Rapat Koordinasi</td>
                        <td>OPD Terkait</td>
                        <td>Surat Undangan</td>
                        <td><span class="badge badge-green">Terbit</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-outline btn-sm"><span class="material-icons">visibility</span></button>
                                <button class="btn btn-outline btn-sm"><span class="material-icons">edit</span></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>12-04-2026</td>
                        <td><strong>800/002/SEKDA/2026</strong></td>
                        <td>Nota Dinas Internal</td>
                        <td>Bagian Umum</td>
                        <td>Nota Dinas</td>
                        <td><span class="badge badge-yellow">Draft</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-outline btn-sm"><span class="material-icons">visibility</span></button>
                                <button class="btn btn-outline btn-sm"><span class="material-icons">edit</span></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>11-04-2026</td>
                        <td><strong>800/003/SEKDA/2026</strong></td>
                        <td>Surat Tugas Pendampingan</td>
                        <td>Tim Pendamping</td>
                        <td>Surat Tugas</td>
                        <td><span class="badge badge-green">Terbit</span></td>
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