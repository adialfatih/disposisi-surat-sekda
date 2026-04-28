# Panduan Instalasi – Surat Masuk V2 (dengan Disposisi)

Fitur ini sepenuhnya terpisah dari modul `surat_masuk` yang sudah ada.
Tidak ada perubahan pada file surat masuk lama.

---

## Struktur File yang Perlu Dikopi

```
surat_masuk_v2/
├── sql/
│   └── surat_masuk_v2.sql              → Jalankan di database
├── config/
│   └── routes_tambahan.php             → Salin ke routes.php
├── controllers/
│   └── Surat_masuk_v2.php              → application/controllers/
├── models/
│   └── Surat_masuk_v2_model.php        → application/models/
├── views/
│   └── surat_masuk_v2/
│       ├── index.php                   → application/views/surat_masuk_v2/
│       ├── form.php                    → application/views/surat_masuk_v2/
│       ├── detail.php                  → application/views/surat_masuk_v2/
│       └── disposisi_form.php          → application/views/surat_masuk_v2/
└── assets/
    └── js/
        └── surat-masuk-v2.js           → assets/js/
```

---

## Langkah Instalasi

### 1. Buat Tabel Database
Jalankan file `sql/surat_masuk_v2.sql` di phpMyAdmin atau MySQL CLI.

### 2. Daftarkan Route
Buka `application/config/routes.php`, tambahkan isi dari `config/routes_tambahan.php`
di bagian bawah (setelah route surat-masuk yang lama).

### 3. Salin Controller
Salin `controllers/Surat_masuk_v2.php` ke `application/controllers/`

### 4. Salin Model
Salin `models/Surat_masuk_v2_model.php` ke `application/models/`

### 5. Salin Views
Buat folder `application/views/surat_masuk_v2/`
Salin semua file dari `views/surat_masuk_v2/` ke sana.

### 6. Salin JavaScript
Salin `assets/js/surat-masuk-v2.js` ke `assets/js/` di project Anda.

### 7. Autoload FPDF (jika belum)
Pastikan `FPDF` sudah di-autoload di CI. Jika belum:
- Buka `application/config/autoload.php`
- Di bagian `libraries`, tambahkan `'fpdf'` (atau nama class sesuai instalasi Anda)
- Atau uncomment baris `require_once` di controller method `cetak()`.

### 8. Logo Instansi (opsional)
Untuk logo di PDF, letakkan file PNG logo Pekalongan di:
`assets/img/logo-pekalongan.png`
Jika file tidak ditemukan, logo akan dilewati otomatis.

### 9. Tambahkan Menu Sidebar
Di `application/views/template/sidebar.php`, tambahkan menu:
```php
<a href="<?= base_url('surat-masuk-v2'); ?>"
   class="sidebar-item <?= ($active_menu === 'surat_masuk_v2') ? 'active' : ''; ?>">
    <span class="material-icons">assignment</span>
    Surat Masuk & Disposisi
</a>
```

### 10. Badge CSS (jika belum ada)
Tambahkan di CSS utama jika `badge-purple` dan `badge-red` belum ada:
```css
.badge-purple { background:#EDE7F6; color:#6A1B9A; border:1px solid #CE93D8; }
.badge-red    { background:#FFEBEE; color:#B71C1C; border:1px solid #EF9A9A; }
```
(Jika tidak ditambahkan di CSS, JS akan menyuntikkannya otomatis saat halaman index dibuka.)

---

## Alur Proses

```
[Input Surat Masuk]
      ↓  (status: masuk)
[Klik Cetak → PDF Lembar Disposisi dicetak]
      ↓  (status: dicetak)
[Pimpinan mengisi lembar kertas secara manual]
      ↓
[Operator klik "Input Disposisi" → form isian disposisi]
      ↓  (status: didisposisi)
[Selesai – data tersimpan lengkap di sistem]
```

---

## Catatan Teknis

- Fitur cetak menggunakan **FPDF** (PHP, sudah autoload di CI Anda).
- PDF dibuat langsung via `Output('I', ...)` → terbuka di tab baru.
- Status surat otomatis berubah `masuk → dicetak` saat PDF pertama kali dicetak.
- Status surat berubah `dicetak → didisposisi` saat isian disposisi disimpan.
- Tombol "Input Disposisi" hanya muncul jika status `dicetak` atau `didisposisi`.
- Tabel `surat_masuk` lama **tidak diubah sama sekali**.