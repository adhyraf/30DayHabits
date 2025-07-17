@extends('layouts.app')

@section('title', 'Hari ' . $day . ' - 30 Day Habits')

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <a href="{{ url('/') }}" class="text-sm text-neutral-300 hover:underline">&larr; Kembali</a>
    <button id="toggle-theme" class="text-sm bg-neutral-800 text-white px-4 py-2 rounded-lg shadow hover:bg-neutral-700 transition">
        🌙 Dark Mode
    </button>
</div>

<h1 class="text-2xl font-bold text-white mb-1">Hari {{ $day }}</h1>
<p class="text-sm text-gray-400 mb-6">{{ $tanggal }}</p>

<div class="space-y-8">
    <!-- Fokus Harian -->
    <div>
        <h2 class="text-lg font-semibold text-white mb-2">📌 Fokus Hari Ini</h2>
        <p class="text-gray-300">Fokus utama hari ini harus dilakukan dengan konsisten dan semangat.</p>
    </div>

    <!-- Refleksi -->
    <div>
        <h2 class="text-lg font-semibold text-white mb-2">✍️ Refleksi Ringan</h2>
        <textarea id="refleksi" rows="5" placeholder="Apa yang kamu pelajari atau rasakan hari ini?"
            class="w-full bg-neutral-900 text-white border border-neutral-700 rounded-lg p-3 focus:ring focus:ring-blue-600 text-sm">{{ $progress->refleksi ?? '' }}</textarea>
    </div>

    <!-- Tandai Selesai -->
    <div class="flex items-center gap-3">
        <input type="checkbox" id="selesai" {{ $progress && $progress->selesai ? 'checked' : '' }}
            class="w-5 h-5 text-blue-500 bg-neutral-800 border-neutral-600 rounded focus:ring-blue-500" />
        <label for="selesai" class="text-white">Tandai hari ini sebagai selesai</label>
    </div>

    <!-- Jadwal Harian -->
    <div>
        <h2 class="text-lg font-semibold text-white mb-3">📅 Jadwal Harian</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($jadwal as $index => [$waktu, $kegiatan])
                @php
                    $checked = $progress && is_array($progress->jadwal) && ($progress->jadwal["jadwal_$index"] ?? false);
                @endphp
                <label for="jadwal_{{ $index }}" class="bg-neutral-800 border border-neutral-700 rounded-xl p-4 flex gap-3 items-start shadow transition hover:border-blue-500">
                    <input type="checkbox" id="jadwal_{{ $index }}" {{ $checked ? 'checked' : '' }}
                        class="mt-1 w-5 h-5 text-blue-500 bg-neutral-700 border-none rounded focus:ring-2 focus:ring-blue-400" />
                    <div>
                        <div class="text-sm text-gray-400 mb-1">{{ $waktu }}</div>
                        <div class="text-white font-medium leading-snug">{{ $kegiatan }}</div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Mapel Hari Ini -->
    @php
        $mapelHariIni = include base_path('routes/jadwal_harian.php');
        $mapel = $mapelHariIni[$day] ?? [];
    @endphp

    <div>
        <h2 class="text-lg font-semibold text-white mb-3">📚 Mapel Hari Ini</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($mapel as $i => $item)
                @php
                    $checked = $progress && is_array($progress->mapel) && ($progress->mapel["mapel_$i"] ?? false);
                @endphp
                <label for="mapel_{{ $i }}" class="bg-blue-950 border border-blue-700 rounded-xl p-4 flex gap-3 items-center shadow hover:border-blue-400">
                    <input type="checkbox" id="mapel_{{ $i }}" {{ $checked ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-500 bg-blue-900 border-none rounded focus:ring-2 focus:ring-blue-400" />
                    <span class="text-white font-medium">{{ $item }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Tombol Simpan Manual -->
    <div>
        <button id="save-progress" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            💾 Simpan Progress
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
const day = {{ $day }};
const refleksi = document.getElementById('refleksi');
const checkbox = document.getElementById('selesai');
const jadwalCheckboxes = document.querySelectorAll('[id^="jadwal_"]');
const mapelCheckboxes = document.querySelectorAll('[id^="mapel_"]');

// Restore dari localStorage (opsional tambahan jika belum ada di DB)
refleksi.value = localStorage.getItem('refleksi_' + day) || refleksi.value;
checkbox.checked = localStorage.getItem('day_' + day) === 'true' || checkbox.checked;

jadwalCheckboxes.forEach((cb) => {
    const key = 'jadwal_' + day + '_' + cb.id.split('_')[1];
    cb.checked = localStorage.getItem(key) === 'true' || cb.checked;
});

mapelCheckboxes.forEach((cb) => {
    const key = 'mapel_' + day + '_' + cb.id.split('_')[1];
    cb.checked = localStorage.getItem(key) === 'true' || cb.checked;
});

// Fungsi simpan ke database
function simpanProgress() {
    const refleksiVal = refleksi.value;
    const selesaiVal = checkbox.checked;

    const jadwal = {};
    jadwalCheckboxes.forEach(cb => {
        jadwal[cb.id] = cb.checked;
        localStorage.setItem('jadwal_' + day + '_' + cb.id.split('_')[1], cb.checked);
    });

    const mapel = {};
    mapelCheckboxes.forEach(cb => {
        mapel[cb.id] = cb.checked;
        localStorage.setItem('mapel_' + day + '_' + cb.id.split('_')[1], cb.checked);
    });

    localStorage.setItem('refleksi_' + day, refleksiVal);
    localStorage.setItem('day_' + day, selesaiVal);

    fetch('/progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            day,
            tanggal: '{{ $tanggal }}',
            refleksi: refleksiVal,
            selesai: selesaiVal,
            jadwal,
            mapel
        })
    })
    .then(res => res.json())
    .then(data => console.log('Progress saved:', data))
    .catch(err => console.error('Error saving progress:', err));
}

// Event listener auto-simpan
checkbox.addEventListener('change', simpanProgress);
refleksi.addEventListener('input', simpanProgress);
jadwalCheckboxes.forEach(cb => cb.addEventListener('change', simpanProgress));
mapelCheckboxes.forEach(cb => cb.addEventListener('change', simpanProgress));

// Tombol manual
document.getElementById('save-progress').addEventListener('click', () => {
    simpanProgress();
    alert('Progress berhasil disimpan!');
});

// Dark mode
const toggleTheme = document.getElementById('toggle-theme');
const html = document.documentElement;
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') html.classList.add('dark');

toggleTheme.addEventListener('click', () => {
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    toggleTheme.textContent = html.classList.contains('dark') ? '☀️ Light Mode' : '🌙 Dark Mode';
});
</script>
@endsection
