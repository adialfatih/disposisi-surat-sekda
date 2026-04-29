<div class="page active">

    <?php if ($this->session->flashdata('crud_success')): ?>
        <div class="alert-flash alert-flash-success">
            <span class="material-icons">check_circle</span>
            <?= $this->session->flashdata('crud_success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('crud_error')): ?>
        <div class="alert-flash alert-flash-error">
            <span class="material-icons">error_outline</span>
            <?= $this->session->flashdata('crud_error'); ?>
        </div>
    <?php endif; ?>

    <div class="sm-stats-strip">
        <?php
        $total  = count($rows);
        $admin  = count(array_filter($rows, fn($r) => $r->hak_akses === 'admin'));
        $kurir  = count(array_filter($rows, fn($r) => $r->hak_akses === 'user'));
        $aktif  = count(array_filter($rows, fn($r) => (int) $r->is_active === 1));
        ?>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:var(--sogan);">groups</span>
            <div>
                <div class="sm-stat-val"><?= $total; ?></div>
                <div class="sm-stat-lbl">Total User</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#6A1B9A;">admin_panel_settings</span>
            <div>
                <div class="sm-stat-val" style="color:#6A1B9A;"><?= $admin; ?></div>
                <div class="sm-stat-lbl">Admin</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#1565C0;">local_shipping</span>
            <div>
                <div class="sm-stat-val" style="color:#1565C0;"><?= $kurir; ?></div>
                <div class="sm-stat-lbl">Caraka</div>
            </div>
        </div>
        <div class="sm-stat-divider"></div>
        <div class="sm-stat-item">
            <span class="material-icons sm-stat-icon" style="color:#2E7D32;">verified_user</span>
            <div>
                <div class="sm-stat-val" style="color:#2E7D32;"><?= $aktif; ?></div>
                <div class="sm-stat-lbl">Aktif</div>
            </div>
        </div>
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <span class="material-icons">search</span>
            <input type="text" id="searchUser" placeholder="Cari nama atau username...">
        </div>

        <select class="filter-select" id="filterRoleUser">
            <option value="">Semua Hak Akses</option>
            <option value="admin">Admin</option>
            <option value="user">Caraka</option>
        </select>

        <select class="filter-select" id="filterStatusUser">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>

        <a href="<?= base_url('management-user/create'); ?>" class="btn btn-primary">
            <span class="material-icons">person_add</span>Tambah User
        </a>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="data-table" id="tableUser">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Hak Akses</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $row): ?>
                            <tr
                                data-search="<?= strtolower(html_escape($row->nama . ' ' . $row->username)); ?>"
                                data-role="<?= html_escape($row->hak_akses); ?>"
                                data-status="<?= (int) $row->is_active; ?>"
                            >
                                <td><?= $no++; ?></td>
                                <td>
                                    <strong><?= html_escape($row->nama); ?></strong>
                                    <?php if ((int) $row->id === (int) $this->session->userdata('user_id')): ?>
                                        <span class="badge badge-blue" style="margin-left:6px;">Anda</span>
                                    <?php endif; ?>
                                </td>
                                <td><code class="nomor-agenda-code"><?= html_escape($row->username); ?></code></td>
                                <td>
                                    <?php if ($row->hak_akses === 'admin'): ?>
                                        <span class="badge badge-purple">Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-blue">Caraka</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ((int) $row->is_active === 1): ?>
                                        <span class="badge badge-green">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape(date('d/m/Y H:i', strtotime($row->created_at))); ?></td>
                                <td>
                                    <?= $row->updated_at ? html_escape(date('d/m/Y H:i', strtotime($row->updated_at))) : '-'; ?>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= base_url('management-user/edit/' . $row->id); ?>"
                                           class="btn btn-outline btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <?php if ((int) $row->id !== (int) $this->session->userdata('user_id')): ?>
                                            <form method="post"
                                                  action="<?= base_url('management-user/delete/' . $row->id); ?>"
                                                  class="formDeleteUser"
                                                  style="display:inline-block;">
                                                <input type="hidden"
                                                       name="<?= $this->security->get_csrf_token_name(); ?>"
                                                       value="<?= $this->security->get_csrf_hash(); ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <span class="material-icons">delete</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="td-empty">
                                <span class="material-icons" style="font-size:36px;color:var(--text-muted);display:block;margin-bottom:8px;">group_off</span>
                                Belum ada data user.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:16px;padding:12px 16px;background:var(--sogan-faint,#f3f0eb);border-radius:10px;border-left:4px solid var(--sogan,#5C3317);font-size:12px;color:#666;">
        <span class="material-icons" style="font-size:14px;vertical-align:middle;color:var(--sogan);">info</span>
        Hak akses <strong>Caraka</strong> hanya diarahkan ke Portal Caraka, sedangkan <strong>Admin</strong> dapat mengakses seluruh modul.
    </div>

</div>
