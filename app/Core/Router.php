<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Mencocokkan request dengan route yang terdaftar.
 */
class Router
{
    private array $routes;

    /**
     * Menyimpan daftar route dari konfigurasi.
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Menjalankan handler yang cocok atau mengirim respons 404.
     */
    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            if (strtoupper($route['method']) !== $request->method()) {
                continue;
            }

            $params = $this->cocokkanPath($route['path'], $request->path());
            if ($params === null) {
                continue;
            }

            $handler = $route['handler'];
            $handler($request, $params);
            return;
        }

        $this->kirimNotFound($request->path());
    }

    /**
     * Mencocokkan path statis dan parameter seperti {id}.
     */
    private function cocokkanPath(string $routePath, string $requestPath): ?array
    {
        if ($routePath === '/') {
            return $requestPath === '/' ? [] : null;
        }

        $namaParameter = [];
        $segmen = explode('/', trim($routePath, '/'));
        $polaSegmen = [];

        foreach ($segmen as $bagian) {
            if (preg_match('/^\{([A-Za-z][A-Za-z0-9_]*)\}$/', $bagian, $cocok)) {
                $namaParameter[] = $cocok[1];
                $polaSegmen[] = '([^/]+)';
                continue;
            }

            $polaSegmen[] = preg_quote($bagian, '#');
        }

        $pola = '#^/' . implode('/', $polaSegmen) . '/?$#';
        if (preg_match($pola, $requestPath, $cocok) !== 1) {
            return null;
        }

        $params = [];
        foreach ($namaParameter as $indeks => $nama) {
            $params[$nama] = rawurldecode($cocok[$indeks + 1]);
        }

        return $params;
    }

    /**
     * Mengirim respons 404 sesuai jenis URL yang diminta.
     */
    private function kirimNotFound(string $path): void
    {
        if ($path === '/api' || str_starts_with($path, '/api/')) {
            Response::json([
                'sukses' => false,
                'pesan' => 'Route tidak ditemukan',
            ], 404);
            return;
        }

        Response::text('Halaman tidak ditemukan', 404);
    }
}
