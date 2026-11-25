<?php
declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;
use OverflowException;

/**
 * Menghitung subtotal, diskon, pajak, dan total pesanan dalam rupiah.
 */
class HitungService
{
    private int $pajakPersen;
    private array $tabelDiskon;

    /**
     * Mengambil aturan pajak dan diskon dari konfigurasi aplikasi.
     */
    public function __construct(array $konfigurasi)
    {
        $pajakPersen = $konfigurasi['pajak_persen'] ?? null;
        $tabelDiskon = $konfigurasi['diskon'] ?? null;

        if (!is_int($pajakPersen) || $pajakPersen < 0 || !is_array($tabelDiskon)) {
            throw new InvalidArgumentException('Konfigurasi perhitungan tidak valid.');
        }

        foreach ($tabelDiskon as $aturan) {
            if (
                !is_array($aturan)
                || !is_int($aturan['min_subtotal'] ?? null)
                || !is_int($aturan['persen'] ?? null)
                || $aturan['min_subtotal'] < 0
                || $aturan['persen'] < 0
                || $aturan['persen'] > 100
            ) {
                throw new InvalidArgumentException('Tabel diskon tidak valid.');
            }
        }

        $this->pajakPersen = $pajakPersen;
        $this->tabelDiskon = $tabelDiskon;
    }

    /**
     * Menghitung biaya dari item yang sudah memiliki harga satuan dari server.
     */
    public function hitung(array $items): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            if (
                !is_array($item)
                || !is_int($item['hargaSatuan'] ?? null)
                || !is_int($item['qty'] ?? null)
                || $item['hargaSatuan'] < 0
                || $item['qty'] < 0
            ) {
                throw new InvalidArgumentException('Harga dan jumlah item harus berupa bilangan bulat nonnegatif.');
            }

            $subtotalItem = $this->hitungSubtotalItem($item['hargaSatuan'], $item['qty']);
            if ($subtotal > PHP_INT_MAX - $subtotalItem) {
                throw new OverflowException('Subtotal pesanan melebihi batas bilangan bulat.');
            }

            $subtotal += $subtotalItem;
        }

        $persenDiskon = $this->cariPersenDiskon($subtotal);
        $diskon = $this->hitungPersentase($subtotal, $persenDiskon);
        $setelahDiskon = $subtotal - $diskon;
        $pajak = $this->hitungPersentase($setelahDiskon, $this->pajakPersen);

        return [
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'persenDiskon' => $persenDiskon,
            'setelahDiskon' => $setelahDiskon,
            'pajak' => $pajak,
            'total' => $setelahDiskon + $pajak,
        ];
    }

    /**
     * Menghindari overflow ketika mengalikan harga satuan dan jumlah.
     */
    private function hitungSubtotalItem(int $hargaSatuan, int $qty): int
    {
        if ($hargaSatuan > 0 && $qty > intdiv(PHP_INT_MAX, $hargaSatuan)) {
            throw new OverflowException('Subtotal item melebihi batas bilangan bulat.');
        }

        return $hargaSatuan * $qty;
    }

    /**
     * Memilih aturan diskon pertama yang ambang minimumnya terpenuhi.
     */
    private function cariPersenDiskon(int $subtotal): int
    {
        foreach ($this->tabelDiskon as $aturan) {
            if ($subtotal >= $aturan['min_subtotal']) {
                return $aturan['persen'];
            }
        }

        return 0;
    }

    /**
     * Menghitung persentase dengan pembulatan rupiah setengah ke atas.
     */
    private function hitungPersentase(int $nilai, int $persen): int
    {
        if ($persen === 0) {
            return 0;
        }
        if ($nilai > intdiv(PHP_INT_MAX - 50, $persen)) {
            throw new OverflowException('Hasil persentase melebihi batas bilangan bulat.');
        }

        return intdiv(($nilai * $persen) + 50, 100);
    }
}
