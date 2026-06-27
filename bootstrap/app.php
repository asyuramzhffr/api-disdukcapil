<?php

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// --- POTONG KOMPAS CONFIG& MANIFEST LANGSUNG DI INSTANCE ---
$app->useStoragePath('/tmp');

// Kita override path config & cache langsung ke /tmp
$app->instance('path.config', '/tmp');
$app->instance('manifest.path', '/tmp/packages.php');

// Trik Pamungkas: Daftarkan ulang PackageManifest khusus untuk Vercel
$app->singleton(\Illuminate\Foundation\PackageManifest::class, function () use ($app) {
    return new \Illuminate\Foundation\PackageManifest(
        new \Illuminate\Filesystem\Filesystem,
        '/tmp', // Base path diubah ke tmp
        '/tmp/packages.php' // File path diubah ke tmp
    );
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