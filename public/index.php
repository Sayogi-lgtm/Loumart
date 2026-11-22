<?php
declare(strict_types=1);

// Memuat kelas App secara otomatis dari folder app/.
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDirectory = __DIR__ . '/../app/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDirectory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

try {
    $request = Request::fromGlobals();
    $routes = require __DIR__ . '/../config/routes.php';
    $router = new Router($routes);
    $router->dispatch($request);
} catch (\InvalidArgumentException $error) {
    Response::json([
        'sukses' => false,
        'pesan' => $error->getMessage(),
    ], 400);
} catch (\Throwable $error) {
    Response::json([
        'sukses' => false,
        'pesan' => 'Terjadi kesalahan pada server',
    ], 500);
}
