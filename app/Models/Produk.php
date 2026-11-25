<?php
declare(strict_types=1);

namespace App\Models;

use OverflowException;
use PDO;

/**
 * Mengelola data produk melalui MariaDB.
 */
class Produk
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Mengambil produk dengan filter nama dan kategori opsional.
     */
    public function semua(?string $q = null, ?string $kategori = null): array
    {
        $sql = 'SELECT id, nama, kategori, harga, stok, gambar FROM produk';
        $syarat = [];
        $parameter = [];

        if ($q !== null && $q !== '') {
            $syarat[] = 'LOCATE(LOWER(:q), LOWER(nama)) > 0';
            $parameter['q'] = $q;
        }
        if ($kategori !== null && $kategori !== '') {
            $syarat[] = 'kategori = :kategori';
            $parameter['kategori'] = $kategori;
        }
        if ($syarat !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $syarat);
        }
        $sql .= ' ORDER BY id ASC';

        $pernyataan = $this->pdo->prepare($sql);
        $pernyataan->execute($parameter);

        return array_map(fn (array $baris): array => $this->petakanProduk($baris), $pernyataan->fetchAll());
    }

    /**
     * Mencari satu produk berdasarkan ID.
     */
    public function cari(int $id): ?array
    {
        $pernyataan = $this->pdo->prepare(
            'SELECT id, nama, kategori, harga, stok, gambar FROM produk WHERE id = :id'
        );
        $pernyataan->execute(['id' => $id]);
        $baris = $pernyataan->fetch();

        return is_array($baris) ? $this->petakanProduk($baris) : null;
    }

    /**
     * Menambahkan produk dan mengembalikan data beserta ID database.
     */
    public function tambah(array $data): array
    {
        $field = $this->ambilFieldProduk($data);
        $pernyataan = $this->pdo->prepare(
            'INSERT INTO produk (nama, kategori, harga, stok, gambar)
             VALUES (:nama, :kategori, :harga, :stok, :gambar)'
        );
        $pernyataan->execute([
            'nama' => $field['nama'],
            'kategori' => $field['kategori'],
            'harga' => $field['harga'],
            'stok' => $field['stok'],
            'gambar' => $field['gambar'],
        ]);

        return $this->cari((int) $this->pdo->lastInsertId()) ?? [];
    }

    /**
     * Mengubah data produk tanpa mengganti ID-nya.
     */
    public function ubah(int $id, array $data): ?array
    {
        $produkLama = $this->cari($id);
        if ($produkLama === null) {
            return null;
        }

        $field = array_replace($produkLama, $this->ambilFieldProduk($data));
        $pernyataan = $this->pdo->prepare(
            'UPDATE produk
             SET nama = :nama, kategori = :kategori, harga = :harga, stok = :stok, gambar = :gambar
             WHERE id = :id'
        );
        $pernyataan->execute([
            'nama' => $field['nama'],
            'kategori' => $field['kategori'],
            'harga' => $field['harga'],
            'stok' => $field['stok'],
            'gambar' => $field['gambar'],
            'id' => $id,
        ]);

        return $this->cari($id);
    }

    /**
     * Menghapus produk berdasarkan ID.
     */
    public function hapus(int $id): bool
    {
        $pernyataan = $this->pdo->prepare('DELETE FROM produk WHERE id = :id');
        $pernyataan->execute(['id' => $id]);

        return $pernyataan->rowCount() > 0;
    }

    /**
     * Mengurangi stok secara atomik jika produk tersedia dan stok mencukupi.
     */
    public function kurangiStok(int $id, int $qty): bool
    {
        if ($qty <= 0) {
            return false;
        }

        $pernyataan = $this->pdo->prepare(
            'UPDATE produk SET stok = stok - :qty
             WHERE id = :id AND stok >= :stok_minimum'
        );
        $pernyataan->execute([
            'qty' => $qty,
            'id' => $id,
            'stok_minimum' => $qty,
        ]);

        return $pernyataan->rowCount() === 1;
    }

    /**
     * Mengembalikan stok tanpa melewati batas bilangan bulat PHP.
     */
    public function kembalikanStok(int $id, int $qty): bool
    {
        if ($qty <= 0) {
            return false;
        }
        if ($this->cari($id) === null) {
            return false;
        }

        $batasStok = PHP_INT_MAX - $qty;
        $pernyataan = $this->pdo->prepare(
            'UPDATE produk SET stok = stok + :qty
             WHERE id = :id AND stok <= :batas'
        );
        $pernyataan->execute([
            'qty' => $qty,
            'id' => $id,
            'batas' => $batasStok,
        ]);

        if ($pernyataan->rowCount() !== 1) {
            throw new OverflowException('Stok produk melebihi batas bilangan bulat.');
        }

        return true;
    }

    /**
     * Mengambil hanya field produk yang boleh disimpan.
     */
    private function ambilFieldProduk(array $data): array
    {
        $field = [];
        foreach (['nama', 'kategori', 'harga', 'stok', 'gambar'] as $namaField) {
            if (array_key_exists($namaField, $data)) {
                $field[$namaField] = $data[$namaField];
            }
        }

        return $field;
    }

    /**
     * Menyamakan tipe hasil PDO dengan tipe data yang dipakai API.
     */
    private function petakanProduk(array $baris): array
    {
        return [
            'id' => (int) $baris['id'],
            'nama' => (string) $baris['nama'],
            'kategori' => (string) $baris['kategori'],
            'harga' => (int) $baris['harga'],
            'stok' => (int) $baris['stok'],
            'gambar' => (string) $baris['gambar'],
        ];
    }
}
