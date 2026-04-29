<?php
$is_edit = isset($mode) && $mode === 'edit';
$action_url = $is_edit ? base_url('management-user/update/' . $row->id) : base_url('management-user/store');
$self_locked = $is_edit && (int) $row->id === (int) $this->session->userdata('user_id');

function um_old($ci, $row, $field, $default = '')
{
    $old = $ci->input->post($field);
    if ($old !== null) return $old;
    if ($row && isset($row->$field)) return $row->$field;
    return $default;
}
?>

<div class="page active penomoran-page">

    <?php if (validation_errors()): ?>
        <div class="module-alert error">
            <span class="material-icons">error_outline</span>
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('crud_error')): ?>
        <div class="module-alert error">
            <span class="material-icons">error_outline</span>
            <?= $this->session->flashdata('crud_error'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($form_error)): ?>
        <div class="module-alert error">
            <span class="material-icons">error_outline</span>
            <?= html_escape($form_error); ?>
        </div>
    <?php endif; ?>

    <div class="form-meta-box">
        <div class="meta-left">
            <h3><?= $is_edit ? 'Edit Data User' : 'Tambah Data User'; ?></h3>
            <p><?= $is_edit ? 'Perbarui identitas, hak akses, status, atau password akun.' : 'Buat akun admin atau caraka untuk Portal Caraka.'; ?></p>
        </div>
        <div class="form-type-badge" style="background:#E3F2FD;color:#1565C0;border-color:#90CAF9;">
            <span class="material-icons"><?= $is_edit ? 'manage_accounts' : 'person_add'; ?></span>
            <?= $is_edit ? 'Edit User' : 'User Baru'; ?>
        </div>
    </div>

    <form method="post" action="<?= $action_url; ?>" novalidate>
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="form-section form-section-soft">
            <div class="form-section-title">
                <span class="material-icons">account_circle</span>
                Identitas User
            </div>

            <div class="form-grid cols-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        maxlength="100"
                        placeholder="Nama pengguna..."
                        value="<?= html_escape(um_old($this, $row, 'nama')); ?>"
                    >
                    <div class="field-note">Nama yang tampil pada profil dan footer sidebar.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Username <span class="req">*</span></label>
                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        maxlength="50"
                        placeholder="contoh: caraka01"
                        value="<?= html_escape(um_old($this, $row, 'username')); ?>"
                    >
                    <div class="field-note">Gunakan huruf, angka, underscore, atau dash.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Password <?= $is_edit ? '' : '<span class="req">*</span>'; ?>
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        maxlength="100"
                        placeholder="<?= $is_edit ? 'Kosongkan jika tidak diganti' : 'Minimal 6 karakter'; ?>"
                        autocomplete="new-password"
                    >
                    <div class="field-note"><?= $is_edit ? 'Isi hanya jika ingin mengganti password.' : 'Password akan disimpan dalam bentuk hash.'; ?></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hak Akses <span class="req">*</span></label>
                    <?php if ($self_locked): ?>
                        <input type="hidden" name="hak_akses" value="admin">
                    <?php endif; ?>
                    <select name="hak_akses" class="form-control" <?= $self_locked ? 'disabled' : ''; ?>>
                        <?php $hak_akses = um_old($this, $row, 'hak_akses', 'user'); ?>
                        <option value="admin" <?= $hak_akses === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?= $hak_akses === 'user' ? 'selected' : ''; ?>>Caraka</option>
                    </select>
                    <div class="field-note">Caraka hanya dapat mengakses Portal Caraka.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="req">*</span></label>
                    <?php if ($self_locked): ?>
                        <input type="hidden" name="is_active" value="1">
                    <?php endif; ?>
                    <select name="is_active" class="form-control" <?= $self_locked ? 'disabled' : ''; ?>>
                        <?php $is_active = (string) um_old($this, $row, 'is_active', '1'); ?>
                        <option value="1" <?= $is_active === '1' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?= $is_active === '0' ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                    <div class="field-note">User nonaktif tidak dapat login.</div>
                </div>
            </div>
        </div>

        <?php if ($self_locked): ?>
            <div class="module-alert success">
                <span class="material-icons">verified_user</span>
                Akun yang sedang login tetap harus aktif dan memiliki hak akses admin.
            </div>
        <?php endif; ?>

        <div class="form-actions-wrap">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">save</span><?= $is_edit ? 'Update User' : 'Simpan User'; ?>
                </button>
                <a href="<?= base_url('management-user'); ?>" class="btn btn-outline">
                    <span class="material-icons">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </form>
</div>
