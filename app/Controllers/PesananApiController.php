<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Pesanan;
use App\Services\PesananService;

/**
 * Menangani perhitungan dan pembuatan pesanan melalui API.
 */
class PesananApiController
{
    public function __construct(private PesananService $pesananService)
    {
    }

    /**
     * Mengirim rincian biaya tanpa menyimpan pesanan.
     */
    public function hitung(Request $request, array $params): void
    {
        $hasil = $this->pesananService->hitung($request->body());
        if ($hasil['errors'] !== []) {
            $this->kirimValidasiGagal($hasil['errors']);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $hasil['data'],
        ]);
    }

    /**
     * Membuat pesanan baru setelah validasi berhasil.
     */
    public function buat(Request $request, array $params): void
    {
        $hasil = $this->pesananService->buat($request->body());
        if ($hasil['errors'] !== []) {
            $this->kirimValidasiGagal($hasil['errors']);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $hasil['data'],
        ], 201);
    }

    /**
     * Mengirim semua pesanan atau hasil filter status.
     */
    public function semua(Request $request, array $params): void
    {
        $status = $request->query('status');
        if ($status !== null && !is_string($status)) {
            Response::json([
                'sukses' => false,
                'pesan' => 'Parameter status tidak valid',
            ], 400);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $this->pesananService->semua($status),
        ]);
    }

    /**
     * Mengirim satu pesanan atau respons 404 jika tidak ditemukan.
     */
    public function satu(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $pesanan = $this->pesananService->cari($id);
        if ($pesanan === null) {
            $this->kirimPesananTidakDitemukan();
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $pesanan,
        ]);
    }

    /**
     * Mengubah status pesanan ke Lunas atau Dibatalkan.
     */
    public function ubahStatus(Request $request, array $params): void
    {
        $data = $request->body();
        $status = $data['status'] ?? null;
        if (!is_string($status) || !in_array($status, [Pesanan::LUNAS, Pesanan::DIBATALKAN], true)) {
            Response::json([
                'sukses' => false,
                'pesan' => 'Status tidak valid',
            ], 400);
            return;
        }

        $hasil = $this->pesananService->ubahStatus($params['id'] ?? '', $status);
        if ($hasil['hasil'] === 'tidak_ditemukan') {
            $this->kirimPesananTidakDitemukan();
            return;
        }
        if ($hasil['hasil'] === 'konflik') {
            Response::json([
                'sukses' => false,
                'pesan' => 'Status pesanan sudah final',
            ], 409);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $hasil['data'],
        ]);
    }

    /**
     * Mengirim error validasi dengan bentuk response API yang seragam.
     */
    private function kirimValidasiGagal(array $errors): void
    {
        Response::json([
            'sukses' => false,
            'pesan' => 'Validasi gagal',
            'errors' => $errors,
        ], 422);
    }

    /**
     * Mengirim respons gagal seragam untuk pesanan yang tidak ditemukan.
     */
    private function kirimPesananTidakDitemukan(): void
    {
        Response::json([
            'sukses' => false,
            'pesan' => 'Pesanan tidak ditemukan',
        ], 404);
    }
}
