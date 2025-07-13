@extends('layouts.app')

@section('title', 'Hari ' . $day . ' - 30 Day Habits')

@section('content')
    <!-- Header + Toggle -->
    <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
        <a href="{{ url('/') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">&larr; Kembali ke Dashboard</a>
        <button id="toggle-theme" class="text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            🌙 Dark Mode
        </button>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300 text-center">
        Hari {{ $day }}
        <span class="block text-base text-gray-500 dark:text-gray-400 mt-1">{{ $tanggal }}</span>
    </h1>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 sm:p-6 space-y-6 sm:space-y-8">
        <!-- Fokus Harian -->
        <div>
            <h2 class="text-lg sm:text-xl font-semibold mb-2">📌 Fokus Hari Ini</h2>
            <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                Lakukan kegiatan utama yang sudah direncanakan hari ini. Tetap konsisten dan semangat!
            </p>
        </div>

        <!-- Refleksi -->
        <div>
            <h2 class="text-lg sm:text-xl font-semibold mb-2">✍️ Refleksi Ringan</h2>
            <textarea id="refleksi" rows="5" placeholder="Apa yang kamu pelajari atau rasakan hari ini?"
                class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"></textarea>
        </div>

        <!-- Checklist Hari -->
        <div class="flex items-center gap-3">
            <input type="checkbox" id="selesai" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
            <label for="selesai" class="text-gray-800 dark:text-gray-200 font-medium">Tandai hari ini sebagai selesai</label>
        </div>

        <!-- Jadwal Harian -->
        <div>
            <h2 class="text-lg sm:text-xl font-semibold mb-3">📅 Jadwal Harian</h2>
            <div class="space-y-3">
                @foreach ($jadwal as $index => [$waktu, $kegiatan])
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 shadow-sm">
                        <input type="checkbox" id="jadwal_{{ $index }}" class="mt-1 w-5 h-5 text-blue-600" />
                        <div>
                            <div class="flex items-center gap-2 mb-1 text-sm text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $waktu }}
                            </div>
                            <label for="jadwal_{{ $index }}" class="text-gray-800 dark:text-gray-100 font-medium leading-snug">
                                {{ $kegiatan }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Mapel Hari Ini -->
        @php
            $mapelHariIni = include base_path('routes/jadwal_harian.php');
            $mapel = $mapelHariIni[$day] ?? [];
        @endphp

        <div>
            <h2 class="text-lg sm:text-xl font-semibold mb-3">📚 Mapel Hari Ini</h2>
            <div class="space-y-2">
                @foreach ($mapel as $i => $item)
                    <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-800 rounded-lg p-3 shadow-sm">
                        <input type="checkbox" id="mapel_{{ $i }}" class="w-5 h-5 text-blue-600" />
                        <label for="mapel_{{ $i }}" class="text-gray-800 dark:text-white font-medium">{{ $item }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const day = {{ $day }};
    const checkbox = document.getElementById('selesai');
    const refleksi = document.getElementById('refleksi');
    const refleksiKey = 'refleksi_' + day;
    const statusKey = 'day_' + day;

    checkbox.checked = localStorage.getItem(statusKey) === 'true';
    refleksi.value = localStorage.getItem(refleksiKey) || '';

    checkbox.addEventListener('change', () => {
        localStorage.setItem(statusKey, checkbox.checked);
    });

    refleksi.addEventListener('input', () => {
        localStorage.setItem(refleksiKey, refleksi.value);
    });

    // Jadwal harian checkbox
    document.querySelectorAll('[id^="jadwal_"]').forEach((input) => {
        const key = 'jadwal_' + day + '_' + input.id.split('_')[1];
        input.checked = localStorage.getItem(key) === 'true';
        input.addEventListener('change', () => {
            localStorage.setItem(key, input.checked);
        });
    });

    // Mapel hari ini checkbox
    document.querySelectorAll('[id^="mapel_"]').forEach((input) => {
        const key = 'mapel_' + day + '_' + input.id.split('_')[1];
        input.checked = localStorage.getItem(key) === 'true';
        input.addEventListener('change', () => {
            localStorage.setItem(key, input.checked);
        });
    });

    // Dark Mode toggle
    const toggleTheme = document.getElementById('toggle-theme');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        html.classList.add('dark');
    }

    toggleTheme.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
@endsection
