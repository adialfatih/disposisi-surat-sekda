<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'dashboard';
$route['dashboard'] = 'dashboard/index';
$route['dashboard/penomoran-surat'] = 'dashboard/penomoran_surat';
$route['dashboard/agenda-disposisi'] = 'dashboard/agenda_disposisi';
$route['dashboard/agenda-surat-masuk'] = 'dashboard/agenda_surat_masuk';
//$route['login'] = 'dashboard/login';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['penomoran-surat'] = 'penomoran_surat/index';
$route['penomoran-surat/create/(:any)'] = 'penomoran_surat/create/$1';
$route['penomoran-surat/store'] = 'penomoran_surat/store';
$route['penomoran-surat/detail/(:num)'] = 'penomoran_surat/detail/$1';
$route['penomoran-surat/edit/(:num)'] = 'penomoran_surat/edit/$1';
$route['penomoran-surat/update/(:num)'] = 'penomoran_surat/update/$1';
$route['penomoran-surat/delete/(:num)'] = 'penomoran_surat/delete/$1';
$route['penomoran-surat/next-nomor'] = 'penomoran_surat/get_next_nomor_urut';
$route['penomoran-surat/csrf-token'] = 'penomoran_surat/get_csrf_token';


$route['surat-masuk']                = 'surat_masuk/index';
$route['surat-masuk/create']         = 'surat_masuk/create';
$route['surat-masuk/store']          = 'surat_masuk/store';
$route['surat-masuk/next-agenda']    = 'surat_masuk/get_next_nomor_agenda';
$route['surat-masuk/detail/(:num)']  = 'surat_masuk/detail/$1';
$route['surat-masuk/edit/(:num)']    = 'surat_masuk/edit/$1';
$route['surat-masuk/update/(:num)']  = 'surat_masuk/update/$1';
$route['surat-masuk/delete/(:num)']  = 'surat_masuk/delete/$1';

$route['csrf-token'] = 'surat_masuk/get_csrf_token';

$route['disposisi-surat']                                         = 'disposisi_surat/index';
$route['disposisi-surat/create']                                  = 'disposisi_surat/create';
$route['disposisi-surat/store']                                   = 'disposisi_surat/store';
$route['disposisi-surat/next-nomor']                              = 'disposisi_surat/get_next_nomor';
$route['disposisi-surat/detail/(:num)']                           = 'disposisi_surat/detail/$1';
$route['disposisi-surat/edit/(:num)']                             = 'disposisi_surat/edit/$1';
$route['disposisi-surat/update/(:num)']                           = 'disposisi_surat/update/$1';
$route['disposisi-surat/delete/(:num)']                           = 'disposisi_surat/delete/$1';
$route['disposisi-surat/tracking/(:num)/(:num)']                  = 'disposisi_surat/tracking/$1/$2';
$route['disposisi-surat/kirim/(:num)/(:num)']                     = 'disposisi_surat/store_kirim/$1/$2';
$route['disposisi-surat/terima/(:num)/(:num)']                    = 'disposisi_surat/store_terima/$1/$2';

$route['kurir']              = 'kurir/index';
$route['kurir/terima/(:num)']= 'kurir/terima/$1';
$route['kurir/step-kamera']  = 'kurir/step_kamera';
$route['kurir/step-ttd']     = 'kurir/step_ttd';
$route['kurir/simpan']       = 'kurir/simpan';
$route['kurir/csrf']         = 'kurir/csrf';

$route['penomoran-surat/export'] = 'export/export';

// --- Surat Masuk V2 (dengan Disposisi) ---
$route['surat-masuk-v2']                       = 'surat_masuk_v2/index';
$route['surat-masuk-v2/create']                = 'surat_masuk_v2/create';
$route['surat-masuk-v2/store']                 = 'surat_masuk_v2/store';
$route['surat-masuk-v2/next-agenda']           = 'surat_masuk_v2/get_next_nomor_agenda';
$route['surat-masuk-v2/detail/(:num)']         = 'surat_masuk_v2/detail/$1';
$route['surat-masuk-v2/edit/(:num)']           = 'surat_masuk_v2/edit/$1';
$route['surat-masuk-v2/update/(:num)']         = 'surat_masuk_v2/update/$1';
$route['surat-masuk-v2/delete/(:num)']         = 'surat_masuk_v2/delete/$1';
$route['surat-masuk-v2/cetak/(:num)']          = 'surat_masuk_v2/cetak/$1';
$route['surat-masuk-v2/disposisi/(:num)']      = 'surat_masuk_v2/disposisi/$1';
$route['surat-masuk-v2/disposisi-store/(:num)']= 'surat_masuk_v2/disposisi_store/$1';
$route['surat-masuk-v2/csrf-token']            = 'surat_masuk_v2/get_csrf_token';