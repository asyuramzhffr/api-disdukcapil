<?php

// 1. Tentukan path sementara
$tmpPath = '/tmp';

// 2. Buat folder bayangan kalau belum ada
if (!is_dir($tmpPath . '/bootstrap/cache')) {
    mkdir($tmpPath . '/bootstrap/cache', 0755, true);
}

// 3. Paksa setting environment PHP sebelum aplikasi dibuat
putenv("APP_STORAGE=$tmpPath/storage");

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// 4. Override path menggunakan method yang didukung Laravel
$app->useStoragePath($tmpPath . '/storage');
$app->useBootstrapPath($tmpPath . '/bootstrap');

// 5. Daftarkan path manifest secara manual agar tidak error
$app->instance('manifest.path', $tmpPath . '/bootstrap/cache/packages.php');

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;