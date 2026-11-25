<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Produk;
use App\Services\ValidasiProdukService;

/**
 * Menangani endpoint katalog dan pengelolaan produk admin.
 */
class ProdukApiController
{
    private Produk $produk;
    private ValidasiProdukService $validasiProduk;

    /**
     * Menerima model dan service validasi produk.
     */
    public function __construct(Produk $produk, ValidasiProdukService $validasiProduk)
    {
        $this->produk = $produk;
        $this->validasiProduk = $validasiProduk;
    }

    /**
     * Mengirim daftar produk dengan filter nama dan kategori opsional.
     */
    public function semua(Request $request, array $params): void
    {
        $q = $request->query('q');
        $kategori = $request->query('kategori');

        if (($q !== null && !is_string($q)) || ($kategori !== null && !is_string($kategori))) {
            Response::json([
                'sukses' => false,
                'pesan' => 'Parameter pencarian tidak valid',
            ], 400);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $this->produk->semua($q, $kategori),
        ]);
    }

    /**
     * Mengirim satu produk atau respons 404 jika tidak ditemukan.
     */
    public function satu(Request $request, array $params): void
    {
        $id = filter_var($params['id'] ?? '', FILTER_VALIDATE_INT);
        if (!is_int($id) || $id < 1) {
            $this->kirimProdukTidakDitemukan();
            return;
        }

        $produk = $this->produk->cari($id);
        if ($produk === null) {
            $this->kirimProdukTidakDitemukan();
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $produk,
        ]);
    }

    /**
     * Menambahkan produk baru setelah datanya lolos validasi.
     */
    public function tambah(Request $request, array $params): void
    {
        $data = $request->body();
        $errors = $this->validasiProduk->validasi($data);
        if ($errors !== []) {
            $this->kirimValidasiGagal($errors);
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $this->produk->tambah($this->validasiProduk->normalisasi($data, true)),
        ], 201);
    }

    /**
     * Mengubah produk yang sudah ada.
     */
    public function ubah(Request $request, array $params): void
    {
        $id = $this->idDariParameter($params);
        if ($id === null || $this->produk->cari($id) === null) {
            $this->kirimProdukTidakDitemukan();
            return;
        }

        $data = $request->body();
        $errors = $this->validasiProduk->validasi($data);
        if ($errors !== []) {
            $this->kirimValidasiGagal($errors);
            return;
        }

        $produk = $this->produk->ubah($id, $this->validasiProduk->normalisasi($data));
        if ($produk === null) {
            $this->kirimProdukTidakDitemukan();
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => $produk,
        ]);
    }

    /**
     * Menghapus produk yang sudah ada.
     */
    public function hapus(Request $request, array $params): void
    {
        $id = $this->idDariParameter($params);
        if ($id === null || !$this->produk->hapus($id)) {
            $this->kirimProdukTidakDitemukan();
            return;
        }

        Response::json([
            'sukses' => true,
            'data' => ['id' => $id],
        ]);
    }

    /**
     * Mengubah ID route menjadi bilangan bulat positif.
     */
    private function idDariParameter(array $params): ?int
    {
        $id = filter_var($params['id'] ?? '', FILTER_VALIDATE_INT);
        return is_int($id) && $id > 0 ? $id : null;
    }

    /**
     * Mengirim daftar kesalahan validasi dengan kode HTTP 422.
     */
    private function kirimValidasiGagal(array $errors): void
    {
        Response::json([
            'sukses' => false,
            'pesan' => 'Data produk tidak valid',
            'errors' => $errors,
        ], 422);
    }

    /**
     * Mengirim respons gagal yang seragam untuk produk yang tidak ada.
     */
    private function kirimProdukTidakDitemukan(): void
    {
        Response::json([
            'sukses' => false,
            'pesan' => 'Produk tidak ditemukan',
        ], 404);
    }
}
