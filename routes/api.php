<?php

use App\Http\Controllers\Api\MasyarakatController;
use Illuminate\Support\Facades\Route;

// Endpoint sesuai spesifikasi Apidog
Route::post('/register', [MasyarakatController::class, 'register']);
Route::post('/login', [MasyarakatController::class, 'login']);
Route::get('/kuota', [MasyarakatController::class, 'cekKuota']);
Route::post('/antrean', [MasyarakatController::class, 'daftarAntrean']);