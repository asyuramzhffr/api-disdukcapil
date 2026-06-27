<?php

// Bypass folder storage & cache agar mengarah ke folder internal Vercel (/tmp)
$appEnv = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true', // Kita buat true dulu agar kalau error, teks error aslinya kelihatan di browser!
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

// Jalankan bootstrap Laravel seperti biasa
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath('/tmp');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);