# AGENTS.md: Panduan untuk GitHub Copilot (LouMart)

Kamu membantu membangun **LouMart**, aplikasi web toko online sederhana (PHP native, pola MVC,
data disimpan di file JSON). Ini tugas kuliah dikerjakan 6 orang, jadi kode harus sederhana dan
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

- Kerjakan **satu task (T-XX) per sesi**. Jangan mengerjakan task lain.
- Sebelum menulis kode, tulis rencana singkat: file apa yang dibuat/diubah dan alasannya. Tunggu persetujuan jika lebih dari 3 file.
- Sentuh hanya file yang tercantum pada task. Jika perlu file lain, jelaskan alasannya dulu.
- Setelah selesai: jalankan cara verifikasi pada task (mis. `php tests/run.php`), lalu ringkas perubahan dan centang task di `docs/task.md`.
- Jelaskan keputusan teknis dengan bahasa sederhana; penggunanya masih belajar backend.

## Batasan keras

- Dilarang menambah framework, Composer, database SQL, library PHP/JS baru tanpa persetujuan.
- Target **PHP 8.3.3**. Setiap file PHP diawali `declare(strict_types=1);` dan memakai tipe pada parameter, return, dan properti (lihat `docs/rules.md` bagian 10).
- Tampilan: HTML + **Bootstrap 5.3.3** + CSS kustom di file eksternal `public/assets/css/style.css`. Dilarang atribut `style=` inline dan tag `<style>` di View (lihat `docs/rules.md` bagian 11).
- Komunikasi data memakai AJAX (`fetch`) dengan format JSON, sesuai ketentuan tugas.
- Dilarang mengubah format JSON di `data/`, kontrak API, atau rumus perhitungan tanpa persetujuan.
- Uang selalu bilangan bulat (`int`), tidak pernah `float`.
- Rumus dan validasi hanya di `app/Services/`. Akses JSON hanya lewat `JsonStore` dari Model.
- Jangan menimpa `data/produk.json` dan `data/pesanan.json` kecuali diminta. Tes memakai data sementara.
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

## Format jawaban saat menyelesaikan task

1. Daftar file yang dibuat/diubah.
2. Cara memeriksa hasilnya.
3. Hal yang belum pasti atau perlu keputusan tim (jika ada).
