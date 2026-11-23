# Daftar Task LouMart

Cara memakai: kerjakan satu task per sesi. Centang `[x]` setelah semua poin "Selesai bila" terpenuhi.
Peran: **BE** = Backend/Logic, **FE1** = Frontend 1 (HTML/Bootstrap), **FE2** = Frontend 2 (JS), **PM**, **SA** = Sistem Analis, **UIUX** = UI/UX.
Acuan: `architecture.md` (disingkat **ARS**), `prd.md`, `rules.md`.
Pola prompt untuk mendelegasikan task ke agen: lihat `docs/prompt-delegasi.md`.

Aturan fase A dan U: hasil ditulis ke file baru yang tercantum di "File". Dokumen acuan (prd, architecture, rules, design) tidak boleh ditimpa langsung; perubahan diajukan sebagai usulan di file hasil, lalu disetujui PM sebelum diterapkan.

Urutan pengerjaan yang disarankan:
1. Fase 0 dan A-01, A-02 dapat berjalan bersamaan.
2. U-01, lalu U-03 sampai U-06, berjalan bersamaan dengan Fase 1 (backend); keduanya tidak saling menunggu.
3. A-03 sampai A-05 setelah A-01 dan A-02; U-07 setelah U-01, U-03, U-04, U-05.
4. Fase 2A dimulai setelah U-01, U-02, U-03 selesai; bagian responsif menyusul U-05.
5. Fase 2B, lalu Fase 3 bersama A-06 dan U-08.

## Fase 0: Fondasi

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

- [ ] **A-02 Log asumsi dan hal terbuka** (SA)
  - File: `docs/keputusan.md`
  - Kerjakan: buat tabel Keputusan dengan kolom: No, Hal, Status (Terbuka/Diputuskan), Pemutus, Tanggal, Dampak ke task. Isi minimal: (1) aturan diskon 10% untuk subtotal >= Rp500.000 belum dikonfirmasi dosen; (2) login admin (T-32) belum diputuskan; (3) filter kategori berstatus Tambahan; (4) gambar `img/usecase.png` dan `img/flowchart-checkout.png` disebut di PRD tetapi belum ada; (5) apakah desain tablet dan HP harus selesai sebelum FE1 mulai; (6) cara halaman Status Pesanan membedakan "baru dibuat" dan "dibuka ulang" untuk judul header; (7) baris diskon selalu tampil di rincian biaya walau 0% (sudah diputuskan: tampil).
  - Selesai bila: tujuh hal di atas tercatat dengan status benar; tiap hal Terbuka menyebut task mana yang terhambat.

- [ ] **A-03 Matriks keterlacakan** (SA)
  - File: `docs/keterlacakan.md`
  - Kerjakan: tabel semua FR dan NFR di `prd.md` dengan kolom: ID, ringkasan, task T-xx yang mengimplementasikan, skenario uji PRD bagian 17 yang menguji, status.
  - Butuh: A-01.
  - Selesai bila: setiap FR/NFR punya baris; yang belum punya task atau skenario uji ditandai "BELUM ADA" (jangan mengarang task atau skenario).

- [ ] **A-04 Daftar pertanyaan untuk dosen** (SA)
  - File: `docs/pertanyaan-dosen.md`
  - Kerjakan: ubah semua hal berstatus Terbuka di A-02 menjadi pertanyaan singkat yang bisa dijawab ya/tidak atau memilih satu opsi, lengkap dengan akibat tiap jawaban terhadap pekerjaan.
  - Butuh: A-02.
  - Selesai bila: satu pertanyaan per hal Terbuka, tiap pertanyaan maksimal tiga kalimat dan menyebut opsi jawabannya.

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
  - Kerjakan: tulis token berikut sebagai tabel dan sebagai blok `:root` siap salin ke `public/assets/css/style.css`, lengkap dengan pemetaan ke variabel Bootstrap 5.3.3 (mis. `--bs-primary`, `--bs-body-bg`, `--bs-body-color`, `--bs-border-radius`).
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
  - File: `docs/design/*.png`, `docs/design.md` bagian 5.1 sampai 5.6
  - Kerjakan: ekspor tiap artboard sebagai PNG (nama: `katalog-laptop.png`, `katalog-hp.png`, dst.); di `design.md` tulis tiap halaman: susunan dari atas ke bawah, ukuran dan jarak penting, token yang dipakai, komponen Bootstrap yang disarankan, dan daftar kondisi (dari U-04). Acuan gambar dengan nama file.
  - Butuh: U-01, U-03, U-04, U-05.
  - Selesai bila: setiap bagian 5.x memuat nilai pasti (bukan hanya "oranye" atau "besar") dan merujuk PNG yang ada di `docs/design/`.

- [ ] **U-08 Tinjau kesesuaian tampilan** (UIUX)
  - File: `docs/review-ui.md`
  - Kerjakan: bandingkan hasil HTML (T-15 sampai T-20) di lebar HP, tablet, dan laptop dengan PNG di `docs/design/`; catat selisih (warna, jarak, font, kondisi yang terlewat) sebagai daftar bug.
  - Butuh: T-20, T-27.
  - Selesai bila: setiap halaman diperiksa di tiga lebar dan tiap selisih menyebut file view yang perlu diubah.

- [ ] **U-09 Halaman login admin** (UIUX, hanya jika T-32 jadi)
  - File: Wonder, `docs/design.md`
  - Butuh: keputusan A-02 nomor 2.
  - Selesai bila: tampilan login, error kredensial salah, dan tombol keluar di sidebar admin tersedia.

## Fase 1: Logika dan API (Backend)

- [x] **T-05 HitungService** (BE)
  - File: `app/Services/HitungService.php`, `tests/run.php`
  - Kerjakan: rumus ARS 9.2 dengan bilangan bulat dan nilai dari config.
  - Selesai bila: skenario uji PRD 1 (400.000 -> 444.000) dan 2 (600.000 -> 599.400) lolos, ditambah kasus tepat 500.000 (diskon 10%) dan 499.999 (diskon 0).

- [x] **T-06 ValidasiService** (BE)
  - File: `app/Services/ValidasiService.php`, `tests/run.php`
  - Kerjakan: VAL-01 sampai VAL-07 sesuai ARS 9.3 dan format error ARS 7.1.
  - Selesai bila: ada tes untuk tiap aturan (keranjang kosong, qty 0/negatif/desimal/teks, qty > stok, produk tidak ada, nama kosong, kontak salah, alamat kosong) dan satu kasus semua valid.

- [x] **T-07 Model Produk** (BE)
  - File: `app/Models/Produk.php`
  - Kerjakan: `semua($q, $kategori)`, `cari($id)`, `tambah`, `ubah`, `hapus`, `kurangiStok`, `kembalikanStok`; ID berurutan.
  - Selesai bila: tes menambah, mengubah, menghapus, dan mengurangi stok memberi data JSON yang benar (pakai file data sementara untuk tes, bukan data tim).

- [x] **T-08 Model Pesanan** (BE)
  - File: `app/Models/Pesanan.php`
  - Kerjakan: `semua($status)`, `cari($id)`, `buatIdBaru()` (`ORD-001` dst), `simpan`, `ubahStatus`.
  - Selesai bila: tes membuat dua pesanan berturut-turut menghasilkan `ORD-001` dan `ORD-002`.

- [x] **T-09 API Produk: baca** (BE)
  - File: `app/Controllers/ProdukApiController.php`, `config/routes.php`
  - Kerjakan: `GET /api/produk` (parameter `q` cari nama tanpa membedakan huruf besar kecil, `kategori`) dan `GET /api/produk/{id}` (404 jika tidak ada).
  - Selesai bila: pencarian "kaos" dan filter kategori memberi hasil benar; id ngawur memberi 404.

- [ ] **T-10 API Produk: admin** (BE)
  - File: `app/Controllers/ProdukApiController.php`, `config/routes.php`
  - Kerjakan: `POST`, `PUT`, `DELETE` dengan validasi produk (ARS 7.3), 201/200/404/422.
  - Selesai bila: tambah, ubah, hapus bekerja dan data produk tidak valid (harga negatif, nama kosong) ditolak 422.

- [ ] **T-11 API Pesanan: hitung dan buat** (BE)
  - File: `app/Services/PesananService.php`, `app/Controllers/PesananApiController.php`, `config/routes.php`
  - Kerjakan: `POST /api/pesanan/hitung` dan `POST /api/pesanan` sesuai alur ARS bagian 3: validasi, ambil harga dari server, hitung, simpan pesanan + kurangi stok dalam satu kunci, status awal "Menunggu Pembayaran", tanggal hari ini.
  - Selesai bila: skenario uji PRD 3, 4, 5, 6 lolos di `tests/run.php`; kegagalan validasi tidak mengubah stok.

- [ ] **T-12 API Pesanan: baca dan ubah status** (BE)
  - File: `app/Services/PesananService.php`, `app/Controllers/PesananApiController.php`, `config/routes.php`
  - Kerjakan: `GET /api/pesanan?status=`, `GET /api/pesanan/{id}`, `PUT /api/pesanan/{id}/status`. Lunas/Dibatalkan final (409). Dibatalkan mengembalikan stok.
  - Selesai bila: skenario uji PRD 7 dan 8 lolos; mengubah status pesanan yang sudah final memberi 409; membatalkan menambah stok kembali.

- [ ] **T-13 API Ringkasan** (BE, tambahan FR-17)
  - File: `app/Controllers/RingkasanApiController.php`, `config/routes.php`
  - Kerjakan: `GET /api/ringkasan` sesuai ARS 7.3; pendapatan hanya dari pesanan Lunas.
  - Selesai bila: dengan satu pesanan Lunas dan satu Dibatalkan, pendapatan hanya menghitung yang Lunas.

## Fase 2A: Tampilan (Frontend 1)

- [x] **T-14 Layout dan routing halaman** (FE1, dibantu BE untuk route)
  - File: `app/Core/View.php`, `app/Controllers/PageController.php`, `app/Views/layouts/main.php`, `public/assets/css/style.css`, `config/routes.php`
  - Kerjakan: kelas View yang memuat layout + isi halaman, navbar dan footer sesuai `design.md`, enam route halaman ARS bagian 6 yang menampilkan kerangka kosong. Layout memuat Bootstrap 5.3.3 (CSS di `<head>`, `bootstrap.bundle.min.js` di akhir `<body>`) lalu `style.css`; tanpa `style=` inline.
  - Selesai bila: keenam URL halaman terbuka dengan navbar yang sama dan responsif di lebar HP.

- [ ] **T-14b Terapkan token desain** (FE1)
  - File: `public/assets/css/style.css`, `app/Views/layouts/main.php`
  - Kerjakan: salin blok `:root` dari `design.md` (U-01), muat font Plus Jakarta Sans dan DM Sans, pasang logo dan favicon (U-06), ganti warna sementara.
  - Butuh: U-01, U-06.
  - Selesai bila: navbar dan warna dasar sesuai token; tanpa `style=` inline.

- [ ] **T-15 View Katalog** (FE1) - File: `app/Views/katalog.php`. Kerangka: kolom cari, filter kategori, wadah grid produk, spinner. Selesai bila tata letak sesuai `design.md` 5.1 dengan data contoh statis. Butuh T-14b, U-02, U-04. Responsif butuh U-05.
- [ ] **T-16 View Keranjang** (FE1) - File: `app/Views/keranjang.php`. Selesai bila sesuai `design.md` 5.2 termasuk keadaan kosong. Butuh T-14b, U-02, U-04 (keranjang kosong). Responsif butuh U-05.
- [ ] **T-17 View Checkout** (FE1) - File: `app/Views/checkout.php`. Form, tempat pesan error per kolom, panel rincian biaya. Selesai bila sesuai `design.md` 5.3. Butuh T-14b, U-02, U-04 (error per kolom, stok kurang, memproses). Responsif butuh U-05.
- [ ] **T-18 View Status Pesanan** (FE1) - File: `app/Views/status-pesanan.php`. Selesai bila sesuai `design.md` 5.4 (ID pesanan dibaca dari URL). Butuh T-14b, U-02, U-04 (Lunas, Dibatalkan, 404). Responsif butuh U-05.
- [ ] **T-19 View Admin Produk** (FE1) - File: `app/Views/admin/produk.php`. Tabel + modal form. Selesai bila sesuai `design.md` 5.5. Butuh T-14b, U-03, U-04. Responsif butuh U-05.
- [ ] **T-20 View Admin Transaksi** (FE1) - File: `app/Views/admin/transaksi.php`. Selesai bila sesuai `design.md` 5.6. Butuh T-14b, U-03, U-04. Responsif butuh U-05.

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
  - Kerjakan: pastikan delapan skenario PRD bagian 17 ada semuanya, memakai file data sementara, dan skrip keluar dengan kode tidak nol jika ada yang gagal.
  - Selesai bila `php tests/run.php` menampilkan semua lulus.

- [ ] **T-29 Uji manual dan perbaikan bug** (semua)
  - Kerjakan: jalankan alur lengkap di Chrome dan Firefox, di lebar HP dan laptop; catat bug sebagai task baru di bawah.
  - Butuh: A-06 (daftar uji) dan U-08 (tinjauan tampilan).
  - Selesai bila alur Katalog sampai Status Pesanan dan kedua halaman admin berjalan tanpa error di konsol.

- [ ] **T-30 README dan bahan laporan** (PM/SA)
  - File: `README.md`
  - Kerjakan: cara menjalankan, struktur singkat, anggota, tangkapan layar; sinkronkan diagram di `docs/` dengan hasil akhir.
  - Butuh: A-03 dan A-05 untuk bahan laporan.
  - Selesai bila orang baru bisa menjalankan proyek hanya dari README.

## Opsional

- [ ] **T-31 Filter kategori di katalog** (FR-03) (FE1+FE2) - Pastikan filter kategori berfungsi di tampilan; endpoint sudah mendukung `kategori`.
- [ ] **T-32 Login admin sederhana** (BE+FE1) - Hanya jika dosen meminta: sesi PHP, halaman login, proteksi route `/admin/*` dan endpoint admin. Putuskan cara menyimpan kredensial bersama tim dulu. Butuh keputusan A-02 nomor 2 dan U-09.

## Bug dan catatan tambahan

(isi di sini saat pengujian)
