<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

/**
 * Memuat isi halaman ke dalam layout utama.
 */
class View
{
    /**
     * Merender halaman yang tersedia atau kerangka untuk halaman berikutnya.
     */
    public static function render(string $halaman, string $judul, string $menuAktif, array $data = []): void
    {
        $folderView = dirname(__DIR__) . '/Views/';
        $fileView = $folderView . $halaman . '.php';
        $fileLayout = $folderView . 'layouts/main.php';

        if (!is_file($fileLayout)) {
            throw new RuntimeException('Layout utama tidak ditemukan.');
        }

        $judulHalaman = $judul;
        $konten = '';

        if (is_file($fileView)) {
            extract($data, EXTR_SKIP);
            ob_start();

            try {
                require $fileView;
                $konten = (string) ob_get_clean();
            } catch (Throwable $error) {
                ob_end_clean();
                throw $error;
            }
        }

        require $fileLayout;
    }
}
