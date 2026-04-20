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


$route['surat-masuk']                = 'surat_masuk/index';
$route['surat-masuk/create']         = 'surat_masuk/create';
$route['surat-masuk/store']          = 'surat_masuk/store';
$route['surat-masuk/next-agenda']    = 'surat_masuk/get_next_nomor_agenda';
$route['surat-masuk/detail/(:num)']  = 'surat_masuk/detail/$1';
$route['surat-masuk/edit/(:num)']    = 'surat_masuk/edit/$1';
$route['surat-masuk/update/(:num)']  = 'surat_masuk/update/$1';
$route['surat-masuk/delete/(:num)']  = 'surat_masuk/delete/$1';