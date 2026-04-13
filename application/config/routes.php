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