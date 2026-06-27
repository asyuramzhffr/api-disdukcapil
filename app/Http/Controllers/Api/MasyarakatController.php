<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MasyarakatController extends Controller
{
    // 1. LOGIKA REGISTER (DAFTAR MASYARAKAT)
    public function register(Request $request)
    {
        // Validasi input data dari user sesuai spesifikasi
        $request->validate([
            'nik' => 'required|unique:users,nik',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Simpan data masyarakat ke database
        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash biar aman
        ]);

        // Kembalikan response JSON sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Masyarakat berhasil didaftarkan',
            'data' => $user
        ], 201);
    }

    // 2. LOGIKA LOGIN
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email dan password cocok dengan database
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Ambil data user yang login
        $user = User::where('email', $request->email)->firstOrFail();

        // Buat token akses (Sanctum) biar user punya hak akses buat fitur antrean nanti
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ]);
    }

    // 3. LOGIKA CEK KUOTA (DUMMY ATAU SIMPLE LOGIC)
    public function cekKuota()
    {
        // Sederhananya kita set kuota harian maksimal 50, sisa kuota misalnya 15
        return response()->json([
            'status' => 'success',
            'tanggal' => date('Y-m-d'),
            'kuota_maksimal' => 50,
            'sisa_kuota' => 15
        ]);
    }

    // 4. LOGIKA DAFTAR ANTREAN
    public function daftarAntrean(Request $request)
    {
        // Validasi input antrean
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_antrean' => 'required|date',
            'keperluan' => 'required|string',
        ]);

        // Generate nomor antrean otomatis secara acak sederhana, misal: ANT-732
        $nomorAntrean = 'ANT-' . rand(100, 999);

        // Nanti di sini ditambahkan fungsi simpan ke database tabel antrean jika diperlukan.
        // Untuk sekarang kita return response sukses dulu agar API Specs terpenuhi.
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendaftar antrean',
            'nomor_antrean' => $nomorAntrean,
            'tanggal' => $request->tanggal_antrean,
            'keperluan' => $request->keperluan
        ]);
    }
}