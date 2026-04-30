# DINAMIT - Sistem Disposisi dan Penomoran Surat

DINAMIT adalah aplikasi pengelolaan persuratan berbasis PHP (Framework CodeIgniter) untuk lingkungan Sekretariat Daerah Kota Pekalongan. Aplikasi ini mencakup penomoran naskah dinas, pencatatan surat masuk, disposisi, pelacakan pengiriman oleh kurir, agenda acara, dashboard statistik, dan manajemen pengguna.

## Fitur Utama

- Autentikasi admin dan kurir dengan pembatasan akses berbasis peran.
- Dashboard dinamis yang membaca data dari database: nomor surat terbit, surat masuk, disposisi selesai, agenda tertunda, grafik mingguan, aktivitas terkini, dan agenda terbaru.
- Penomoran surat untuk beberapa jenis naskah:
  - SETDA - Surat Keluar
  - SETDA - SPPD
  - UMUM - Surat Keluar
  - UMUM - SPPD
  - Nota Dinas
- Kuota nomor surat harian per jenis surat. Default 100 nomor per hari, dengan dukungan kuota khusus seperti Nota Dinas 20 nomor per hari.
- Format nomor surat otomatis, termasuk format tanpa kode keamanan: `Kode_klasifikasi/No_surat/Tahun/`.
- Detail penomoran dengan tombol salin nomor dan kirim WhatsApp ke nomor pengolah.
- Export data penomoran surat ke Excel.
- Agenda Surat Masuk dan Surat Masuk V2 dengan lembar disposisi.
- Cetak lembar disposisi PDF.
- Modul disposisi surat dan tracking status kirim/terima.
- Modul kurir untuk input bukti foto dan tanda tangan.
- Modul acara untuk surat masuk berkategori undangan.
- Manajemen user admin dan kurir.

## Teknologi

- PHP 7.4
- CodeIgniter 3
- MySQL/MariaDB
- Composer
- PhpSpreadsheet
- FPDF
- Material Icons
- Roboto Font

## Struktur Penting

```text
application/
  config/
    config.php
    database.php
    routes.php
  controllers/
    Auth.php
    Dashboard.php
    Penomoran_surat.php
    Surat_masuk.php
    Surat_masuk_v2.php
    Disposisi_surat.php
    Kurir.php
    Acara.php
    User_management.php
  core/
    MY_Controller.php
  models/
    Dashboard_model.php
    Penomoran_surat_model.php
    Surat_masuk_model.php
    Surat_masuk_v2_model.php
    Disposisi_surat_model.php
    User_model.php
  views/
    auth/
    dashboard/
    penomoran_surat/
    surat_masuk/
    surat_masuk_v2/
    disposisi_surat/
    kurir/
assets/
  css/
  js/
db/
  new.sql
uploads/
vendor/
```

## Instalasi Lokal

1. Letakkan project di web root, contoh:

```bash
C:\xampp\htdocs\disposisi-surat
```

2. Install dependency Composer:

```bash
composer install
```

3. Buat database MySQL:

```sql
CREATE DATABASE aplikasi_disposisi_surat;
```

4. Import database dari:

```text
db/new.sql
```

5. Sesuaikan konfigurasi database di:

```text
application/config/database.php
```

Default lokal:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'aplikasi_disposisi_surat',
'dbdriver' => 'mysqli',
```

6. Sesuaikan base URL di:

```text
application/config/config.php
```

Contoh:

```php
$config['base_url'] = 'http://localhost:8080/disposisi-surat/';
```

7. Pastikan rewrite aktif. File `.htaccess` sudah tersedia agar URL tanpa `index.php` bisa digunakan.

## Akun Awal

Data awal tersedia di `db/new.sql`.

| Role       | Username | Password   |
| ---------- | -------- | ---------- |
| Admin      | `admin`  | `password` |
| Kurir/User | `user`   | `password` |

Jika password sudah diubah pada database produksi, gunakan kredensial terbaru dari administrator.

## Modul dan URL

| Modul            | URL                               |
| ---------------- | --------------------------------- |
| Login            | `/login`                          |
| Dashboard        | `/dashboard`                      |
| Penomoran Surat  | `/penomoran-surat`                |
| Tambah Penomoran | `/penomoran-surat/create/{jenis}` |
| Surat Masuk V2   | `/surat-masuk-v2`                 |
| Surat Masuk lama | `/surat-masuk`                    |
| Disposisi Surat  | `/disposisi-surat`                |
| Kurir            | `/kurir`                          |
| Acara            | `/acara`                          |
| Manajemen User   | `/management-user`                |
| Export Penomoran | `/penomoran-surat/export`         |

## Jenis Penomoran Surat

Slug jenis surat yang digunakan:

```text
setda-surat-keluar
setda-sppd
umum-surat-keluar
umum-sppd
nota-dinas
```

Kuota harian dikonfigurasi di `application/controllers/Penomoran_surat.php` pada method `get_jenis_surat_options()`.

Contoh:

```php
'nota-dinas' => [
    'label' => 'NOTA DINAS',
    'show_kode_umum' => FALSE,
    'daily_quota' => 20
],
```

## Catatan Pengembangan

- `MY_Controller` menangani pengecekan login dan pembatasan akses user/kurir.
- Dashboard memakai `Dashboard_model` agar statistik login dan dashboard berasal dari data aktual.
- Nomor urut surat dihitung server-side agar tidak bergantung pada input browser.
- Field `no_wa_pengolah` disimpan tanpa awalan `0` atau `+62`; tombol WhatsApp akan menambahkan kode negara otomatis.
- Untuk hosting Linux, pastikan nama file dan class CodeIgniter mengikuti kapitalisasi yang tepat, misalnya `application/core/MY_Controller.php`.

## Deployment Singkat

1. Upload seluruh project ke hosting.
2. Jalankan `composer install` jika folder `vendor` belum ikut diupload.
3. Import database.
4. Ubah `base_url`, konfigurasi database, dan environment sesuai hosting.
5. Pastikan folder `uploads/` dapat ditulis oleh web server.
6. Jika perubahan PHP tidak langsung terbaca, clear OPcache dari panel hosting.

## Lisensi

Project ini digunakan untuk kebutuhan internal aplikasi persuratan. Sesuaikan lisensi dan informasi kepemilikan dengan kebijakan instansi atau pengembang.
