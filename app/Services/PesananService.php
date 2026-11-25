<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Produk;
use PDO;
use Throwable;

/**
 * Menyiapkan perhitungan dan pembuatan pesanan.
 */
class PesananService
{
    public function __construct(
        private PDO $pdo,
        private Produk $produk,
        private Pesanan $pesanan,
        private HitungService $hitungService,
        private ValidasiService $validasiService
    ) {
    }

    /**
     * Menghitung biaya dari item valid tanpa mewajibkan data pemesan.
     */
    public function hitung(array $data): array
    {
        $dataHitung = [
            'items' => $data['items'] ?? null,
            'pemesan' => 'Pembeli',
            'kontak' => '081234567890',
            'alamat' => 'Alamat sementara',
        ];
        $persiapan = $this->siapkanItems($dataHitung);

        if ($persiapan['errors'] !== []) {
            return ['data' => null, 'errors' => $persiapan['errors']];
        }

        return [
            'data' => $this->hitungService->hitung($persiapan['items']),
            'errors' => [],
        ];
    }

    /**
     * Memvalidasi, menghitung, mengurangi stok, dan menyimpan pesanan secara atomik.
     */
    public function buat(array $data): array
    {
        $this->pdo->beginTransaction();

        try {
            $persiapan = $this->siapkanItems($data);
            if ($persiapan['errors'] !== []) {
                $this->pdo->rollBack();
                return ['data' => null, 'errors' => $persiapan['errors']];
            }

            $items = $persiapan['items'];
            $rincian = $this->hitungService->hitung($items);
            $errors = [];
            $errorsStok = $this->kurangiStokItems($data['items'], $errors);
            if ($errorsStok) {
                $this->pdo->rollBack();
                return ['data' => null, 'errors' => $errors];
            }

            $dataPesanan = [
                'id' => $this->pesanan->buatIdBaru(),
                'pemesan' => trim($data['pemesan']),
                'kontak' => trim($data['kontak']),
                'alamat' => trim($data['alamat']),
                'items' => $items,
                'subtotal' => $rincian['subtotal'],
                'diskon' => $rincian['diskon'],
                'pajak' => $rincian['pajak'],
                'total' => $rincian['total'],
                'status' => Pesanan::MENUNGGU,
                'tanggal' => date('Y-m-d'),
            ];
            $pesananTersimpan = $this->pesanan->simpan($dataPesanan);
            $this->pdo->commit();

            return ['data' => $pesananTersimpan, 'errors' => []];
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }
    }

    /**
     * Mengambil semua pesanan atau menyaringnya berdasarkan status.
     */
    public function semua(?string $status = null): array
    {
        return $this->pesanan->semua($status);
    }

    /**
     * Mengambil satu pesanan berdasarkan ID.
     */
    public function cari(string $id): ?array
    {
        return $this->pesanan->cari($id);
    }

    /**
     * Mengubah status pesanan dan mengembalikan stok saat dibatalkan.
     */
    public function ubahStatus(string $id, string $status): array
    {
        if ($status !== Pesanan::LUNAS && $status !== Pesanan::DIBATALKAN) {
            return ['hasil' => 'status_tidak_valid', 'data' => null];
        }

        $this->pdo->beginTransaction();
        try {
            $pesanan = $this->pesanan->cari($id);
            if ($pesanan === null) {
                $this->pdo->rollBack();
                return ['hasil' => 'tidak_ditemukan', 'data' => null];
            }
            if ($pesanan['status'] !== Pesanan::MENUNGGU) {
                $this->pdo->rollBack();
                return ['hasil' => 'konflik', 'data' => null];
            }

            if ($status === Pesanan::DIBATALKAN) {
                $this->kembalikanStokPesanan($pesanan);
            }

            $pesananDiubah = $this->pesanan->ubahStatusJikaMenunggu($id, $status);
            if ($pesananDiubah === null) {
                $this->pdo->rollBack();
                return ['hasil' => 'konflik', 'data' => null];
            }

            $this->pdo->commit();
            return ['hasil' => 'berhasil', 'data' => $pesananDiubah];
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }
    }

    /**
     * Mengembalikan stok item yang produknya masih ada di katalog.
     */
    private function kembalikanStokPesanan(array $pesanan): void
    {
        foreach ($pesanan['items'] as $item) {
            $produkId = $item['produkId'] ?? null;
            if (is_int($produkId)) {
                $this->produk->kembalikanStok($produkId, $item['qty']);
            }
        }
    }

    /**
     * Memvalidasi item dan memasangkan harga serta nama dari katalog database.
     */
    private function siapkanItems(array $data): array
    {
        $produkTersedia = $this->produk->semua();
        $errors = $this->validasiService->validasi($data, $produkTersedia);
        if ($errors !== []) {
            return ['items' => [], 'errors' => $errors];
        }

        $produkById = [];
        foreach ($produkTersedia as $produk) {
            $produkById[$produk['id']] = $produk;
        }

        $items = [];
        foreach ($data['items'] as $item) {
            $produk = $produkById[$item['produkId']];
            $items[] = [
                'produkId' => $produk['id'],
                'namaProduk' => $produk['nama'],
                'qty' => $item['qty'],
                'hargaSatuan' => $produk['harga'],
            ];
        }

        return ['items' => $items, 'errors' => []];
    }

    /**
     * Mengurangi stok tiap item; stok dicek ulang oleh query atomik Model.
     */
    private function kurangiStokItems(array $items, array &$errors): bool
    {
        $gagal = false;
        foreach ($items as $indeks => $item) {
            if (!$this->produk->kurangiStok($item['produkId'], $item['qty'])) {
                $errors[] = [
                    'kode' => 'VAL-03',
                    'field' => "items.{$indeks}.qty",
                    'pesan' => 'Stok tidak mencukupi',
                ];
                $gagal = true;
            }
        }

        return $gagal;
    }
}
