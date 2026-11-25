<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Pesanan;

/**
 * Menangani endpoint ringkasan penjualan.
 */
class RingkasanApiController
{
    public function __construct(private Pesanan $pesanan)
    {
    }

    /**
     * Mengirim jumlah pesanan dan pendapatan yang sudah Lunas.
     */
    public function tampilkan(Request $request, array $params): void
    {
        Response::json([
            'sukses' => true,
            'data' => $this->pesanan->ringkasan(),
        ]);
    }
}
