<?php

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// --- POTONG KOMPAS JALUR MANIFEST & SERVICES KESELURUHAN ---
$app->useStoragePath('/tmp');

// Kita override fungsi bawaan Laravel untuk menentukan folder cache bootstrap
$app->instance('manifest.path', '/tmp/packages.php');

// Paksa file services.php milik ProviderRepository pindah ke /tmp
$app->afterResolving(\Illuminate\Foundation\PackageManifest::class, function () use ($app) {
    $app->instance('path.bootstrap', '/tmp');
});

// override fungsi getCachedServicesPath bawaan Application.php
$app->bind('path.bootstrap', function () {
    return '/tmp';
});
// ------------------------------------------------------------

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