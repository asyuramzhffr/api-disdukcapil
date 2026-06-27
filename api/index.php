<?php

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