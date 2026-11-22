<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Mengirim respons HTTP ke browser.
 */
class Response
{
    /**
     * Mengirim data dengan format JSON.
     */
    public static function json(array $data, int $kode = 200): void
    {
        http_response_code($kode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }

    /**
     * Mengirim teks biasa, misalnya untuk halaman 404.
     */
    public static function text(string $teks, int $kode = 200): void
    {
        http_response_code($kode);
        header('Content-Type: text/plain; charset=utf-8');
        echo $teks;
    }
}
