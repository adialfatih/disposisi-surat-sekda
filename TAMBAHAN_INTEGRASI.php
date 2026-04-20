// ================================================================
// TAMBAHAN 1: Route — tambahkan ke config/routes.php
// ================================================================

$route['surat-masuk']                = 'surat_masuk/index';
$route['surat-masuk/create']         = 'surat_masuk/create';
$route['surat-masuk/store']          = 'surat_masuk/store';
$route['surat-masuk/next-agenda']    = 'surat_masuk/get_next_nomor_agenda'; // <-- tambah ini
$route['surat-masuk/detail/(:num)']  = 'surat_masuk/detail/$1';
$route['surat-masuk/edit/(:num)']    = 'surat_masuk/edit/$1';
$route['surat-masuk/update/(:num)']  = 'surat_masuk/update/$1';
$route['surat-masuk/delete/(:num)']  = 'surat_masuk/delete/$1';


// ================================================================
// TAMBAHAN 2: Method di Controller/Surat_masuk.php
//             Tambahkan sebelum method index()
// ================================================================

public function get_next_nomor_agenda()
{
    if ($this->input->method(TRUE) !== 'POST') {
        show_404();
    }

    $tahun = (int) $this->input->post('tahun', TRUE);

    if ($tahun < 2000) {
        echo json_encode(['success' => FALSE, 'next_nomor' => 'SM-' . date('Y') . '-001']);
        return;
    }

    $next = $this->Surat_masuk_model->get_next_nomor_ajax($tahun);
    echo json_encode(['success' => TRUE, 'next_nomor' => $next]);
}


// ================================================================
// TAMBAHAN 3: views/template/header.php
//             Tambahkan kondisi load CSS surat-masuk
//             Letakkan setelah blok penomoran_surat yang sudah ada
// ================================================================

<?php if (!empty($active_menu) && $active_menu === 'agenda_surat_masuk'): ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/surat-masuk.css'); ?>">
<?php endif; ?>


// ================================================================
// TAMBAHAN 4: views/template/sidebar.php
//             Update link Agenda Surat Masuk (URL sudah benar ke surat-masuk)
// ================================================================

<a href="<?= base_url('surat-masuk'); ?>" class="nav-item <?= ($active_menu == 'agenda_surat_masuk') ? 'active' : ''; ?>">
    <span class="material-icons">mail</span>
    Agenda Surat Masuk
</a>

// Surat Masuk
$route['surat-masuk']                = 'surat_masuk/index';
$route['surat-masuk/create']         = 'surat_masuk/create';
$route['surat-masuk/store']          = 'surat_masuk/store';
$route['surat-masuk/detail/(:num)']  = 'surat_masuk/detail/$1';
$route['surat-masuk/edit/(:num)']    = 'surat_masuk/edit/$1';
$route['surat-masuk/update/(:num)']  = 'surat_masuk/update/$1';
$route['surat-masuk/delete/(:num)']  = 'surat_masuk/delete/$1';

// Disposisi (child dari surat masuk)
$route['surat-masuk/(:num)/disposisi']               = 'disposisi_surat/index/$1';
$route['surat-masuk/(:num)/disposisi/create']        = 'disposisi_surat/create/$1';
$route['surat-masuk/(:num)/disposisi/store']         = 'disposisi_surat/store/$1';
$route['surat-masuk/(:num)/disposisi/edit/(:num)']   = 'disposisi_surat/edit/$1/$2';
$route['surat-masuk/(:num)/disposisi/update/(:num)'] = 'disposisi_surat/update/$1/$2';
$route['surat-masuk/(:num)/disposisi/delete/(:num)'] = 'disposisi_surat/delete/$1/$2';

// Agenda Kegiatan
$route['agenda-kegiatan']                = 'agenda_kegiatan/index';
$route['agenda-kegiatan/create']         = 'agenda_kegiatan/create';
$route['agenda-kegiatan/store']          = 'agenda_kegiatan/store';
$route['agenda-kegiatan/detail/(:num)']  = 'agenda_kegiatan/detail/$1';
$route['agenda-kegiatan/edit/(:num)']    = 'agenda_kegiatan/edit/$1';
$route['agenda-kegiatan/update/(:num)']  = 'agenda_kegiatan/update/$1';
$route['agenda-kegiatan/delete/(:num)']  = 'agenda_kegiatan/delete/$1';