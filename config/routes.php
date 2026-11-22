<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;

return [
    [
        'method' => 'GET',
        'path' => '/api/ping',
        'handler' => static function (Request $request, array $params): void {
            Response::json([
                'sukses' => true,
                'data' => 'pong',
            ]);
        },
    ],
];
