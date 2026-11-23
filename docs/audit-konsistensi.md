# Audit Konsistensi Dokumen

Tanggal: 2026-11-23
Dokumen diperiksa: `AGENTS.md`, `docs/prd.md`, `docs/architecture.md`, `docs/rules.md`, `docs/techstack.md`, `docs/design.md`, `docs/plan.md`, `docs/task.md`

## Ringkasan

Temuan: **Tinggi 2 / Sedang 5 / Rendah 1**.

## Temuan Tinggi

| No | Dokumen A (bagian) | Dokumen B (bagian) | Isi yang bertentangan | Saran perbaikan |
|---|---|---|---|---|
| 1 | `docs/prd.md` §7.1–7.3 dan §14 NFR-03 | `docs/architecture.md` §1 dan §9.2; `docs/rules.md` §4; `docs/task.md` T-05 | PRD menetapkan tarif dan rumus tetapi tidak menjelaskan cara pembulatan ketika hasil bukan bilangan bulat rupiah. Architecture menyebut pembulatan setengah ke atas sebagai usulan yang perlu disepakati, sedangkan Rules/T-05 mewajibkan rumus tersebut. Keputusan yang masih berstatus usulan diperlakukan sebagai aturan pasti oleh dokumen lain dan dapat menghasilkan nilai berbeda. | Minta keputusan tim/dosen untuk metode pembulatan, lalu samakan status dan aturan di PRD, architecture, rules, serta task. Jangan menganggap usulan telah disetujui tanpa keputusan. |
| 2 | `docs/prd.md` §7.4 dan §19 | `docs/architecture.md` §9.3 dan §10; `docs/task.md` T-06 | Daftar validasi PRD hanya memuat VAL-01 sampai VAL-06; architecture menambahkan VAL-07 (alamat wajib) dan task T-06 meminta VAL-01 sampai VAL-07. PRD meminta alamat di FR-07, tetapi tidak menetapkan validasinya. | Konfirmasi apakah alamat wajib divalidasi; bila disetujui, tambahkan VAL-07 secara konsisten ke PRD dan kriteria uji, atau hapus ketergantungan VAL-07 dari dokumen implementasi. |

## Temuan Sedang

| No | Dokumen A (bagian) | Dokumen B (bagian) | Isi yang bertentangan | Saran perbaikan |
|---|---|---|---|---|
| 1 | `docs/prd.md` §13 | `docs/architecture.md` §7.2–7.3; `docs/task.md` T-11–T-13 | Daftar endpoint PRD tidak mencantumkan `GET /api/produk/{id}`, `POST /api/pesanan/hitung`, dan `GET /api/ringkasan`, padahal endpoint tersebut menjadi kontrak implementasi di architecture dan task. PRD §20 hanya menyatakan bahwa endpoint §13 diberi awalan `/api`; tidak menyelaraskan daftar endpointnya. | Sinkronkan daftar endpoint PRD dengan kontrak final architecture, atau tandai endpoint tambahan tersebut sebagai perubahan yang disepakati dan rujuk ke architecture. |
| 2 | `docs/design.md` §5.5–5.7 dan §8 | `docs/task.md` T-19–T-20; `docs/architecture.md` §6 | Penomoran halaman admin di design berbeda dengan rujukan task: design §5.5 adalah Admin Dashboard (opsional), §5.6 Admin Produk, dan §5.7 Admin Transaksi; task T-19 merujuk §5.5 untuk Admin Produk dan T-20 merujuk §5.6 untuk Admin Transaksi. Architecture hanya mencantumkan route Admin Produk dan Admin Transaksi, bukan Dashboard. Selain itu design §8 menyebut enam layar laptop termasuk Dashboard, sementara U-03 meminta Admin Produk dan Admin Transaksi. | Perbarui rujukan bagian design di task dan sepakati cakupan enam layar. Pastikan Dashboard tetap jelas berstatus opsional jika route/task implementasinya tidak ditambahkan. |
| 3 | `docs/prd.md` §9.1, §10, dan §11 | `docs/task.md` U-02, U-03, U-05, U-07 | PRD merujuk gambar `img/usecase.png` dan `img/flowchart-checkout.png`; kedua nama berkas tidak ditemukan di repositori saat audit. Task A-02 juga sudah mencatat bahwa gambar tersebut belum ada, sehingga rujukan PRD belum menunjuk aset yang tersedia. | Tambahkan aset pada lokasi yang disepakati atau ubah rujukan PRD ke lokasi/hasil diagram yang benar setelah task diagram selesai. |
| 4 | `AGENTS.md` §6 dan §19–24 | `docs/task.md` §39–46 dan instruksi fase A/U | AGENTS mengarahkan pencarian satu task `T-XX` per sesi dan menyebut mencentang task setelah selesai; task mencakup fase A/U dengan ID `A-xx`/`U-xx`, dan aturan khusus task meminta hasil fase tersebut ditulis ke file baru tanpa menimpa dokumen acuan. Arahan umum tidak menjelaskan penerapan untuk task non-T dan berbeda dengan batasan peran System Analyst di identitas sesi. | Jelaskan bahwa aturan satu task berlaku juga untuk ID A/U, dan sinkronkan aturan penyelesaian/centang dengan pemilik keputusan task agar tidak bertentangan dengan arahan khusus fase/peran. |
| 5 | `docs/prd.md` §13 dan §20 | `docs/architecture.md` §7.1–7.2; `docs/task.md` T-09–T-13 | PRD §13 mencatat endpoint dasar dengan contoh awalan `/api` dan menyatakan nama dapat disesuaikan; architecture menetapkan kontrak yang lebih rinci termasuk response, status HTTP, serta filter `q`, `kategori`, dan `status`. Task mengimplementasikan kontrak rinci architecture, tetapi PRD tidak secara eksplisit menyatakan bahwa daftar endpoint §13 telah digantikan oleh kontrak tersebut. | Tambahkan rujukan eksplisit dari PRD ke kontrak API architecture sebagai spesifikasi implementasi yang berlaku, atau selaraskan daftar PRD. (Temuan ini terkait cakupan endpoint pada Temuan Sedang 1; hindari menduplikasi perubahan daftar endpoint.) |

## Temuan Rendah

| No | Dokumen A (bagian) | Dokumen B (bagian) | Isi yang bertentangan | Saran perbaikan |
|---|---|---|---|---|
| 1 | `docs/prd.md` §11.2 diagram ERD | `docs/architecture.md` §1, §8, §10 | ERD PRD memakai atribut relasi `pesanan_id` dan `produk_id`, sedangkan keputusan architecture menetapkan semua JSON memakai `camelCase` (`produkId`, `hargaSatuan`). Architecture §10 sendiri menjelaskan perubahan ini, tetapi ERD PRD tetap memperlihatkan gaya berbeda dan bisa disalahartikan sebagai format JSON. | Tambahkan catatan bahwa nama pada ERD adalah notasi relasi konseptual, bukan nama field JSON; atau samakan label ERD saat dokumen acuan diperbarui. |

## Pasangan tanpa temuan

- `AGENTS.md` dengan `docs/prd.md`: tidak ada temuan tambahan di luar temuan yang tercatat.
- `AGENTS.md` dengan `docs/architecture.md`: tidak ada temuan tambahan di luar temuan yang tercatat.
- `AGENTS.md` dengan `docs/rules.md`: tidak ada temuan tambahan di luar temuan yang tercatat.
- `AGENTS.md` dengan `docs/techstack.md`: tidak ada.
- `AGENTS.md` dengan `docs/design.md`: tidak ada temuan tambahan di luar temuan yang tercatat.
- `AGENTS.md` dengan `docs/plan.md`: tidak ada.

- `docs/prd.md` dengan `docs/techstack.md`: tidak ada.
- `docs/prd.md` dengan `docs/design.md`: tidak ada.
- `docs/prd.md` dengan `docs/plan.md`: tidak ada.
- `docs/architecture.md` dengan `docs/techstack.md`: tidak ada.
- `docs/architecture.md` dengan `docs/plan.md`: tidak ada.
- `docs/rules.md` dengan `docs/techstack.md`: tidak ada.
- `docs/rules.md` dengan `docs/design.md`: tidak ada.
- `docs/rules.md` dengan `docs/plan.md`: tidak ada.
- `docs/rules.md` dengan `docs/task.md`: tidak ada.
- `docs/techstack.md` dengan `docs/design.md`: tidak ada.
- `docs/techstack.md` dengan `docs/plan.md`: tidak ada.
- `docs/techstack.md` dengan `docs/task.md`: tidak ada.
- `docs/design.md` dengan `docs/plan.md`: tidak ada.
- `docs/plan.md` dengan `docs/task.md`: tidak ada.

## Verifikasi

1. Kedelapan dokumen yang disebut pada task A-01 telah dibaca.
2. Temuan menyebut dokumen/bagian, perbedaan, dan saran tindak lanjut.
3. Temuan dikelompokkan menjadi Tinggi, Sedang, dan Rendah.
4. Semua 20 pasangan tanpa temuan dinyatakan "tidak ada"; pasangan yang memiliki temuan dirujuk pada tabel temuan.
5. Berkas gambar `img/usecase.png` dan `img/flowchart-checkout.png` yang dirujuk PRD tidak ditemukan saat diperiksa.
6. Berkas yang dibuat/diubah oleh audit hanya `docs/audit-konsistensi.md`. Pemeriksaan `git status --short` sebelum penulisan menunjukkan perubahan lain yang sudah ada di working tree (`config/routes.php`, `docs/design.md`, `docs/task.md`, sejumlah file aplikasi/data, `docs/prompt-delegasi.md`, dan aset). Perubahan tersebut tidak dibuat atau diubah oleh audit; karena itu kondisi kerja bersih yang diminta skill tidak dapat dinyatakan terpenuhi.
