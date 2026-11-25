# Aturan Kode LouMart

Aturan ini wajib diikuti manusia maupun Copilot. Jika harus melanggar, minta persetujuan tim dulu.

## 1. Umum

- Kode ditulis sederhana dan mudah dibaca pemula (NFR-06). Pilih cara yang jelas daripada yang singkat.
- Komentar dan pesan ke pengguna memakai **Bahasa Indonesia**. Nama kelas, fungsi, dan variabel memakai **Bahasa Inggris atau Indonesia yang konsisten** seperti di `architecture.md` (contoh: `HitungService`, `produkId`).
- Satu file, satu tanggung jawab. Fungsi pendek (idealnya di bawah 30 baris) dan diberi komentar singkat tentang tujuannya.
- Jangan menambah fitur di luar task yang dikerjakan.

## 2. Penamaan

| Hal | Gaya | Contoh |
|---|---|---|
| Kelas PHP, nama file kelas | PascalCase | `PesananService.php` |
| Method dan variabel PHP | camelCase | `hitungTotal()`, `$setelahDiskon` |
| Field JSON dan API | camelCase | `hargaSatuan`, `produkId` |
| Konstanta/kunci config | snake_case (config) / UPPER_CASE (konstanta) | `pajak_persen` |
| File View | huruf kecil, tanda hubung | `status-pesanan.php` |
| File JS/CSS | huruf kecil, tanda hubung | `admin-produk.js` |
| ID pesanan | `ORD-` + nomor 3 digit | `ORD-001` |

## 3. Struktur MVC

- Controller **tipis**: baca request, panggil Service/Model, kirim Response.
- Rumus dan aturan validasi **hanya** di `app/Services/`. Dilarang menduplikasinya di Controller, View, atau JS.
- Akses MariaDB hanya melalui Model dengan PDO dan query berparameter. Jangan membuat query dengan menempelkan input pengguna langsung ke SQL.
- View hanya menampilkan data; tidak ada query atau rumus.
- Hanya folder `public/` yang boleh diakses browser. `app/`, `config/`, `data/` tidak boleh.

## 4. Uang dan angka

- Semua uang adalah **bilangan bulat rupiah** (`int`). Dilarang memakai `float` untuk uang.
- Rumus persis seperti `architecture.md` bagian 9.2 (pembulatan setengah ke atas dengan `intdiv`).
- Nilai seperti 11% dan ambang diskon dibaca dari `config/app.php`, bukan ditulis langsung di kode.
- Format tampilan: `Rp599.400` (titik sebagai pemisah ribuan, tanpa desimal). Pemformatan hanya terjadi saat menampilkan, data tersimpan tetap angka.

## 5. API dan data

- Bentuk request/response, kode HTTP, dan format error mengikuti `architecture.md` bagian 7. Jangan diubah tanpa persetujuan.
- Server **tidak memercayai** data dari klien: harga, total, diskon, dan pajak selalu dihitung ulang di server.
- Setiap input divalidasi di server walau sudah divalidasi di JavaScript.
- JSON digunakan sebagai format request/response API, bukan sebagai penyimpanan utama aplikasi. Perubahan kontrak API tetap harus disetujui bersama.
- Operasi checkout dan pembatalan yang mengubah beberapa baris harus memakai transaksi database agar perubahan berhasil seluruhnya atau dibatalkan seluruhnya.
- Setiap anggota memakai database MariaDB lokal dengan skema dan data awal yang sama. Jangan mengunggah kata sandi atau kredensial lokal ke GitHub.

## 6. Keamanan dasar

- Semua teks dari pengguna yang ditampilkan di View dilewatkan `htmlspecialchars`.
- Di JavaScript, tampilkan data pengguna dengan `textContent`, bukan `innerHTML`.
- Tangani `json_decode` request API yang gagal (balas 400), jangan biarkan error PHP tampil ke pengguna.
- Jangan tampilkan detail error internal di response (balas 500 dengan pesan umum).
- Nama file gambar produk dibersihkan (huruf, angka, `-`, `_`, `.` saja) dan tidak boleh mengandung `/` atau `..`.
- Catatan: halaman dan API admin saat ini **tanpa login**. Itu keputusan sementara, bukan praktik yang aman untuk produksi.

## 7. JavaScript frontend

- Panggilan ke server hanya lewat fungsi di `public/assets/js/api.js`.
- Pemformatan rupiah hanya lewat `format.js`; akses keranjang hanya lewat `cart.js`.
- Tampilkan keadaan **loading**, **kosong**, dan **error** pada setiap halaman yang memuat data.
- Satu file JS per halaman; jangan membuat satu file raksasa.
- Komunikasi dengan server memakai AJAX (`fetch`) dan JSON, tanpa memuat ulang halaman.
- Nilai `qty` dan angka lain dari kolom input (yang bertipe teks di JavaScript) diubah dulu ke angka dengan `Number()` sebelum dikirim.

## 8. Git

- Satu task = satu branch = satu Pull Request kecil. Nama branch: `t-05-hitung-service`.
- Pesan commit: `T-05: tambah HitungService` (ID task di depan).
- Jangan commit langsung ke `main`.
- Jangan commit file rahasia atau file pribadi editor.
- Hanya sentuh file di area kerja perannya (lihat tabel di `architecture.md` bagian 4).

## 9. Definisi selesai (Definition of Done)

Sebuah task selesai jika:

1. Semua poin "Selesai bila" di `task.md` terpenuhi.
2. `php tests/run.php` tidak ada yang gagal (jika task menyentuh logika).
3. Sudah dicoba manual di browser (jika task menyentuh tampilan).
4. Tidak ada file di luar daftar task yang berubah.
5. Kotak task di `task.md` sudah dicentang.

## 10. PHP 8.3

- Semua file PHP diawali `<?php` lalu `declare(strict_types=1);` (termasuk `config/*.php`, kecuali file View yang berisi HTML).
- Beri tipe pada setiap parameter, nilai kembalian, dan properti kelas (`int`, `string`, `array`, `bool`, `?string`, `void`).
- Deklarasikan semua properti kelas. Dilarang membuat properti secara dinamis (sudah deprecated sejak PHP 8.2).
- Boleh dipakai: `match`, `str_contains`/`str_starts_with`, constructor promotion, named arguments, `readonly` untuk properti yang tidak berubah, operator `?->` dan `??`.
- Status pesanan memakai konstanta kelas di `Pesanan` (`Pesanan::MENUNGGU`, `Pesanan::LUNAS`, `Pesanan::DIBATALKAN`), bukan teks yang diketik berulang. Enum belum dipakai agar kode tetap sederhana.
- Baca JSON dengan `json_decode($teks, true, 512, JSON_THROW_ON_ERROR)` dan tangkap `JsonException`.
- Karena `strict_types` aktif, pastikan tipe data sesuai sebelum memanggil fungsi (mis. `intdiv()` hanya menerima `int`). Data dari klien diperiksa tipenya dulu di `ValidasiService`.
- Jangan menekan error dengan `@`. Jangan membiarkan `warning` atau `deprecated` muncul di hasil tes.

## 11. CSS dan Tailwind

- Struktur memakai HTML5 semantik (`header`, `nav`, `main`, `section`, `footer`, `table`, `form`).
- Tampilan utama memakai utility class **Tailwind CSS melalui CDN**. Jangan memakai Bootstrap.
- Muat CDN Tailwind pada layout utama. Koneksi internet diperlukan saat halaman dibuka.
- Dilarang atribut `style=` inline dan tag `<style>` di View. Variabel token dan aturan tambahan yang memang diperlukan ditulis di `public/assets/css/style.css`.
- Rancang untuk layar HP terlebih dahulu (mobile-first) dan gunakan utility responsif Tailwind sesuai kebutuhan desain.
- Nama kelas kustom buatan tim memakai awalan `lm-` agar mudah dibedakan dari utility Tailwind.
- Aset gambar ditaruh di `public/assets/img/`; semua `<img>` punya `alt`.
