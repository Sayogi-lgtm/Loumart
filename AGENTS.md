# AGENTS.md: Panduan untuk GitHub Copilot (LouMart)

Kamu membantu membangun **LouMart**, aplikasi web sistem pemesanan dan manajemen penjualan
(PHP native, pola MVC, data disimpan di MariaDB). Ini tugas kuliah dikerjakan 6 orang, jadi kode harus sederhana dan
mudah dibaca pemula. Balas dan beri komentar kode dalam Bahasa Indonesia.

## Baca dulu, sesuai urutan

1. `docs/prd.md`: apa yang dibuat dan aturan bisnisnya
2. `docs/architecture.md`: struktur folder, alur, kontrak API, format data, rumus
3. `docs/techstack.md`: teknologi yang boleh dipakai dan cara menjalankan
4. `docs/rules.md`: aturan penulisan kode (wajib dipatuhi)
5. `docs/design.md`: hanya jika task menyentuh tampilan
6. `docs/task.md`: temukan task yang diminta

Jika dua dokumen bertentangan: untuk hal teknis, `architecture.md` menang; untuk aturan bisnis,
`prd.md` menang. Jika masih ragu, **bertanya, jangan menebak**.

## Cara kerja

- Kerjakan **satu task per sesi** (ID `T-XX`, `A-XX`, atau `U-XX`). Jangan mengerjakan task lain.
- Untuk task fase A/U, ikuti aturan khusus di `docs/task.md`: tulis hasil pada file baru yang tercantum di task dan ajukan perubahan dokumen acuan untuk persetujuan PM sebelum menerapkannya.
- Sebelum menulis kode, tulis rencana singkat: file apa yang dibuat/diubah dan alasannya. Tunggu persetujuan jika lebih dari 3 file.
- Sentuh hanya file yang tercantum pada task. Jika perlu file lain, jelaskan alasannya dulu.
- Setelah kriteria task terpenuhi, jalankan cara verifikasi pada task (mis. `php tests/run.php`), lalu ringkas perubahan dan centang task di `docs/task.md`. Mencentang task A/U bukan persetujuan untuk menerapkan perubahan pada dokumen acuan.
- Jelaskan keputusan teknis dengan bahasa sederhana; penggunanya masih belajar backend.

## Batasan keras

- Dilarang menambah framework, Composer, atau library PHP/JS baru tanpa persetujuan. Database MariaDB dan Tailwind CSS melalui CDN sudah disetujui untuk proyek.
- Target **PHP 8.3.3**. Setiap file PHP diawali `declare(strict_types=1);` dan memakai tipe pada parameter, return, dan properti (lihat `docs/rules.md` bagian 10).
- Tampilan: HTML + **Tailwind CSS melalui CDN**; gaya kustom tambahan ditulis di file eksternal `public/assets/css/style.css`. Dilarang atribut `style=` inline dan tag `<style>` di View (lihat `docs/rules.md` bagian 11).
- Komunikasi data memakai AJAX (`fetch`) dengan format JSON, sesuai ketentuan tugas.
- JSON digunakan sebagai format komunikasi API, bukan penyimpanan data aplikasi. Perubahan kontrak API atau rumus perhitungan tetap memerlukan persetujuan.
- Uang selalu bilangan bulat (`int`), tidak pernah `float`.
- Rumus dan validasi hanya di `app/Services/`. Akses MariaDB dilakukan melalui Model dengan cara aman sesuai `docs/rules.md`.
- Setiap anggota memakai MariaDB lokal. Struktur tabel dan data awal harus disamakan; jangan mengunggah kredensial database ke GitHub.
- Jangan menaruh apa pun selain aset publik di `public/`.

## Area kerja per peran

| Peran | Folder |
|---|---|
| Frontend 1 | `app/Views/`, `public/assets/css/` |
| Frontend 2 | `public/assets/js/` |
| Backend/Logic | `app/Core/`, `app/Controllers/`, `app/Models/`, `app/Services/`, `config/`, `data/`, `tests/` |
| UI/UX | `docs/design.md`, `public/assets/img/` |
| PM / System Analyst | `docs/`, `README.md` |

Task di `docs/task.md` sudah mencantumkan perannya. Jangan keluar dari area peran tanpa alasan.

## Perintah penting

```bash
php -S localhost:8000 -t public     # menjalankan aplikasi
php tests/run.php                   # menjalankan uji
```

Tailwind CSS dimuat melalui CDN sehingga koneksi internet diperlukan saat membuka aplikasi.

## Format jawaban saat menyelesaikan task

1. Daftar file yang dibuat/diubah.
2. Cara memeriksa hasilnya.
3. Hal yang belum pasti atau perlu keputusan tim (jika ada).
