<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// Load array jadwal harian riil dari file eksternal
$jadwalHarian = require base_path('routes/jadwal_harian_rill.php');

// Route untuk halaman dashboard utama
Route::get('/', function () {
    return view('dashboard');
});

// Route untuk setiap halaman per hari
Route::get('/day/{day}', function ($day) use ($jadwalHarian) {
    // Tanggal mulai tantangan: 14 Juli 2025 (hari Senin)
    $tanggalMulai = Carbon::create(2025, 7, 14);

    // Hitung tanggal berdasarkan hari ke-n
    $tanggalIni = $tanggalMulai->copy()->addDays($day - 1);
    $hariNama = strtolower($tanggalIni->format('l')); // e.g., "monday", "saturday"

    // Tentukan jenis hari dan ambil jadwalnya
    $tipeHari = match ($hariNama) {
        'saturday' => 'sabtu',
        'sunday' => 'minggu',
        default => 'senin_jumat',
    };

    $jadwal = $jadwalHarian[$tipeHari] ?? [];

    // Kirim data ke view
    return view('day', [
        'day' => $day,
        'tanggal' => $tanggalIni->format('d M Y'),
        'jadwal' => $jadwal,
    ]);
});
