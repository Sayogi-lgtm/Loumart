# Rencana Pengerjaan LouMart

Mengikuti jadwal 4 minggu di `prd.md` bagian 16. Daftar task rinci ada di `task.md`.

## Fase dan target

| Fase | Minggu | Fokus | Task | Hasil yang bisa diperiksa |
|---|---|---|---|---|
| 0. Fondasi | 1 | Kerangka proyek, format JSON dikunci, data contoh | T-01 sampai T-04 | `php -S localhost:8000 -t public` jalan dan route contoh membalas JSON |
| 1. Logika & API | 2 | Rumus, validasi, model, endpoint | T-05 sampai T-13 | Semua endpoint bisa dites dengan browser/Postman; skenario uji 1 sampai 8 lolos di `tests/run.php` |
| 2. Tampilan | 2-3 | View Bootstrap (Frontend 1) dan JavaScript (Frontend 2) | T-14 sampai T-27 | Alur Katalog sampai Status Pesanan dan halaman admin bekerja di browser |
| 3. Integrasi & uji | 3-4 | Uji menyeluruh, perbaikan bug, dokumen akhir | T-28 sampai T-30 | Semua kriteria penerimaan PRD bagian 17 terpenuhi; siap demo |
| Opsional | bila sempat | Filter kategori, login admin | T-31, T-32 | Hanya jika waktu cukup atau diminta dosen |

## Urutan dan ketergantungan

```text
T-01 -> T-02 -> T-03 -> T-04
                   |
                   v
   T-05, T-06 (bisa paralel) -> T-07, T-08 -> T-09..T-13 (API)
                                               |
   T-14 (layout) -> T-15..T-20 (View)          |
   T-21 (util JS) -> T-22..T-27 (JS halaman) <-+  (butuh API terkait)
                                               |
                                    T-28 -> T-29 -> T-30
```

Frontend bisa mulai tanpa menunggu API selesai dengan memakai data contoh di `data/produk.json`
dan mock respons sesuai kontrak di `architecture.md` bagian 7.

## Cara bekerja dengan Copilot

1. Pilih satu task di `task.md`, buat branch `t-XX-nama-singkat`.
2. Minta Copilot: "Kerjakan task T-XX. Baca AGENTS.md dan dokumen yang disebutkan di sana."
3. Minta rencana file dulu, periksa, baru izinkan menulis kode.
4. Jalankan cara verifikasi di task. Perbaiki sampai lolos.
5. Centang task, commit `T-XX: ...`, buka Pull Request kecil.
6. Reviewer (anggota lain atau PM) mengecek terhadap `rules.md` bagian 9.

## Kesepakatan yang harus beres di Minggu 1

- [ ] Semua anggota memakai PHP 8.3.3 (`php -v`) dan cara menjalankan (XAMPP atau server bawaan) disepakati.
- [ ] Aturan diskon dan pajak dikonfirmasi ke dosen.
- [ ] Keputusan login admin.
- [ ] Format JSON, kontrak API, dan daftar "Perubahan dari PRD" (architecture bagian 10) disetujui semua anggota.
- [ ] Repo, branch `main`, dan aturan PR disiapkan PM.

## Risiko utama

| Risiko | Pencegahan |
|---|---|
| Frontend dan backend tidak cocok | Kontrak API dikunci; perubahan lewat persetujuan bersama |
| Copilot membuat kode di luar rencana | Satu task per sesi, batas file di setiap task, review PR |
| Data JSON rusak karena tulis bersamaan | `JsonStore` memakai `flock` dan tulis atomik |
| Rumus beda antara tampilan dan server | Hanya server yang menghitung; JS memakai `/api/pesanan/hitung` |
| Task tertinggal | PM memantau tiap pertemuan; task opsional dikorbankan lebih dulu |
