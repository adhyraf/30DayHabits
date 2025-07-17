<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|integer',
            'tanggal' => 'nullable|string',
            'refleksi' => 'nullable|string',
            'selesai' => 'required|boolean',
            'jadwal' => 'nullable|array',
            'mapel' => 'nullable|array',
        ]);

        $progress = Progress::updateOrCreate(
            ['day' => $validated['day']],
            [
                'tanggal' => $validated['tanggal'] ?? now()->format('d M Y'),
                'refleksi' => $validated['refleksi'] ?? '',
                'selesai' => $validated['selesai'],
                'jadwal' => $validated['jadwal'] ?? [],
                'mapel' => $validated['mapel'] ?? [],
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Progress berhasil disimpan.',
            'data' => $progress
        ]);
    }
}
