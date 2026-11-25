<?php
declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

/**
 * Memeriksa data transaksi dan mengumpulkan semua kesalahan validasi.
 */
class ValidasiService
{
    private string $polaKontak;

    /**
     * Mengambil pola nomor telepon dari konfigurasi aplikasi.
     */
    public function __construct(array $konfigurasi)
    {
        $polaKontak = $konfigurasi['pola_kontak'] ?? null;
        if (!is_string($polaKontak) || $polaKontak === '') {
            throw new InvalidArgumentException('Pola kontak tidak tersedia.');
        }

        $this->polaKontak = $polaKontak;
    }

    /**
     * Mengembalikan daftar error; array kosong berarti semua data valid.
     */
    public function validasi(array $data, array $produk): array
    {
        $errors = [];
        $produkById = $this->indeksProduk($produk);
        $items = $data['items'] ?? null;

        if (!is_array($items) || $items === []) {
            $this->tambahError($errors, 'VAL-01', 'items', 'Keranjang masih kosong');
        } else {
            $this->validasiItems($items, $produkById, $errors);
        }

        $this->validasiPemesan($data, $errors);

        return $errors;
    }

    /**
     * Membuat indeks katalog agar pencarian produk mudah dan ketat tipenya.
     */
    private function indeksProduk(array $produk): array
    {
        $produkById = [];
        foreach ($produk as $baris) {
            if (
                is_array($baris)
                && is_int($baris['id'] ?? null)
                && is_int($baris['stok'] ?? null)
            ) {
                $produkById[$baris['id']] = $baris;
            }
        }

        return $produkById;
    }

    /**
     * Memeriksa qty, keberadaan produk, dan total qty per produk.
     */
    private function validasiItems(array $items, array $produkById, array &$errors): void
    {
        $jumlahPerProduk = [];
        $indeks = 0;

        foreach ($items as $item) {
            $item = is_array($item) ? $item : [];
            $qty = $item['qty'] ?? null;
            $produkId = $item['produkId'] ?? null;
            $qtyValid = is_int($qty) && $qty > 0;

            if (!$qtyValid) {
                $this->tambahError($errors, 'VAL-02', "items.{$indeks}.qty", 'Jumlah barang tidak valid');
            }

            if (!is_int($produkId) || !array_key_exists($produkId, $produkById)) {
                $this->tambahError($errors, 'VAL-06', "items.{$indeks}.produkId", 'Produk tidak ditemukan');
            } elseif ($qtyValid) {
                if (!isset($jumlahPerProduk[$produkId])) {
                    $jumlahPerProduk[$produkId] = [
                        'qty' => 0,
                        'indeks' => $indeks,
                    ];
                }
                $jumlahPerProduk[$produkId]['qty'] += $qty;
            }

            $indeks++;
        }

        $this->validasiStok($jumlahPerProduk, $produkById, $errors);
    }

    /**
     * Memeriksa stok setelah menjumlahkan qty produk yang sama.
     */
    private function validasiStok(array $jumlahPerProduk, array $produkById, array &$errors): void
    {
        foreach ($jumlahPerProduk as $produkId => $jumlah) {
            if ($jumlah['qty'] > $produkById[$produkId]['stok']) {
                $indeks = $jumlah['indeks'];
                $this->tambahError($errors, 'VAL-03', "items.{$indeks}.qty", 'Stok tidak mencukupi');
            }
        }
    }

    /**
     * Memeriksa nama, nomor telepon, dan alamat pemesan.
     */
    private function validasiPemesan(array $data, array &$errors): void
    {
        $pemesan = $data['pemesan'] ?? null;
        if (!is_string($pemesan) || trim($pemesan) === '') {
            $this->tambahError($errors, 'VAL-04', 'pemesan', 'Nama wajib diisi');
        }

        $kontak = $data['kontak'] ?? null;
        $kontakBersih = is_string($kontak) ? preg_replace('/[\s-]+/', '', $kontak) : '';
        if (!is_string($kontakBersih) || $kontakBersih === '' || preg_match($this->polaKontak, $kontakBersih) !== 1) {
            $this->tambahError($errors, 'VAL-05', 'kontak', 'Kontak tidak valid');
        }

        $alamat = $data['alamat'] ?? null;
        if (!is_string($alamat) || trim($alamat) === '') {
            $this->tambahError($errors, 'VAL-07', 'alamat', 'Alamat wajib diisi');
        }
    }

    /**
     * Menambahkan error dengan bentuk yang sesuai kontrak API.
     */
    private function tambahError(array &$errors, string $kode, string $field, string $pesan): void
    {
        $errors[] = [
            'kode' => $kode,
            'field' => $field,
            'pesan' => $pesan,
        ];
    }
}
