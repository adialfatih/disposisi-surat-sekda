<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper: upload_url()
 * Konversi path DB ke URL yang bisa diakses browser.
 *
 * Solusi untuk CI3 yang ada di subfolder (misal /disposisi-surat/)
 * sementara folder uploads/ ada di document root.
 *
 * Letakkan di: application/helpers/upload_helper.php
 * Load di autoload.php: $autoload['helper'] = ['upload'];
 * ATAU load manual di controller: $this->load->helper('upload');
 */
if (!function_exists('upload_url'))
{
    function upload_url($db_path)
    {
        if (empty($db_path)) return '';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'];

        // Selalu arahkan ke document root, bukan ke subfolder CI
        return $scheme . '://' . $host . '/' . ltrim($db_path, '/');
    }
}