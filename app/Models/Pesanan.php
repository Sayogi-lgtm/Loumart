<?php
declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;
use OverflowException;
use PDO;
use Throwable;

/**
 * Mengelola data pesanan dan detailnya melalui MariaDB.
 */
class Pesanan
{
    public const MENUNGGU = 'Menunggu Pembayaran';
    public const LUNAS = 'Lunas';
    public const DIBATALKAN = 'Dibatalkan';

    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Mengambil semua pesanan atau memfilter berdasarkan status.
     */
    public function semua(?string $status = null): array
    {
        if ($status === null || $status === '') {
            $pernyataan = $this->pdo->query('SELECT * FROM pesanan ORDER BY tanggal, id');
        } else {
            $pernyataan = $this->pdo->prepare(
                'SELECT * FROM pesanan WHERE status = :status ORDER BY tanggal, id'
            );
            $pernyataan->execute(['status' => $status]);
        }

        return array_map(
            fn (array $baris): array => $this->petakanPesanan($baris),
            $pernyataan->fetchAll()
        );
    }

    /**
     * Menghitung jumlah seluruh pesanan dan pendapatan dari pesanan Lunas.
     */
    public function ringkasan(): array
    {
        $pernyataan = $this->pdo->prepare(
            'SELECT COUNT(*) AS jumlah_pesanan,
                    COALESCE(SUM(CASE WHEN status = :status_lunas THEN total ELSE 0 END), 0)
                        AS total_pendapatan
             FROM pesanan'
        );
        $pernyataan->execute(['status_lunas' => self::LUNAS]);
        $baris = $pernyataan->fetch();

        return [
            'jumlahPesanan' => (int) ($baris['jumlah_pesanan'] ?? 0),
            'totalPendapatan' => (int) ($baris['total_pendapatan'] ?? 0),
        ];
    }

    /**
     * Mencari satu pesanan beserta detailnya berdasarkan ID.
     */
    public function cari(string $id): ?array
    {
        $pernyataan = $this->pdo->prepare('SELECT * FROM pesanan WHERE id = :id');
        $pernyataan->execute(['id' => $id]);
        $baris = $pernyataan->fetch();

        return is_array($baris) ? $this->petakanPesanan($baris) : null;
    }

    /**
     * Membuat ID berikutnya dengan format ORD-001.
     */
    public function buatIdBaru(): string
    {
        $nomorTerbesar = 0;
        $pernyataan = $this->pdo->query('SELECT id FROM pesanan');
        foreach ($pernyataan->fetchAll() as $baris) {
            $nomor = $this->nomorDariId($baris['id'] ?? null);
            if ($nomor !== null && $nomor > $nomorTerbesar) {
                $nomorTerbesar = $nomor;
            }
        }

        if ($nomorTerbesar === PHP_INT_MAX) {
            throw new OverflowException('Nomor pesanan melebihi batas bilangan bulat.');
        }

        return 'ORD-' . str_pad((string) ($nomorTerbesar + 1), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Menyimpan pesanan beserta itemnya secara atomik.
     */
    public function simpan(array $data): array
    {
        $id = $data['id'] ?? $this->buatIdBaru();
        if (!is_string($id) || $id === '') {
            throw new InvalidArgumentException('ID pesanan tidak valid.');
        }
        if ($this->cari($id) !== null) {
            throw new InvalidArgumentException('ID pesanan sudah digunakan.');
        }

        $punyaTransaksi = !$this->pdo->inTransaction();
        if ($punyaTransaksi) {
            $this->pdo->beginTransaction();
        }

        try {
            $this->simpanDataPesanan($id, $data);
            $this->simpanDetailPesanan($id, $data['items'] ?? []);

            if ($punyaTransaksi) {
                $this->pdo->commit();
            }
        } catch (Throwable $exception) {
            if ($punyaTransaksi && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }

        return $this->cari($id) ?? [];
    }

    /**
     * Mengubah status pesanan tanpa menerapkan aturan perpindahan status.
     */
    public function ubahStatus(string $id, string $status): ?array
    {
        if ($this->cari($id) === null) {
            return null;
        }

        $pernyataan = $this->pdo->prepare(
            'UPDATE pesanan SET status = :status WHERE id = :id'
        );
        $pernyataan->execute([
            'status' => $status,
            'id' => $id,
        ]);

        return $this->cari($id);
    }

    /**
     * Mengubah status hanya jika pesanan masih menunggu pembayaran.
     */
    public function ubahStatusJikaMenunggu(string $id, string $status): ?array
    {
        $pernyataan = $this->pdo->prepare(
            'UPDATE pesanan
             SET status = :status_baru
             WHERE id = :id AND status = :status_lama'
        );
        $pernyataan->execute([
            'status_baru' => $status,
            'id' => $id,
            'status_lama' => self::MENUNGGU,
        ]);

        if ($pernyataan->rowCount() !== 1) {
            return null;
        }

        return $this->cari($id);
    }

    /**
     * Menyimpan kolom utama pesanan.
     */
    private function simpanDataPesanan(string $id, array $data): void
    {
        $pernyataan = $this->pdo->prepare(
            'INSERT INTO pesanan
                (id, pemesan, kontak, alamat, subtotal, diskon, pajak, total, status, tanggal)
             VALUES
                (:id, :pemesan, :kontak, :alamat, :subtotal, :diskon, :pajak, :total, :status, :tanggal)'
        );
        $pernyataan->execute([
            'id' => $id,
            'pemesan' => $data['pemesan'],
            'kontak' => $data['kontak'],
            'alamat' => $data['alamat'],
            'subtotal' => $data['subtotal'],
            'diskon' => $data['diskon'],
            'pajak' => $data['pajak'],
            'total' => $data['total'],
            'status' => $data['status'],
            'tanggal' => $data['tanggal'],
        ]);
    }

    /**
     * Menyimpan setiap item ke tabel detail_pesanan.
     */
    private function simpanDetailPesanan(string $pesananId, mixed $items): void
    {
        if (!is_array($items)) {
            throw new InvalidArgumentException('Item pesanan tidak valid.');
        }

        $pernyataan = $this->pdo->prepare(
            'INSERT INTO detail_pesanan
                (pesanan_id, produk_id, nama_produk, qty, harga_satuan)
             VALUES
                (:pesanan_id, :produk_id, :nama_produk, :qty, :harga_satuan)'
        );

        foreach ($items as $item) {
            if (!is_array($item)) {
                throw new InvalidArgumentException('Detail item pesanan tidak valid.');
            }

            $produkId = $item['produkId'] ?? null;
            $pernyataan->execute([
                'pesanan_id' => $pesananId,
                'produk_id' => is_int($produkId) ? $produkId : null,
                'nama_produk' => $item['namaProduk'] ?? '',
                'qty' => $item['qty'],
                'harga_satuan' => $item['hargaSatuan'],
            ]);
        }
    }

    /**
     * Mengubah baris pesanan dan item menjadi bentuk response API.
     */
    private function petakanPesanan(array $baris): array
    {
        $pernyataan = $this->pdo->prepare(
            'SELECT produk_id, nama_produk, qty, harga_satuan
             FROM detail_pesanan WHERE pesanan_id = :id ORDER BY id'
        );
        $pernyataan->execute(['id' => $baris['id']]);
        $items = array_map(
            static fn (array $item): array => [
                'produkId' => $item['produk_id'] === null ? null : (int) $item['produk_id'],
                'namaProduk' => (string) $item['nama_produk'],
                'qty' => (int) $item['qty'],
                'hargaSatuan' => (int) $item['harga_satuan'],
            ],
            $pernyataan->fetchAll()
        );

        return [
            'id' => (string) $baris['id'],
            'pemesan' => (string) $baris['pemesan'],
            'kontak' => (string) $baris['kontak'],
            'alamat' => (string) $baris['alamat'],
            'items' => $items,
            'subtotal' => (int) $baris['subtotal'],
            'diskon' => (int) $baris['diskon'],
            'pajak' => (int) $baris['pajak'],
            'total' => (int) $baris['total'],
            'status' => (string) $baris['status'],
            'tanggal' => (string) $baris['tanggal'],
        ];
    }

    /**
     * Mengambil nomor dari format ID ORD-001 atau ORD-1000.
     */
    private function nomorDariId(mixed $id): ?int
    {
        if (!is_string($id) || preg_match('/^ORD-(\d+)$/', $id, $cocok) !== 1) {
            return null;
        }

        $angka = ltrim($cocok[1], '0');
        $nomor = filter_var($angka === '' ? '0' : $angka, FILTER_VALIDATE_INT);

        return is_int($nomor) ? $nomor : null;
    }
}
