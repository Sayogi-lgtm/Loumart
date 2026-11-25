# Rencana Pengerjaan LouMart

Daftar pekerjaan rinci ada di `task.md`. Jadwal empat minggu di PRD merupakan rencana, bukan kewajiban untuk menunggu sampai minggu terakhir: tim boleh selesai lebih awal dan tidak boleh melewati tenggat dosen.

## Fase dan target

| Fase | Fokus | Hasil yang bisa diperiksa |
|---|---|---|
| 0. Fondasi | Kerangka proyek dan kontrak API | Aplikasi PHP berjalan dan endpoint mengembalikan JSON |
| 1. Persiapan database | MariaDB lokal, skema tabel, data awal, konfigurasi aman | Setiap anggota dapat menyiapkan database lokal dengan struktur/data awal yang sama |
| 2. Logika & API | Rumus, validasi, akses data, endpoint | Skenario uji bisnis berjalan dengan MariaDB |
| 3. Tampilan & interaksi | Tailwind CDN, View, JavaScript, AJAX | Alur pembeli dan admin berjalan di browser |
| 4. Integrasi & uji | Uji menyeluruh, perbaikan, dokumentasi proyek | Kriteria penerimaan PRD terpenuhi dan proyek siap didemokan |

## Urutan kerja umum

```text
Persiapan PHP dan MariaDB lokal
        |
Struktur tabel + data awal yang sama untuk semua anggota
        |
Model dan API menggunakan MariaDB
        |
View dengan Tailwind CDN + JavaScript/AJAX
        |
Integrasi -> pengujian -> demo
```

Task di `task.md` menentukan urutan dan ketergantungan yang lebih rinci. JSON tetap digunakan untuk request/response API, bukan sebagai penyimpanan utama aplikasi.

## Kerja tim dan progres mingguan

- PM menjadwalkan presentasi progres setiap minggu; kewajiban ini berlaku juga saat tim sedang memperbaiki atau mengembangkan fitur.
- Setiap anggota menjalankan MariaDB lokal. Struktur tabel dan data awal harus disiapkan dengan cara yang sama.
- Kredensial database bersifat lokal dan tidak boleh diunggah ke GitHub.
- Tailwind dimuat melalui CDN, sehingga demo tampilan memerlukan koneksi internet.
- Tim boleh menyelesaikan proyek lebih awal, tetapi wajib memenuhi tenggat dosen.

## Cara bekerja dengan Copilot

1. Pilih satu task di `task.md`, lalu buat branch dengan nama task.
2. Baca `AGENTS.md` dan dokumen acuan yang disebutkan pada task.
3. Tulis rencana file sebelum mengubah kode dan minta persetujuan jika cakupannya lebih dari tiga file.
4. Jalankan cara verifikasi pada task.
5. Centang task setelah kriterianya terpenuhi, lalu ikuti aturan commit dan Pull Request di `rules.md`.

## Risiko utama

| Risiko | Pencegahan |
|---|---|
| Database lokal anggota berbeda | Sediakan satu sumber skema dan data awal untuk seluruh tim |
| Kredensial lokal tidak sengaja masuk repository | Simpan kredensial di konfigurasi lokal dan pastikan diabaikan Git |
| Tailwind CDN tidak termuat saat demo | Pastikan koneksi internet tersedia |
| Frontend dan backend tidak cocok | Jaga kontrak API tetap konsisten |
| Task tertinggal | PM memantau progres dan presentasi mingguan; task tambahan diprioritaskan setelah fitur wajib |

## Laporan

Laporan akhir berbentuk Word. Kerangka FSD akan dibahas kemudian dan belum menjadi bagian dari rencana ini.
