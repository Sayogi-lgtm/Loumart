<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Memeriksa dan menormalkan data produk dari API admin.
 */
class ValidasiProdukService
{
    /**
     * Mengumpulkan semua kesalahan pada data produk.
     */
    public function validasi(array $data): array
    {
        $errors = [];

        foreach (['nama' => 'Nama produk wajib diisi', 'kategori' => 'Kategori wajib diisi'] as $field => $pesan) {
            if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === '') {
                $errors[] = [
                    'kode' => 'PRODUK-01',
                    'field' => $field,
                    'pesan' => $pesan,
                ];
            }
        }

        foreach (['harga' => 'Harga harus berupa bilangan bulat minimal 0', 'stok' => 'Stok harus berupa bilangan bulat minimal 0'] as $field => $pesan) {
            if (!isset($data[$field]) || !is_int($data[$field]) || $data[$field] < 0) {
                $errors[] = [
                    'kode' => 'PRODUK-02',
                    'field' => $field,
                    'pesan' => $pesan,
                ];
            }
        }

        if (array_key_exists('gambar', $data) && !$this->gambarValid($data['gambar'])) {
            $errors[] = [
                'kode' => 'PRODUK-03',
                'field' => 'gambar',
                'pesan' => 'Nama gambar tidak valid',
            ];
        }

        return $errors;
    }

    /**
     * Merapikan teks dan menjaga bentuk field yang disimpan model.
     */
    public function normalisasi(array $data, bool $produkBaru = false): array
    {
        $hasil = [
            'nama' => trim($data['nama']),
            'kategori' => trim($data['kategori']),
            'harga' => $data['harga'],
            'stok' => $data['stok'],
        ];

        if (array_key_exists('gambar', $data)) {
            $hasil['gambar'] = trim($data['gambar']);
        } elseif ($produkBaru) {
            $hasil['gambar'] = '';
        }

        return $hasil;
    }

    /**
     * Memastikan nama gambar berupa nama file aman tanpa path atau dua titik.
     */
    private function gambarValid(mixed $gambar): bool
    {
        if (!is_string($gambar)) {
            return false;
        }

        $gambar = trim($gambar);
        if ($gambar === '') {
            return true;
        }

        return preg_match('/^[A-Za-z0-9_-][A-Za-z0-9._-]*$/', $gambar) === 1
            && !str_contains($gambar, '..');
    }
}
