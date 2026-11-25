# Daftar Task LouMart

Cara memakai: kerjakan satu task per sesi untuk semua ID (T-XX, A-XX, dan U-XX). Centang `[x]` setelah semua poin "Selesai bila" terpenuhi; untuk fase A/U, pencentangan tidak menggantikan persetujuan PM sebelum perubahan diterapkan ke dokumen acuan.
Peran: **BE** = Backend/Logic, **FE1** = Frontend 1 (HTML/Tailwind), **FE2** = Frontend 2 (JS), **PM**, **SA** = Sistem Analis, **UIUX** = UI/UX.
Acuan: `architecture.md` (disingkat **ARS**), `prd.md`, `rules.md`.
Pola prompt untuk mendelegasikan task ke agen: lihat `docs/prompt-delegasi.md`.

Aturan fase A dan U: hasil ditulis ke file baru yang tercantum di "File". Dokumen acuan (prd, architecture, rules, design) tidak boleh ditimpa langsung; perubahan diajukan sebagai usulan di file hasil, lalu disetujui PM sebelum diterapkan. Satu task A/U tetap dikerjakan per sesi; memenuhi kriteria dan mencentang task tidak berarti usulan perubahan dokumen acuan sudah disetujui.

Urutan pengerjaan yang disarankan:
1. Fase 0 dan A-01, A-02 dapat berjalan bersamaan.
2. U-01, U-02, dan U-03 dapat dikerjakan sesuai prasyaratnya bersamaan dengan Fase 1 (backend); U-04 menunggu U-02 dan U-03, U-05 menunggu U-04, sedangkan U-06 menunggu U-01.
3. A-03 menunggu A-01; A-04 menunggu A-02; A-05 menunggu A-01. U-07 menunggu U-01 sampai U-05 yang menjadi sumber desain dan kondisi.
4. Fase 2A mengikuti prasyarat tiap task: T-14b menunggu U-01 dan U-06; T-15 sampai T-20 menunggu mockup dan kondisi yang dirujuk. Penerapan responsif menunggu U-05.
5. Fase 2B mengikuti prasyarat API dan View pada tiap task. Fase 3: T-28 setelah API siap, lalu T-29 setelah T-28, A-06, dan U-08; T-30 setelah hasil integrasi serta A-03 dan A-05.

## Fase 0: Fondasi

Catatan: T-03/T-04 dan task terkait yang sudah dicentang mencatat fondasi JSON yang sebelumnya dibuat. Migrasi dari penyimpanan tersebut ke MariaDB dikerjakan terpisah pada T-34 dan T-35.

- [x] **T-01 Kerangka proyek** (PM/BE)
  - File: struktur folder ARS bagian 4 (folder kosong boleh pakai `.gitkeep`), `public/index.php`, `public/.htaccess`, `.gitignore`
  - Kerjakan: buat folder, `spl_autoload_register` untuk namespace `App\` ke `app/`, `index.php` memuat autoload lalu menjalankan Router (sementara balas teks "LouMart jalan").
  - Selesai bila: `php -S localhost:8000 -t public` lalu `http://localhost:8000` menampilkan teks tersebut.

- [x] **T-02 Core: Request, Response, Router** (BE)
  - File: `app/Core/Request.php`, `Response.php`, `Router.php`, `config/routes.php`, `public/index.php`
  - Kerjakan: Router mencocokkan metode + path (termasuk `{id}`), `Response::json($data, $kode)`, `Request` membaca body JSON dan query. Route tidak ditemukan membalas 404 (JSON untuk `/api/...`).
  - Selesai bila: route contoh `GET /api/ping` membalas `{"sukses":true,"data":"pong"}`; JSON rusak membalas 400; path ngawur membalas 404.

- [x] **T-03 Core: JsonStore** (BE)
  - File: `app/Core/JsonStore.php`
  - Kerjakan: `baca($file)`, `tulis($file, $array)` dengan `flock` + file sementara + `rename`, dan metode untuk menjalankan beberapa perubahan dalam satu kunci (ARS bagian 8). File hilang/kosong dianggap `[]`.
  - Selesai bila: tes kecil di `tests/run.php` menulis lalu membaca ulang data identik dan file hilang menghasilkan `[]`.

- [x] **T-04 Konfigurasi dan data contoh** (BE)
  - File: `config/app.php`, `data/produk.json`, `data/pesanan.json`
  - Kerjakan: isi konfigurasi sesuai ARS 9.1; buat 8 sampai 10 produk contoh (beberapa kategori, ada satu stok 0, harga bervariasi agar subtotal bisa di bawah dan di atas Rp500.000); `pesanan.json` berisi `[]`.
  - Selesai bila: kedua file adalah JSON valid dan sesuai format ARS bagian 8.

## Fase A: Analisis (SA)

Fase ini hanya menyentuh folder `docs/`. Tidak boleh mengubah `app/`, `public/`, `config/`, `data/`, atau `tests/`.

- [ ] **A-01 Audit konsistensi dokumen** (SA)
  - File: `docs/audit-konsistensi.md`
  - Kerjakan: bandingkan `AGENTS.md`, `prd.md`, `architecture.md`, `rules.md`, `techstack.md`, `design.md`, `plan.md`, dan `task.md`. Periksa nama endpoint, format response, aturan hitung, status pesanan, istilah, nomor FR/NFR/VAL, dan rujukan antar bagian. Jangan ubah dokumen yang diperiksa.
  - Selesai bila: semua delapan dokumen sudah diperiksa; tiap temuan memuat file, bagian, isi yang bertentangan, dan saran perbaikan; temuan dikelompokkan Tinggi/Sedang/Rendah; jika tidak ada temuan, tertulis "tidak ada" per pasangan dokumen.

- [ ] **A-02 Catatan keputusan dosen dan tim** (SA)
  - File: `docs/keputusan.md`
  - Kerjakan: catat keputusan dosen dan tim secara ringkas: MariaDB, Tailwind CDN, database lokal per anggota, presentasi progres mingguan, izin menyelesaikan lebih awal selama tidak melewati tenggat, tema LouMart, dan pembahasan kerangka FSD yang ditunda.
  - Selesai bila: semua keputusan tersebut tercatat jelas dan dokumen tidak menyatakan FSD sudah ditetapkan.

- [ ] **A-03 Matriks keterlacakan** (SA)
  - File: `docs/keterlacakan.md`
  - Kerjakan: tabel semua FR dan NFR di `prd.md` dengan kolom: ID, ringkasan, task T-xx yang mengimplementasikan, skenario uji PRD bagian 17 yang menguji, status.
  - Butuh: A-01.
  - Selesai bila: setiap FR/NFR punya baris; yang belum punya task atau skenario uji ditandai "BELUM ADA" (jangan mengarang task atau skenario).

- [ ] **A-04 Daftar pertanyaan untuk dosen** (SA)
  - File: `docs/pertanyaan-dosen.md`
  - Kerjakan: ubah hal yang masih berstatus Terbuka pada catatan keputusan menjadi pertanyaan singkat yang bisa dijawab ya/tidak atau memilih satu opsi, lengkap dengan akibat tiap jawaban terhadap pekerjaan. Jika tidak ada pertanyaan terbuka untuk dosen, catat "tidak ada".
  - Butuh: A-02.
  - Selesai bila: satu pertanyaan maksimal tiga kalimat untuk tiap hal Terbuka, atau tertulis "tidak ada" jika tidak ada pertanyaan terbuka.

- [ ] **A-05 Diagram dokumentasi** (SA)
  - File: `docs/diagram/usecase.md`, `docs/diagram/flowchart-checkout.md` (Mermaid); opsional PNG di `docs/img/`
  - Kerjakan: buat use case (Pembeli, Admin) dan flowchart checkout sesuai alur ARS bagian 3, termasuk jalur gagal validasi dan stok kurang.
  - Butuh: A-01.
  - Selesai bila: kedua diagram tampil benar di pratinjau Markdown dan setiap langkah sama dengan ARS bagian 3; nama file cocok dengan rujukan di `prd.md` atau rujukan itu dicatat sebagai temuan di A-01.

- [ ] **A-06 Daftar uji manual** (SA)
  - File: `docs/uji-manual.md`
  - Kerjakan: ubah delapan skenario PRD bagian 17 dan semua kondisi di U-04 menjadi daftar centang langkah demi langkah (langkah, data masukan, hasil yang diharapkan), untuk dipakai di T-29.
  - Butuh: A-03, U-04.
  - Selesai bila: tiap skenario PRD punya langkah yang bisa diikuti orang yang belum pernah melihat aplikasinya, dan ada bagian khusus lebar layar HP, tablet, dan laptop.

## Fase U: UI/UX (UIUX)

Hasil desain dikerjakan di Wonder (file "LouMart"). Karena agen kode tidak bisa membuka Wonder, setiap hasil harus dituliskan ke `docs/design.md` dengan nilai pasti dan diekspor sebagai PNG ke `docs/design/`.

- [x] **U-01 Design token** (UIUX)
  - File: `docs/design.md` (bagian Token), blok variabel CSS di dalamnya
  - Kerjakan: tulis token berikut sebagai tabel dan sebagai blok `:root` siap salin ke `public/assets/css/style.css`, beserta contoh pemakaian token melalui utility class Tailwind.
    - Warna merek: primary `#C2410C`, primary-hover `#9A3412`, soft `#FFEDD5`, accent `#F59E0B`
    - Permukaan: page `#FFF8EE`, card `#FFFFFF`, sunken `#FBEFDD`; garis `#EBDCC8`
    - Teks: primary `#3B2416`, secondary `#6B5444`, on-brand `#FFFFFF`
    - Status: warning `#FEF3C7`/`#92400E`, success `#DCFCE7`/`#166534`, danger `#FEE2E2`/`#991B1B` (latar/teks)
    - Radius: 8, 12, 16, 24 px
    - Font: judul Plus Jakarta Sans, isi DM Sans (cara memuat dari Google Fonts dan fallback `system-ui`)
  - Selesai bila: semua nilai di atas tertulis, blok `:root` valid, dan tidak ada warna sementara (`[SEMENTARA]`) tersisa di `design.md`.

- [x] **U-02 Mockup laptop: halaman pembeli** (UIUX)
  - File: Wonder (file LouMart): Katalog, Keranjang, Checkout, Status Pesanan lebar 1440px
  - Selesai bila: keempat artboard ada di Wonder. (Sudah selesai; ekspor PNG dan penulisan ke `design.md` ada di U-07.)

- [x] **U-03 Mockup laptop: halaman admin** (UIUX)
  - File: Wonder (Admin Produk, Admin Transaksi lebar 1440px)
  - Kerjakan: sidebar kiri; Admin Produk berupa tabel + modal tambah/ubah + konfirmasi hapus; Admin Transaksi berupa kartu ringkasan (FR-17), tabel pesanan, filter status, badge status, tombol Lunas/Batalkan dengan konfirmasi.
  - Butuh: U-01.
  - Selesai bila: kedua artboard ada dan memakai token U-01.

- [ ] **U-04 Kondisi tambahan** (UIUX)
  - File: Wonder (artboard "Kondisi")
  - Kerjakan: desain kondisi berikut sesuai `design.md`: katalog memuat (loading), katalog kosong (hasil cari tidak ditemukan), gambar produk gagal dimuat (gambar pengganti), keranjang kosong, form checkout kosong (placeholder), banner stok tidak mencukupi dengan jalan kembali ke keranjang, tombol "Buat pesanan" saat memproses, error server (500/jaringan), status pesanan Lunas dan Dibatalkan, pesanan tidak ditemukan (404), toast berhasil/gagal, modal konfirmasi (hapus produk, Lunas, Batalkan), pesan error 409 untuk status yang sudah final.
  - Butuh: U-02, U-03.
  - Selesai bila: semua kondisi di atas punya tampilan; tiap kondisi tidak hanya mengandalkan warna (ada ikon atau teks).

- [ ] **U-05 Versi tablet dan HP** (UIUX)
  - File: Wonder (artboard 768px dan 390px)
  - Kerjakan: grid produk 2 kolom di HP, 3 di tablet; total dan tombol aksi menempel di bawah untuk Keranjang dan Checkout; admin berupa daftar kartu di HP dan tabel di tablet ke atas; navbar menyempit.
  - Butuh: U-02, U-03, U-04.
  - Selesai bila: keenam halaman punya versi 390px; halaman pembeli juga punya versi 768px.

- [ ] **U-06 Aset gambar** (UIUX)
  - File: `public/assets/img/` (`logo.svg`, `favicon.svg`, `produk-pengganti.svg`)
  - Kerjakan: ekspor logo LouMart (ikon keranjang pada kotak oranye + tulisan), favicon, dan gambar pengganti produk. Foto produk asli ditambahkan tim, bukan dibuat di sini.
  - Butuh: U-01.
  - Selesai bila: ketiga berkas ada, berformat SVG, dan terlihat jelas di ukuran 32px.

- [ ] **U-07 Ekspor dan sinkronkan design.md** (UIUX)
  - File: `docs/design/*.png`, `docs/design.md` bagian 5.1 sampai 5.7
  - Kerjakan: ekspor artboard laptop keenam halaman, versi HP keenam halaman, dan versi tablet empat halaman pembeli sebagai PNG (`[halaman]-[ukuran].png`, misalnya `katalog-laptop.png`); di `design.md` tulis tiap halaman: susunan dari atas ke bawah, ukuran dan jarak penting, token yang dipakai, utility class Tailwind yang disarankan, dan daftar kondisi (dari U-04). Acuan gambar dengan nama file. Untuk tablet, empat halaman pembeli adalah Katalog, Keranjang, Checkout, dan Status Pesanan.
  - Butuh: U-01, U-02, U-03, U-04, U-05.
  - Selesai bila: bagian 5.1–5.4 dan 5.6–5.7 memuat nilai pasti (bukan hanya "oranye" atau "besar") serta semua PNG wajib tersebut ada di `docs/design/` dan dirujuk dengan nama file di `design.md`.

- [ ] **U-08 Tinjau kesesuaian tampilan** (UIUX)
  - File: `docs/review-ui.md`
  - Kerjakan: bandingkan hasil HTML (T-15 sampai T-20) dengan PNG di `docs/design/`: keenam halaman di lebar HP dan laptop, halaman pembeli juga di tablet, serta halaman admin di tablet untuk memeriksa transisi kartu ke tabel sesuai U-05. Catat selisih (warna, jarak, font, kondisi yang terlewat) sebagai daftar bug.
  - Butuh: T-15 sampai T-20, U-04, U-05, U-07.
  - Selesai bila: keenam halaman diperiksa pada lebar HP dan laptop; halaman pembeli juga diperiksa pada lebar tablet; halaman admin diperiksa pada lebar tablet untuk memastikan transisi kartu ke tabel sesuai U-05. Tiap selisih menyebut file view yang perlu diubah.

- [ ] **U-09 Halaman login admin** (UIUX, hanya jika T-32 jadi)
  - File: Wonder, `docs/design.md`
  - Butuh: keputusan tim untuk mengerjakan T-32.
  - Selesai bila: tampilan login, error kredensial salah, dan tombol keluar di sidebar admin tersedia.

## Fase 1: Logika dan API (Backend)

- [x] **T-05 HitungService** (BE)
  - File: `app/Services/HitungService.php`, `tests/run.php`
  - Butuh: T-04.
  - Kerjakan: rumus ARS 9.2 dengan bilangan bulat dan nilai dari config.
  - Selesai bila: skenario uji PRD 1 (400.000 -> 444.000) dan 2 (600.000 -> 599.400) lolos, ditambah kasus tepat 500.000 (diskon 10%) dan 499.999 (diskon 0).

- [x] **T-06 ValidasiService** (BE)
  - File: `app/Services/ValidasiService.php`, `tests/run.php`
  - Butuh: T-04.
  - Kerjakan: VAL-01 sampai VAL-07 sesuai ARS 9.3 dan format error ARS 7.1.
  - Selesai bila: ada tes untuk tiap aturan (keranjang kosong, qty 0/negatif/desimal/teks, qty > stok, produk tidak ada, nama kosong, kontak salah, alamat kosong) dan satu kasus semua valid.

- [x] **T-07 Model Produk** (BE)
  - File: `app/Models/Produk.php`
  - Butuh: T-03, T-04.
  - Kerjakan: `semua($q, $kategori)`, `cari($id)`, `tambah`, `ubah`, `hapus`, `kurangiStok`, `kembalikanStok`; ID berurutan.
  - Selesai bila: tes menambah, mengubah, menghapus, dan mengurangi stok memberi data JSON yang benar (pakai file data sementara untuk tes, bukan data tim).

- [x] **T-08 Model Pesanan** (BE)
  - File: `app/Models/Pesanan.php`
  - Butuh: T-03, T-04.
  - Kerjakan: `semua($status)`, `cari($id)`, `buatIdBaru()` (`ORD-001` dst), `simpan`, `ubahStatus`.
  - Selesai bila: tes membuat dua pesanan berturut-turut menghasilkan `ORD-001` dan `ORD-002`.

- [x] **T-09 API Produk: baca** (BE)
  - File: `app/Controllers/ProdukApiController.php`, `config/routes.php`
  - Kerjakan: `GET /api/produk` (parameter `q` cari nama tanpa membedakan huruf besar kecil, `kategori`) dan `GET /api/produk/{id}` (404 jika tidak ada).
  - Selesai bila: pencarian "kaos" dan filter kategori memberi hasil benar; id ngawur memberi 404.

- [x] **T-10 API Produk: admin** (BE)
  - File: `app/Controllers/ProdukApiController.php`, `config/routes.php`
  - Kerjakan: `POST`, `PUT`, `DELETE` dengan validasi produk (ARS 7.3), 201/200/404/422.
  - Selesai bila: tambah, ubah, hapus bekerja dan data produk tidak valid (harga negatif, nama kosong) ditolak 422.

- [x] **T-11 API Pesanan: hitung dan buat** (BE)
  - File: `app/Services/PesananService.php`, `app/Controllers/PesananApiController.php`, `config/routes.php`, `tests/run.php`
  - Kerjakan: `POST /api/pesanan/hitung` dan `POST /api/pesanan` sesuai alur ARS bagian 3: validasi, ambil harga dari MariaDB, hitung, simpan pesanan + kurangi stok dalam satu transaksi database, status awal "Menunggu Pembayaran", tanggal hari ini.
  - Butuh: T-34, T-35.
  - Selesai bila: skenario uji PRD 3, 4, 5, 6 lolos di `tests/run.php`; kegagalan validasi tidak mengubah stok.

- [x] **T-12 API Pesanan: baca dan ubah status** (BE)
  - File: `app/Services/PesananService.php`, `app/Controllers/PesananApiController.php`, `app/Models/Pesanan.php`, `config/routes.php`, `tests/run.php`
  - Kerjakan: `GET /api/pesanan?status=`, `GET /api/pesanan/{id}`, `PUT /api/pesanan/{id}/status`. Lunas/Dibatalkan final (409). Dibatalkan mengembalikan stok dalam transaksi database.
  - Butuh: T-11, T-34, T-35.
  - Selesai bila: daftar/detail dan skenario uji PRD 7 dan 8 lolos; mengubah status pesanan yang sudah final memberi 409; membatalkan menambah stok kembali hanya sekali.

- [x] **T-13 API Ringkasan** (BE, tambahan FR-17)
  - File: `app/Models/Pesanan.php`, `app/Controllers/RingkasanApiController.php`, `config/routes.php`, `tests/run.php`
  - Kerjakan: `GET /api/ringkasan` sesuai ARS 7.3; pendapatan hanya dari pesanan Lunas.
  - Butuh: T-12, T-34, T-35.
  - Selesai bila: dengan satu pesanan Lunas dan satu Dibatalkan, pendapatan hanya menghitung yang Lunas.

## Fase 2A: Tampilan (Frontend 1)

- [x] **T-14 Layout dan routing halaman** (FE1, dibantu BE untuk route)
  - File: `app/Core/View.php`, `app/Controllers/PageController.php`, `app/Views/layouts/main.php`, `public/assets/css/style.css`, `config/routes.php`
  - Kerjakan: kelas View yang memuat layout + isi halaman, navbar dan footer sesuai `design.md`, enam route halaman ARS bagian 6 yang menampilkan kerangka kosong. Task migrasi Tailwind CDN tercatat pada T-36.
  - Selesai bila: keenam URL halaman terbuka dengan navbar yang sama dan responsif di lebar HP.

- [x] **T-14b Terapkan token desain** (FE1)
  - File: `public/assets/css/style.css`, `app/Views/layouts/main.php`
  - Kerjakan: salin blok `:root` dari `design.md` (U-01), muat font Plus Jakarta Sans dan DM Sans, pasang logo dan favicon (U-06), ganti warna sementara.
  - Butuh: U-01, U-06.
  - Selesai bila: navbar dan warna dasar sesuai token; tanpa `style=` inline.

- [ ] **T-15 View Katalog** (FE1) - File: `app/Views/katalog.php`. Kerangka: kolom cari, filter kategori, wadah grid produk, spinner. Selesai bila tata letak sesuai `design.md` 5.1 dengan data contoh statis. Butuh T-36, U-02, U-04, U-07. Responsif butuh U-05.
- [ ] **T-16 View Keranjang** (FE1) - File: `app/Views/keranjang.php`. Selesai bila sesuai `design.md` 5.2 termasuk keadaan kosong. Butuh T-36, U-02, U-04 (keranjang kosong), U-07. Responsif butuh U-05.
- [ ] **T-17 View Checkout** (FE1) - File: `app/Views/checkout.php`. Form, tempat pesan error per kolom, panel rincian biaya. Selesai bila sesuai `design.md` 5.3. Butuh T-36, U-02, U-04 (error per kolom, stok kurang, memproses), U-07. Responsif butuh U-05.
- [ ] **T-18 View Status Pesanan** (FE1) - File: `app/Views/status-pesanan.php`. Selesai bila sesuai `design.md` 5.4 (ID pesanan dibaca dari URL). Butuh T-36, U-02, U-04 (Lunas, Dibatalkan, 404), U-07. Responsif butuh U-05.
- [ ] **T-19 View Admin Produk** (FE1) - File: `app/Views/admin/produk.php`. Tabel + modal form. Selesai bila sesuai `design.md` 5.6. Butuh T-36, U-03, U-04, U-07. Responsif butuh U-05.
- [ ] **T-20 View Admin Transaksi** (FE1) - File: `app/Views/admin/transaksi.php`. Selesai bila sesuai `design.md` 5.7. Butuh T-36, U-03, U-04, U-07. Responsif butuh U-05.

## Fase 2B: Interaksi (Frontend 2)

- [ ] **T-21 Util JavaScript** (FE2)
  - File: `public/assets/js/api.js`, `format.js`, `cart.js`
  - Kerjakan: `api.js` membungkus `fetch` untuk AJAX (membaca format response ARS 7.1, melempar error berisi daftar `errors`); `format.js` berisi `formatRupiah(angka)` dan `formatTanggal`; `cart.js` mengelola `localStorage` (`tambah`, `ubahQty`, `hapus`, `ambil`, `kosongkan`, `jumlahItem`) dan memperbarui badge navbar.
  - Selesai bila: `formatRupiah(599400)` menghasilkan `Rp599.400` dan keranjang bertahan setelah halaman dimuat ulang.

- [ ] **T-22 Katalog (JS)** (FE2) - File: `public/assets/js/katalog.js`. Ambil `/api/produk`, tampilkan kartu, cari dan filter, tambah ke keranjang. Butuh T-09, T-15, T-21. Selesai bila produk muncul dari server dan tombol tambah menaikkan badge.
- [ ] **T-23 Keranjang (JS)** (FE2) - File: `public/assets/js/keranjang.js`. Tampilkan item dari `cart.js`, ubah qty/hapus, total sementara, qty tidak melebihi stok yang tampil. Butuh T-16, T-21. Selesai bila perubahan langsung terlihat dan tersimpan.
- [ ] **T-24 Checkout (JS)** (FE2) - File: `public/assets/js/checkout.js`. Panggil `/api/pesanan/hitung` untuk rincian, kirim `POST /api/pesanan`, tampilkan error per kolom, kosongkan keranjang dan pindah ke `/pesanan/{id}` jika sukses. Butuh T-11, T-17, T-21. Selesai bila skenario uji PRD 3 sampai 6 bisa dicoba dari browser.
- [ ] **T-25 Status Pesanan (JS)** (FE2) - File: `public/assets/js/status-pesanan.js`. Ambil `/api/pesanan/{id}`, tampilkan; 404 menampilkan "Pesanan tidak ditemukan". Butuh T-12, T-18. Selesai bila halaman benar untuk ID valid dan tidak valid.
- [ ] **T-26 Admin Produk (JS)** (FE2) - File: `public/assets/js/admin-produk.js`. Tabel, tambah/ubah lewat modal, hapus dengan konfirmasi, tampilkan error 422. Butuh T-10, T-19. Selesai bila CRUD produk bekerja dan katalog ikut berubah.
- [ ] **T-27 Admin Transaksi (JS)** (FE2) - File: `public/assets/js/admin-transaksi.js`. Tabel pesanan, ringkasan, tombol Lunas/Batalkan dengan konfirmasi, tampilkan 409. Butuh T-12, T-13, T-20. Selesai bila skenario uji PRD 7 dan 8 bisa dicoba dari browser.

## Fase 3: Integrasi dan uji

- [ ] **T-28 Uji otomatis lengkap** (BE)
  - File: `tests/run.php`
  - Kerjakan: pastikan delapan skenario PRD bagian 17 ada semuanya, memakai database pengujian yang terpisah dari database kerja anggota, dan skrip keluar dengan kode tidak nol jika ada yang gagal. T-13 (ringkasan) dan CRUD T-10 diuji dalam task terkait atau verifikasi tambahan karena tidak tercakup oleh delapan skenario PRD.
  - Butuh: T-05 sampai T-12, T-34, T-35. T-13 bukan prasyarat untuk delapan skenario PRD, tetapi harus selesai sebelum T-27.
  - Selesai bila `php tests/run.php` menampilkan semua skenario PRD lulus; bila T-13 sudah dikerjakan, uji pendapatan dari pesanan Lunas juga lulus.

- [ ] **T-29 Uji manual dan perbaikan bug** (semua)
  - Kerjakan: jalankan alur lengkap di Chrome dan Firefox, di lebar HP, tablet, dan laptop; catat bug sebagai task baru di bawah.
  - Butuh: T-22 sampai T-27, T-28, A-06 (daftar uji), dan U-08 (tinjauan tampilan).
  - Selesai bila alur Katalog sampai Status Pesanan dan kedua halaman admin berjalan tanpa error di konsol.

- [ ] **T-30 README dan bahan laporan** (PM/SA)
  - File: `README.md`
  - Kerjakan: cara menjalankan, struktur singkat, anggota, tangkapan layar; sinkronkan diagram di `docs/` dengan hasil akhir.
  - Butuh: T-29, A-03, dan A-05 untuk bahan laporan serta tangkapan layar hasil akhir.
  - Selesai bila orang baru bisa menjalankan proyek hanya dari README.

## Opsional

- [ ] **T-31 Filter kategori di katalog** (FR-03) (FE1+FE2) - Pastikan filter kategori berfungsi di tampilan; endpoint sudah mendukung `kategori`.
- [ ] **T-32 Login admin sederhana** (BE+FE1) - Hanya jika dosen meminta: sesi PHP, halaman login, proteksi route `/admin/*` dan endpoint admin. Putuskan kebutuhan login dan cara menyimpan kredensial bersama tim dulu. Butuh keputusan tim dan U-09.

## Fase 4: Penyelarasan ketentuan proyek

- [x] **T-33 Selaraskan dokumen dengan ketentuan baru** (PM/SA)
  - File: `AGENTS.md`, `README.md`, `docs/prd.md`, `docs/architecture.md`, `docs/techstack.md`, `docs/rules.md`, `docs/design.md`, `docs/plan.md`, `docs/task.md`, `docs/keputusan.md`
  - Kerjakan: perbarui dokumen agar konsisten dengan keputusan dosen dan tim: penyimpanan aplikasi memakai MariaDB; tiap anggota memakai database lokal dengan struktur/data awal yang disamakan; tampilan memakai Tailwind CSS melalui CDN; proyek boleh selesai lebih awal tetapi tidak melewati tenggat; progres dipresentasikan setiap minggu; tema proyek adalah LouMart. JSON tetap digunakan sebagai format komunikasi API jika sesuai kontrak. Jangan membahas atau menetapkan kerangka FSD pada task ini.
  - Perbarui daftar task dan rencana agar langkah implementasi MariaDB dan Tailwind tercatat sebagai pekerjaan lanjutan yang terpisah; jangan mengubah kode aplikasi dalam task ini.
  - Selesai bila: tidak ada aturan aktif yang masih mewajibkan penyimpanan aplikasi di file JSON atau penggunaan Bootstrap; dokumen menjelaskan bahwa JSON API berbeda dari penyimpanan database; panduan database lokal tidak meminta kredensial dimasukkan ke GitHub; serta rujukan dan urutan task antar dokumen konsisten.

## Fase 5: Migrasi teknologi (implementasi lanjutan)

- [x] **T-34 Persiapan MariaDB lokal** (BE)
  - File: `app/Core/Database.php`, `config/database.example.php`, `config/database.local.php`, `database/schema.sql`, `database/seed.sql`, `.gitignore`
  - Kerjakan: siapkan koneksi PDO ke MariaDB, skema tabel produk/pesanan/detail pesanan, data awal bersama, dan konfigurasi lokal yang dikecualikan dari Git.
  - Selesai bila: anggota dapat membuat database lokal dengan struktur/data awal yang sama; kredensial lokal tidak dilacak Git; koneksi database dapat diuji.

- [x] **T-35 Migrasi penyimpanan Model ke MariaDB** (BE)
  - File: `app/Core/JsonStore.php`, `app/Models/Produk.php`, `app/Models/Pesanan.php`, `config/routes.php`, `tests/run.php`
  - Butuh: T-34.
  - Kerjakan: pindahkan akses data Model dari JsonStore ke PDO, perbarui `config/routes.php` agar memakai koneksi MariaDB, gunakan query berparameter, dan transaksi database untuk perubahan beberapa tabel. Pertahankan bentuk response API.
  - Selesai bila: operasi Model lulus uji dengan tabel sementara MariaDB; uji tidak mengubah data kerja; tidak ada data produk/pesanan aplikasi yang dibaca atau ditulis ke file JSON.

- [ ] **T-36 Migrasi tampilan ke Tailwind CDN** (FE1)
  - File: `app/Views/layouts/main.php`, `app/Views/`, `public/assets/css/style.css`
  - Butuh: T-33.
  - Kerjakan: muat Tailwind melalui CDN, hapus Bootstrap dari layout dan View, lalu sesuaikan tampilan dengan token serta utility class yang dijelaskan di `design.md`.
  - Selesai bila: halaman tidak memuat Bootstrap; tampilan menggunakan Tailwind dan tetap responsif; tidak ada atribut `style=` inline atau tag `<style>` di View.

## Bug dan catatan tambahan

(isi di sini saat pengujian)
