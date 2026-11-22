# Tech Stack LouMart

## Ketentuan tugas (acuan utama)

| Ketentuan | Dipenuhi dengan |
|---|---|
| HTML untuk struktur halaman | View PHP berisi HTML5 (`app/Views/`) |
| CSS dan Bootstrap untuk tampilan | Bootstrap 5.3.3 + CSS kustom di file eksternal `public/assets/css/style.css` |
| JavaScript untuk interaksi pengguna | JavaScript murni (ES6), satu file per halaman di `public/assets/js/` |
| AJAX untuk komunikasi data asynchronous | `fetch` dari JavaScript ke endpoint `/api/...` tanpa memuat ulang halaman |
| JSON sebagai format pertukaran data | Semua request dan response API berformat JSON |

## Teknologi yang dipakai

| Bagian | Pilihan | Catatan |
|---|---|---|
| Bahasa backend | PHP native **8.3.3** | Boleh memakai fitur PHP 8 (`match`, `str_contains`, constructor promotion, named arguments, `readonly`), lihat `rules.md` bagian 10 |
| Framework / Composer | Tidak dipakai | Autoload ditulis sendiri dengan `spl_autoload_register` |
| Pola | MVC + Service | Lihat `architecture.md` |
| Penyimpanan | File JSON (`data/*.json`) | Tanpa database SQL |
| Struktur halaman | HTML5 di View PHP | |
| Tampilan | **Bootstrap 5.3.3** (CDN, versi dikunci) + **CSS kustom eksternal** | CSS kustom hanya di `public/assets/css/style.css`; tanpa `style=` inline dan tanpa tag `<style>`, lihat `rules.md` bagian 11 |
| Interaksi | JavaScript murni (ES6) + `fetch` (AJAX) | Tanpa jQuery, tanpa framework JS, tanpa build tool |
| Penyimpanan keranjang | `localStorage` | Kunci: `loumart_keranjang` |
| Pengujian | Skrip PHP sederhana `tests/run.php` + uji manual | Tanpa PHPUnit |
| Editor / VCS | VS Code, GitHub Copilot, Git | Satu repo bersama |

Bootstrap dimuat dari CDN: CSS di `<head>` dan `bootstrap.bundle.min.js` di akhir `<body>`
(dibutuhkan untuk komponen seperti modal). Jika demo harus bisa tanpa internet, unduh kedua file
ke `public/assets/vendor/bootstrap/` dan ganti tautan di `app/Views/layouts/main.php`.

## Menjalankan proyek

Cara 1, server bawaan PHP (paling mudah):

```bash
php -S localhost:8000 -t public
```

Lalu buka `http://localhost:8000`. Server bawaan PHP otomatis meneruskan path yang tidak ada filenya ke `public/index.php`.

Cara 2, XAMPP/Apache: arahkan document root ke folder `public/` dan pastikan `mod_rewrite` aktif (file `public/.htaccess` sudah menangani rewrite). Pastikan XAMPP yang dipakai membawa PHP 8.3.

Memeriksa versi PHP:

```bash
php -v
```

Menjalankan uji:

```bash
php tests/run.php
```

## Aturan memilih teknologi

- Dilarang menambah library, framework, atau ekstensi baru tanpa persetujuan tim (catat di bawah jika disetujui).
- Ekstensi PHP yang dibutuhkan hanya yang bawaan: `json`, `mbstring` (opsional).
- Semua hal harus bisa dijalankan di Chrome, Edge, dan Firefox terbaru tanpa instalasi rumit (NFR-05).

## Daftar persetujuan tambahan

| Tanggal | Teknologi | Alasan | Disetujui oleh |
|---|---|---|---|
| (kosong) | | | |
