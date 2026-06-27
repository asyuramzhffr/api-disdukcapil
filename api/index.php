<?php

// Pasang kembali paksaan environment agar Laravel tidak bingung di Vercel
$appEnv = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true', // Biar kalau ada error database/env langsung kelihatan teksnya, bukan abu-abu lagi
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

// Memuat file autoload bawaan Laravel
require __DIR__ . '/../vendor/autoload.php';

// Memuat aplikasi Laravel (bootstrap)
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Menjalankan request lewat Kernel Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);