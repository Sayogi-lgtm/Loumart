<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

/**
 * Menampilkan halaman HTML LouMart.
 */
class PageController
{
    /**
     * Menampilkan katalog.
     */
    public function katalog(Request $request, array $params): void
    {
        View::render('katalog', 'Katalog', 'katalog');
    }

    /**
     * Menampilkan keranjang belanja.
     */
    public function keranjang(Request $request, array $params): void
    {
        View::render('keranjang', 'Keranjang', 'keranjang');
    }

    /**
     * Menampilkan kerangka halaman checkout.
     */
    public function checkout(Request $request, array $params): void
    {
        View::render('checkout', 'Checkout', 'keranjang');
    }

    /**
     * Menampilkan status satu pesanan.
     */
    public function statusPesanan(Request $request, array $params): void
    {
        View::render('status-pesanan', 'Status Pesanan', 'keranjang', [
            'idPesanan' => $params['id'] ?? '',
        ]);
    }

    /**
     * Menampilkan kerangka halaman admin produk.
     */
    public function adminProduk(Request $request, array $params): void
    {
        View::render('admin/produk', 'Produk', 'admin');
    }

    /**
     * Menampilkan kerangka halaman admin transaksi.
     */
    public function adminTransaksi(Request $request, array $params): void
    {
        View::render('admin/transaksi', 'Transaksi', 'admin');
    }
}
