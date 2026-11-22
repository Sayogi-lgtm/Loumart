# Panduan Desain UI/UX LouMart

Dokumen ini adalah acuan tampilan untuk Frontend 1 dan 2. **UI/UX Designer** memiliki dokumen ini
dan boleh mengganti isinya. Sumber utama adalah desain final di Wonder (file "LouMart", 6 layar
laptop). Bagian bertanda `[BELUM DIDESAIN]` belum ada di Wonder; kerjakan mengikuti deskripsinya
dan samakan gayanya dengan layar yang sudah ada.

## 1. Prinsip

- Bahasa Indonesia, nada ramah dan singkat.
- Checkout maksimal **4 langkah**: Katalog, Keranjang, Checkout, Ringkasan/Status (NFR-02).
  Langkah ini ditampilkan sebagai stepper di header halaman.
- Responsif untuk HP dan laptop (NFR-01) memakai Bootstrap 5.3.3 untuk grid dan komponen,
  dengan CSS kustom eksternal di `public/assets/css/style.css`. Rancang untuk layar HP dulu.
- Pesan kesalahan memakai teks dari tabel validasi di `prd.md` bagian 7.4.
- Semua gaya ada di file eksternal `public/assets/css/style.css` (tanpa CSS inline).
  Warna, font, dan sudut diatur lewat variabel CSS di `:root` agar mudah diganti satu tempat.

## 2. Gaya visual

Tema hangat berwarna oranye. Nama token sama dengan token di Wonder.

### 2.1 Warna

| Token Wonder | Variabel CSS | Nilai | Dipakai untuk |
|---|---|---|---|
| brand/primary | `--brand-primary` | `#c2410c` | Header halaman, tombol utama, tautan aksi, harga penting |
| brand/primary-hover | `--brand-primary-hover` | `#9a3412` | Tombol utama saat disorot |
| brand/accent | `--brand-accent` | `#f59e0b` | Kartu promo diskon |
| brand/soft | `--brand-soft` | `#ffedd5` | Menu aktif, kotak ikon, kotak info |
| surface/page | `--surface-page` | `#fff8ee` | Latar halaman |
| surface/card | `--surface-card` | `#ffffff` | Kartu, navbar, panel |
| surface/sunken | `--surface-sunken` | `#fbefdd` | Kotak gambar produk (selang-seling dengan brand/soft), tombol nonaktif |
| content/primary | `--content-primary` | `#3b2416` | Teks utama |
| content/secondary | `--content-secondary` | `#6b5444` | Teks pendukung, label |
| content/on-brand | `--content-on-brand` | `#ffffff` | Teks di atas warna utama |
| border/default | `--border-default` | `#ebdcc8` | Garis kartu, input, pemisah |

### 2.2 Warna status

| Status | Latar | Teks | Ikon |
|---|---|---|---|
| Menunggu Pembayaran | `--status-warning-bg` `#fef3c7` | `--status-warning-text` `#92400e` | jam |
| Lunas | `--status-success-bg` `#dcfce7` | `--status-success-text` `#166534` | centang dalam lingkaran |
| Dibatalkan | `--status-danger-bg` `#fee2e2` | `--status-danger-text` `#991b1b` | silang dalam lingkaran |

Status tidak hanya dibedakan warna: selalu ada ikon dan teks. Warna yang sama dipakai untuk
stok rendah (kuning), stok habis (merah), dan pesan error (merah).

### 2.3 Font

| Token | Variabel CSS | Nilai | Dipakai untuk |
|---|---|---|---|
| font/heading | `--font-heading` | Plus Jakarta Sans | Judul, nama produk, harga, angka besar |
| font/body | `--font-body` | DM Sans | Teks isi, label, tombol |

Muat kedua font dari Google Fonts di `<head>` dan beri cadangan `system-ui, sans-serif`.

### 2.4 Sudut

| Token | Variabel CSS | Nilai | Dipakai untuk |
|---|---|---|---|
| radius/sm | `--radius-sm` | 8px | Kotak ikon kecil |
| radius/md | `--radius-md` | 12px | Input, kartu item, kotak info |
| radius/lg | `--radius-lg` | 16px | Kartu produk dan kartu besar |
| radius/xl | `--radius-xl` | 24px | Panel ringkasan, kartu promo |

Tombol, chip kategori, kolom cari, badge status, dan stepper qty berbentuk pil
(`border-radius: 999px`).

### 2.5 Blok variabel untuk `style.css`

```css
:root {
  --brand-primary: #c2410c;
  --brand-primary-hover: #9a3412;
  --brand-accent: #f59e0b;
  --brand-soft: #ffedd5;
  --surface-page: #fff8ee;
  --surface-card: #ffffff;
  --surface-sunken: #fbefdd;
  --content-primary: #3b2416;
  --content-secondary: #6b5444;
  --content-on-brand: #ffffff;
  --border-default: #ebdcc8;
  --status-warning-bg: #fef3c7;
  --status-warning-text: #92400e;
  --status-success-bg: #dcfce7;
  --status-success-text: #166534;
  --status-danger-bg: #fee2e2;
  --status-danger-text: #991b1b;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --font-heading: "Plus Jakarta Sans", system-ui, sans-serif;
  --font-body: "DM Sans", system-ui, sans-serif;
}
```

### 2.6 Responsif `[SEMENTARA]`

Versi HP belum didesain di Wonder. Nilai awal agar pengerjaan tidak tertahan:

| Lebar layar | Grid katalog | Catatan |
|---|---|---|
| HP (bawah 768px) | 2 kolom | Panel ringkasan pindah ke bawah daftar; navbar menjadi ringkas |
| Tablet (768px ke atas) | 3 kolom | |
| Laptop (1024px ke atas) | 4 kolom | Sesuai desain Wonder (lebar 1440px) |

## 3. Format tampilan

- Uang: `Rp599.400` (titik ribuan, tanpa desimal).
- Uang singkat `Rp11,1 jt` hanya untuk judul dan label grafik di dashboard admin.
- Tanggal: `18 November 2026`. Di tabel admin boleh singkat: `20 Nov 2026`.
- ID pesanan: `ORD-001` (huruf besar, tebal, mudah disalin).
- Stok: lebih dari 10 tampil `Stok N`; 10 atau kurang tampil `Sisa N` (merah di katalog, chip kuning di admin).
- Stok 0: tombol tambah ke keranjang nonaktif dan tampil label "Stok habis" di gambar serta
  teks "Tidak tersedia".
- Gambar produk: di desain memakai ikon pada kotak berwarna. Jika produk punya foto, tampilkan foto;
  jika tidak ada atau gagal dimuat, tampilkan ikon pengganti seperti desain.

## 4. Komponen bersama

- **Navbar** (putih): kiri logo (ikon keranjang di kotak oranye membulat + teks "LouMart");
  tengah tautan "Katalog" dan "Keranjang" (ikon + badge jumlah item); kanan tautan kecil "Admin"
  dengan ikon roda gigi menuju `/admin/produk`. Menu aktif berbentuk pil `--brand-soft` dengan teks
  `--brand-primary`.
- **Header halaman** (khusus Keranjang, Checkout, Status): pita `--brand-primary`, judul besar
  putih dengan satu baris keterangan, dan **stepper** di kanan.
- **Stepper 4 langkah** (Katalog, Keranjang, Checkout, Status): langkah selesai = lingkaran
  `--brand-soft` berisi centang; langkah aktif = lingkaran putih berisi angka dan label tebal;
  langkah belum = lingkaran garis putih.
- **Kartu**: latar putih, garis `--border-default`, sudut `--radius-lg` atau `--radius-xl`.
- **Tombol utama**: pil `--brand-primary`, teks putih, ikon panah atau centang bila perlu.
  **Tombol sekunder**: pil garis `--brand-primary` dengan teks oranye.
- **Chip kategori**: pil putih bergaris; chip aktif berisi `--brand-primary` dengan teks putih.
- **Kotak info**: latar `--brand-soft`, ikon info di kiri, teks kecil.
- **Badge status**: pil sesuai bagian 2.2.
- **Stepper qty**: pil berisi tombol "−", angka, dan "+" (tombol ditandai agar mudah disentuh).
- **Toast/alert** untuk pesan sukses dan error. `[BELUM DIDESAIN]`
- **Keadaan halaman** `[BELUM DIDESAIN]`: loading (spinner), kosong (teks + tombol aksi),
  error (alert + tombol coba lagi).

## 5. Halaman

### 5.1 Katalog (`/`)
- Navbar, lalu **hero** `--brand-primary`:
  - Judul "Mau belanja apa hari ini?" dan keterangan "Pilih produk, total dihitung otomatis, dan
    pembayaran dikonfirmasi admin."
  - Kolom cari pil putih dengan ikon kaca pembesar dan placeholder "Cari nama produk...".
  - Di kanan, kartu promo `--brand-accent` sedikit miring: label "PROMO BELANJA", "Diskon 10%",
    "Otomatis untuk subtotal Rp500.000 atau lebih."
- Baris **chip kategori** (FR-03): Semua, Pakaian, Aksesori, Makanan & Minuman, Rumah Tangga,
  Sepatu, ATK.
- Judul "Semua produk" (berubah mengikuti kategori) dan jumlah produk di kanan ("8 produk").
- **Kartu produk**: kotak gambar, kategori (kecil), nama, harga tebal, info stok, dan tombol bulat
  "+" di kanan bawah. Tidak ada teks "Tambah ke keranjang"; beri `aria-label` pada tombol.
- Grid: 2 kolom di HP, 3 di tablet, 4 di laptop.
- Setelah menambah: toast "Ditambahkan ke keranjang" dan badge navbar bertambah.

### 5.2 Keranjang (`/keranjang`)
- Header "Keranjang belanja" dan keterangan "N produk siap di-checkout". Stepper di langkah 2.
- Kiri: **kartu per item**: kotak ikon, kategori, nama, "Rp75.000 per buah · Stok tersedia 20",
  stepper qty, subtotal item, dan tombol hapus bulat (ikon tempat sampah).
- Tautan "← Lanjut belanja" di bawah daftar.
- Kanan: kartu **Ringkasan keranjang**: "Total sementara" dengan angka besar; kotak info
  "Belum termasuk diskon dan pajak 11%. Rincian lengkap muncul di langkah Checkout."; tombol
  "Checkout →"; catatan kecil "Stok dicek ulang saat pesanan dibuat."
- Keranjang kosong `[BELUM DIDESAIN]`: pesan "Keranjang masih kosong" + tombol ke katalog.
- Ide tambahan (opsional): petunjuk "Tambah Rp X lagi untuk diskon 10%" di ringkasan.

### 5.3 Checkout (`/checkout`)
- Header "Checkout" dan keterangan "Isi data pemesan, lalu buat pesanan". Stepper di langkah 3.
- Kiri: kartu **Data pemesan** ("Tanpa akun. Pesananmu dilacak lewat ID pesanan.") berisi
  kolom Nama lengkap, Kontak (nomor telepon), dan Alamat (textarea). Tautan "← Kembali ke
  keranjang" di bawah.
- **Kondisi error** pada kolom: latar `--status-danger-bg`, garis `--status-danger-text`, dan di
  bawahnya ikon + pesan (contoh: "Kontak tidak valid").
- Kanan: kartu **Rincian pesanan**: daftar item ("1 × Rp75.000" dan subtotal), Subtotal,
  Diskon (dengan persen), Pajak (11%), lalu "Total pembayaran" dengan angka besar. Data dari
  `POST /api/pesanan/hitung`.
- Kotak info "Pembayaran dikonfirmasi oleh admin setelah pesanan dibuat." dan tombol
  "✓ Buat pesanan" (nonaktif saat memproses agar tidak terkirim dua kali).
- Jika stok kurang `[BELUM DIDESAIN]`: pesan "Stok tidak mencukupi" dan arahkan kembali ke keranjang.

### 5.4 Status Pesanan (`/pesanan/{id}`)
- Header berikon centang dalam lingkaran putih: "Pesanan berhasil dibuat" dan keterangan
  "Simpan ID pesananmu untuk melihat status kapan saja." Stepper di langkah 4.
- Kartu atas: label "ID PESANAN", ID besar (contoh `ORD-001`) dengan tombol "Salin ID";
  label "STATUS" dengan badge status, dan "Dibuat 20 November 2026". Di bawahnya kotak info
  "Pembayaran dikonfirmasi oleh admin. Buka halaman ini lagi kapan saja untuk melihat status terbaru."
- Kartu "Item pesanan" (ikon, nama, "1 × Rp75.000", subtotal) dan kartu "Data pemesan"
  (Nama, Kontak, Alamat).
- Kanan: kartu "Rincian biaya" (Subtotal, Diskon, Pajak, Total pembayaran) dan tombol
  sekunder "Belanja lagi".
- Cara membuka pesanan lama lewat ID (misalnya kolom "Cek pesanan" di navbar atau katalog)
  `[BELUM DIDESAIN]`.

### 5.5 Admin: Dashboard (opsional; belum termasuk route dan task implementasi)

Bagian ini adalah rancangan tambahan untuk FR-17, bukan halaman wajib pada cakupan implementasi
saat ini. Route `/admin` dan task implementasinya belum tercantum di `architecture.md` maupun
`task.md`. Jika dashboard akan dikerjakan, sepakati dan tambahkan route serta task terlebih dahulu.

Layout admin dipakai bersama oleh semua halaman admin:
- **Sidebar** kiri: logo + "Panel admin"; label "KELOLA TOKO"; menu Dashboard, Transaksi
  (badge jumlah pesanan Menunggu Pembayaran), dan Produk; tombol "Lihat toko" di bawah.
- **Topbar**: judul halaman dengan keterangan (tanggal atau ringkasan), kolom cari, ikon lonceng,
  dan avatar. Lonceng dan avatar belum punya fungsi di PRD; tampilkan sebagai hiasan atau hapus.

Isi Dashboard penjualan:
- Empat **kartu angka**: Total pesanan, Pendapatan (Lunas), Menunggu Pembayaran
  ("Perlu dicek admin"), Dibatalkan.
- Kartu **Pendapatan 7 hari terakhir**: grafik batang (hanya pesanan Lunas); batang tertinggi
  memakai `--brand-primary`, lainnya `--brand-soft`.
- Kartu **Status pesanan**: bar bertingkat hijau, kuning, merah dan tiga baris Lunas,
  Menunggu Pembayaran, Dibatalkan beserta jumlah dan persentasenya.
- Tabel **Pesanan terbaru**: ID, Pemesan (+ tanggal), Total, Status, Aksi. Pesanan Menunggu
  Pembayaran punya tombol "Lunas" dan "Batalkan"; pesanan final tampil teks "Status final".
  Tautan "Semua transaksi →" di kanan atas.
- Kartu **Stok perlu diisi**: daftar produk dengan stok 10 atau kurang (chip "Stok habis" atau
  "Sisa N") dan tombol "Kelola produk".
- Ringkasan penjualan (FR-17, tambahan) sekarang ada di dashboard ini.

### 5.6 Admin: Produk (`/admin/produk`)
- Topbar "Produk" dengan keterangan "8 produk di katalog, 1 stok habis".
- Baris chip kategori di kiri dan tombol "+ Tambah produk" di kanan.
- Tabel dalam kartu: Produk (ikon + nama + "ID N"), Kategori, Harga (rata kanan), Stok, Aksi.
  Stok biasa tampil angka; 10 atau kurang chip kuning "Sisa N"; 0 chip merah "Stok habis".
  Aksi: tombol "Ubah" (garis oranye) dan "Hapus" (latar `--status-danger-bg`).
- Footer tabel: "Menampilkan 8 dari 8 produk" dan keterangan warna stok.
- Form Tambah/Ubah produk (modal) `[BELUM DIDESAIN]`: kolom nama, kategori, harga, stok, gambar.
- Dialog konfirmasi Hapus `[BELUM DIDESAIN]`.

### 5.7 Admin: Transaksi (`/admin/transaksi`) `[BELUM DIDESAIN]`
- Tabel pesanan: ID, tanggal, pemesan, total, badge status, aksi.
- Pesanan "Menunggu Pembayaran": tombol Lunas dan Batalkan, **dengan dialog konfirmasi**
  (status final dan pembatalan mengembalikan stok). Pesanan final: teks "Status final".
- Filter status (opsional). Gaya mengikuti tabel "Pesanan terbaru" di dashboard.

## 6. Alur navigasi

```text
Katalog -> Keranjang -> Checkout -> Status Pesanan
   ^                         |
   +---- (stok kurang) ------+

Admin: Produk <-> Transaksi (lewat navigasi admin)
Navbar pembeli: tautan Admin (kecil) -> `/admin/produk`
Admin: tombol "Lihat toko" -> Katalog
```

## 7. Aksesibilitas dasar

- Setiap input punya `label`; gambar punya `alt`; tombol ikon (tambah, hapus, salin) punya `aria-label`.
- Kontras teks memadai; status dibedakan warna, ikon, dan teks.
- Tombol aksi cukup besar untuk disentuh di HP.
- Pesan error tampil di dekat kolom yang salah dan memakai ikon, bukan hanya warna merah.

## 8. Yang diserahkan UI/UX Designer

Sudah ada di Wonder: 6 layar laptop (Katalog, Keranjang, Checkout, Status Pesanan, Admin
Dashboard, Admin Produk), token warna/font/sudut, dan alur navigasi. Dashboard Admin merupakan
desain opsional dan belum termasuk route atau task implementasi saat ini.

Belum ada: halaman Admin Transaksi, modal produk dan dialog konfirmasi, keadaan kosong/loading/error,
toast, versi HP, dan pintu masuk "cek pesanan lewat ID". Setelah ini dibuat, perbarui bagian 4 dan 5
di sini dan hapus tanda `[BELUM DIDESAIN]`-nya.
