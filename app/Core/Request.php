<?php
declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use JsonException;

/**
 * Menyediakan data request HTTP dalam bentuk yang mudah digunakan.
 */
class Request
{
    private string $metode;
    private string $path;
    private array $query;
    private array $body;

    /**
     * Menyimpan bagian request yang sudah dibaca.
     */
    private function __construct(string $metode, string $path, array $query, array $body)
    {
        $this->metode = $metode;
        $this->path = $path;
        $this->query = $query;
        $this->body = $body;
    }

    /**
     * Membaca request dari variabel server PHP.
     *
     * @throws InvalidArgumentException Jika body berisi JSON yang rusak.
     */
    public static function fromGlobals(): self
    {
        $metode = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        $path = is_string($path) ? rawurldecode($path) : '/';
        $path = rtrim($path, '/') ?: '/';

        $teksBody = file_get_contents('php://input');
        if ($teksBody === false) {
            throw new InvalidArgumentException('Body request tidak dapat dibaca.');
        }

        return new self($metode, $path, $_GET, self::bacaJson($teksBody));
    }

    /**
     * Mengubah body JSON menjadi array asosiatif.
     */
    private static function bacaJson(string $teks): array
    {
        if (trim($teks) === '') {
            return [];
        }

        try {
            $data = json_decode($teks, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new InvalidArgumentException('Body JSON tidak valid.', 0, $error);
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Body JSON harus berupa objek atau array.');
        }

        return $data;
    }

    /**
     * Mengembalikan metode HTTP request.
     */
    public function method(): string
    {
        return $this->metode;
    }

    /**
     * Mengembalikan path tanpa query string.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Mengambil satu nilai query atau seluruh query.
     */
    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    /**
     * Mengembalikan body JSON yang sudah dibaca.
     */
    public function body(): array
    {
        return $this->body;
    }
}
