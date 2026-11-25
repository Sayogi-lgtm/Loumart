<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;
use App\Controllers\PageController;
use App\Controllers\PesananApiController;
use App\Controllers\ProdukApiController;
use App\Controllers\RingkasanApiController;
use App\Core\Database;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\HitungService;
use App\Services\PesananService;
use App\Services\ValidasiProdukService;
use App\Services\ValidasiService;

$halaman = new PageController();
$pdo = Database::koneksi();
$produk = new Produk($pdo);
$pesanan = new Pesanan($pdo);
$konfigurasi = require dirname(__DIR__) . '/config/app.php';
$produkApi = new ProdukApiController(
    $produk,
    new ValidasiProdukService()
);
$pesananApi = new PesananApiController(
    new PesananService(
        $pdo,
        $produk,
        $pesanan,
        new HitungService($konfigurasi),
        new ValidasiService($konfigurasi)
    )
);
$ringkasanApi = new RingkasanApiController($pesanan);

return [
    [
        'method' => 'GET',
        'path' => '/',
        'handler' => [$halaman, 'katalog'],
    ],
    [
        'method' => 'GET',
        'path' => '/keranjang',
        'handler' => [$halaman, 'keranjang'],
    ],
    [
        'method' => 'GET',
        'path' => '/checkout',
        'handler' => [$halaman, 'checkout'],
    ],
    [
        'method' => 'GET',
        'path' => '/pesanan/{id}',
        'handler' => [$halaman, 'statusPesanan'],
    ],
    [
        'method' => 'GET',
        'path' => '/admin/produk',
        'handler' => [$halaman, 'adminProduk'],
    ],
    [
        'method' => 'GET',
        'path' => '/admin/transaksi',
        'handler' => [$halaman, 'adminTransaksi'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/produk',
        'handler' => [$produkApi, 'semua'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/produk/{id}',
        'handler' => [$produkApi, 'satu'],
    ],
    [
        'method' => 'POST',
        'path' => '/api/produk',
        'handler' => [$produkApi, 'tambah'],
    ],
    [
        'method' => 'PUT',
        'path' => '/api/produk/{id}',
        'handler' => [$produkApi, 'ubah'],
    ],
    [
        'method' => 'DELETE',
        'path' => '/api/produk/{id}',
        'handler' => [$produkApi, 'hapus'],
    ],
    [
        'method' => 'POST',
        'path' => '/api/pesanan/hitung',
        'handler' => [$pesananApi, 'hitung'],
    ],
    [
        'method' => 'POST',
        'path' => '/api/pesanan',
        'handler' => [$pesananApi, 'buat'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/pesanan',
        'handler' => [$pesananApi, 'semua'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/pesanan/{id}',
        'handler' => [$pesananApi, 'satu'],
    ],
    [
        'method' => 'PUT',
        'path' => '/api/pesanan/{id}/status',
        'handler' => [$pesananApi, 'ubahStatus'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/ringkasan',
        'handler' => [$ringkasanApi, 'tampilkan'],
    ],
    [
        'method' => 'GET',
        'path' => '/api/ping',
        'handler' => static function (Request $request, array $params): void {
            Response::json([
                'sukses' => true,
                'data' => 'pong',
            ]);
        },
    ],
];
