# Tech Stack LouMart

## Ketentuan tugas

| Ketentuan | Dipenuhi dengan |
|---|---|
| HTML untuk struktur halaman | View PHP berisi HTML5 (`app/Views/`) |
| CSS untuk tampilan | Tailwind CSS melalui CDN; CSS tambahan eksternal di `public/assets/css/style.css` |
| JavaScript untuk interaksi | JavaScript murni (ES6), satu file per halaman di `public/assets/js/` |
| AJAX untuk komunikasi data | `fetch` dari JavaScript ke endpoint `/api/...` |
| JSON sebagai format pertukaran | Request dan response API berformat JSON |
| Penyimpanan aplikasi | MariaDB lokal pada komputer setiap anggota |

## Teknologi yang dipakai

| Bagian | Pilihan | Catatan |
|---|---|---|
| Backend | PHP native **8.3.3** | Tanpa framework |
| Pola | MVC + Service | Lihat `architecture.md` |
| Database | MariaDB | Setiap anggota memakai database lokal dengan struktur dan data awal yang sama |
| Akses database | PDO bawaan PHP | Gunakan query berparameter; kredensial disimpan pada konfigurasi lokal yang tidak diunggah ke GitHub |
| Struktur halaman | HTML5 di View PHP | |
| Tampilan | Tailwind CSS melalui CDN | Tidak memakai Bootstrap; koneksi internet diperlukan saat membuka aplikasi |
| Interaksi | JavaScript murni (ES6) + `fetch` | Tanpa jQuery atau framework JavaScript |
| Keranjang | `localStorage` | Kunci: `loumart_keranjang` |
| Pengujian | Skrip PHP sederhana `tests/run.php` + uji manual | Tanpa PHPUnit |
| Editor / VCS | VS Code, GitHub Copilot, Git | Satu repository bersama |

## Menjalankan proyek

Pastikan PHP 8.3.3 dan MariaDB tersedia. Buat database lokal dan siapkan tabel serta data awal mengikuti berkas SQL dan konfigurasi contoh yang disediakan proyek setelah task persiapannya selesai. Jangan mengunggah konfigurasi berisi kata sandi ke GitHub.

Jalankan server bawaan PHP:

```bash
php -S localhost:8000 -t public
```

Buka `http://localhost:8000`. Tailwind CSS dimuat dari CDN, jadi tampilan memerlukan koneksi internet.

Untuk XAMPP/Apache, arahkan document root ke folder `public/` dan pastikan `mod_rewrite` aktif.

Memeriksa versi PHP:

```bash
php -v
```

Menjalankan uji:

```bash
php tests/run.php
```

## Aturan memilih teknologi

- MariaDB dan Tailwind CSS melalui CDN adalah pilihan proyek yang telah disetujui.
- Jangan menambah framework, Composer, atau library PHP/JavaScript baru tanpa persetujuan tim.
- Pastikan ekstensi PHP untuk PDO MariaDB tersedia pada lingkungan anggota.
- Aplikasi ditujukan berjalan di Chrome, Edge, dan Firefox terbaru.
