<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Progress;
use App\Http\Controllers\ProgressController;

// ✅ Include jadwal harian (pastikan ini ada sebelum route)
$jadwalHarian = require base_path('routes/jadwal_harian_rill.php');

// ✅ Halaman dashboard utama
Route::get('/', function () {
    return view('dashboard');
});

// ✅ Halaman hari ke-n
Route::get('/day/{day}', function ($day) use ($jadwalHarian) {
    // Tanggal mulai challenge
    $tanggalMulai = Carbon::create(2025, 7, 14);

    // Hitung tanggal ke-n
    $tanggalIni = $tanggalMulai->copy()->addDays($day - 1);
    $hariNama = strtolower($tanggalIni->format('l')); // ex: monday, tuesday

    // Tentukan tipe hari
    $tipeHari = match ($hariNama) {
        'saturday' => 'sabtu',
        'sunday' => 'minggu',
        default => 'senin_jumat',
    };

    // Ambil jadwal dari file
    $jadwal = $jadwalHarian[$tipeHari] ?? [];

    // ✅ Ambil progress dari database (jika ada)
    $progress = Progress::where('day', $day)->first();

    // Tampilkan view
    return view('day', [
        'day' => $day,
        'tanggal' => $tanggalIni->format('d M Y'),
        'jadwal' => $jadwal,
        'progress' => $progress, // dikirim ke view
    ]);
});

// ✅ Route untuk menyimpan/update progress (AJAX POST)
Route::post('/progress', [ProgressController::class, 'storeOrUpdate']);
