<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Services/HitungService.php';
require_once __DIR__ . '/../app/Services/ValidasiService.php';
require_once __DIR__ . '/../app/Services/PesananService.php';
require_once __DIR__ . '/../app/Models/Produk.php';
require_once __DIR__ . '/../app/Models/Pesanan.php';

use App\Core\Database;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\HitungService;
use App\Services\PesananService;
use App\Services\ValidasiService;

$pdo = Database::koneksi();
$lulus = true;

try {
    // Tabel sementara melindungi data database kerja selama pengujian Model.
    $pdo->exec(
        'CREATE TEMPORARY TABLE produk (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(150) NOT NULL,
            kategori VARCHAR(80) NOT NULL,
            harga BIGINT UNSIGNED NOT NULL,
            stok INT UNSIGNED NOT NULL DEFAULT 0,
            gambar VARCHAR(255) NOT NULL DEFAULT ""
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TEMPORARY TABLE pesanan (
            id VARCHAR(20) NOT NULL PRIMARY KEY,
            pemesan VARCHAR(150) NOT NULL,
            kontak VARCHAR(30) NOT NULL,
            alamat TEXT NOT NULL,
            subtotal BIGINT UNSIGNED NOT NULL,
            diskon BIGINT UNSIGNED NOT NULL,
            pajak BIGINT UNSIGNED NOT NULL,
            total BIGINT UNSIGNED NOT NULL,
            status VARCHAR(30) NOT NULL,
            tanggal DATE NOT NULL
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TEMPORARY TABLE detail_pesanan (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            pesanan_id VARCHAR(20) NOT NULL,
            produk_id INT UNSIGNED NULL,
            nama_produk VARCHAR(150) NOT NULL,
            qty INT UNSIGNED NOT NULL,
            harga_satuan BIGINT UNSIGNED NOT NULL
        ) ENGINE=InnoDB'
    );

    $konfigurasi = require __DIR__ . '/../config/app.php';
    $hitungService = new HitungService($konfigurasi);
    $kasusHitung = [
        [
            'nama' => 'Subtotal Rp400.000',
            'items' => [['hargaSatuan' => 400000, 'qty' => 1]],
            'hasil' => [
                'subtotal' => 400000,
                'diskon' => 0,
                'persenDiskon' => 0,
                'setelahDiskon' => 400000,
                'pajak' => 44000,
                'total' => 444000,
            ],
        ],
        [
            'nama' => 'Subtotal Rp600.000',
            'items' => [['hargaSatuan' => 600000, 'qty' => 1]],
            'hasil' => [
                'subtotal' => 600000,
                'diskon' => 60000,
                'persenDiskon' => 10,
                'setelahDiskon' => 540000,
                'pajak' => 59400,
                'total' => 599400,
            ],
        ],
        [
            'nama' => 'Subtotal tepat Rp500.000',
            'items' => [['hargaSatuan' => 250000, 'qty' => 2]],
            'hasil' => [
                'subtotal' => 500000,
                'diskon' => 50000,
                'persenDiskon' => 10,
                'setelahDiskon' => 450000,
                'pajak' => 49500,
                'total' => 499500,
            ],
        ],
        [
            'nama' => 'Subtotal Rp499.999',
            'items' => [['hargaSatuan' => 499999, 'qty' => 1]],
            'hasil' => [
                'subtotal' => 499999,
                'diskon' => 0,
                'persenDiskon' => 0,
                'setelahDiskon' => 499999,
                'pajak' => 55000,
                'total' => 554999,
            ],
        ],
    ];

    foreach ($kasusHitung as $kasus) {
        if ($hitungService->hitung($kasus['items']) !== $kasus['hasil']) {
            $lulus = false;
            fwrite(STDERR, "GAGAL: {$kasus['nama']} tidak sesuai.\n");
        } else {
            fwrite(STDOUT, "LULUS: {$kasus['nama']}.\n");
        }
    }

    $validasiService = new ValidasiService($konfigurasi);
    $produkUji = [
        ['id' => 1, 'nama' => 'Kaos', 'stok' => 5],
        ['id' => 2, 'nama' => 'Tas', 'stok' => 10],
    ];
    $dataValid = [
        'items' => [['produkId' => 1, 'qty' => 2]],
        'pemesan' => 'Budi',
        'kontak' => '081234567890',
        'alamat' => 'Jl. Merdeka No. 1',
    ];
    $ujiValidasi = static function (
        string $nama,
        array $data,
        array $kodeDiharapkan,
        array $fieldDiharapkan = []
    ) use ($validasiService, $produkUji, &$lulus): void {
        $errors = $validasiService->validasi($data, $produkUji);
        $kodeAktual = array_column($errors, 'kode');
        $fieldAktual = array_column($errors, 'field');

        if (
            $kodeAktual !== $kodeDiharapkan
            || ($fieldDiharapkan !== [] && $fieldAktual !== $fieldDiharapkan)
        ) {
            $lulus = false;
            fwrite(STDERR, "GAGAL: {$nama} tidak menghasilkan error yang diharapkan.\n");
        } else {
            fwrite(STDOUT, "LULUS: {$nama}.\n");
        }
    };

    $dataUji = $dataValid;
    $dataUji['items'] = [];
    $ujiValidasi('Keranjang kosong', $dataUji, ['VAL-01'], ['items']);

    foreach ([
        'qty nol' => 0,
        'qty negatif' => -1,
        'qty desimal' => 1.5,
        'qty berupa teks' => '2',
    ] as $nama => $qtyTidakValid) {
        $dataUji = $dataValid;
        $dataUji['items'][0]['qty'] = $qtyTidakValid;
        $ujiValidasi(ucfirst($nama), $dataUji, ['VAL-02'], ['items.0.qty']);
    }

    $dataUji = $dataValid;
    $dataUji['items'][0]['qty'] = 6;
    $ujiValidasi('Qty melebihi stok', $dataUji, ['VAL-03'], ['items.0.qty']);

    $dataUji = $dataValid;
    $dataUji['items'][0]['produkId'] = 999;
    $ujiValidasi('Produk tidak ditemukan', $dataUji, ['VAL-06'], ['items.0.produkId']);

    $dataUji = $dataValid;
    $dataUji['pemesan'] = '   ';
    $ujiValidasi('Nama kosong', $dataUji, ['VAL-04'], ['pemesan']);

    $dataUji = $dataValid;
    $dataUji['kontak'] = 'nomor-salah';
    $ujiValidasi('Kontak tidak valid', $dataUji, ['VAL-05'], ['kontak']);

    $dataUji = $dataValid;
    $dataUji['alamat'] = '';
    $ujiValidasi('Alamat kosong', $dataUji, ['VAL-07'], ['alamat']);

    $dataUji = $dataValid;
    $dataUji['items'] = [
        ['produkId' => 1, 'qty' => 3],
        ['produkId' => 1, 'qty' => 3],
    ];
    $ujiValidasi('Qty duplikat dijumlahkan untuk cek stok', $dataUji, ['VAL-03'], ['items.0.qty']);

    $dataUji = $dataValid;
    $dataUji['kontak'] = '0812 3456-7890';
    $ujiValidasi('Semua data valid dan kontak dinormalisasi', $dataUji, []);

    $modelProduk = new Produk($pdo);
    $dataProduk = [
        'nama' => 'Kaos Polos',
        'kategori' => 'Pakaian',
        'harga' => 75000,
        'stok' => 10,
        'gambar' => 'kaos-polos.jpg',
    ];
    $produkPertama = $modelProduk->tambah($dataProduk);
    $produkKedua = $modelProduk->tambah([
        'nama' => 'Tas Kanvas',
        'kategori' => 'Aksesori',
        'harga' => 90000,
        'stok' => 4,
        'gambar' => 'tas-kanvas.jpg',
    ]);

    if ($produkPertama['id'] !== 1 || $produkKedua['id'] !== 2) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: ID produk tidak berurutan.\n");
    } else {
        fwrite(STDOUT, "LULUS: ID produk berurutan.\n");
    }

    $produkDicari = $modelProduk->cari(1);
    if (
        count($modelProduk->semua('kAoS')) !== 1
        || count($modelProduk->semua(null, 'Aksesori')) !== 1
        || $produkDicari === null
        || $produkDicari['nama'] !== 'Kaos Polos'
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: pencarian atau filter produk tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: pencarian nama, filter kategori, dan cari ID.\n");
    }

    $produkDiubah = $modelProduk->ubah(1, ['harga' => 80000]);
    if ($produkDiubah === null || $produkDiubah['harga'] !== 80000 || $produkDiubah['id'] !== 1) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: perubahan produk tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: produk dapat diubah tanpa mengganti ID.\n");
    }

    $stokBerhasilDikurangi = $modelProduk->kurangiStok(1, 3);
    $produkSetelahDikurangi = $modelProduk->cari(1);
    if (!$stokBerhasilDikurangi || $produkSetelahDikurangi === null || $produkSetelahDikurangi['stok'] !== 7) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: pengurangan stok tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: stok produk dapat dikurangi.\n");
    }

    $stokBerhasilDikembalikan = $modelProduk->kembalikanStok(1, 2);
    $produkSetelahDikembalikan = $modelProduk->cari(1);
    if (
        !$stokBerhasilDikembalikan
        || $produkSetelahDikembalikan === null
        || $produkSetelahDikembalikan['stok'] !== 9
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: pengembalian stok tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: stok produk dapat dikembalikan.\n");
    }

    if (!$modelProduk->hapus(2) || $modelProduk->cari(2) !== null) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: produk tidak berhasil dihapus.\n");
    } else {
        fwrite(STDOUT, "LULUS: produk dapat dihapus.\n");
    }

    $modelPesanan = new Pesanan($pdo);
    $dataPesanan = [
        'pemesan' => 'Budi',
        'kontak' => '081234567890',
        'alamat' => 'Jl. Merdeka No. 1',
        'items' => [['produkId' => 1, 'namaProduk' => 'Kaos Polos', 'qty' => 2, 'hargaSatuan' => 75000]],
        'subtotal' => 150000,
        'diskon' => 0,
        'pajak' => 16500,
        'total' => 166500,
        'status' => Pesanan::MENUNGGU,
        'tanggal' => '2026-11-18',
    ];
    $pesananPertama = $modelPesanan->simpan($dataPesanan);
    $pesananKedua = $modelPesanan->simpan($dataPesanan);

    if (
        $pesananPertama['id'] !== 'ORD-001'
        || $pesananKedua['id'] !== 'ORD-002'
        || $modelPesanan->buatIdBaru() !== 'ORD-003'
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: ID pesanan tidak berurutan.\n");
    } else {
        fwrite(STDOUT, "LULUS: dua pesanan mendapat ID ORD-001 dan ORD-002.\n");
    }

    $pesananDicari = $modelPesanan->cari('ORD-001');
    if (
        $pesananDicari === null
        || $pesananDicari['pemesan'] !== 'Budi'
        || count($pesananDicari['items']) !== 1
        || $pesananDicari['items'][0]['produkId'] !== 1
        || $pesananDicari['items'][0]['namaProduk'] !== 'Kaos Polos'
        || $pesananDicari['items'][0]['qty'] !== 2
        || $pesananDicari['items'][0]['hargaSatuan'] !== 75000
        || count($modelPesanan->semua(Pesanan::MENUNGGU)) !== 2
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: pencarian atau filter pesanan tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: cari pesanan dan filter status.\n");
    }

    $pesananDiubah = $modelPesanan->ubahStatus('ORD-001', Pesanan::LUNAS);
    if (
        $pesananDiubah === null
        || $pesananDiubah['status'] !== Pesanan::LUNAS
        || count($modelPesanan->semua(Pesanan::LUNAS)) !== 1
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: perubahan status pesanan tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: status pesanan dapat diubah.\n");
    }

    $produkCheckout = $modelProduk->tambah([
        'nama' => 'Produk Checkout',
        'kategori' => 'Pakaian',
        'harga' => 150000,
        'stok' => 5,
        'gambar' => 'produk-checkout.jpg',
    ]);
    $pesananService = new PesananService(
        $pdo,
        $modelProduk,
        $modelPesanan,
        $hitungService,
        $validasiService
    );
    $dataHitung = [
        'items' => [
            ['produkId' => $produkCheckout['id'], 'qty' => 2, 'hargaSatuan' => 1],
        ],
    ];
    $hasilHitung = $pesananService->hitung($dataHitung);
    if (
        $hasilHitung['errors'] !== []
        || $hasilHitung['data']['subtotal'] !== 300000
        || $hasilHitung['data']['diskon'] !== 0
        || $hasilHitung['data']['pajak'] !== 33000
        || $hasilHitung['data']['total'] !== 333000
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: hitung pesanan tidak memakai harga dari database.\n");
    } else {
        fwrite(STDOUT, "LULUS: hitung pesanan memakai harga dari database.\n");
    }

    $dataCheckout = [
        'items' => [['produkId' => $produkCheckout['id'], 'qty' => 2]],
        'pemesan' => 'Siti',
        'kontak' => '081234567890',
        'alamat' => 'Jl. Merdeka No. 2',
    ];
    $stokAwalCheckout = $modelProduk->cari($produkCheckout['id'])['stok'];
    $hasilKeranjangKosong = $pesananService->buat(array_replace($dataCheckout, ['items' => []]));
    $hasilStokKurang = $pesananService->buat(array_replace(
        $dataCheckout,
        ['items' => [['produkId' => $produkCheckout['id'], 'qty' => 6]]]
    ));
    $hasilNamaKosong = $pesananService->buat(array_replace($dataCheckout, ['pemesan' => '']));
    if (
        array_column($hasilKeranjangKosong['errors'], 'kode') !== ['VAL-01']
        || array_column($hasilStokKurang['errors'], 'kode') !== ['VAL-03']
        || array_column($hasilNamaKosong['errors'], 'kode') !== ['VAL-04']
        || $modelProduk->cari($produkCheckout['id'])['stok'] !== $stokAwalCheckout
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: checkout tidak menolak data invalid atau stok berubah.\n");
    } else {
        fwrite(STDOUT, "LULUS: keranjang kosong, stok kurang, dan nama kosong ditolak tanpa mengubah stok.\n");
    }

    $hasilBuatPesanan = $pesananService->buat($dataCheckout);
    $stokSetelahCheckout = $modelProduk->cari($produkCheckout['id'])['stok'];
    if (
        $hasilBuatPesanan['errors'] !== []
        || $hasilBuatPesanan['data']['id'] !== 'ORD-003'
        || $hasilBuatPesanan['data']['status'] !== Pesanan::MENUNGGU
        || $hasilBuatPesanan['data']['tanggal'] !== date('Y-m-d')
        || $hasilBuatPesanan['data']['items'][0]['hargaSatuan'] !== 150000
        || $hasilBuatPesanan['data']['total'] !== 333000
        || $stokSetelahCheckout !== $stokAwalCheckout - 2
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: checkout valid tidak membuat pesanan atau mengurangi stok dengan benar.\n");
    } else {
        fwrite(STDOUT, "LULUS: checkout valid menyimpan pesanan dan mengurangi stok.\n");
    }

    $dataPesananGagal = $dataPesanan;
    $dataPesananGagal['id'] = 'ORD-004';
    $dataPesananGagal['items'] = 'bukan-array';
    try {
        $modelPesanan->simpan($dataPesananGagal);
        $lulus = false;
        fwrite(STDERR, "GAGAL: pesanan dengan detail tidak valid seharusnya ditolak.\n");
    } catch (InvalidArgumentException) {
        if ($modelPesanan->cari('ORD-004') !== null) {
            $lulus = false;
            fwrite(STDERR, "GAGAL: pesanan parsial tersimpan setelah transaksi gagal.\n");
        } else {
            fwrite(STDOUT, "LULUS: pesanan parsial dibatalkan saat detail tidak valid.\n");
        }
    }

    $daftarPesanan = $pesananService->semua();
    $pesananMenunggu = $pesananService->semua(Pesanan::MENUNGGU);
    if (
        count($daftarPesanan) !== 3
        || count($pesananMenunggu) !== 2
        || $pesananService->cari('ORD-999') !== null
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: daftar, filter status, atau pencarian pesanan tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: daftar pesanan, filter status, dan pesanan tidak ditemukan.\n");
    }

    $stokSebelumLunas = $modelProduk->cari(1)['stok'];
    $hasilLunas = $pesananService->ubahStatus('ORD-002', Pesanan::LUNAS);
    if (
        $hasilLunas['hasil'] !== 'berhasil'
        || $hasilLunas['data']['status'] !== Pesanan::LUNAS
        || $modelProduk->cari(1)['stok'] !== $stokSebelumLunas
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: status Lunas tidak tersimpan dengan benar.\n");
    } else {
        fwrite(STDOUT, "LULUS: pesanan dapat diubah menjadi Lunas tanpa mengubah stok.\n");
    }

    $hasilBatal = $pesananService->ubahStatus('ORD-003', Pesanan::DIBATALKAN);
    $stokSetelahBatal = $modelProduk->cari($produkCheckout['id'])['stok'];
    $hasilBatalUlang = $pesananService->ubahStatus('ORD-003', Pesanan::DIBATALKAN);
    $stokSetelahBatalUlang = $modelProduk->cari($produkCheckout['id'])['stok'];
    $hasilFinal = $pesananService->ubahStatus('ORD-001', Pesanan::DIBATALKAN);
    if (
        $hasilBatal['hasil'] !== 'berhasil'
        || $hasilBatal['data']['status'] !== Pesanan::DIBATALKAN
        || $stokSetelahBatal !== $stokSetelahCheckout + 2
        || $hasilBatalUlang['hasil'] !== 'konflik'
        || $stokSetelahBatalUlang !== $stokSetelahBatal
        || $hasilFinal['hasil'] !== 'konflik'
    ) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: pembatalan, pengembalian stok, atau status final tidak sesuai.\n");
    } else {
        fwrite(STDOUT, "LULUS: pembatalan mengembalikan stok sekali; status final memberi konflik.\n");
    }

    if ($pesananService->ubahStatus('ORD-999', Pesanan::LUNAS)['hasil'] !== 'tidak_ditemukan') {
        $lulus = false;
        fwrite(STDERR, "GAGAL: perubahan status pesanan yang tidak ada tidak menghasilkan 404.\n");
    } else {
        fwrite(STDOUT, "LULUS: perubahan status pesanan yang tidak ada ditolak.\n");
    }

    $ringkasan = $modelPesanan->ringkasan();
    if ($ringkasan !== ['jumlahPesanan' => 3, 'totalPendapatan' => 333000]) {
        $lulus = false;
        fwrite(STDERR, "GAGAL: ringkasan memasukkan pendapatan pesanan yang bukan Lunas.\n");
    } else {
        fwrite(STDOUT, "LULUS: ringkasan menghitung semua pesanan dan pendapatan Lunas saja.\n");
    }
} finally {
    $pdo->exec('DROP TEMPORARY TABLE IF EXISTS detail_pesanan');
    $pdo->exec('DROP TEMPORARY TABLE IF EXISTS pesanan');
    $pdo->exec('DROP TEMPORARY TABLE IF EXISTS produk');
}

exit($lulus ? 0 : 1);
