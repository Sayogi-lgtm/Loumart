<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Membuat dan menyimpan satu koneksi PDO ke MariaDB lokal.
 */
final class Database
{
    private static ?PDO $koneksi = null;

    /**
     * Mengembalikan koneksi database yang dipakai aplikasi.
     */
    public static function koneksi(): PDO
    {
        if (self::$koneksi instanceof PDO) {
            return self::$koneksi;
        }

        $fileKonfigurasi = dirname(__DIR__, 2) . '/config/database.local.php';
        if (!is_file($fileKonfigurasi)) {
            throw new RuntimeException(
                'Konfigurasi MariaDB belum ada. Salin database.example.php menjadi database.local.php.'
            );
        }

        $konfigurasi = require $fileKonfigurasi;
        if (!is_array($konfigurasi)) {
            throw new RuntimeException('Konfigurasi MariaDB tidak valid.');
        }

        $host = $konfigurasi['host'] ?? null;
        $port = $konfigurasi['port'] ?? null;
        $namaDatabase = $konfigurasi['database'] ?? null;
        $username = $konfigurasi['username'] ?? null;
        $password = $konfigurasi['password'] ?? null;

        if (
            !is_string($host)
            || !is_int($port)
            || !is_string($namaDatabase)
            || !is_string($username)
            || !is_string($password)
        ) {
            throw new RuntimeException('Isi konfigurasi MariaDB belum lengkap.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $namaDatabase
        );

        try {
            self::$koneksi = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Koneksi MariaDB gagal. Periksa layanan dan konfigurasi lokal.',
                0,
                $exception
            );
        }

        return self::$koneksi;
    }
}
