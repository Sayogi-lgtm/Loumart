  # Daftar Task LouMart

  Cara memakai: kerjakan satu task per sesi. Centang `[x]` setelah semua poin "Selesai bila" terpenuhi.
  Peran: **BE** = Backend/Logic, **FE1** = Frontend 1 (HTML/Bootstrap), **FE2** = Frontend 2 (JS), **PM**, **SA**.
  Acuan: `architecture.md` (disingkat **ARS**), `prd.md`, `rules.md`.

  ## Fase 0: Fondasi

  - `[x]` **T-01 Kerangka proyek** (PM/BE)
    - File: struktur folder ARS bagian 4 (folder kosong boleh pakai `.gitkeep`), `public/index.php`, `public/.htaccess`, `.gitignore`
    - Kerjakan: buat folder, `spl_autoload_register` untuk namespace `App\` ke `app/`, `index.php` memuat autoload lalu menjalankan Router (sementara balas teks "LouMart jalan").
    - Selesai bila: `php -S localhost:8000 -t public` lalu `http://localhost:8000` menampilkan teks tersebut.

  - [x] **T-02 Core: Request, Response, Router** (BE)
    - File: `app/Core/Request.php`, `Response.php`, `Router.php`, `config/routes.php`, `public/index.php`
    - Kerjakan: Router mencocokkan metode + path (termasuk `{id}`), `Response::json($data, $kode)`, `Request` membaca body JSON dan query. Route tidak ditemukan membalas 404 (JSON untuk `/api/...`).
    - Selesai bila: route contoh `GET /api/ping` membalas `{"sukses":true,"data":"pong"}`; JSON rusak membalas 400; path ngawur membalas 404.

  - [ ] **T-03 Core: JsonStore** (BE)
    - File: `app/Core/JsonStore.php`
    - Kerjakan: `baca($file)`, `tulis($file, $array)` dengan `flock` + file sementara + `rename`, dan metode untuk menjalankan beberapa perubahan dalam satu kunci (ARS bagian 8). File hilang/kosong dianggap `[]`.
    - Selesai bila: tes kecil di `tests/run.php` menulis lalu membaca ulang data identik dan file hilang menghasilkan `[]`.

  - [ ] **T-04 Konfigurasi dan data contoh** (BE)
    - File: `config/app.php`, `data/produk.json`, `data/pesanan.json`
    - Kerjakan: isi konfigurasi sesuai ARS 9.1; buat 8 sampai 10 produk contoh (beberapa kategori, ada satu stok 0, harga bervariasi agar subtotal bisa di bawah dan di atas Rp500.000); `pesanan.json` berisi `[]`.
    - Selesai bila: kedua file adalah JSON valid dan sesuai format ARS bagian 8.

  ## Fase 1: Logika dan API (Backend)

  - [ ] **T-05 HitungService** (BE)
    - File: `app/Services/HitungService.php`, `tests/run.php`
    - Kerjakan: rumus ARS 9.2 dengan bilangan bulat dan nilai dari config.
    - Selesai bila: skenario uji PRD 1 (400.000 -> 444.000) dan 2 (600.000 -> 599.400) lolos, ditambah kasus tepat 500.000 (diskon 10%) dan 499.999 (diskon 0).

  - [ ] **T-06 ValidasiService** (BE)
    - File: `app/Services/ValidasiService.php`, `tests/run.php`
    - Kerjakan: VAL-01 sampai VAL-07 sesuai ARS 9.3 dan format error ARS 7.1.
    - Selesai bila: ada tes untuk tiap aturan (keranjang kosong, qty 0/negatif/desimal/teks, qty > stok, produk tidak ada, nama kosong, kontak salah, alamat kosong) dan satu kasus semua valid.

  - [ ] **T-07 Model Produk** (BE)
    - File: `app/Models/Produk.php`
    - Kerjakan: `semua($q, $kategori)`, `cari($id)`, `tambah`, `ubah`, `hapus`, `kurangiStok`, `kembalikanStok`; ID berurutan.
    - Selesai bila: tes menambah, mengubah, menghapus, dan mengurangi stok memberi data JSON yang benar (pakai file data sementara untuk tes, bukan data tim).

  - [ ] **T-08 Model Pesanan** (BE)
    - File: `app/Models/Pesanan.php`
    - Kerjakan: `semua($status)`, `cari($id)`, `buatIdBaru()` (`ORD-001` dst), `simpan`, `ubahStatus`.
    - Selesai bila: tes membuat dua pesanan berturut-turut menghasilkan `ORD-001` dan `ORD-002`.

  - [ ] **T-09 API Produk: baca** (BE)
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

  - [ ] **T-14 Layout dan routing halaman** (FE1, dibantu BE untuk route)
    - File: `app/Core/View.php`, `app/Controllers/PageController.php`, `app/Views/layouts/main.php`, `public/assets/css/style.css`, `config/routes.php`
    - Kerjakan: kelas View yang memuat layout + isi halaman, navbar dan footer sesuai `design.md`, enam route halaman ARS bagian 6 yang menampilkan kerangka kosong. Layout memuat Bootstrap 5.3.3 (CSS di `<head>`, `bootstrap.bundle.min.js` di akhir `<body>`) lalu `style.css`; tanpa `style=` inline.
    - Selesai bila: keenam URL halaman terbuka dengan navbar yang sama dan responsif di lebar HP.

  - [ ] **T-15 View Katalog** (FE1) - File: `app/Views/katalog.php`. Kerangka: kolom cari, filter kategori, wadah grid produk, spinner. Selesai bila tata letak sesuai `design.md` 5.1 dengan data contoh statis.
  - [ ] **T-16 View Keranjang** (FE1) - File: `app/Views/keranjang.php`. Selesai bila sesuai `design.md` 5.2 termasuk keadaan kosong.
  - [ ] **T-17 View Checkout** (FE1) - File: `app/Views/checkout.php`. Form, tempat pesan error per kolom, panel rincian biaya. Selesai bila sesuai `design.md` 5.3.
  - [ ] **T-18 View Status Pesanan** (FE1) - File: `app/Views/status-pesanan.php`. Selesai bila sesuai `design.md` 5.4 (ID pesanan dibaca dari URL).
  - [ ] **T-19 View Admin Produk** (FE1) - File: `app/Views/admin/produk.php`. Tabel + modal form. Selesai bila sesuai `design.md` 5.5.
  - [ ] **T-20 View Admin Transaksi** (FE1) - File: `app/Views/admin/transaksi.php`. Selesai bila sesuai `design.md` 5.6.

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
    - Selesai bila alur Katalog sampai Status Pesanan dan kedua halaman admin berjalan tanpa error di konsol.

  - [ ] **T-30 README dan bahan laporan** (PM/SA)
    - File: `README.md`
    - Kerjakan: cara menjalankan, struktur singkat, anggota, tangkapan layar; sinkronkan diagram di `docs/` dengan hasil akhir.
    - Selesai bila orang baru bisa menjalankan proyek hanya dari README.

  ## Opsional

  - [ ] **T-31 Filter kategori di katalog** (FR-03) (FE1+FE2) - Pastikan filter kategori berfungsi di tampilan; endpoint sudah mendukung `kategori`.
  - [ ] **T-32 Login admin sederhana** (BE+FE1) - Hanya jika dosen meminta: sesi PHP, halaman login, proteksi route `/admin/*` dan endpoint admin. Putuskan cara menyimpan kredensial bersama tim dulu.

  ## Bug dan catatan tambahan

  (isi di sini saat pengujian)
