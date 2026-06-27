<?php

// Bypass folder storage & cache agar mengarah ke folder internal Vercel (/tmp)
$appEnv = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'LOG_CHANNEL' => 'stderr',
    'VIEW_COMPILED_PATH' => '/tmp',
    'CACHE_DRIVER' => 'array',
    'SESSION_DRIVER' => 'array',
    'QUEUE_CONNECTION' => 'sync',
];

foreach ($appEnv as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Jalankan bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// PAKSA JALUR MANIFEST DI SINI SEBELUM KERNEL BERJALAN
$app->useStoragePath('/tmp');
$app->instance('manifest.path', '/tmp/packages.php');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);