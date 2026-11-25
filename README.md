# LouMart

LouMart adalah aplikasi sistem pemesanan dan manajemen penjualan. Proyek ini menggunakan PHP native dengan pola MVC, MariaDB, dan Tailwind CSS melalui CDN.

## Persiapan

- PHP 8.3.3
- MariaDB lokal
- Browser modern dan koneksi internet untuk memuat Tailwind CSS dari CDN

Setiap anggota tim menggunakan database lokal masing-masing. Samakan struktur tabel dan data awal dengan berkas setup database proyek. Simpan kredensial database pada konfigurasi lokal dan jangan unggah ke GitHub.

> **Status:** dokumentasi sudah menetapkan MariaDB dan Tailwind CDN sebagai teknologi target. Task implementasi koneksi database, berkas setup database, dan perpindahan tampilan ke Tailwind masih perlu dikerjakan.

## Menjalankan proyek

Setelah task persiapan database selesai dan database lokal dikonfigurasi:

```bash
php -S localhost:8000 -t public
```

Buka `http://localhost:8000`. Koneksi internet diperlukan agar Tailwind CSS dari CDN dapat dimuat.

## Menjalankan pengujian

```bash
php tests/run.php
```

## Teknologi

- PHP native 8.3.3, pola MVC + Service
- MariaDB lokal per anggota
- Tailwind CSS melalui CDN
- JavaScript murni dan `fetch` untuk komunikasi AJAX
- JSON sebagai format pertukaran data API

Laporan Word dengan kerangka FSD akan dibahas kemudian.
